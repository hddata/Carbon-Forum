<?php
require(LanguagePath . 'home.php');
require(LanguagePath . 'favorite_tags.php');
$Page      = intval(Request('Request', 'page'));
$TotalPage = ceil($Config['NumTopics'] / $Config['TopicsPerPage']);
$TimeStamp = $_SERVER['REQUEST_TIME'];
if (($Page < 0 || $Page == 1) && !$IsApp) 
	Redirect();
if ($Page > $TotalPage) 
	Redirect('page/' . $TotalPage);
if ($Page == 0)
	$Page = 1;
$TopicsArray = array();
if ($MCache && $Page == 1) {
	$TopicsArray = $MCache->get(MemCachePrefix . 'Homepage');
}
/*
if (!$TopicsArray) {
	if ($Page <= 10) {
		$TopicsArray = $DB->query('SELECT `ID`, `Topic`, `Tags`, `UserID`, `UserName`, `LastName`, `LastTime`, `Replies` 
			FROM ' . PREFIX . 'topics force index(LastTime) 
			WHERE IsDel=0 
			ORDER BY LastTime DESC 
			LIMIT ' . ($Page - 1) * $Config['TopicsPerPage'] . ',' . $Config['TopicsPerPage']);
		if ($MCache && $Page == 1) {
			$MCache->set(MemCachePrefix . 'Homepage', $TopicsArray, 600);
		}
	} else {
		$TopicsArray = $DB->query('SELECT `ID`, `Topic`, `Tags`, `UserID`, `UserName`, `LastName`, `LastTime`, `Replies` 
			FROM ' . PREFIX . 'topics force index(LastTime) 
			WHERE LastTime<=(SELECT LastTime 
					FROM ' . PREFIX . 'topics force index(LastTime) 
					WHERE IsDel=0 
					ORDER BY LastTime DESC 
					LIMIT ' . ($Page - 1) * $Config['TopicsPerPage'] . ', 1) 
				and IsDel=0 
			ORDER BY LastTime DESC 
			LIMIT ' . $Config['TopicsPerPage']);
	}
}
*/
//增加判断，如果用户未登录看到所有帖子，如果用户登录，看到关注的贴咖下的帖子
if(!$CurUserID && $UrlPath != 'login' && $UrlPath != 'register' && $UrlPath != 'oauth'){
	//展示所有帖子，同时不在首页显示置顶贴
	if (!$TopicsArray) {
	if ($Page <= 10) {
		$TopicsArray = $DB->query('SELECT `ID`, `Topic`, `Tags`, `UserID`, `UserName`, `LastName`, `LastTime`, `Replies` 
			FROM ' . PREFIX . 'topics force index(LastTime) 
			WHERE IsDel=0 and LastTime<='. $_SERVER['REQUEST_TIME'] .'
			ORDER BY LastTime DESC 
			LIMIT ' . ($Page - 1) * $Config['TopicsPerPage'] . ',' . $Config['TopicsPerPage']);
		if ($MCache && $Page == 1) {
			$MCache->set(MemCachePrefix . 'Homepage', $TopicsArray, 600);
		}
	} else {
		$TopicsArray = $DB->query('SELECT `ID`, `Topic`, `Tags`, `UserID`, `UserName`, `LastName`, `LastTime`, `Replies` 
			FROM ' . PREFIX . 'topics force index(LastTime) 
			WHERE LastTime<=(SELECT LastTime 
					FROM ' . PREFIX . 'topics force index(LastTime) 
					WHERE IsDel=0 
					ORDER BY LastTime DESC 
					LIMIT ' . ($Page - 1) * $Config['TopicsPerPage'] . ', 1) 
				and IsDel=0 and LastTime<='. $_SERVER['REQUEST_TIME'] .'
			ORDER BY LastTime DESC 
			LIMIT ' . $Config['TopicsPerPage']);
			}
	}
} else{
	//展示关注的贴咖下的帖子，同时不在首页显示置顶贴
	$Page = Request('Get', 'page');
	if ($Page < 0 || $Page == 1) 
	Redirect('tags/following');
	if ($Page == 0)
		$Page = 1;
		$TagsFollowing = $DB->query('SELECT * FROM ' . PREFIX . 'favorites force index(UsersFavorites) Where UserID=? and Type=2', array(
		$CurUserID
	));
	$TopicIDArray  = array();
	if ($TagsFollowing)
	$TopicIDArray = $DB->column('SELECT TopicID 
            FROM ' . PREFIX . 'posttags force index(TagsIndex) 
            WHERE TagID in (?) 
            ORDER BY TopicID DESC 
            LIMIT ' . ($Page - 1) * $Config['TopicsPerPage'] . ',' . ($Config['TopicsPerPage'] + 1),
        ArrayColumn($TagsFollowing, 'FavoriteID'));

	if (count($TopicIDArray) > $Config['TopicsPerPage']) {
    	$IsLastPage = false;
    	array_pop($TopicIDArray);
	} else {
    	$IsLastPage = true;
	}

	array_unique($TopicIDArray);
	$TopicsArray = array();
	if ($TopicIDArray)
		$TopicsArray = $DB->query('SELECT `ID`, `Topic`, `Tags`, `UserID`, `UserName`, `LastName`, `LastTime`, `Replies` 
            FROM ' . PREFIX . 'topics force index(PRI) 
            WHERE ID in (?) and IsDel=0 and LastTime<='. $_SERVER['REQUEST_TIME'] .'
            ORDER BY LastTime DESC',
        	$TopicIDArray);
}

$DB->CloseConnection();
$PageTitle = $Page > 1 ? ' Page' . $Page . '-' : '';
$PageTitle .= $Config['SiteName'];
$PageMetaDesc = htmlspecialchars(mb_substr($Config['SiteDesc'], 0, 150, 'utf-8'));
$ContentFile  = $TemplatePath . 'home.php';
include($TemplatePath . 'layout.php');