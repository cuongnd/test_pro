<?php

// Check to ensure this file is included in Joomla!
defined ( '_JEXEC' ) or die ();
AImporter::helper('route');
class AdminUIHelper {

	static function startAdminArea($backEnd=true) {
		$app=JFactory::getApplication();
		$Itemid=$app->input->get('Itemid',0,'int');
		$Itemid=$Itemid?"&Itemid=$Itemid":'';
		$uri = (string) JUri::getInstance();
		$return = urlencode(base64_encode($uri));
		$configRoute['route'] = 'index.php?option=com_config&view=component&component=' . OPTION . '&return=' . $return.$Itemid;
		$configRoute['params'] = array();
		
		$config=AFactory::getConfig();
		$supplie_group_id=$config->supplierUsergroup;
		
		$document=JFactory::getDocument();
		echo '<div id="j-sidebar-container" class="span2">';
		JHtmlSidebar::addEntry(JText::_('COM_BOOKPRO_DASHBOARD'),'index.php?option=com_bookpro'.$Itemid);
		JHtmlSidebar::addEntry(JText::_('COM_BOOKPRO_CONFIGURATION'),$configRoute['route'].$Itemid);
		JHtmlSidebar::addEntry(JText::_('COM_BOOKPRO_CUSTOMER'),'index.php?option=com_bookpro&view=customers&group_id='.$config->customersUsergroup.$Itemid);
		//JHtmlSidebar::addEntry(JText::_('COM_BOOKPRO_SUPPLIER'),'index.php?option=com_bookpro&view=customers&group_id='.$supplie_group_id);
		JHtmlSidebar::addEntry(JText::_('COM_BOOKPRO_AGENTS'),'index.php?option=com_bookpro&view=customers&group_id='.$config->agentUsergroup.$Itemid);

		JHtmlSidebar::addEntry(JText::_('COM_BOOKPRO_APPLICATION'),JRoute::_(ARoute::view(VIEW_APPLICATIONS)).$Itemid);
		JHtmlSidebar::addEntry(JText::_('COM_BOOKPRO_DESTINATION'),JRoute::_(ARoute::view('airports')));
		JHtmlSidebar::addEntry(JText::_('COM_BOOKPRO_COUPONS'),JRoute::_(ARoute::view('coupons')));
		JHtmlSidebar::addEntry(JText::_('COM_BOOKPRO_ORDERS'),JRoute::_(ARoute::view('orders')));
		JHtmlSidebar::addEntry(JText::_('COM_BOOKPRO_CATEGORY'),JRoute::_(ARoute::view('categories')));
		
		AImporter::model('applications');
		$omodel = new BookProModelApplications();
		$items = $omodel->getData();
		foreach ($items as $item){
			
			if($item->state==1){
				
				$views=explode(';', $item->views);
				if(count($views))
					for ($j=0;$j < count($views);$j++){
					JHtmlSidebar::addEntry(JText::_('COM_BOOKPRO_'.strtoupper($views[$j])),JRoute::_(ARoute::view($views[$j])));
				}
			}
		}
		echo JHtmlSidebar::render();
		
		echo '</div>';


	}

}

