<?php
if (!defined('InternalAccess')) exit('error: 403 Access Denied');
?>
<!-- main-content start -->
<div class="main-content">
	<div class="title">
		<a href="<?php echo $Config['WebsitePath']; ?>/">
			<?php echo $Config['SiteName']; ?>
		</a>
		&raquo;
		<a href="<?php echo $Config['WebsitePath']; ?>/tags">
			<?php echo $Lang['Tag']; ?>
		</a>
		&raquo;
		<?php echo $TagInfo['Name']; ?>
	</div>
	<div class="main-box home-box-list">
		<?php
		foreach ($TopicsArray as $Topic) {
			?>
			<div class="post-list">
				<div class="item-avatar">
					<a href="<?php echo $Config['WebsitePath']; ?>/u/<?php echo urlencode($Topic['UserName']); ?>"
					   target="_blank">
						<?php echo GetAvatar($Topic['UserID'], $Topic['UserName'], 'middle'); ?>
					</a>
				</div>
				<div class="item-content">
					<h2>
						<a href="<?php echo $Config['WebsitePath']; ?>/t/<?php echo $Topic['ID']; ?>"><?php echo $Topic['Topic']; ?></a>
					</h2>
					<span class="item-tags">
					<?php
					if ($Topic['Tags']) {
						foreach (explode("|", $Topic['Tags']) as $Tag) {
							?><a
							href="<?php echo $Config['WebsitePath']; ?>/tag/<?php echo urlencode($Tag); ?>"><?php echo $Tag; ?></a>
							<?php
						}
					}
					?>
				</span>
					<span class="item-date float-right">
					<a href="<?php echo $Config['WebsitePath']; ?>/u/<?php echo urlencode($Topic['UserName']); ?>"><?php echo $Topic['UserName']; ?></a>&nbsp;•&nbsp;
						<?php echo FormatTime($Topic['LastTime']);
						if ($Topic['Replies']) {
							?>&nbsp;•&nbsp;<?php echo $Lang['Last_Reply_From']; ?>&nbsp;<a
									href="<?php echo $Config['WebsitePath']; ?>/u/<?php echo urlencode($Topic['LastName']); ?>"><?php echo $Topic['LastName']; ?></a>
						<?php } ?>
				</span>
				</div>
				<?php if ($Topic['Replies']) { ?>
					<div class="item-count">
						<a href="<?php echo $Config['WebsitePath']; ?>/t/<?php echo $Topic['ID']; ?>"
						   target="_blank"><?php echo $Topic['Replies']; ?></a>
					</div>
				<?php } ?>
				<div class="c"></div>
			</div>
			<?php
		}
		?>
		<div class="pagination">
			<?php Pagination('/tag/' . $TagInfo['Name'] . '/page/', $Page, $TotalPage); ?>
			<div class="c"></div>
		</div>
	</div>
</div>
<!-- main-content end -->
<!-- main-sider start -->
<div class="main-sider">
	<div class="sider-box">
		<div class="sider-box-title"><?php echo $Lang['Tag']; ?>：<?php echo $TagName; ?></div>
		<div class="sider-box-content btn">
			<?php echo GetTagIcon($TagInfo['ID'], $TagInfo['Icon'], $TagInfo['Name'], 'large'); ?>
			<p><span><h1><?php echo $TagInfo['Name']; ?></h1></span></p>
			<p>
			<div id="TagDescription">
				&nbsp;&nbsp;&nbsp;&nbsp;
				<?php echo $TagInfo['Description']; ?>
				<br/>
			</div>
			<div id="EditTagDescription" class="hide">
				<p>
					<textarea id="TagDescriptionInput"
							  style="width:230px;height:160px;"><?php echo $TagInfo['Description']; ?></textarea>
				</p>
				<p>
					<input type="button" value="<?php echo $Lang['Submit']; ?>" class="textbtn"
						   onclick="JavaScript:SubmitTagDescription(<?php echo $TagInfo['ID']; ?>);">
					&nbsp;&nbsp;&nbsp;&nbsp;
					<input type="button" value="<?php echo $Lang['Cancel']; ?>" class="textbtn"
						   onclick="JavaScript:CompletedEditingTagDescription();">
				</p>
				<p></p>
			</div>
			</p>
			<p>
				<?php
				if ($CurUserID) {
					?>
					<a href="###" onclick="javascript:Manage(<?php echo $TagInfo['ID']; ?>, 4, 2, false, this);">
						<?php echo $IsFavorite ? $Lang['Unfollow'] : $Lang['Follow']; ?></a>
					<?php
				}
				if ($CurUserRole >= 3) {
					?>
					<script type="text/javascript">
						loadScript("<?php echo $Config['WebsitePath']; ?>/static/js/jquery.async.uploader.js?version=<?php echo CARBON_FORUM_VERSION; ?>", function () {
							loadScript("<?php echo $Config['WebsitePath']; ?>/static/js/default/tag.function.js?version=<?php echo CARBON_FORUM_VERSION; ?>", function () {
							});
						});
					</script>
					<a href="###" class="edittag" onclick="javascript:EditTagDescription();">
						<?php echo $Lang['Edit_Description']; ?>
					</a>
					<div class="c"></div>
					<a href="###" onclick="javascript:UploadTagIcon(<?php echo $TagInfo['ID']; ?>);">
						<?php echo $Lang['Upload_A_New_Icon']; ?>
					</a>
					<?php
				}
				if ($CurUserRole >= 4) {
					?>
					<a href="###" onclick="javascript:Manage(<?php echo $TagInfo['ID']; ?>, 5, 'SwitchStatus', true, this);">
						<?php echo $TagInfo['IsEnabled'] ? $Lang['Disable_Tag'] : $Lang['Enable_Tag']; ?>
					</a>
					<?php
				}
				?>
			</p>
			<ul class="grey">
				<li>
					<?php echo $TagInfo['TotalPosts']; ?><?php echo $Lang['Topics']; ?>
					&nbsp;&nbsp;&nbsp;&nbsp;
					<?php echo $TagInfo['Followers']; ?><?php echo $Lang['Followers']; ?>
				</li>
				<li><?php echo $Lang['Created_In']; ?><?php echo FormatTime($TagInfo['DateCreated']); ?></li>
				<li><?php echo $Lang['Last_Updated_In']; ?><?php echo FormatTime($TagInfo['MostRecentPostTime']); ?></li>
			</ul>
		</div>
	</div>
	<?php
	include($TemplatePath . 'sider.php');
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
<!-- main-sider end -->

<!-- 话题（标签，贴咖）下添加创建帖子的区域，引用new.php -->


<!-- main-content start -->

<?php
if($CurUserID){
?>
<script>
var AllowEmptyTags = <?php echo $Config["AllowEmptyTags"]; ?>;//允许空话题
var MaxTagNum = <?php echo $Config["MaxTagsNum"]; ?>;//最多的话题数量
var MaxTitleChars = <?php echo $Config['MaxTitleChars']; ?>;//主题标题最多字节数
var MaxPostChars = <?php echo $Config['MaxPostChars']; ?>;//主题内容最多字节数
loadScript("<?php echo $Config['WebsitePath']; ?>/static/editor/ueditor.config.js?version=<?php echo $Config['Version']; ?>",function() {
	loadScript("<?php echo $Config['WebsitePath']; ?>/static/editor/ueditor.all.min.js?version=<?php echo $Config['Version']; ?>",function(){
		loadScript("<?php echo $Config['WebsitePath']; ?>/language/<?php echo ForumLanguage; ?>/<?php echo ForumLanguage; ?>.js?version=<?php echo $Config['Version']; ?>",function(){
			loadScript("<?php echo $Config['WebsitePath']; ?>/static/js/default/new.function.js?version=<?php echo $Config['Version']; ?>",function(){
				$("#editor").empty();
				InitNewTopicEditor();
				$.each(<?php echo json_encode(ArrayColumn($HotTagsArray, 'Name')); ?>,function(Offset,TagName) {
					TagsListAppend(TagName, Offset);
				});
				console.log('editor loaded.');
			});
		});
	});
});
</script>
<div class="main-content">
		<!-- 	下面单个div引用topic.php和new.php，做了简单更改  -->
	<a name="reply"></a> 
	<div class="main-box">		
			<form name="NewForm" onkeydown="if(event.keyCode==13)return false;">
			<input type="hidden" name="FormHash" value="<?php echo $FormHash; ?>" />
			<input type="hidden" name="ContentHash" value="" />
			<p><input type="text" name="Title" id="Title" value="<?php echo htmlspecialchars($Title); ?>" style="width:624px;" placeholder="<?php echo $Lang['Title']; ?>" /></p>
			<p>
				<div id="editor" style="width:100%;height:180px;">Loading……</div>
				<script type="text/javascript">
				var Content='<?php echo $Content; ?>';
				</script>
			</p>
			<input type="hidden" id="existTagName" value="<?php echo $TagInfo['Name']; ?>" />
			<!-- <p>
							<div class="tags-list bth" style="width:624px;height:33px;" onclick="JavaScript:document.NewForm.AlternativeTag.focus();">
								<span id="SelectTags" class="btn"></span>					
								<input type="text" name="AlternativeTag" id="AlternativeTag" maxlength="0" value="<?php echo $TagInfo['Name']; ?>"  class="tag-input" onfocus="JavaScript:GetTags();" placeholder="<?php echo $Lang['Add_Tags']; ?>" />								
							</div>
						</p> -->	
			<div class="float-right"><input type="button" value="<?php echo $Lang['Submit']; ?>(Ctrl+Enter)" name="submit" class="textbtn" id="PublishButton" onclick="JavaScript:CreateNewTopicToTag();"/></div>
			<div class="c"></div> 
			<p></p>
			</form>
	</div>
</div>

<?php
}
?>
<!-- main-content end -->