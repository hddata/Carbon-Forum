<?php
SetStyle('api', 'API');

switch (Request('Request', 'action')) {
	case 'get_notifications':
		Auth(1);
		header("Cache-Control: no-cache, must-revalidate");
		@set_time_limit(0);
		//如果是自己的服务器，建议调大超时时间，然后把长连接时长调大，以节约服务器资源
		$Config['PushConnectionTimeoutPeriod'] = intval((intval($Config['PushConnectionTimeoutPeriod']) < 22) ? 22 : $Config['PushConnectionTimeoutPeriod']);
		while ((time() - $TimeStamp) < $Config['PushConnectionTimeoutPeriod']) {
			if ($MCache) {
				$CurUserInfo = $MCache->get(MemCachePrefix . 'UserInfo_' . $CurUserID);
				if ($CurUserInfo) {
					$CurNewNotification = $CurUserInfo['NewNotification'];
				} else {
					$TempUserInfo = $DB->row("SELECT *, (NewReply + NewMention + NewMessage) as NewNotification FROM " . PREFIX . "users WHERE ID = :UserID", array(
						"UserID" => $CurUserID
					));
					$MCache->set(MemCachePrefix . 'UserInfo_' . $CurUserID, $TempUserInfo, 86400);
					$CurNewNotification = $TempUserInfo['NewNotification'];
				}
			} else {
				$CurNewNotification = $DB->single("SELECT (NewReply + NewMention + NewMessage) AS NewNotification FROM " . PREFIX . "users WHERE ID = :UserID", array(
					"UserID" => $CurUserID
				));
			}
			
			if ($CurNewNotification > 0) {
				break;
			}
			sleep(3);
		}
		echo json_encode(array(
			'Status' => 1,
			'NewMessage' => $CurNewNotification
		));
		break;
	
	
	case 'get_tags':
		Auth(1);
		require(LibraryPath . "PHPAnalysis.class.php");
		$str                   = Request('Post', 'Title') . "/r/n" . Request('Post', 'Content');
		$do_fork               = $do_unit = true;
		$do_multi              = $do_prop = $pri_dict = false;
		//初始化类
		PhpAnalysis::$loadInit = false;
		$pa                    = new PhpAnalysis('utf-8', 'utf-8', $pri_dict);
		//载入词典
		$pa->LoadDict();
		//执行分词
		$pa->SetSource($str);
		$pa->differMax = $do_multi;
		$pa->unitWord  = $do_unit;
		$pa->StartAnalysis($do_fork);
		$ResultString   = $pa->GetFinallyResult('|', $do_prop);
		$tags           = array();
		$tags['status'] = 0;
		if ($ResultString) {
			foreach (explode('|', $ResultString) as $key => $value) {
				if ($value != '' && !is_numeric($value) && mb_strlen($value, "utf-8") >= 2) {
					$SQLParameters[] = $value;
				}
			}
			$TagsLists1 = $DB->column("SELECT Name FROM " . PREFIX . "tags Where Name IN (?)", $SQLParameters);
			$TagsLists2 = $DB->column("SELECT Title FROM " . PREFIX . "dict Where Title IN (?) Group By Title", $SQLParameters);
			//$TagsLists2 = array();
			$TagsLists  = array_merge($TagsLists1, array_diff($TagsLists2, $TagsLists1));
			//获取热门话题
			$TagsLists  = array_merge($TagsLists, ArrayColumn($HotTagsArray, 'Name'));
			if ($TagsLists) {
				$tags['status'] = 1;
				rsort($TagsLists);
				$tags['lists'] = $TagsLists;
			}
		}
		echo json_encode($tags);
		break;
	
	
	case 'tag_autocomplete':
		//Auth(1);
		$Keyword           = Request('Post', 'query');
		$Response          = array();
		$Response['query'] = 'Unit';
		$Result            = $DB->column("SELECT Title FROM " . PREFIX . "dict WHERE Title LIKE :Keyword limit 10", array(
			"Keyword" => $Keyword . "%"
		));
		if ($Result) {
			foreach ($Result as $key => $val) {
				$Response['suggestions'][] = array(
					'value' => $val,
					'data' => $val
				);
			}
		} else {
			$Response['suggestions'][] = '';
		}
		echo json_encode($Response);
		break;
	
	case 'user_exist':
		$UserName  = strtolower(Request('Post', 'UserName'));
		$UserExist = $DB->single("SELECT ID FROM " . PREFIX . "users WHERE UserName = :UserName", array(
			'UserName' => $UserName
		));
		echo json_encode(array(
			'Status' => $UserExist ? 1 : 0
		));
		break;
	
	case 'get_post':
		$PostId = intval(Request('Post', 'PostId'));
		$row    = $DB->row("SELECT UserName, Content, TopicID FROM " . PREFIX . "posts WHERE ID = :PostId AND IsDel = 0", array(
			'PostId' => $PostId
		));
		if ($CurUserRole < 4) {
			// 对超级管理员以下的用户需要检查整个主题是否被删除了
			$TopicID  = $row['TopicID'];
			$TopicRow = $DB->single("SELECT COUNT(*) FROM " . PREFIX . "topics WHERE ID = :TopicID AND IsDel = 0", array(
				'TopicID' => $TopicID
			));
			if ($TopicRow < 1) {
				$row = false;
			}
		}
		echo json_encode($row);
		break;
	
	default:
		# code...
		break;


	/*获取自己关注的标签列表，直接从数据库中获取，有参考根目录下manage.php*/
	case 'list_tags':
		#$UserId           = $_POST['userid'];
		# $Result            = $DB->column("select tags from carbon_topics where UserId = " . $CurUserID  );
		$Favoritesresult            = $DB->column("select title from `" . PREFIX . "favorites` where Type = 2 AND UserId = " . $CurUserID );

		echo json_encode($Favoritesresult);

		break;
		


	case 'create_tags':
			$ErrorCodeList = require(LibraryPath . 'code/new.error.code.php');
			$Error     = '';
			$ErrorCode = $ErrorCodeList['Default'];
			$Title     = '';
			$Content   = '';
			$TagsArray = array();
				$Title     = "欢迎~∩__∩y";
				$Content   = "来来来~ (O ^ ~ ^ O) ";
				$TagsArray = $_POST['Tag'];
				if ($Config['AllowNewTopic'] === 'false' && $CurUserRole < 3) {
					$Error     = $Lang['Prohibited_New_Topic'];
					$ErrorCode = $ErrorCodeList['Prohibited_New_Topic'];
				break;
				echo json_encode("error");	
				}
				do {

					//$ID     = intval(Request('POST', 'ID', 0));
					if ($Title) {
						if (strlen($Title) <= $Config['MaxTitleChars'] || strlen($Content) <= $Config['MaxPostChars']) {
							if (!empty($TagsArray) && !in_array('', $TagsArray) && count($TagsArray) <= $Config["MaxTagsNum"]) {
								//获取已存在的标签
								$TagsExistArray = $DB->query("SELECT ID,Name FROM `" . PREFIX . "tags` WHERE `Name` in (?)", $TagsArray);
								$TagsExist      = ArrayColumn($TagsExistArray, 'Name');
								$TagsID         = ArrayColumn($TagsExistArray, 'ID');
								//var_dump($TagsExist);
								$NewTags        = TagsDiff($TagsArray, $TagsExist);
								//新建不存在的标签	
								if ($NewTags) {
									foreach ($NewTags as $Name) {
										$DB->query("INSERT INTO `" . PREFIX . "tags` 
											(`ID`, `Name`,`Followers`,`Icon`,`Description`, `IsEnabled`, `TotalPosts`, `MostRecentPostTime`, `DateCreated`) 
											VALUES (?,?,?,?,?,?,?,?,?)", array(
											null,
											htmlspecialchars(trim($Name)),
											0,
											0,
											null,
											1,
											1,
											$TimeStamp,
											$TimeStamp
										));
										$TagsID[] = $DB->lastInsertId();
												

										//插入用户关注的话题
										$DB->query('INSERT INTO `' . PREFIX . 'favorites`(`ID`, `UserID`, `Category`, `Title`, `Type`, `FavoriteID`, `DateCreated`, `Description`) VALUES (?,?,?,?,?,?,?,?)', array(
											null,
											$CurUserID,
											'',
											htmlspecialchars(trim($Name)),
											2,
											$TagsID[0] ,
											$TimeStamp,
											''
										));

										//add by sz，用户关注话题时候，需要更新的表
									$DB->query('UPDATE ' . PREFIX . 'tags SET Followers = Followers' . '+1' . ' Where ID=:FavoriteID', array(
										'FavoriteID' => $TagsID[0]
									));
									$DB->query('UPDATE `' . PREFIX . 'users` SET NumFavTags=NumFavTags' . '+1' . ' WHERE `ID`=?', array(
										$CurUserID
									));
									}
									//更新全站统计数据
									$NewConfig = array(
										"NumTags" => $Config["NumTags"] + count($NewTags)
									);

									$TagsArray      = array_merge($TagsExist, $NewTags);
								//往Topics表插入数据
								$TopicData      = array(
									"ID" => null,
									"Topic" => htmlspecialchars($Title),
									"Tags" => implode("|", $TagsArray), //过滤不合法的标签请求
									"UserID" => $CurUserID,
									"UserName" => $CurUserName,
									"LastName" => "",
									"PostTime" => $TimeStamp,
									"LastTime" => $TimeStamp,
									"IsGood" => 0,
									"IsTop" => 0,
									"IsLocked" => 0,
									"IsDel" => 0,
									"IsVote" => 0,
									"Views" => 0,
									"Replies" => 0,
									"Favorites" => 0,
									"RatingSum" => 0,
									"TotalRatings" => 0,
									"LastViewedTime" => 0,
									"PostsTableName" => null,
									"ThreadStyle" => "",
									"Lists" => "",
									"ListsTime" => $TimeStamp,
									"Log" => ""
								);
								$NewTopicResult = $DB->query("INSERT INTO `" . PREFIX . "topics` 
									(
										`ID`, 
										`Topic`, 
										`Tags`, 
										`UserID`, 
										`UserName`, 
										`LastName`, 
										`PostTime`, 
										`LastTime`, 
										`IsGood`, 
										`IsTop`, 
										`IsLocked`, 
										`IsDel`, 
										`IsVote`, 
										`Views`, 
										`Replies`, 
										`Favorites`, 
										`RatingSum`, 
										`TotalRatings`, 
										`LastViewedTime`, 
										`PostsTableName`, 
										`ThreadStyle`, 
										`Lists`, 
										`ListsTime`, 
										`Log`
									) 
									VALUES 
									(
										:ID,
										:Topic,
										:Tags,
										:UserID,
										:UserName,
										:LastName,
										:PostTime,
										:LastTime,
										:IsGood,
										:IsTop,
										:IsLocked,
										:IsDel,
										:IsVote,
										:Views,
										:Replies,
										:Favorites,
										:RatingSum,
										:TotalRatings,
										:LastViewedTime,
										:PostsTableName,
										:ThreadStyle,
										:Lists,
										:ListsTime,
										:Log
									)", $TopicData);

								$TopicID       = $DB->lastInsertId();
								//往Posts表插入数据
								$PostData      = array(
									"ID" => null,
									"TopicID" => $TopicID,
									"IsTopic" => 1,
									"UserID" => $CurUserID,
									"UserName" => $CurUserName,
									"Subject" => htmlspecialchars($Title),
									"Content" => XssEscape($Content),
									"PostIP" => $CurIP,
									"PostTime" => $TimeStamp
								);
								$NewPostResult = $DB->query("INSERT INTO `" . PREFIX . "posts` 
									(`ID`, `TopicID`, `IsTopic`, `UserID`, `UserName`, `Subject`, `Content`, `PostIP`, `PostTime`) 
									VALUES (:ID,:TopicID,:IsTopic,:UserID,:UserName,:Subject,:Content,:PostIP,:PostTime)", $PostData);
														
								$PostID = $DB->lastInsertId();
								
								if ($NewTopicResult && $NewPostResult) {
									//更新全站统计数据
									$NewConfig = array(
										"NumTopics" => $Config["NumTopics"] + 1,
										"DaysTopics" => $Config["DaysTopics"] + 1
									);
									UpdateConfig($NewConfig);
									//更新用户自身统计数据
									UpdateUserInfo(array(
										"Topics" => $CurUserInfo['Topics'] + 1,
										"LastPostTime" => $TimeStamp + $GagTime
									));
									//标记附件所对应的帖子标签
									$DB->query("UPDATE `" . PREFIX . "upload` SET PostID=? WHERE `PostID`=0 and `UserName`=?", array(
										$PostID,
										$CurUserName
									));
									//记录标签与TopicID的对应关系
									foreach ($TagsID as $TagID) {
										$DB->query("INSERT INTO `" . PREFIX . "posttags` 
											(`TagID`, `TopicID`, `PostID`) 
											VALUES (?,?,?)", array(
											$TagID,
											$TopicID,
											$PostID
										));
									}
									//更新标签统计数据
									if ($TagsExist) {
										$DB->query("UPDATE `" . PREFIX . "tags` SET TotalPosts=TotalPosts+1, MostRecentPostTime=" . $TimeStamp . " WHERE `Name` in (?)", $TagsExist);
									}
									//添加提醒消息
									AddingNotifications($Content, $TopicID, $PostID);
									//清理首页内存缓存
									if ($MCache) {
										$MCache->delete(MemCachePrefix . 'Homepage');
									}
									//跳转到主题页
									//header('location: '.$Config['WebsitePath'].'/t/'.$TopicID);
								}
									//var_dump($NewTags);
								}					
								
								
							} else {
								$Error = $Lang['Tags_Empty'];
							}
						} else {
							$Error = str_replace('{{MaxPostChars}}', $Config['MaxPostChars'], str_replace('{{MaxTitleChars}}', $Config['MaxTitleChars'], $Lang['Too_Long']));
						}
					} else {
						$Error = $Lang['Title_Empty'];
					}
				} while (false);
				$DB->CloseConnection();
				echo json_encode("success");	
		break;

}