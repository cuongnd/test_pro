<?php
/**
 * @package   AdminTools
 * @copyright Copyright (c)2010-2014 Nicholas K. Dionysopoulos
 * @license   GNU General Public License version 3, or later
 */

// Protect from unauthorized access
defined('_JEXEC') or die;

class AdmintoolsViewFixperms extends F0FViewHtml
{
	protected function onBrowse($tpl = null)
	{
		$model = $this->getModel();
		$state = $model->getState('scanstate', false);

		$total = $model->totalFolders;
		$done = $model->doneFolders;

		if ($state)
		{
			if ($total > 0)
			{
				$percent = min(max(round(100 * $done / $total), 1), 100);
			}

			$more = true;
		}
		else
		{
			$percent = 100;
			$more = false;

			JToolBarHelper::back('JTOOLBAR_BACK', 'index.php?option=com_admintools');
		}

		$this->more = $more;
		$this->percentage = $percent;
		$this->setLayout('default');

		if ($more)
		{
			$script = "window.addEvent( 'domready' ,  function() {\n";
			$script .= "document.forms.adminForm.submit();\n";
			$script .= "});\n";
			JFactory::getDocument()->addScriptDeclaration($script);
		}
	}

	public function onRun()
	{
		$this->onBrowse();
	}
}