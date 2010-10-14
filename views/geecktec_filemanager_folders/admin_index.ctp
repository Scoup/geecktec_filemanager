<?php
		echo $this->Html->css(array(
			'/geecktec_filemanager/css/themes/default/style',			// jsTree CSS
			'/geecktec_filemanager/css/jquery-layout',					// jquery layout CSS
			'/geecktec_filemanager/css/basic',							// Basic CSS
			'/geecktec_filemanager/css/jquery.contextMenu',				// ContextMenu
			'/geecktec_filemanager/css/uploader/fileuploader',			// Valums Fileuploader CSS
//			'/geecktec_filemanager/css/layout-custom',					// Custom Css for jquery-layout
		));  
		
		echo $this->Html->script(array(
			'/geecktec_filemanager/js/jquery.layout.1-3-2',				// Page Layout Manager
			'/geecktec_filemanager/js/jquery.contextMenu',				// ContextMenu
			'/geecktec_filemanager/js/jquery.jstree.js',				// jsTree
			'/geecktec_filemanager/js/_lib/jquery.hotkeys',				// jsTree hotkeys plugin
			'/geecktec_filemanager/js/_lib/jquery.cookie',				// jsTree cookie plugin

			'/geecktec_filemanager/js/fileuploader',					// Valums FileUploader JS
			'/geecktec_filemanager/js/commom',
		), array('inline' => false));		
?>
<script type="text/javascript">
//Ajax Links
var getChildren = '<?php echo $this->webroot?>admin/geecktec_filemanager/geecktec_filemanager_folders/ajaxGetChildren';
var addNode = '<?php echo $this->webroot?>admin/geecktec_filemanager/geecktec_filemanager_folders/ajaxAddNode';
var renameNode = '<?php echo $this->webroot?>admin/geecktec_filemanager/geecktec_filemanager_folders/ajaxRenameNode';
var removeNode = '<?php echo $this->webroot?>admin/geecktec_filemanager/geecktec_filemanager_folders/ajaxRemoveNode';
var moveNode = '<?php echo $this->webroot?>admin/geecktec_filemanager/geecktec_filemanager_folders/ajaxMoveNode';
var ajaxRefreshScreen = '<?php echo $this->webroot?>admin/geecktec_filemanager/geecktec_filemanager_folders/ajaxRefreshScreen/';
var ajaxSearch = '<?php echo $this->webroot?>admin/geecktec_filemanager/geecktec_filemanager_folders/ajaxSearch';

//Images Links
var image_Root = "<?php echo $this->webroot?>geecktec_filemanager/img/root.png";
var image_File = "<?php echo $this->webroot?>geecktec_filemanager/img/file.png";
var image_Folder = "<?php echo $this->webroot?>geecktec_filemanager/img/folder.png";
</script>

<h2>Geecktec Filemanager</h2>
<?php echo $this->element('my_menu');?>
<div id="filemanager">

	<div id="mmenu" class="ui-layout-north">
		<input type="button" id="add_folder" value="add folder" style="display:block; float:left;"/>
		<input type="button" id="rename" value="rename" style="display:block; float:left;"/>
		<input type="button" id="remove" value="remove" style="display:block; float:left;"/>
		<input type="button" id="cut" value="cut" style="display:block; float:left;"/>
		<input type="button" id="paste" value="paste" style="display:block; float:left;"/>
		<input type="button" id="clear_search" value="clear" style="display:block; float:right;"/>
		<input type="button" id="search" value="search" style="display:block; float:right;"/>
		<input type="text" id="text" value="" style="display:block; float:right;" />
	</div> 
	
	<div class="outer-center">
			<div class="inner-center">
				<div id="options">
					<a href="#" rel="#filemanager-send"><?php __('Send File');?></a>
					<a href="#"><?php __('Update');?></a>
					<a href="#" rel="#filemanager-config"><?php __('Config');?></a>
				</div>
				<div id="filemanager-send">
					<div id="file-uploader">		
						<noscript>			
						<?php
							echo $this->Form->create('GeecktecFile', array('action' => 'ajaxSendFile'));
							echo $this->Form->input('GeecktecFile.folder_id');
							echo $this->Form->input('GeecktecFile.send', array('action' => 'title'));
							echo $this->Form->input('GeecktecFile.file');
							echo $this->Form->end(_('Send'));
						?>
						</noscript>         
					</div>
				</div>
				<div id="filemanager-config">Div de configuração</div>
				
				<div id="file-view">
					Center
					<p><a href="http://layout.jquery-dev.net/demos.html"><b>Go to the Demos page</b></a></p>
				</div>
				
			</div> 

	</div> 
	<!-- the tree container (notice NOT an UL node) -->
	<div class="outer-west" id="folder-tree"></div> 
</div>
<div class="modal" id="yesno">
	<h2>This is a modal dialog</h2>

	<p>
		You can only interact with elements that are inside this dialog.
		To close it click a button or use the ESC key.
	</p>

	<!-- yes/no buttons -->
	<p>
		<button class="close"> Yes </button>
		<button class="close"> No </button>
	</p>
</div>
<script type="text/javascript">
$(function(){
	overlay = $(".modal").overlay({

	// some mask tweaks suitable for modal dialogs
	mask: {
		color: '#ebecff',
		loadSpeed: 200,
		opacity: 0.9
	},
	fixed: false,

	closeOnClick: false
	}).data('overlay');
});
</script>