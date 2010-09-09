<?php
class GeecktecFilemanagerActivation {
	
	public $name = 'GeecktecFilemanager';
	
	/**
	 * Configurações que podem ser modificadas no plugin
	 * @var array
	 */
	public $configs = array(
		array(
			'key' => 'GeecktecCkeditor.lang',
			'value' => 'en',
			'title' => 'Lang',
			'description' => 'Choose your default language',
			'input_type' => 'text',
			'editable' => 1,
			'weight' => 0,
		),
		array(
			'key' => 'GeecktecCkeditor.skin',
			'value' => 'kama',
			'title' => 'Skin',
			'description' => 'Choose your skin',
			'input_type' => 'text',
			'editable' => 1,
			'weight' => 1
		),		
		array(
			'key' => 'GeecktecCkeditor.toolbar',
			'value' => 'default',
			'title' => 'Toolbar',
			'description' => 'Choose your tools',
			'input_type' => 'text',
			'editable' => 1,
			'weight' => 2,
		),
		array(
			'key' => 'GeecktecCkeditor.styles',
			'value' => 'default',
			'title' => 'Styles',
			'description' => 'Choose your style (CSS)',
			'input_type' => 'text',
			'editable' => 1,
			'weight' => 3,
		),
		array(
			'key' => 'GeecktecCkeditor.output',
			'value' => 'default',
			'title' => 'Output',
			'description' => 'Choose your output (HTML)',
			'input_type' => 'text',
			'editable' => 0,
			'weight' => 4,
		),		
		array(
			'key' => 'GeecktecCkeditor.templates',
			'value' => 'default',
			'title' => 'Default Template',
			'description' => 'Choose your default template',
			'input_type' => 'text',
			'editable' => 1,
			'weight' => 5
		),	
		array(
			'key' => 'GeecktecCkeditor.filebrowserBrowseUrl',
			'value' => 'default',
			'title' => 'Filebrowser Browser Url',
			'description' => 'Choose your default filebrowser Browser url',
			'input_type' => 'text',
			'editable' => 0,
			'weight' => 6
		),						
	);
	
/**
 * onActivate will be called if this returns true
 *
 * @param  object $controller Controller
 * @return boolean
 */
	public function beforeActivation(&$controller) {
		return true;
	}
		
	/**
	 * onActivation of plugin
	 * @param Object $controller
	 */
	public function onActivation(&$controller){
//		$this->_initConfig();
		$this->initDb();
	}
	
/**
 * onDeactivate will be called if this returns true
 *
 * @param  object $controller Controller
 * @return boolean
 */
	public function beforeDeactivation(&$controller) {
		return true;
	}
		
	public function onDeactivation(&$controller){
	}
	
	/**
	 * Install the database of filemanager 
	 * @author Fahad Ibnay Heylaal <contact@fahad19.com>
	 * @modified Léo Haddad <leo@geecktec.com>
	 * @package GeecktecFilemanager / Croogo
	 */
	function initDb(){
		App::import('Core', 'File');
		App::import('Model', 'CakeSchema', false);
		App::import('Model', 'ConnectionManager');
		
		$name = Inflector::underscore($this->name);
		
		$db =& ConnectionManager::getDataSource('default');
		$schema =& new CakeSchema(array('plugin'=> $name));
		$schema = $schema->load();
		foreach($schema->tables as $table => $fields) {
			if(!in_array($table, $db->_sources)){
				$create = $db->createSchema($schema, $table);
				$db->execute($create);
				$db->_sources[] = $table;
			}
		}
		$dataObjects = App::objects('class', APP . 'plugins' . DS . $name . DS . 'config' . DS . 'schema' . DS . 'data' . DS);
		
		foreach ($dataObjects as $data) {
			App::import('class', $data, false, APP . 'plugins' . DS . $name . DS . 'config' . DS . 'schema' . DS . 'data' . DS);
			$classVars = get_class_vars($data);
			$modelAlias = substr($data, 0, -4);
			$table = $classVars['table'];
			$records = $classVars['records'];
			App::import('Model', 'Model', false);
			$modelObject =& new Model(array(
				'name' => $modelAlias,
				'table' => $table,
				'ds' => 'default',
			));
			if (is_array($records) && count($records) > 0) {
				foreach($records as $record) {
					if(!$modelObject->find('first', array('conditions' => array('id' => $record['id'])))){
						$modelObject->create($record);
						$modelObject->save();	
					}
				}
			}
		}		
		
	}
	
	/**
 	*	Init the SQL to config the plugin
 	*	All configurations are save in Setting Model
 	*	@author Léo Haddad
 	*	@version 1.0 
 	*/
	private function _initConfig(){
		App::import('Model', 'Setting');
		$this->Setting = new Setting();
		
		$plugins = $this->Setting->find('all', array(
			'order' => 'Setting.weight ASC',
			'conditions' => array(
				'Setting.key LIKE' => $this->name . '.%',
				'Setting.editable' => 1,
			)
		));
		
		$plugins = Set::extract('/Setting/key', $plugins);
		foreach($this->configs as $config){
			if(!in_array($config['key'], $plugins)){
				$this->Setting->create();
				$this->Setting->save($config);
			}
		}
	}
}
?>