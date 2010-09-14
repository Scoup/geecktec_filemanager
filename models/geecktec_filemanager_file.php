<?php
class GeecktecFilemanagerFile extends GeecktecFilemanagerAppModel {
	
	public $name = 'GeecktecFilemanagerFile';
	
	public $actsAs = array(
		'GeecktecFilemanager.MeioUpload' => array(
			'filename' => array(
				'dir' => 'uploads{DS}geecktec_filemanager',
				'maxSize' => 2097152,
			)
		),
		'GeecktecFilemanager.Sequence' => array(
			'group_fields' =>'geecktec_filemanager_folder_id'
		)
//		'Tree',
	);
	
	public $belongsTo = array(
		'GeecktecFilemanagerFolder' => array(
			'className' => 'GeecktecFilemanager.GeecktecFilemanagerFolder'
		)
	);
	
	function beforeSave(){
		if(!isset($this->data['GeecktecFilemanagerFile']['title']) || empty($this->data['GeecktecFilemanagerFile']['title'])){
			$this->data['GeecktecFilemanagerFile']['title'] = $this->data['GeecktecFilemanagerFile']['filename'];
		}
		return true;
	}
	
	function beforeDelete(){
		return true;
	}
	
}
?>