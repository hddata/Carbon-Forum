<?php
if (!defined('InternalAccess')) exit('error: 403 Access Denied');

//只有上一页下一页的分页
function PaginationSimplified($PageUrl, $CurrentPage, $IsLastPage = false)
{
	global $Config, $Lang;
	$PageUrl = $Config['WebsitePath'] . $PageUrl;
	if ($CurrentPage != 1)
		echo '<div class="float-left"><a class="previous-next" href="' . $PageUrl . ($CurrentPage - 1) . '">&lsaquo;&lsaquo;' . $Lang['Page_Previous'] . '</a></div>';
	echo '<span class="currentpage">' . $CurrentPage . '</span>';
	if (!$IsLastPage)
		echo '<div class="float-right"><a href="' . $PageUrl . ($CurrentPage + 1) . '">' . $Lang['Page_Next'] . '&rsaquo;&rsaquo;</a></div>';
}

//分页
function Pagination($PageUrl, $CurrentPage, $TotalPage)
{
	if ($TotalPage <= 1)
		return false;
	global $Config, $Lang;
	$PageUrl = $Config['WebsitePath'] . $PageUrl;
	$PageLast = $CurrentPage - 1;
	$PageNext = $CurrentPage + 1;
	//echo '<span id="pagenum"><span class="currentpage">' . $CurrentPage . '/' . $TotalPage . '</span>';
	if ($CurrentPage != 1)
		echo '<div class="float-left"><a href="' . $PageUrl . $PageLast . '">&lsaquo;&lsaquo;' . $Lang['Page_Previous'] . '</a></div>';
	if ($CurrentPage <= 4) {
		$PageiStart = 1;
	} else if ($CurrentPage >= ($TotalPage - 3)) {
		$PageiStart = $TotalPage - 7;
	} else {
		$PageiStart = $CurrentPage - 3;
	}

	if ($CurrentPage + 3 >= $TotalPage) {
		$PageiEnd = $TotalPage;
	} else if ($CurrentPage <= 3 && $TotalPage >= 8) {
		$PageiEnd = 8;
	} else {
		$PageiEnd = $CurrentPage + 3;
	}
	if ($CurrentPage >= 5 && $PageiStart > 1)
		echo '<a href="' . $PageUrl . '1">1</a>';
	for ($Pagei = $PageiStart; $Pagei <= $PageiEnd; $Pagei++) {
		if ($CurrentPage == $Pagei) {
			echo '<span class="currentpage">' . $Pagei . '</span>';
		} elseif ($Pagei > 0 && $Pagei <= $TotalPage) {
			echo '<a href="' . $PageUrl . $Pagei . '">' . $Pagei . '</a>';
		}
	}
	if ($CurrentPage + 3 < $TotalPage && $PageiEnd < $TotalPage) {
		echo '<a href="' . $PageUrl . $TotalPage . '">' . $TotalPage . '</a>';
	}
	if ($CurrentPage != $TotalPage) {
		echo '<div class="float-right"><a href="' . $PageUrl . $PageNext . '">' . $Lang['Page_Next'] . '&rsaquo;&rsaquo;</a></div>';
	}
	//echo '&nbsp;&nbsp;&nbsp;<input type="text" onkeydown="JavaScript:if((event.keyCode==13)&&(this.value!=\'\')){window.location=\''.$PageUrl.'\'+this.value;}" onkeyup="JavaScript:if(isNaN(this.value)){this.value=\'\';}" size=4 title="请输入要跳转到第几页,然后按下回车键">';
	//echo '</span>';
}


$LayoutPageTitle = ($CurUserID && $CurUserInfo['NewNotification'] ? str_replace('{{NewMessage}}', $CurUserInfo['NewNotification'], $Lang['New_Message']) : '') . $PageTitle . ($UrlPath == 'home' ? '' : ' - ' . $Config['SiteName']);

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
ob_start();
if(!$IsAjax){
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $Lang['Language']; ?>"
	  lang="<?php echo $Lang['Language']; ?>">
<head>
	<meta name="renderer" content="webkit"/>
	<meta http-equiv="Content-Type" content="text/html;charset=utf-8"/>
	<meta http-equiv="Cache-Control" content="no-siteapp"/>
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
	<?php
	if ($Config['MobileDomainName']) {
		?>
		<meta http-equiv="mobile-agent"
			  content="format=xhtml; url=<?php echo $CurProtocol . $Config['MobileDomainName'] . $RequestURI; ?>"/>
		<?php
	}
	if (isset($PageMetaKeyword) && $PageMetaKeyword) {
		echo '<meta name="keywords" content="', $PageMetaKeyword, '" />
';
	}
	if (isset($PageMetaDesc) && $PageMetaDesc) {
		echo '<meta name="description" content="', $PageMetaDesc, '" />
';
	}
	if (IsSSL()) {
		echo '<meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests" />
';
	}
	?>
	<meta name="msapplication-TileImage" content="<?php echo $Config['WebsitePath']; ?>/static/img/retinahd_icon.png"/>
	<title><?php echo $LayoutPageTitle; ?></title>
	<!--link rel="dns-prefetch" href="//<?php echo $Config['MainDomainName']; ?>" />
	<link rel="prefetch" href="//<?php echo $Config['MainDomainName']; ?>" /-->
	<link rel="apple-touch-icon-precomposed"
		  href="<?php echo $Config['WebsitePath']; ?>/static/img/apple-touch-icon-57x57-precomposed.png"/>
	<link rel="apple-touch-icon-precomposed" sizes="72x72"
		  href="<?php echo $Config['WebsitePath']; ?>/static/img/apple-touch-icon-72x72-precomposed.png"/>
	<link rel="apple-touch-icon-precomposed" sizes="114x114"
		  href="<?php echo $Config['WebsitePath']; ?>/static/img/apple-touch-icon-114x114-precomposed.png"/>
	<link rel="apple-touch-icon-precomposed" sizes="144x144"
		  href="<?php echo $Config['WebsitePath']; ?>/static/img/apple-touch-icon-144x144-precomposed.png"/>
	<link rel="apple-touch-icon-precomposed" sizes="180x180"
		  href="<?php echo $Config['WebsitePath']; ?>/static/img/retinahd_icon.png"/>
	<link rel="shortcut icon" type="image/ico" href="<?php echo $Config['WebsitePath']; ?>/favicon.ico"/>
	<link
		href="<?php echo $Config['WebsitePath']; ?>/static/css/default/style.css?version=<?php echo CARBON_FORUM_VERSION; ?>"
		rel="stylesheet" type="text/css"/>
	<link rel="search" type="application/opensearchdescription+xml"
		  title="<?php echo mb_substr($Config['SiteName'], 0, 15, 'utf-8'); ?>"
		  href="<?php echo $Config['WebsitePath']; ?>/search.xml"/>
	<script type="text/javascript">
		var Prefix = "<?php echo PREFIX; ?>";
		var WebsitePath = "<?php echo $Config['WebsitePath'];?>";
		/*我加的，跳转到标签页*/
		function forwardToTagsPage(){
			var tagName = $("#SearchInput").val();
			if (tagName!="") {
				window.open("/tag/"+tagName,"_self");
			};
		}
	</script>
	<script type="text/javascript" charset="utf-8" src="<?php echo $Config['LoadJqueryUrl']; ?>"></script>
	<script type="text/javascript" charset="utf-8"
			src="<?php echo $Config['WebsitePath']; ?>/static/js/default/global.js?version=<?php echo CARBON_FORUM_VERSION; ?>"></script>
	<script type="text/javascript">
		<?php if ($CurUserID) {
			echo 'setTimeout(function() {GetNotification();}, 1);';
		}
		?>
		loadScript(WebsitePath + "/language/<?php echo ForumLanguage; ?>/global.js?version=<?php echo CARBON_FORUM_VERSION; ?>", function () {
		});
	</script>
	<?php echo $Config['PageHeadContent']; ?>
</head>
<body>
<!-- GA统计用的 -->
<?php include_once("analyticstracking.php") ?>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-73021157-1', 'auto');
  ga('send', 'pageview');

</script>
<!-- content wrapper start -->
<div class="wrapper">
	<div class="nav-bar">
		<div class="nav-panel">
			<!-- style="position: fixed;"设置标题栏绝对位置与main中margin-top:60px;共同作用 -->
			<div class="inner-nav-panel">
				<div class="logo">
					<a href="<?php echo $Config['WebsitePath']; ?>/">
						<img src="<?php echo $Config['WebsitePath']; ?>/static/img/logo.png"
							 alt="<?php echo $Lang['Home']; ?>"/>
					</a>
				</div>
				<div class="buttons">
					<!-- 原版，更改目标-增加进入话题按钮，并且修改回车键的功能为进入话题 -->
					<!-- 				
					<div class="searchbox">
						<input type="text" id="SearchInput"
							   onkeydown="javascript:if((event.keyCode==13)&&(this.value!='')){$('#SearchButton').trigger('click');}"
							   placeholder="<?php echo $Lang['Search']; ?>"<?php echo $UrlPath == 'search' && !empty($Keyword) ? ' value="' . $Keyword . '"' : ''; ?> />
						<a href="###" id="SearchButton">
							<div class="icon icon-search"></div>
						</a>
					</div>
					-->
					<!--enterbox留着用-->
					<div class="enterbox">
					</div>		
					<div class="searchbox">
						<input type="text" id="SearchInput" 
								onkeydown="javascript:if((event.keyCode==13)&&(this.value!='')){forwardToTagsPage();}" 
								placeholder="<?php echo $Lang['Search']; ?>"<?php echo $UrlPath=='search'&&!empty($Keyword)?' value="'.$Keyword.'"':'';?> />
						<a href="###" id="SearchButton">
							<div class="icon icon-search"></div>
						</a>
						<a href="###" id="SearchTags" onclick="forwardToTagsPage()">
							<div class="icon icon-totags"></div>
						</a>
					</div>

					<?php
					if ($CurUserID) {
						?>
						<a href="<?php echo $Config['WebsitePath']; ?>/settings"
						   title="<?php echo $Lang['Settings']; ?>"<?php echo $UrlPath == 'settings' ? ' class="buttons-active"' : ''; ?>>
							<div class="icon icon-settings"></div>
						</a>
						<a href="<?php echo $Config['WebsitePath']; ?>/notifications/list#notifications1"
						   title="<?php echo $Lang['Notifications']; ?>"<?php echo $UrlPath == 'notifications' ? ' class="buttons-active"' : ''; ?>
						   onclick="javascript:ShowNotification(0);">
							<div class="icon icon-notifications"></div>
							<span class="icon-messages-num" id="MessageNumber">0</span>
						</a>
						<?php
						if ($CurUserRole == 5) {
							?>
							<a href="<?php echo $Config['WebsitePath']; ?>/dashboard"
							   title="<?php echo $Lang['System_Settings']; ?>"<?php echo $UrlPath == 'dashboard' ? ' class="buttons-active"' : ''; ?>>
								<div class="icon icon-dashboard"></div>
							</a>
						<?php }
						?>
						<!-- <a href="<?php echo $Config['WebsitePath']; ?>/users/following"<?php echo $UrlPath == 'favorite_users' ? ' class="buttons-active"' : ''; ?>><?php echo $Lang['Users_Followed']; ?></a>
					<a href="<?php echo $Config['WebsitePath']; ?>/tags/following"<?php echo $UrlPath == 'favorite_tags' ? ' class="buttons-active"' : ''; ?>><?php echo $Lang['Tags_Followed']; ?></a> -->
						<a href="<?php echo $Config['WebsitePath']; ?>/users/following"<?php echo $UrlPath=='favorite_users'?' class="buttons-active"':''; ?>><?php echo $Lang['Users_Followed']; ?></a>
						<!--首页-->
						<a href="<?php echo $Config['WebsitePath']; ?>/"<?php echo $UrlPath=='index'?' class="buttons-active"':''; ?>>
							<?php echo $Lang['Home']; ?>				
						</a>				
						<!--发新帖-->
						<a href="<?php echo $Config['WebsitePath']; ?>/new"<?php echo $UrlPath == 'new' ? ' class="buttons-active"' : ''; ?>><?php echo $Lang['Create_New_Topic']; ?></a>
						<?php
					} else {
						?>
						<a href="<?php echo $Config['WebsitePath']; ?>/register"<?php echo $UrlPath == 'register' ? ' class="buttons-active"' : ''; ?>>
							<?php echo $Lang['Sign_Up']; ?>
						</a>
						<a href="<?php echo $Config['WebsitePath']; ?>/login"<?php echo $UrlPath == 'login' ? ' class="buttons-active"' : ''; ?>>
							<?php echo $Lang['Log_In']; ?>
						</a>
						<!--首页-->
						<a href="<?php echo $Config['WebsitePath']; ?>/"<?php echo $UrlPath=='index'?' class="buttons-active"':''; ?>>
							<?php echo $Lang['Home']; ?>
						</a>
						<?php
					}
					?>
					<!--a href="<?php echo $Config['WebsitePath']; ?>/explore"<?php echo $UrlPath == 'explore' ? ' class="buttons-active"' : ''; ?>>发现</a-->
					<!--首页-->
					<!--原版，现在迁移到条件语句中,并且更改					
					<a href="<?php echo $Config['WebsitePath']; ?>/"<?php echo $UrlPath == 'home' ? ' class="buttons-active"' : ''; ?>>
						<?php echo $Lang['Home']; ?>
					</a>
					-->
				</div>
				<div class="c"></div>
			</div>
		</div>
		<div class="emptyProgressBar">
			<div class="progressBar" id="progressBar">
				<div class="bar1" id="progressBar1"></div>
			</div>
		</div>
	</div>
	<!-- main start -->
	<div class="main-content"></div>
	<?php
	} else {
		?>
		<title><?php echo $LayoutPageTitle; ?></title>
		<?php
	}
	?>
	<div class="main" id="main">
		<?php
		if ($IsMobile && $Config['MobileDomainName']) {
			?>
			<div class="swtich-to-mobile">
				<a href="<?php echo $CurProtocol . $Config['MainDomainName']; ?>/redirect-mobile?callback=<?php echo urlencode($RequestURI); ?>">
					<?php echo $Lang['Mobile_Version']; ?>
				</a>
			</div>
			<?php
		}
		?>

		<?php
		include($ContentFile);
		?>
		<div class="c"></div>
		<a style="display: none; " rel="nofollow" href="#top" id="go-to-top">▲</a>
	</div>
	<?php
	if (!$IsAjax){
	?>
	<!-- main end -->
	<div class="c"></div>

	<!-- footer start -->
	<div class="copyright">
		<p>
			<?php echo $Config['SiteName']; ?> Powered By © 2006-2026 <a href="http://www.tiekaa.com" target="_blank">TIEKAA</a> V
			<a href="<?php echo $Config['WebsitePath']; ?>/statistics"><?php echo $Lang['Statistics']; ?></a>
			<br/>
			<?php
			$MicroTime = explode(' ', microtime());
			$TotalTime = number_format((microtime(true) - $StartTime) * 1000, 3);
			?>
			Processed in <?php echo $TotalTime; ?> ms,
			<?php echo $DB->querycount; ?> SQL Query(s),
			<?php echo FormatBytes(memory_get_usage(false)); ?> Memory Usage 浙ICP备15030112号-1
		</p>
	</div>
	<!-- footer end -->
</div>
<!-- content wrapper end -->
<?php
if ($Config['PageBottomContent']) {
	echo $Config['PageBottomContent'];
}
?>
</body>
</html>
<?php
}
ob_end_flush();
?>