<?php
// namespace administrator\components\com_jrealtimeanalytics\controllers;
/**
 *
 * @package JREALTIMEANALYTICS::administrator::components::com_jrealtimeanalytics
 * @subpackage controllers
 * @author Joomla! Extensions Store
 * @copyright (C) 2013 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html  
 */
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 * Base controller class
 * 
 * @package JREALTIMEANALYTICS::CPANEL::administrator::components::com_jrealtimeanalytics
 * @subpackage controllers
 * @since 1.0
 */
class JRealtimeAnalyticsControllerBase extends JControllerLegacy {
	/**
	 * Setta il model state a partire dallo userstate di sessione
	 * @access protected
	 * @param string $scope
	 * @return void
	 */
	protected function setModelState($scope = 'default') {
		// App instance
		$app = JFactory::getApplication();
		$option= JRequest::getVar('option');
		$limit = $app->getUserStateFromRequest("$option.$scope.limit", 'limit', $app->getCfg('list_limit'), 'int') ;
		$limitStart = $app->getUserStateFromRequest("$option.$scope.limitstart", 'limitstart', 0, 'int') ;
		// Round del limit al change proof
		$limitStart = ( $limit != 0 ? (floor($limitStart / $limit) * $limit) : 0 );
		
		$search = $app->getUserStateFromRequest("$option.$scope.searchword", 'search', null) ;
		$filter_order = $app->getUserStateFromRequest( "$option.$scope.filter_order", 'filter_order', 'a.sent', 'cmd' );
		$filter_order_Dir = $app->getUserStateFromRequest( "$option.$scope.filter_order_Dir", 'filter_order_Dir', 'DESC', 'word' );
		
		// Get default model
		$defaultModel = $this->getModel();
		
		// Set model state
		$defaultModel->setState('option', $option);
		$defaultModel->setState('limit', $limit);
		$defaultModel->setState('limitstart', $limitStart);
		$defaultModel->setState('searchword', $search);
		$defaultModel->setState('order', $filter_order);
		$defaultModel->setState('order_dir', $filter_order_Dir);
	}
	 
	/**
	 * Typical view method for MVC based architecture
	 *
	 * This function is provide as a default implementation, in most cases
	 * you will need to override it in your own controllers.
	 *
	 * @access public
	 * @param $cachable string
	 *       	 the view output will be cached
	 * @since 1.5
	 */
	function display($cachable = false) {
		$document = & JFactory::getDocument ();
		
		$viewType = $document->getType ();
		$coreName = $this->getNames ();
		$viewLayout = JRequest::getCmd ( 'layout', 'default' );
		
		$view = & $this->getView ( $coreName, $viewType, '', array ('base_path' => $this->basePath ) );
		
		// Get/Create the model
		if ($model = & $this->getModel ( $coreName )) {
			// Push the model into the view (as default)
			$view->setModel ( $model, true );
		}
		
		// Set the layout
		$view->setLayout ( $viewLayout );
		$view->display ();
	}
	/**
	 * Method to get the controller name
	 *
	 * The dispatcher name by default parsed using the classname, or it can be
	 * set
	 * by passing a $config['name'] in the class constructor
	 *
	 * @access public
	 * @return string The name of the dispatcher
	 * @since 1.5
	 */
	function getNames() {
		$name = $this->name;
		
		if (empty ( $name )) {
			$r = null;
			if (! preg_match ( '/(.*)Controller(.*)/i', get_class ( $this ), $r )) {
				JError::raiseError ( 500, JText::_ ( 'JLIB_APPLICATION_ERROR_CONTROLLER_GET_NAME' ) );
			}
			$name = ($r [2]);
		}
		
		return ($name);
	}
	
	/**
	 * Method to get the controller name
	 *
	 * The dispatcher name by default parsed using the classname, or it can be
	 * set
	 * by passing a $config['name'] in the class constructor
	 *
	 * @access public
	 * @return string The name of the dispatcher
	 * @since 1.5
	 */
	function getName() {
		
		$r = null;
		if (! preg_match ( '/(.*)Controller/i', get_class ( $this ), $r )) {
			JError::raiseError ( 500, JText::_ ( 'JLIB_APPLICATION_ERROR_CONTROLLER_GET_NAME' ) );
		}
		$name = ($r [1]);
		
		return ($name);
	}
	
	/**
	 * Method to get a reference to the current view and load it if necessary.
	 *
	 * @access public
	 * @param
	 *       	 string	The view name. Optional, defaults to the controller
	 *       	 name.
	 * @param
	 *       	 string	The view type. Optional.
	 * @param
	 *       	 string	The class prefix. Optional.
	 * @param
	 *       	 array	Configuration array for view. Optional.
	 * @return object to the view or an error.
	 * @since 1.5
	 */
	function &getView($name = null, $type = 'html', $prefix = null, $config = array()) {
		static $views;
		
		if (! isset ( $views )) {
			$views = array ();
		}
		
		if (empty ( $name )) {
			$name = $this->getNames ();
		}
		
		if (empty ( $prefix )) {
			$prefix = $this->getName () . 'View';
		}
		
		if (empty ( $views [$name] )) {
			if ($view = & $this->createView ( $name, $prefix, $type, $config )) {
				$views [$name] = & $view;
			} else {
				$result = JError::raiseError ( 500, JText::sprintf ( 'JLIB_APPLICATION_ERROR_VIEW_NOT_FOUND', $name, $type, $prefix ) );
				return $result;
			}
		}
		
		return $views [$name];
	}
	
	/**
	 * Method to get a model object, loading it if required.
	 *
	 * @access public
	 * @param
	 *       	 string	The model name. Optional.
	 * @param
	 *       	 string	The class prefix. Optional.
	 * @param
	 *       	 array	Configuration array for model. Optional.
	 * @return object model.
	 * @since 1.5
	 */
	function &getModel($name = '', $prefix = '', $config = array()) {
		static $models = array();
		
		if (empty ( $name )) {
			$name = $this->getNames ();
		}
		
		if (empty ( $prefix )) {
			$prefix = $this->getName () . 'Model';
		}
		
		if(array_key_exists($name, $models)) {
			return $models[$name];
		}
		
		if ($model = & $this->createModel ( $name, $prefix, $config )) {
			$models[$name] = $model;
			// task is a reserved state
			$model->setState ( 'task', $this->task );
			
			// Lets get the application object and set menu information if its
			// available
			$app = &JFactory::getApplication ();
			$menu = &$app->getMenu ();
			if (is_object ( $menu )) {
				if ($item = $menu->getActive ()) {
					$params = & $menu->getParams ( $item->id );
					// Set Default State Data
					$model->setState ( 'parameters.menu', $params );
				}
			}
		}
		return $model;
	}
	
	/**
	 * Constructor.
	 *
	 * @access protected
	 * @param
	 *       	 array An optional associative array of configuration settings.
	 *       	 Recognized key values include 'name', 'default_task',
	 *       	 'model_path', and
	 *       	 'view_path' (this list is not meant to be comprehensive).
	 * @since 1.5
	 */
	function __construct($config = array()) {
		// Initialize private variables
		$this->redirect = null;
		$this->message = null;
		$this->messageType = 'message';
		$this->taskMap = array ();
		$this->methods = array (); 
	
		// Inserimento css file di default brutto qui ma funzionale
		$doc = JFactory::getDocument ();
		$doc->addStylesheet ( JURI::root ( true ) . '/administrator/components/com_jrealtimeanalytics/css/override.css' );
		
		// Get the methods only for the final controller class
		$thisMethods = get_class_methods ( get_class ( $this ) );
		$baseMethods = get_class_methods ( 'JRealtimeAnalyticsControllerBase' );
		$methods = array_diff ( $thisMethods, $baseMethods );
		$baseMethods = get_class_methods ( 'JController' );
		$methods = array_diff ( $methods, $baseMethods );
	
		// Add default display method
		$methods [] = 'display';
	
		// Iterate through methods and map tasks
		foreach ( $methods as $method ) {
			if (substr ( $method, 0, 1 ) != '_') {
				$this->methods [] = strtolower ( $method );
				// auto register public methods as tasks
				$this->taskMap [strtolower ( $method )] = $method;
			}
		}
	
		// set the view name
		if (empty ( $this->name )) {
			if (array_key_exists ( 'name', $config )) {
				$this->name = $config ['name'];
			} else {
				$this->name = $this->getNames ();
			}
		}
	
		// Set a base path for use by the controller
		if (array_key_exists ( 'base_path', $config )) {
			$this->basePath = $config ['base_path'];
		} else {
			$this->basePath = JPATH_COMPONENT;
		}
	
		// If the default task is set, register it as such
		if (array_key_exists ( 'default_task', $config )) {
			$this->registerDefaultTask ( $config ['default_task'] );
		} else {
			$this->registerDefaultTask ( 'display' );
		}
	
		// set the default model search path
		if (array_key_exists ( 'model_path', $config )) {
			// user-defined dirs
			$this->addModelPath ( $config ['model_path'] );
		} else {
			$this->addModelPath ( $this->basePath . '/' . 'models' );
		}
	
		// set the default view search path
		if (array_key_exists ( 'view_path', $config )) {
			// user-defined dirs
			$this->setPath ( 'view', $config ['view_path'] );
		} else {
			$this->setPath ( 'view', $this->basePath . '/' . 'views' );
		}
	}
}