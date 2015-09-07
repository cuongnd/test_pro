<?php
/**
 * @package 	Bookpro
 * @author 		Nguyen Dinh Cuong
 * @link 		http://ibookingonline.com
 * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: bustrip.php 66 2012-07-31 23:46:01Z quannv $
 **/

defined('_JEXEC') or die('Restricted access');

//import needed JoomLIB helpers
AImporter::helper('request', 'controller');

class BookProControllerGenerate extends AController
{
    
    var $_model;
    var $_busstopModel;

    function __construct($config = array())
    {
        parent::__construct($config);
        $this->_model = $this->getModel('bustrip');
        $this->_busstopModel=$this->getModel('busstation');
        $this->_controllerName = CONTROLLER_BUSTRIP;
    }

    /**
     * Display default view - Airport list	
     */
    function display()
    {
    	switch ($this->getTask()) {
           	case 'publish':
           		$this->state($this->getTask());
           		break;
            case 'unpublish':
              break;
            case 'trash':
            $this->state($this->getTask());
                break;
            default:
        }
        JRequest::setVar('view', 'bustrips');
        parent::display();
    }

   
    function save($apply = false)
    {
        
        AImporter::model('bustrip');
        $mainframe = &JFactory::getApplication();
        
        $post = JRequest::get('post');
        
        $post['id'] = ARequest::getCid();
        
        $post['text'] = JRequest::getVar('text', '', 'post', 'string', JREQUEST_ALLOWRAW);
        $destIds = $post['dest_id'];
        $model = new BookProModelBusTrip();
        $bustrips = $this->getDestIdsBustrips($destIds);
       
        $parent = array();
        $objParent = reset($bustrips);
        $parent['id'] = 0;
        $parent['parent_id'] = 0;
        $parent['from'] = $objParent->from;
        $parent['to'] = $objParent->to;
        $parent['state'] = 0;
        $parent['code'] = $post['code'];
        $parent['seat_layout_id'] = $post['seat_layout_id'];
        $parent['agent_id'] = $post['agent_id'];
       	$parent['route'] = $objParent->route;
        $parent['ordering'] = $objParent->ordering;
        $parent['bus_id'] = $post['bus_id'];
      
        $parent_id =  $model->store($parent);
      	
        if ($parent_id){
        	array_shift($bustrips);
        	for ($i =0;$i < count($bustrips);$i++){
        		
        		$children = array();
        		$objChild = $bustrips[$i];
        		$children['parent_id'] = $parent_id;
        		$children['from'] = $objChild->from;
        		$children['to'] = $objChild->to;
        		$children['state'] = 0;
        		$children['route'] = $objChild->route;
        		$children['ordering'] = $objChild->ordering;
        		$children['bus_id'] = $post['bus_id'];
        		$children['code'] = $post['code'];
        		$children['seat_layout_id'] = $post['seat_layout_id'];
        		$children['agent_id'] = $post['agent_id'];
        		$model = new BookProModelBusTrip();
        		$model->store($children);
        	}
        }
        $this->setRedirect('index.php?option=com_bookpro&view=bustrips');
       
    
    }
    function getDestIdsBustrips($dests){
    	
    	$destIds = array();
    	if (count($dests)) {
    		foreach ($dests as $dest){
    			if ((int) $dest > 0) {
    				$destIds[] = $dest;
    			}
    		}	
    	}
    	
    	$bustrips = array();
    	if (count($destIds)) {
    		$ordering = 1;
    		for ($i = 0;$i < count($destIds)-1;$i++){
    			for ($j = count($destIds)-1;$j >$i;$j--){
    				$obj = new stdClass();
    				if ($i == 0 && $j == count($destIds)-1) {
    					$obj->parent = 0;
    				}else{
    					$obj->parent = 1;
    				}
    				$obj->from = $destIds[$i];
    				$obj->to = $destIds[$j];
    				$obj->route = implode(";", $destIds);
    				$obj->ordering = $ordering;
    				$bustrips[] = $obj;
    				$ordering++;
    			}
    		}
    	}
    	
    	return $bustrips;
    }
    


  }

?>