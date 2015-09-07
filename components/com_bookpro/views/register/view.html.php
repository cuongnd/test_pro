<?php

defined( '_JEXEC' ) or die( 'Restricted access' );
jimport( 'joomla.application.component.view' );
AImporter::helper('bookpro', 'model');
class BookproViewRegister extends JViewLegacy
{
	var $document=null;
	function display($tpl = null)
	{
		$this->document = JFactory::getDocument();
		$user=JFactory::getUser();
		$config = &AFactory::getConfig();

		if($user->id && in_array($config->supplierUsergroup,$user->groups)){

			JFactory::getApplication()->redirect(JUri::base().'index.php?option=com_bookpro&view=supplierpage&Itemid='.JRequest::getVar('Itemid'));
		}
		$this->document->setTitle(JText::_('COM_BOOKPRO_REGISTER_VIEW'));

		parent::display($tpl);
	}


}

?>
