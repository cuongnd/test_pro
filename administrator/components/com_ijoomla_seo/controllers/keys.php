<?php
/**
* @copyright   (C) 2010 iJoomla, Inc. - All rights reserved.
* @license  GNU General Public License, version 2 (http://www.gnu.org/licenses/gpl-2.0.html) 
* @author  iJoomla.com webmaster@ijoomla.com
* @url   http://www.ijoomla.com/licensing/
* the PHP code portions are distributed under the GPL license. If not otherwise stated, all images, manuals, cascading style sheets, and included JavaScript  
* are NOT GPL, and are released under the IJOOMLA Proprietary Use License v1.0 
* More info at http://www.ijoomla.com/licensing/
*/

defined( '_JEXEC' ) or die( 'Restricted access' );

class iJoomla_SeoControllerKeys extends iJoomla_SeoController{
	
	function __construct() {	  
		parent::__construct();		
		// Register Extra tasks		
		$this->registerTask('', 'keys');
		$this->registerTask('keys', 'keys');
		$this->registerTask('save', 'save');
		$this->registerTask('remove', 'delete');
		$this->registerTask('sticky', 'sticky_unsticky');
		$this->registerTask('unsticky', 'sticky_unsticky');
	}
	
	function keys(){
		JRequest::setVar( 'view', 'Keys' );	
		parent::display();
	}
	
	function sticky_unsticky(){
		$model = $this->getModel('keys');
		$result = $model->getStickyUnsticky();
		$link = "index.php?option=com_ijoomla_seo&controller=keys";
		if($result === TRUE){
			$msg = JText::_("COM_IJOOMLA_SEO_STICKY_SUCCESSFULLY");
			$this->setRedirect($link, $msg);
		}
		else{
			$msg = JText::_("COM_IJOOMLA_SEO_STICKY_UNSUCCESSFULLY");
			$this->setRedirect($link, $msg, 'notice');
		}
	}
	
	function save(){
		$model = $this->getModel('keys');
		$result = $model->save();
		$link = "index.php?option=com_ijoomla_seo&controller=keys";
		if($result === TRUE){
			$msg = JText::_("COM_IJOOMLA_SEO_ADDED_SUCCESSFULLY");
			$this->setRedirect($link, $msg);
		}
		else{
			$msg = JText::_("COM_IJOOMLA_SEO_ADDED_UNSUCCESSFULLY");
			$this->setRedirect($link, $msg, 'error');
		}
		return true;
	}
	
	function delete(){
		$model = $this->getModel('keys');
		$result = $model->delete();
		$link = "index.php?option=com_ijoomla_seo&controller=keys";
		if($result === TRUE){
			$msg = JText::_("COM_IJOOMLA_SEO_DELETED_SUCCESSFULLY");
			$this->setRedirect($link, $msg);
		}
		else{
			$msg = JText::_("COM_IJOOMLA_SEO_DELETED_UNSUCCESSFULLY");
			$this->setRedirect($link, $msg, 'error');
		}
		return true;
	}
	
	function cancel(){
		$msg = JText::_('COM_IJOOMLA_SEO_OPERATION_CANCELED');
		$this->setRedirect('index.php?option=com_ijoomla_seo', $msg);
	}
}

?>