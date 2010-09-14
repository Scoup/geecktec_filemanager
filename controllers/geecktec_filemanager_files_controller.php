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
	
	public function admin_ajaxMoveNode(){
		$id = $this->params['url']['id'];
		$delta = $this->params['url']['delta'];
		$save = $this->GeecktecFilemanagerFile->find('first', array('conditions' => array('GeecktecFilemanagerFile.id' => $id)));
		$save['GeecktecFilemanagerFile']['order'] = $delta;
		unset($save['GeecktecFilemanagerFile']['filename']);
		if($this->GeecktecFilemanagerFile->save($save)){
			$output = array(
				'success' => true,
				'msg' => __('Success', true)
			);
		}else{
			$output = array(
				'success' => false,
				'msg' => __('Error :(', true)
			);	
		}
		$this->set(compact('output'));
		$this->render('/json');
	}
}
?>