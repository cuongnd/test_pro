<?php



defined('_JEXEC') or die;

jimport('joomla.application.component.controlleradmin');

class BookproControllerAirportairline extends JControllerAdmin
{
	/**
	 * (non-PHPdoc)
	 * @see JControllerLegacy::getModel()
	 */
	public function __construct($config = array())
	{
		$this->view_list = 'airportairline';
		parent::__construct($config);
	
	}
	
	/**
	 * Cancel edit operation. Check in subject and redirect to subjects list.
	 */
	function cancel()
	{
		$mainframe = &JFactory::getApplication();
		$mainframe->enqueueMessage(JText::_('Subject editing canceled'));
		$mainframe->redirect('index.php?option=com_bookpro&view=airports');
	}
	
	/**
	 * Save subject and state on edit page.
	 */
	function apply()
	{
		$this->save(true);
	}
	
	
	/**
	 * Save subject.
	 *
	 * @param boolean $apply true state on edit page, false return to browse list
	 */
	function save($apply = false)
	{
		JRequest::checkToken() or jexit('Invalid Token');
	
		$input = JFactory::getApplication()->input;
		$mainframe = &JFactory::getApplication();
	
		$post = JRequest::get('post');
		$ids = $input->get('cid',array(),'array');
		$dest_id = $input->get('dest_id',0,'int');
		$this->deleteByDest($dest_id);
		
		$db=JFactory::getDbo();
		$query=$db->getQuery(true);
		$query->insert('#__bookpro_airportairline');
		
		$query->columns('dest_id,airline_id');
		$values=array();
		foreach ($ids as $id){
			
			$temp = array($dest_id,$id);
			$values[]=implode(',', $temp);
		}
			
		
		$query->values($values);
		
		$db->setQuery($query);
		$db->execute();
		
		$this->setRedirect('index.php?option=com_bookpro&view=airports');
			
	}
		function deleteByDest($dest_id){
			
			
			try {
				
				$db=JFactory::getDbo();
				$query=$db->getQuery(true);
				$query->delete('#__bookpro_airportairline')->where('dest_id='.$dest_id);
				$db->setQuery($query);
			
				$db->execute();
				return true;
			}catch (Exception $e){
				
				return false;
			}
		}
}