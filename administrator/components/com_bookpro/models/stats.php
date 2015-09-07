   <?php
 defined('_JEXEC') or die('Restricted access');
/**
* @version		$Id:passenger.php  1 2014-03-15 18:20:26Z Quan $
* @package		Bookpro1
* @subpackage 	Models
* @copyright	Copyright (C) 2014, Nguyen Dinh Cuong. All rights reserved.
* @license #http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/
 defined('_JEXEC') or die('Restricted access');
/**
 * Bookpro1ModelPassenger 
 * @author Nguyen Dinh Cuong
 */
 
class BookproModelStats  extends JModelAdmin { 

		
/**
	 * Method to get the record form.
	 *
	 * @param   array    $data      Data for the form. [optional]
	 * @param   boolean  $loadData  True if the form is to load its own data (default case), false if not. [optional]
	 *
	 * @return  mixed  A JForm object on success, false on failure

	 */
var $_table;
	
	var $_ids;
	
	function __construct()
	{
		parent::__construct();
		if (! class_exists('TableFlight')) {
			AImporter::table('flight');
		}
		$this->_table = $this->getTable('flight');
	}
  	public function getForm($data = array(), $loadData = true)
        {

            $form = $this->loadForm('com_bookpro.stats', 'stats', array('control' => 'jform', 'load_data' => $loadData));
            
            if (empty($form))
                return false;
            return $form;
        }

        /**
        * (non-PHPdoc)
        * @see JModelForm::loadFormData()
        */
     protected function loadFormData()
        {
            $data = JFactory::getApplication()->getUserState('com_bookpro.edit.stats.data', array());
            
            return $data;
        }
	
	
}
?>