<?php
defined('_JEXEC') or die('Restricted access');

//import needed JoomLIB helpers
AImporter::helper('request', 'controller');


class BookProControllerRequest extends AController
{
	var $_model;
	function __construct($config = array())
	{
		parent::__construct($config);

	}

	/**
	 * Display default view - Airport list
	 */
	function display()
	{

		switch ($this->getTask()) {
			case 'publish':
				$this->publish();
				break;
			case 'unpublish':
				$this->unpublish();
				break;
			case 'trash':
				$this->trash();
				break;
			default:
				JRequest::setVar('view', 'request');
		}

		parent::display();
	}
	function request()
	{
		$mainframe = &JFactory::getApplication();
		$post = JRequest::get('post');

		JPluginHelper::importPlugin('captcha');
		$dispatcher = JDispatcher::getInstance();
		$res = $dispatcher->trigger('onCheckAnswer',$post['recaptcha_response_field']);
		if(!$res[0]){
			$mainframe->enqueueMessage(JText::_('The answer you entered for the CAPTCHA was not correct.'), 'message');
			$view=$this->getView('request','html','BookProView');
			$view->assign('obj',$this->GetObjectFromDataAndModel($post, "Review"));
		}else{
			$view=$this->getView('review','html','BookProView');
			$view->assign('jform',$this->GetObjectFromDataAndModel($post, "Review"));
		}

		$view->display();


	}

	function GetObjectFromDataAndModel($post, $name='Review')
	{
		$obj = new stdClass();
		$modelname = 'BookProModel'.ucfirst($name);
		if (! class_exists($modelname)) {
			AImporter::model(strtolower($name));
		}

		$model = new $modelname();
		$modelFields = $model->getTable()->getFields();
		if($modelFields){
			foreach ($modelFields as $key => $val)
			{
				if(isset($post[$key])){
					$obj->$key = $post[$key];
				};
			}
		}

		return $obj;
	}

	function GetArrayFromDataAndModel($post, $name='Review')
	{
		$arr = array();
		$modelname = 'BookProModel'.ucfirst($name);
		if (! class_exists($modelname)) {
			AImporter::model(strtolower($name));
		}

		$model = new $modelname();
		$modelFields = $model->getTable()->getFields();
		if($modelFields){
			foreach ($modelFields as $key => $val)
			{
				if(isset($post[$key])){
					$arr[$key] = $post[$key];
				};
			}
		}

		return $arr;
	}
}

?>