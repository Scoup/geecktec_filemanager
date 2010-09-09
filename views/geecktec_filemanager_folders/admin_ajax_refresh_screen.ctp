<script type="text/javascript">
$(document).ready(function() {
	// Show menu when a list item is clicked
	$("#imagens .thumb").contextMenu({
		menu: 'myMenu'
	}, function(action, el, pos) {
		var img = el.find('img');
		switch(action){
			case "select":
				selectItem(img.attr('rel'));
			break;
			case "edit":
				overlay.load();
			break;
		}
	});
});
</script>
<div id="imagens">
<?php if(count($files) == 0):?>
<div class="empty"><?php echo __('No files founds', true)?></div>
<?php endif;?>
<?php
	foreach($files as $file){		
?>
	<div class="thumb">
			<span class="file_image">
			<?php
			echo $images->resize(
				$file['GeecktecFilemanagerFile']['dir'].DS.$file['GeecktecFilemanagerFile']['filename'], 
				array(
					'width' => 128,
					'height' => 138,
					'aspect' => false,
					'adaptive' => true,
				),
				array('rel' => $this->webroot.$file['GeecktecFilemanagerFile']['dir'].'/'.$file['GeecktecFilemanagerFile']['filename'])
			);
			?>
			</span>
			<div class="file_details">
				<ul>
					<li class="file_name" id="texto_<?= $file['GeecktecFilemanagerFile']['id']?>"><?= $file['GeecktecFilemanagerFile']['title']; ?></li>
					<li class="file_entry"><?php echo $file['GeecktecFilemanagerFile']['created'];?></li>
					<li class="file_size"><?php echo round($file['GeecktecFilemanagerFile']['filesize'] / 1024);?> kb</li>
				</ul>				
			</div>
	</div>
<?php } ?>
</div>
<div class="clear"></div>