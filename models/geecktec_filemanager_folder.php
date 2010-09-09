<?php
class GeecktecFilemanagerFolder extends GeecktecFilemanagerAppModel {
	
	public $name = 'GeecktecFilemanagerFolder';
	
//	public $tablePrefix = 'gt_filemanager_';
	
//	public $table = 'folders';
	
	public $actsAs = array('Tree');
	
	public $hasMany = array(
		'GeecktecFilemanagerFile' => array(
			'className' => 'GeecktecFilemanager.GeecktecFilemanagerFile',
			'dependent' => true,
		)
	);
}
?>