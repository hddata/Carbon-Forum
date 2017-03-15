<?php


require(__DIR__ . '/common.php');

$HTTPMethod = $_SERVER['REQUEST_METHOD'];
if (!in_array($HTTPMethod, array('GET', 'POST', 'HEAD', 'PUT', 'DELETE', 'OPTIONS'))) {
	exit('Unsupport HTTP method');
}
if ($Config['WebsitePath']) {
	$WebsitePathPosition = strpos($RequestURI, $Config['WebsitePath']);
	if ($WebsitePathPosition !== 0) {
		exit('WebsitePath Error!');
	} else {
		$ShortRequestURI = substr($RequestURI, strlen($Config['WebsitePath']));
	}
} else {
	$ShortRequestURI = $RequestURI;
}
$NotFound = true;
$HTTPParameters = array();
if (in_array($HTTPMethod, array('PUT', 'DELETE', 'OPTIONS'))) {
	parse_str(file_get_contents('php://input'), $HTTPParameters);
}
$Routes = array();

//Support HTTP Method: GET / POST / PUT / DELETE / OPTIONS
//这里是Routes Start

$Routes['GET']['/']                                                                        = 'home';
$Routes['POST']['/']                                                                       = 'home'; //Delete later
$Routes['GET']['/dashboard']                                                               = 'dashboard';
$Routes['POST']['/dashboard']                                                              = 'dashboard';
$Routes['GET']['/favorites(/page/(?<page>[0-9]+))?']                                       = 'favorites';
$Routes['GET']['/forgot']                                                                  = 'forgot';
$Routes['POST']['/forgot']                                                                 = 'forgot';
$Routes['GET']['/goto/(?<topic_id>[0-9]+)-(?<post_id>[0-9]+)']                             = 'goto';
$Routes['POST']['/json/(?<action>[0-9a-z_\-]+)']                                           = 'json';
$Routes['GET']['/json/(?<action>[0-9a-z_\-]+)']                                            = 'json';
$Routes['GET']['/login']                                                                   = 'login';
$Routes['POST']['/login']                                                                  = 'login';
$Routes['POST']['/manage']                                                                 = 'manage';
$Routes['GET']['/new']                                                                     = 'new';
$Routes['POST']['/new']                                                                    = 'new';
$Routes['GET']['/notifications']                                                           = 'notifications';
$Routes['POST']['/notifications']                                                          = 'notifications'; //Delete later
$Routes['GET']['/oauth-(?<app_id>[0-9]+)']                                                 = 'oauth';
$Routes['POST']['/oauth-(?<app_id>[0-9]+)']                                                = 'oauth';
$Routes['GET']['/page/(?<page>[0-9]+)']                                                    = 'home';
$Routes['POST']['/page/(?<page>[0-9]+)']                                                   = 'home'; //Delete later
$Routes['GET']['/recycle-bin(/page/(?<page>[0-9]+))?']                                     = 'recycle_bin';
$Routes['GET']['/register']                                                                = 'register';
$Routes['POST']['/register']                                                               = 'register';
$Routes['GET']['/reply']                                                                   = 'reply';
$Routes['POST']['/reply']                                                                  = 'reply';
$Routes['GET']['/reset_password/(?<access_token>.*?)']                                     = 'reset_password';
$Routes['POST']['/reset_password/(?<access_token>.*?)']                                    = 'reset_password';
$Routes['GET']['/robots.txt']                                                              = 'robots';
$Routes['GET']['/search.xml']                                                              = 'open_search';
$Routes['GET']['/search/(?<keyword>.*?)(/page/(?<page>[0-9]*))?']                          = 'search';
$Routes['GET']['/settings']                                                                = 'settings';
$Routes['POST']['/settings']                                                               = 'settings';
$Routes['GET']['/sitemap-(?<action>topics|pages|tags|users|index)(-(?<page>[0-9]+))?.xml'] = 'sitemap';
$Routes['GET']['/statistics']                                                              = 'statistics';
$Routes['GET']['/t/(?<id>[0-9]+)(-(?<page>[0-9]*))?']                                      = 'topic';
$Routes['POST']['/t/(?<id>[0-9]+)(-(?<page>[0-9]*))?']                                     = 'topic'; //Delete later
$Routes['GET']['/tag/(?<name>.*?)(/page/(?<page>[0-9]*))?']                                = 'tag';
$Routes['GET']['/tags/following(/page/(?<page>[0-9]*))?']                                  = 'favorite_tags';
$Routes['GET']['/tags(/page/(?<page>[0-9]*))?']                                            = 'tags';
$Routes['GET']['/u/(?<username>.*?)']                                                      = 'user';
$Routes['GET']['/users/following(/page/(?<page>[0-9]*))?']                                 = 'favorite_users';
$Routes['GET']['/upload_controller']                                                       = 'upload_controller';
$Routes['POST']['/upload_controller']                                                      = 'upload_controller';
$Routes['GET']['/view-(?<view>desktop|mobile)']                                            = 'view';

//这里是Routes End


foreach ($Routes as $Method => $SubRoutes) {
	if ($Method === $HTTPMethod) {
		$ParametersVariableName = '_' . $Method;
		foreach ($SubRoutes as $URL => $Controller) {
			if (preg_match("#^" . $URL . "$#i", $ShortRequestURI, $Parameters)) {
				$NotFound = false;
				$Parameters = array_merge($Parameters, $HTTPParameters);
				//var_dump($Parameters);
				foreach ($Parameters as $Key => $Value) {
					if (!is_int($Key)) {
						${$ParametersVariableName}[$Key] = urldecode($Value);
						$_REQUEST[$Key] = urldecode($Value);
					}
				}
				//$MicroTime = explode(' ', microtime());
				//echo number_format(($MicroTime[1] + $MicroTime[0] - $StartTime), 6) * 1000;
				$UrlPath = $Controller;
				break 2;
			}
		}
		break;
	}
}

if ($NotFound === true) {
	require(__DIR__ . '/404.php');
	exit();
}

if ($Config['MobileDomainName'] && $_SERVER['HTTP_HOST'] != $Config['MobileDomainName'] && $CurView == 'mobile' && !$IsApp && $UrlPath != 'view') {
	//如果是手机，则跳转到移动版
	header("HTTP/1.1 302 Moved Temporarily");
	header("Status: 302 Moved Temporarily");
	header('Location: ' . $CurProtocol . $Config['MobileDomainName'] . $RequestURI);
	exit();
}

require(__DIR__ . '/controller/' . $UrlPath . '.php');

//以上为Original Edition
//新版版本整合了controll目录下的favorite_tags.php
require(__DIR__ . '/common.php');
require(LanguagePath . 'favorite_tags.php');
require(__DIR__ . '/controller/' . $UrlPath . '.php');
//require(__DIR__ . '/language/' . ForumLanguage . '/home.php');
//require(__DIR__ . '/language/' . ForumLanguage . '/favorite_tags.php');
//角色权限指定，favorite_tags.php中是1，作为主页由于需要改为0
Auth(0);
$Page      = intval(Request('Get', 'page'));
//$Page = Request('Get', 'page');
$TotalPage = ceil($Config['NumTopics'] / $Config['TopicsPerPage']);
if (($Page < 0 || $Page == 1) && !$IsApp) {
	header('location: ' . $Config['WebsitePath'] . '/');
	exit;
}
/*
if ($Page < 0 || $Page == 1) {
	header('location: ' . $Config['WebsitePath'] . '/tags/following');
	exit;
}
*/
if ($Page > $TotalPage) {
	header('location: ' . $Config['WebsitePath'] . '/page/' . $TotalPage);
	exit;
}
/*
if ($Page == 0)
	$Page = 1;
$TopicsArray = array();
*/
if ($Page == 0)
	$Page = 1;
		$TagsFollowing = $DB->query('SELECT * FROM ' . PREFIX . 'favorites force index(UsersFavorites) Where UserID=? and Type=2', array(
	$CurUserID
));
$TopicIDArray  = array();

if ($MCache && $Page == 1) {
	$TopicsArray = $MCache->get(MemCachePrefix . 'Homepage');
}

//增加判断，如果用户未登录看到所有帖子，如果用户登录，看到关注的贴咖下的帖子
if(!$CurUserID && $UrlPath != 'login' && $UrlPath != 'register' && $UrlPath != 'oauth'){
//展示所有帖子
	if (!$TopicsArray) {
		if ($Page <= 10) {
			$TopicsArray = $DB->query('SELECT `ID`, `Topic`, `Tags`, `UserID`, `UserName`, `LastName`, `LastTime`, `Replies` 
				FROM ' . PREFIX . 'topics force index(LastTime) 
				WHERE IsDel=0 
				ORDER BY LastTime DESC 
				LIMIT ' . ($Page - 1) * $Config['TopicsPerPage'] . ',' . ($Config['TopicsPerPage'] + 1));
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
						LIMIT ' . ($Page - 1) * $Config['TopicsPerPage'] . ',1) 
					and IsDel=0 
				ORDER BY LastTime DESC 
				LIMIT ' . $Config['TopicsPerPage']);
		}
	}

} else{

//展示关注的贴咖下的帖子

	if ($TagsFollowing)
		$TopicIDArray = $DB->column('SELECT TopicID 
			FROM ' . PREFIX . 'posttags force index(TagsIndex) 
			Where TagID in (?) 
			ORDER BY TopicID DESC LIMIT ' . ($Page - 1) * $Config['TopicsPerPage'] . ',' . $Config['TopicsPerPage'], ArrayColumn($TagsFollowing, 'FavoriteID'));
	array_unique($TopicIDArray);
		$TopicsArray = array();
	if ($TopicIDArray)
		$TopicsArray = $DB->query('SELECT `ID`, `Topic`, `Tags`, `UserID`, `UserName`, `LastName`, `LastTime`, `Replies` 
			FROM ' . PREFIX . 'topics force index(PRI) 
			Where ID in (?) and IsDel=0 
			ORDER BY LastTime DESC', 
		$TopicIDArray);
}


$DB->CloseConnection();
$PageTitle = $Lang['My_Following_Tags'];
//$PageTitle .= $Page > 1 ? ' Page' . $Page : '';
$PageTitle = $Page > 1 ? ' Page' . $Page . '-' : '';
$PageTitle .= $Config['SiteName'];
$PageMetaDesc = htmlspecialchars(mb_substr($Config['SiteDesc'], 0, 150, 'utf-8'));
//$ContentFile = $TemplatePath . 'favorite_tags.php';
$ContentFile  = $TemplatePath . 'home.php';
include($TemplatePath . 'layout.php');