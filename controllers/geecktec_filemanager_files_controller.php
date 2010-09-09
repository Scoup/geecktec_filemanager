<?php
class GeecktecFilemanagerFilesController extends GeecktecFilemanagerAppController {
	
	public $name = 'GeecktecFilemanagerFiles';
	
//	public $components = array('GeecktecFilemanager.Uploader');
	public $components = array('GeecktecFilemanager.Upload');
	
	public function admin_index(){
		$this->GeecktecFilemanagerFile->find('all');
	}
	
	public function admin_add(){
		$this->Upload->xhrToData();
		debug($this->data);
		if(!empty($this->data)){
			debug($this->data);
			$this->GeecktecFilemanagerFile->create();
			if($this->GeecktecFilemanagerFile->save($this->data)){
				$this->Session->setFlash('Salvo com sucesso');
			}
		}
		else{ debug('fail');}
//		exit;
	}
	
	public function admin_ajaxAdd(){
		$this->Upload->xhrToData();
		if(!empty($this->data)){
			$this->GeecktecFilemanagerFile->create();
			if($this->GeecktecFilemanagerFile->save($this->data)){
				$output = array('success' => true);
			}else{
				$output = array('error' => __('Error msg', true));
			}
		}else{
			$output = array('error' => __('File dont found', true));
		}
		$this->set(compact('output'));
		$this->render('/json');
	}
}
?>