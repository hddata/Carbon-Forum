<?php
if (!defined('InternalAccess')) exit('error: 403 Access Denied');
?>
<!-- main-content start -->
<div class="main-content-home">
	<div class="main-box home-box-list">
		<?php
		foreach ($TopicsArray as $Topic) {
		?>
			<div class="post-list">
				<div class="item-avatar">
					<a href="<?php echo $Config['WebsitePath']; ?>/u/<?php echo urlencode($Topic['UserName']); ?>">
						<?php echo GetAvatar($Topic['UserID'], $Topic['UserName'], 'middle'); ?>
					</a>
				</div>
				<div class="item-content">
					<h2>
						<a href="<?php echo $Config['WebsitePath']; ?>/t/<?php echo $Topic['ID']; ?>">
							<?php echo $Topic['Topic']; ?>
						</a>
					</h2>
					<span class="item-tags">
						<?php
						if($Topic['Tags']){
							foreach (explode("|", $Topic['Tags']) as $Tag) {
						?><a href="<?php echo $Config['WebsitePath']; ?>/tag/<?php echo urlencode($Tag); ?>"><?php echo $Tag; ?></a>
							<?php
							}
						}
						?></span><span class="item-date float-right"><a href="<?php echo $Config['WebsitePath']; ?>/u/<?php echo urlencode($Topic['UserName']); ?>"><?php echo $Topic['UserName']; ?></a>&nbsp;•&nbsp;<?php echo FormatTime($Topic['LastTime']); 
							if($Topic['Replies']){
						?>&nbsp;•&nbsp;<?php echo $Lang['Last_Reply_From']; ?>&nbsp;<a href="<?php echo $Config['WebsitePath']; ?>/u/<?php echo urlencode($Topic['LastName']); ?>"><?php echo $Topic['LastName']; ?></a><?php } ?>
					</span>
				</div>
							<?php if($Topic['Replies']){ ?>
							<div class="item-count">
							<a href="<?php echo $Config['WebsitePath']; ?>/t/<?php echo $Topic['ID']; ?>"><?php echo $Topic['Replies']; ?></a>
							</div>
							<?php } ?>
							<div class="c"></div>
			</div>
							
		<?php
		}
		?>
		<!--因为全页码显示不对，改为单页码显示，引用favorite_tags.php（前端）
								<div class="pagination">
<?php
/*
foreach (range(1, 10) as $TotalPage) {
	foreach (range(1, $TotalPage) as $Page) {
		echo '<div class="pagination">';
		Pagination("/page/",$Page,$TotalPage);
		# code...
		echo "</div>";
	}
}
*/
Pagination("/page/",$Page,$TotalPage);
?>
									<div class="c">
									</div>
								</div>
				</div>
			</div>
			-->
						<div class="pagination">
						<?php PaginationSimplified('/page/', $Page, empty($TopicsArray)); ?>
						<div class="c"></div>
						</div>
				</div>
			</div>
			<!-- main-content end -->
			<!-- main-sider start -->
<!-- 原版
			<div class="main-sider">
				<?php
				include($TemplatePath.'sider.php');
				?>
				<div class="sider-box">
					<div class="sider-box-title"><?php echo $Lang['Website_Statistics']; ?></div>
					<div class="sider-box-content">
						<ul>
							<li><?php echo $Lang['Topics_Number']; ?>：<?php echo $Config['NumTopics']; ?></li>
							<li><?php echo $Lang['Posts_Number']; ?>：<?php echo $Config['NumPosts']; ?></li>
							<li><?php echo $Lang['Tags_Number']; ?>：<?php echo $Config['NumTags']; ?></li>
							<li><?php echo $Lang['Users_Number']; ?>：<?php echo $Config['NumUsers']; ?></li>
						</ul>
					</div>
				</div>
			</div>
			 -->
			<!-- main-sider end -->
			<!-- 我加的 -->
			<!-- main-sider start -->
			
			<div class="main-sider-home">
			<!-- 增加判断对未登录显示“贴咖广场”，登录显示“我的贴咖”  -->
			<?php
				if($CurUserID){
			?>
				<div class="sider-box">
					<div class="sider-box-title">
						<?php echo $Lang['My_Following_Tags']; ?>
						<!-- “我的贴咖”，增加查看跟多，引用sider.php的查看跟多部分代码  -->
						<span class="float-right"><a href="<?php echo $Config['WebsitePath']; ?>/tags"><?php echo $Lang['Show_More']; ?></a></span>
					</div>
					<div class="sider-box-content btn">
						<?php foreach ($TagsFollowing as $Tag) {?>
						<a href="<?php echo $Config['WebsitePath']; ?>/tag/<?php echo urlencode($Tag['Title']); ?>" target="_blank"><?php echo $Tag['Title']; ?></a>
						<?php } ?>
					</div>
				</div>
			<?php
			} else{
			?>
				<?php if(!$CurUserID && $UrlPath != 'login' && $UrlPath != 'register' && $UrlPath != 'oauth'){ ?>
				<?php
				}
				if($HotTagsArray) {
				?>
				<div class="sider-box">
					<div class="sider-box-title">
						<?php echo $Lang['Hot_Tags']; ?>
						<span class="float-right"><a href="<?php echo $Config['WebsitePath']; ?>/tags"><?php echo $Lang['Show_More']; ?></a></span>
					</div>
					<div class="sider-box-content btn">
						<?php foreach ($HotTagsArray as $Tag) {?>
						<a href="<?php echo $Config['WebsitePath']; ?>/tag/<?php echo urlencode($Tag['Name']); ?>"><?php echo $Tag['Name']; ?></a>
						<?php } ?>
					</div>
				</div>
				<?php
				}
				?>
			<?php
				}
			?>	
				<!-- 向sider.php传入参数，进行判断，登录的首页不出现“贴咖广场”  -->
				<?php $hideHottag=hideHottag ?>
				<?php
				include($TemplatePath.'sider.php');
				?>
				<!--上面这段是补充从favorite_tags.php摘取过来的-->
				<div class="sider-box">
					<div class="sider-box-title"><?php echo $Lang['Website_Statistics']; ?></div>
					<div class="sider-box-content">
						<ul>
							<li><?php echo $Lang['Topics_Number']; ?>：<?php echo $Config['NumTopics']; ?></li>
							<li><?php echo $Lang['Posts_Number']; ?>：<?php echo $Config['NumPosts']; ?></li>
							<li><?php echo $Lang['Tags_Number']; ?>：<?php echo $Config['NumTags']; ?></li>
							<li><?php echo $Lang['Users_Number']; ?>：<?php echo $Config['NumUsers']; ?></li>
						</ul>
					</div>
				</div>
			</div>
			<!-- main-sider end -->