<?php
/**
 * Component to work with XHR upload
 * Designed to work with valums ajax-upload (http://valums.com/ajax-upload/)
 * Convert the XHR in a temp file with a default upload options
 * @author Léo Haddad
 * @email leo@geecktec.com
 * @package GeecktecFilemanager Plugin for Croogo
 * @version 1.0
 * @lastupdate 07 nov 2010
 */
class UploadComponent extends Object {
	
	/**
	 * @var array
	 * 
	 * adicionalFields = Add fields to pass to
	 * modelName => Name of model where will be save
	 * fieldName => Name of the field where is upload the file (in normal upload)
	 * file => Name of the param where is the file uploaded
	 */
	public $options = array(
		'modelName' => 'GeecktecFilemanagerFile',
		'fieldName' => 'filename',
		'adicionalFields' => array(
			'parent_id', 'geecktec_filemanager_folder_id'
		),
		'file' => 'qqfile'
	);
	
	public function startup(&$controller){
		$this->controller =& $controller;
	}
	
	public function xhrToData(){
		if(isset($this->controller->params['url'][$this->options['file']])){
			$this->_convertData();
		}
	}
	
	/**
	 * Create a temp file with the XHR uploaded file
	 */
	private function _convertData(){
		$file = array('name' => $this->controller->params['url'][$this->options['file']]);
		$input = fopen('php://input', 'r'); // Open uploaded File
		
		$file['tmp_name'] = tempnam(sys_get_temp_dir(), 'php'); // Create a tmp file
		$temp = fopen($file['tmp_name'], 'w'); // Open tmp file
		$file['size'] = stream_copy_to_stream($input, $temp); // Copy the uploaded file to temp file
		fclose($input); // Close uploaded file
		$file['type'] = image_type_to_mime_type(exif_imagetype($file['tmp_name']));
		$file['error'] = 0;
		fseek($temp, 0, SEEK_SET);
		$this->_createData($file);		
	}
	
	/**
	 * Transform the temp file to default cakephp data
	 * @param array $file
	 */
	private function _createData($file = array()){
		$this->controller->data = array(
			$this->options['modelName'] => array(
				$this->options['fieldName'] => $file,
			)
		);
		foreach($this->options['adicionalFields'] as $field) {
			$this->controller->data[$this->options['modelName']][$field] = $this->controller->params['url'][$field];
		}
	}
}
?>