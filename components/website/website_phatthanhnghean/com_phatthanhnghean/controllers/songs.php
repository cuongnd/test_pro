<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_cpanel
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * cpanel list controller class.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_cpanel
 * @since       1.6
 */
class PhatThanhNgheAnControllerSongs extends JControllerAdmin
{
	/**
	 * Method to get a model object, loading it if required.
	 *
	 * @param   string  $name    The model name. Optional.
	 * @param   string  $prefix  The class prefix. Optional.
	 * @param   array   $config  Configuration array for model. Optional.
	 *
	 * @return  object  The model.
	 *
	 * @since   1.6
	 */
	public function getModel($name = 'song', $prefix = 'PhatThanhNgheAnModel', $config = array('ignore_request' => true))
	{
		$model = parent::getModel($name, $prefix, $config);
		return $model;
	}
    public function get_list_song(){
        $model=$this->getModel("songs");
        $list_song=$model->getItems();
        $return_music=new stdClass();
        $return_music->music=$list_song;
        header('Content-Type: application/json');
        echo json_encode($return_music,JSON_NUMERIC_CHECK);
        die;
    }




}
