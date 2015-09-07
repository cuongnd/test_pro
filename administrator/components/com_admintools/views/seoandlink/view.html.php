<?php
/**
 * @package   AdminTools
 * @copyright Copyright (c)2010-2014 Nicholas K. Dionysopoulos
 * @license   GNU General Public License version 3, or later
 * @version   $Id$
 */

// Protect from unauthorized access
defined('_JEXEC') or die;

class AdmintoolsViewSeoandlink extends F0FViewHtml
{
	protected function onBrowse($tpl = null)
	{
		$model = $this->getModel();
		$config = $model->getConfig();

		$this->salconfig = $config;
	}
}