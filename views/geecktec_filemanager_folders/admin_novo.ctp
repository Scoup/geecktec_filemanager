<?php
		echo $this->Html->css(array(
			'/geecktec_filemanager/css/themes/default/style',			// jsTree CSS
//			'/geecktec_filemanager/css/jquery-layout',					// jquery layout CSS
//			'/geecktec_filemanager/css/basic',							// Basic CSS
			'/geecktec_filemanager/css/jquery.contextMenu',				// ContextMenu
//			'/geecktec_filemanager/css/uploader/fileuploader',			// Valums Fileuploader CSS
//			'/geecktec_filemanager/css/uploader/new',					// Valums Fileuploader CSS
//			'/geecktec_filemanager/css/layout-custom',					// Custom Css for jquery-layout
		));  
		
		echo $this->Html->script(array(	 
//			'/geecktec_filemanager/js/jquery.layout.1-3-2',				// Page Layout Manager
			'/geecktec_filemanager/js/jquery.contextMenu',				// ContextMenu
			'/geecktec_filemanager/js/jquery.jstree.js',				// jsTree
			'/geecktec_filemanager/js/_lib/jquery.hotkeys',				// jsTree hotkeys plugin
			'/geecktec_filemanager/js/_lib/jquery.cookie',				// jsTree cookie plugin

			'/geecktec_filemanager/js/fileuploader',					// Valums FileUploader JS
//			'/geecktec_filemanager/js/commom',
//			'/geecktec_filemanager/js/novo',
			'/geecktec_filemanager/js/filemanager',
		), array('inline' => false));		
?>
<script type="text/javascript">
$(function(){
	$("body").filemanager(
		{
			imageRoot: "<?php echo $this->webroot?>geecktec_filemanager/img/root.png",
			imageFile: "<?php echo $this->webroot?>geecktec_filemanager/img/file.png",
			imageFolder: "<?php echo $this->webroot?>geecktec_filemanager/img/folder.png",

			folderGetChildren: '<?php echo $this->webroot?>admin/geecktec_filemanager/geecktec_filemanager_folders/ajaxGetChildren',
			folderAdd: '<?php echo $this->webroot?>admin/geecktec_filemanager/geecktec_filemanager_folders/ajaxAddNode',
			folderRename: '<?php echo $this->webroot?>admin/geecktec_filemanager/geecktec_filemanager_folders/ajaxRenameNode',
			folderMove: '<?php echo $this->webroot?>admin/geecktec_filemanager/geecktec_filemanager_folders/ajaxMoveNode',
			folderRemove: '<?php echo $this->webroot?>admin/geecktec_filemanager/geecktec_filemanager_folders/ajaxRemoveNode',

			refreshScreen: '<?php echo $this->webroot?>admin/geecktec_filemanager/geecktec_filemanager_folders/ajaxRefreshScreen/',
			ajaxSearch: '<?php echo $this->webroot?>admin/geecktec_filemanager/geecktec_filemanager_folders/ajaxSearch',

			fileRemove:  '<?php echo $this->webroot?>admin/geecktec_filemanager/geecktec_filemanager_files/ajaxDelete/'
		},
		{
			deleteFile: "Tem certeza que deseja apagar isso?"
		} 
	);	
});
</script>
<div id="file-uploaer">       
    <noscript>          
        <p>Please enable JavaScript to use file uploader.</p>
        <!-- or put a simple form for upload here -->
    </noscript>         
</div>
              
<div id="filemanager">
	<div id="gtbox-file-view" class="ui-gtgrid ui-widget ui-widget-content ui-corner-all">
		<div style="display:none"></div>
		
		<div id="gtview-file-view" class="ui-gtfile-view">
			<div class="ui-gtgrid-titlebar ui-widget-header ui-corner-tl ui-corner-tr ui-helper-clearfix">
				<a href="#" role="link" class="ui-gtgrid-titlebar-close ui-state-default ui-corner-all HeaderButton" style="right: 0pt;">
					<span class="ui-icon ui-icon-circle-triangle-s"></span>
				</a>
				<span class="ui-gtgrid-title"><?php echo __('Choose your folder', true);?></span>
			</div>
			<div class="ui-gtgrid-bdiv" style="display:none">
				<div id="folder-tree">oi</div>
			</div>
		</div>
		
	</div>
	
	<div id="gtbox-file-options">
		<div id="file-uploader"></div>
	</div>
	
	<div id="tabs" class="gtgtabs ui-tabs ui-widget ui-widget-content ui-corner-all">
		<ul>
		</ul>	
	</div>
	<div style="clear:both"></div>
</div>