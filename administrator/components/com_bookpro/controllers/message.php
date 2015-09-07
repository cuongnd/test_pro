<?php

defined('_JEXEC') or die;

jimport('joomla.application.component.controllerform');
//import needed JoomLIB helpers
AImporter::helper('request', 'controller');

AImporter::model('message');
class BookProControllerMessage extends JControllerForm {
    
	function __construct($config = array()) {
	
		parent::__construct($config);
	}
	
	function update_Cidto($id,$cid_to){
		$data = array();
		$data['id'] = $id;
		$data['cid_to'] = $cid_to;
		
			
		$model = new BookProModelMessage();
		
		$id_message =$model ->save($data);
		return $id_message;
		
	}
    function change_user_state()
    {
        $input=JFactory::getApplication()->input;
        $id=$input->get('id',0,'int');
        $user_state=$input->get('user_state','','string');
        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->update('#__bookpro_messages');
        $query->set('user_state='.$db->quote($user_state));
        $query->where('id='.$id);
        $db->setQuery($query);
        $db->execute();
        die;


    }

    function save($apply = false) {
		
		/*
		 * Post: message
		*/
		JRequest::checkToken() or jexit('Invalid Token');
		$mainframe = &JFactory::getApplication();
		$data  = $this->input->post->get('jform', array(), 'array');
		$user = &JFactory::getUser();
		$config = &AFactory::getConfig();
		$model = new BookProModelMessage();
		
		$id_msg= $this ->update_Cidto($data['parent_id'],$user ->id);
		
		$id = $model->save($data);
        require_once JPATH_BASE.'/components/com_bookpro/helpers/message.php';
        $lastMessage=$id;
        messageHelper::sendemail($data['cid_to'],$data['parent_id'],$lastMessage);
		/*
		 * 1. Successfull
		*/
	 
	
		if ($id !== false) {
			//Save successfull ->send email
			$mainframe->enqueueMessage(JText::_('Successfully saved '), 'message');
			AImporter::helper('email');
			$mail=new EmailHelper();
			if($data['parent_id']){
				//echo "reply";s
				$mail->sendMessageEmail($data['parent_id'],2,$id);
			}else{
				//echo "new";
				//$mail->sendMessageEmail($id,0,0);
				
			}
	
		} else {
			$mainframe->enqueueMessage(JText::_('Save failed'), 'error');
		}
		$mainframe->redirect(JURI::base() . 'index.php?option=com_bookpro&view=messages');
	}
	

}