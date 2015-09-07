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

class iJoomla_SeoControllerNewredcategory extends iJoomla_SeoController{
	
	function __construct() {	  
		parent::__construct();
		// Register Extra tasks
		$this->registerTask('edit', 'edit');		
		$this->registerTask('save', 'save');
		$this->registerTask('apply', 'apply');
	}	    	
	
	function save(){
		$model = $this->getModel('newredcategory');
		$result = $model->save();
		$link = "index.php?option=com_ijoomla_seo&controller=redirectcategory";
		
		if ($result["0"] === TRUE) {
			$msg = JText::_("COM_IJOOMLA_SEO_CAT_SAVE_SUCCESSFULLY");
			$this->setRedirect($link, $msg);
		} 
		elseif($result["0"] === FALSE) {
		 	$msg = JText::_("COM_IJOOMLA_SEO_CAT_SAVE_UNSUCCESSFULLY");		
			$this->setRedirect($link, $msg, 'notice');
		}
	}
	
	function apply(){
		$model = $this->getModel('newredcategory');
		$result = $model->save();
		$id = JRequest::getVar("id");
		$link = "index.php?option=com_ijoomla_seo&controller=newredcategory&task=edit&id=".$result["1"];
		
		if ($result["0"] === TRUE) {
			$msg = JText::_("COM_IJOOMLA_SEO_CAT_SAVE_SUCCESSFULLY");
			$this->setRedirect($link, $msg);
		} 
		elseif($result["0"] === FALSE) {
		 	$msg = JText::_("COM_IJOOMLA_SEO_CAT_SAVE_UNSUCCESSFULLY");		
			$this->setRedirect($link, $msg, 'notice');
		}
	}
		
	function edit(){
		JRequest::setVar( 'view', 'Newredcategory' );	
		parent::display();
	}		
	
	function cancel(){
		$msg = JText::_('COM_IJOOMLA_SEO_OPERATION_CANCELED');
		$this->setRedirect('index.php?option=com_ijoomla_seo&controller=redirectcategory', $msg);
	}
}

?>