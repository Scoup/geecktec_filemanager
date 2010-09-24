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
				$output = array(
					'success' => true, 
					'geecktec_filemanager_folder_id' => $this->data['GeecktecFilemanagerFile']['geecktec_filemanager_folder_id']
				);
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
	
	public function admin_ajaxDelete($id = null){
		if(!$id){
			$output = array(
				'success' => false,
				'msg' => __('Id not found', true)
			);
		}else{
			if($this->GeecktecFilemanagerFile->delete($id)){
				$output = array(
					'success' => true,
					'msg' => __('File deleted', true)
				);
			}else{
				$output = array(
					'success' => false, 
					'msg' => __('Fail', true), 
				);
			}
		}
		$this->set(compact('output'));
		$this->render('/json');
	}
	
	public function admin_ajaxDownload($id = null){
		$this->view = 'Media';
		$image = $this->GeecktecFilemanagerFile->findById($id);
		$params = array(
			'id' => $image['GeecktecFilemanagerFile']['filename'],
			'name' => substr($image['GeecktecFilemanagerFile']['title'], 0, strrpos($image['GeecktecFilemanagerFile']['title'], '.')),
			'extension' => substr(strrchr($image['GeecktecFilemanagerFile']['filename'], '.'), 1),
			'download' => true,
			'path' => "webroot" . DS .$image['GeecktecFilemanagerFile']['dir'] . DS
		);
		$this->set($params);
		$this->render();
	}
}
?>