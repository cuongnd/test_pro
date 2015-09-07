<?php

$current = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
$should = str_replace("https://", "", JURI::root());
$should = str_replace("http://", "", $should) . 'administrator';

$backend = strrpos($current, $should);

defined( '_JEXEC' ) or die( 'Restricted access' );
jimport('joomla.application.component.controller');

if($backend === false){
    // if frontend here
   	$db = JFactory::getDBO();
 	$id=JRequest::getVar("id", 0, "get", "int");
  	//Get information about this redirect from the database
 	$query = "SELECT * FROM #__ijseo WHERE `id`=".intval($id);
 	$db->setQuery($query);
 	if(!$db->query()){
   		echo $db->getErrorMsg();
   		return;
  	}
  	$redirectrow = $db->loadObjectList();
	
  	$hits = $redirectrow["0"]->hits + 1;
	$query = "update #__ijseo set `hits`=".intval($hits)." where `id`=".intval($id);
   	$db->setQuery($query);
 	if(!$db->query()){
   		echo $db->getErrorMsg();
   		return;
  	}
	
	if(strpos($redirectrow["0"]->links_to, "http") === FALSE){
		$redirectrow["0"]->links_to = "http://".$redirectrow["0"]->links_to;
	}
	
	$app = JFactory::getApplication("site");
  	$app->redirect($redirectrow["0"]->links_to);
}
else{
	// Require the base controller
	require_once( JPATH_COMPONENT.DS.'controller.php' );
	// Require specific controller if requested
	$pattern = '/^[A-Za-z]*$/';
	if(preg_match($pattern,JRequest::getVar('controller'))){	
		$controller = JRequest::getVar('controller');	
		$path = JPATH_COMPONENT.DS.'controllers'.DS.$controller.'.php';	
		if (file_exists($path)) {
			require_once $path;
		} else {
			$controller = '';
		}
	}

	// Create the controller
	$classname	= 'iJoomla_SeoController'.$controller;
	$controller	= new $classname();
	$controller->execute(JRequest::getVar('task', ""));
	$controller->redirect();
}

?>