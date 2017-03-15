<?php
//局部修改，改成指定已经选择用户关注的话题
/*
if (!defined('InternalAccess')) exit('error: 403 Access Denied');
?>
<!-- main-content start -->
<script>
var MaxTagNum = <?php echo $Config["MaxTagsNum"]; ?>;//最多的话题数量
var MaxTitleChars = <?php echo $Config['MaxTitleChars']; ?>;//主题标题最多字节数
var MaxPostChars = <?php echo $Config['MaxPostChars']; ?>;//主题内容最多字节数
loadScript("<?php echo $Config['WebsitePath']; ?>/static/editor/ueditor.config.js?version=<?php echo $Config['Version']; ?>",function() {
	loadScript("<?php echo $Config['WebsitePath']; ?>/static/editor/ueditor.all.min.js?version=<?php echo $Config['Version']; ?>",function(){
		loadScript("<?php echo $Config['WebsitePath']; ?>/language/<?php echo ForumLanguage; ?>/<?php echo ForumLanguage; ?>.js?version=<?php echo $Config['Version']; ?>",function(){
			loadScript("<?php echo $Config['WebsitePath']; ?>/static/js/new.function.js?version=<?php echo $Config['Version']; ?>",function(){
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
		<div class="main-box">
			<form name="NewForm" onkeydown="if(event.keyCode==13)return false;">
			<input type="hidden" name="FormHash" value="<?php echo $FormHash; ?>" />
			<input type="hidden" name="ContentHash" value="" />
			<p><input type="text" name="Title" id="Title" value="<?php echo htmlspecialchars($Title); ?>" style="width:624px;" placeholder="<?php echo $Lang['Title']; ?>" /></p>
		</div>
			<div id="editor" style="width:100%;height:320px;">Loading……</div>
			<script type="text/javascript">
			var Content='<?php echo $Content; ?>';
			</script>
		<div class="main-box" style="margin-top:20px;">
			<p>
				<div class="tags-list bth" style="width:624px;height:33px;border-bottom-width:2px;" onclick="JavaScript:document.NewForm.AlternativeTag.focus();">
					<span id="SelectTags" class="btn"></span>
					<input type="text" name="AlternativeTag" id="AlternativeTag" value="" class="tag-input" onfocus="JavaScript:GetTags();" placeholder="<?php echo $Lang['Add_Tags']; ?>" />
				</div>
			</p>
			<p>
				<div id="TagsList" class="btn">
				</div>
			</p>
			<p><div class="text-center"><input type="button" value="<?php echo $Lang['Submit']; ?>(Ctrl+Enter)" name="submit" class="textbtn" onclick="JavaScript:CreateNewTopic();" id="PublishButton" /></div><div class="c"></div></p>
			</form>
	</div>
</div>
<!-- main-content end -->
<!-- main-sider start -->
<div class="main-sider">
	<?php include($TemplatePath.'sider.php'); ?>
</div>
<!-- main-sider end -->
*/


if (!defined('InternalAccess')) exit('error: 403 Access Denied');
?>
<!-- main-content start -->
<script>
var MaxTagNum = <?php echo $Config["MaxTagsNum"]; ?>;//最多的话题数量
var MaxTitleChars = <?php echo $Config['MaxTitleChars']; ?>;//主题标题最多字节数
var MaxPostChars = <?php echo $Config['MaxPostChars']; ?>;//主题内容最多字节数
loadScript("<?php echo $Config['WebsitePath']; ?>/static/editor/ueditor.config.js?version=<?php echo $Config['Version']; ?>",function() {
	loadScript("<?php echo $Config['WebsitePath']; ?>/static/editor/ueditor.all.min.js?version=<?php echo $Config['Version']; ?>",function(){
		loadScript("<?php echo $Config['WebsitePath']; ?>/language/<?php echo ForumLanguage; ?>/<?php echo ForumLanguage; ?>.js?version=<?php echo $Config['Version']; ?>",function(){
			loadScript("<?php echo $Config['WebsitePath']; ?>/static/js/new.function.js?version=<?php echo $Config['Version']; ?>",function(){
				$("#editor").empty();
				InitNewTopicEditor();
/*				$.each(<?php echo json_encode(ArrayColumn($HotTagsArray, 'Name')); ?>,function(Offset,TagName) {
					TagsListAppend(TagName, Offset);
				});*/
				console.log('editor loaded.');
			});
		});
	});
});
</script>
<div class="main-content">
		<div class="main-box">
			<form name="NewForm" onkeydown="if(event.keyCode==13)return false;">
			<input type="hidden" name="FormHash" value="<?php echo $FormHash; ?>" />
			<input type="hidden" name="ContentHash" value="" />
			<p><input type="text" name="Title" id="Title" value="<?php echo htmlspecialchars($Title); ?>" style="width:624px;" placeholder="<?php echo $Lang['Title']; ?>" /></p>
			</div>
				<div id="editor" style="width:100%;height:320px;">Loading……</div>
				<script type="text/javascript">
				var Content='<?php echo $Content; ?>';
				</script>
			<div class="main-box" style="margin-top:20px;">
			<p>
				<div class="tags-list bth" style="width:624px;height:33px;border-bottom-width:2px;" onclick="JavaScript:document.NewForm.AlternativeTag.focus();">
					<span id="SelectTags" class="btn"></span>
					<input type="text" name="AlternativeTag" id="AlternativeTag" maxlength="0" value="" class="tag-input" onfocus="JavaScript:GetTags();" placeholder="<?php echo $Lang['Add_Tags']; ?>" />
				</div>
			</p>
			<p>
				<div id="TagsList" class="btn">
				</div>
			</p>
			<p><div class="text-center"><input type="button" value="<?php echo $Lang['Submit']; ?>(Ctrl+Enter)" name="submit" class="textbtn" onclick="JavaScript:CreateNewTopic();" id="PublishButton" /></div><div class="c"></div></p>
			</form>
	</div>
</div>
<!-- main-content end -->
<!-- main-sider start -->

			<div class="main-sider">
				<div class="sider-box">
					<div class="sider-box-title"><?php echo $Lang['New_Topic']; ?></div>
					<div class="sider-box-content btn">
					<input  type="text" name="CreateTagContent" id="CreateTagContent" value=""  class="tagcontent-input" placeholder="<?php echo $Lang['CreateTagContent']; ?>" />
					</div>			
 					<p><div class="text-center"><input type="button" value="<?php echo $Lang['CreateOneTag']; ?>" name="CreateOneTag" class="textbtn" onclick="JavaScript:CreateTags();" id="TagPublishButton" /></div><div class="c"></div></p>

 				</div>

<div class="main-sider">
	<?php include($TemplatePath.'sider.php'); ?>
</div>
<!-- main-sider end -->