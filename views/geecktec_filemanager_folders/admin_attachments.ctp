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
		}
	});
});
</script>

<div id="imagens">

<?php
	foreach($files as $file){		
?>
	<div class="thumb">
			<span class="file_image">
				<?php echo $image->resize($file['Node']['path'], 128, 138, array(
					'rel' => $this->webroot.$file['Node']['path']
				)); ?>
			</span>
			<div class="file_details">
				<ul>
					<li class="file_name" id="texto_<?= $file['Node']['id']?>"><?= $file['Node']['title']; ?></li>
					<li class="file_entry"><?php echo $file['Node']['created'];?></li>
					<li class="file_size"><?php echo __('undefinied');?> kb</li>					
				</ul>	
			</div>
	</div>
<?php } ?>
</div>
<div style="clear:both"></div>