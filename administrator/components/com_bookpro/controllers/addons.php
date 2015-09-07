<?php
defined('_JEXEC') or die;

jimport('joomla.application.component.controlleradmin');

class BookproControlleraddons extends JControllerAdmin
{
	/**
	 * (non-PHPdoc)
	 * @see JControllerLegacy::getModel()
	 */
	public function getModel($name = 'Addon', $prefix = 'BookproModel', $config = array('ignore_request' => true))
	{
		$model = parent::getModel($name, $prefix, $config);
		return $model;

	}

    function ajaxGetAgentByAddon(){
        $id = JRequest::getVar('id');
        AImporter::model('addons');
        $model= new BookProModelAddons();
        $getAddon=$model->getaddonsById($id);
        AImporter::model('agents');
        $modelAgent=new BookProModelAgents();
        $getAgent=$modelAgent->getAgentByIds($getAddon->agent_id);
        foreach($getAgent as $item){
                echo "<option value='$item->id'>";
                echo $item->company;
                echo "</option>";
        }
        die;
    }

    function ajaxAddAddon(){
        $post = JRequest::get('post');
        echo "<pre>";
        print_r($post);
        echo "</pre>";
        die();
    }




}