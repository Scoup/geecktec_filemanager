<?php
class GeecktecFilemanagerFoldersController extends GeecktecFilemanagerAppController {
	
	public $name = 'GeecktecFilemanagerFolders';
	
	public $helpers = array('Image', 'GeecktecFilemanager.Images');
	
	function beforeRender(){
		if($this->RequestHandler->isAjax()){
//			Configure::write('debug', 0);
			$this->layout = '';
		}
	}	
	
	public function admin_index() {
		if (isset($this->params['url']['CKEditor'])) {
			$this->layout = 'admin_full';
		}
	}
	
	public function admin_ajaxGetChildren(){
		$id = $this->params['url']['id'];
		$folders = $this->GeecktecFilemanagerFolder->children($id, true);
		$output = array();
		foreach($folders as $folder){
			$node = ($folder['GeecktecFilemanagerFolder']['id'] > 2) ? true : false;
			$output[] = array(
				'attr' => array('id' => 'node_'.$folder['GeecktecFilemanagerFolder']['id'], 'rel' => $node ? 'folder' : 'drive'),
				'data' => $folder['GeecktecFilemanagerFolder']['name'],
				'state' => ($folder['GeecktecFilemanagerFolder']['rght'] - $folder['GeecktecFilemanagerFolder']['lft'] > 1) ? 'closed' : ''
			);
		}
		
		$this->set(compact('output'));
		$this->render('/json');
	}
	
	public function admin_ajaxAddNode(){
		$form = $this->params['form'];
//		debug($form);
		$save = array('GeecktecFilemanagerFolder' => array(
			'parent_id' => $form['parent_id'],
			'name' => $form['name'],
			'rel' => 'folder',
		));
		$this->GeecktecFilemanagerFolder->create();
		$output = array('status' => 0);
		if($this->GeecktecFilemanagerFolder->save($save)){
			$output['status'] = 1;
			$output['id'] = $this->GeecktecFilemanagerFolder->id;
		}
		$this->set(compact('output'));
		$this->render('/json');
	}
	
	public function admin_ajaxRenameNode(){
		$form = $this->params['form'];
		$this->GeecktecFilemanagerFolder->id = $form['id'];
		$output = array('status' => 0);
		if($this->GeecktecFilemanagerFolder->saveField('name', $form['name'])){
			$output['status'] = 1;
		}
		$this->set(compact('output'));
		$this->render('/json');	
	}
	
	public function admin_ajaxRemoveNode(){
		$id = $this->GeecktecFilemanagerFolder->id = $this->params['form']['id'];
		if($id == 1 || $id == 2){
			$output = __('This folder cannot be deleted, sorry', true);
		}else{
			if($this->GeecktecFilemanagerFolder->delete()){
				$output = __('Success', true);
			}else{
				$output = __('Fail', true);
			}
		}
		$this->set(compact('output'));
		$this->render('/json');
	}
	
	public function admin_ajaxMoveNode(){
		
	}
	
	public function admin_ajaxSearch(){
		$search = $this->params['url']['search'];
		$this->GeecktecFilemanagerFolder->displayField = 'id';
		$folders = $this->GeecktecFilemanagerFolder->find('list', array(
			'conditions' => array('GeecktecFilemanagerFolder.name LIKE' => "%$search%")
		));
		$output = array();
		foreach($folders as $folder){
			$path = $this->GeecktecFilemanagerFolder->getpath($folder, array('id'));
			foreach($path as $folder){
				$output[] = "#node_".$folder['GeecktecFilemanagerFolder']['id'];
			}
		}
		$this->set(compact('output'));
		$this->render('/json');
	}
	
	public function admin_ajaxRefreshScreen($id = null){
		if($id == 2){
			App::Import('Model', 'Node');
			$this->Node =& new Node;
			$this->Node->Behaviors->attach('Containable');
			$files = $this->Node->find('all', array('conditions' => array('type' => 'attachment'), 'contain' => ''));
			$this->set(compact('files'));
			$this->render('admin_attachments');
		}else{
			$files = $this->GeecktecFilemanagerFolder->GeecktecFilemanagerFile->find('all', array(
				'conditions' => array('GeecktecFilemanagerFile.geecktec_filemanager_folder_id' => $id)
			));
		}
		$this->set(compact('files'));
	}
	
}
?>