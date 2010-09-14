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
	$(".imagens").sortable({
		start: startDrag,
		beforeStop: beforeStopDrag
	});
	// Function of start dragging the images
	function startDrag(e,ui){
		var old_position = $(this).parent().find('li.thumb').index(ui.helper);
		ui.item.data("old_position", old_position);
	}
	// Function of end dragging the images
	function beforeStopDrag(e,ui){
		var new_position = $(this).parent().find('li.thumb').index(ui.helper);
		var old_position = ui.item.data("old_position");
		console.debug("Old position:%o new position:%o", old_position, new_position);
		var change = new_position - old_position;
		console.debug(change);
		var move_up = change > 0;
		console.debug(move_up);

		// Request the server response of sortable
		$.ajax({
			url: "<?php echo $this->webroot.'admin/geecktec_filemanager/geecktec_filemanager_files/ajaxMoveNode';?>",
			dataType: 'json',
			data: ({id: ui.helper.attr('id').replace('thumb-',''), delta: new_position}),
			success: function(data){
				console.debug(data);
				if(data['success']){
					alert(data['msg']);
				}else{
					if(old_position == 0){
						ui.helper.prependTo($(this));
					}else{
						var position = move_up ? old_position -1 : old_position + 1;
						var local = $(this).find('li.thumb:eq('+ (position) +')');
						ui.helper.insertAfter(local);
					}					
					alert(data['msg']);
				}		
			}
		});
	}
});
</script>
<div class="sendFile"></div>
<?php if(count($files) == 0):?>
<div class="empty"><?php echo __('No files founds', true)?></div>
<?php endif;?>
<ul class="imagens">
<?php
	foreach($files as $file){		
?>
	<li class="thumb ui-widget-content" id="thumb-<?php echo $file['GeecktecFilemanagerFile']['id'];?>">
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
	</li>
<?php } ?>
</ul>
<div class="clear"></div>
