<?php


defined('_JEXEC') or die;

jimport('joomla.application.component.controllerform');

class BookProControllerFacility extends JControllerForm
{
    function  getRedirectToItemAppend($recordId = null, $urlVar = 'id')
    {
        $str_request='';
        $input=JFactory::getApplication()->input;
        $type=$input->get('type','','string');
        $str_request.=$type?'&type='.$type:'';
        $object_id=$input->get('object_id',0,'int');
        $str_request.=$object_id?'&object_id='.$object_id:'';
        return $str_request.parent::getRedirectToItemAppend($recordId,$urlVar);
    }
    function  getRedirectToListAppend($recordId = null, $urlVar = 'id')
    {

        $str_request='';
        $input=JFactory::getApplication()->input;
        $type=$input->get('type','','string');
        $str_request.=$type?'&type='.$type:'';
        $object_id=$input->get('object_id',0,'string');
        $str_request.=$object_id?'&object_id='.$object_id:'';
        return $str_request.parent::getRedirectToListAppend($recordId,$urlVar);
    }

	
}