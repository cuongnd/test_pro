<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_website
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * View to edit an website.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_website
 * @since       1.6
 */
class WebsiteViewWebsite extends JViewLegacy
{
	protected $form;

	protected $item;

	protected $state;

	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{
        $input=JFactory::getApplication()->input;

		$layout = JRequest::getVar('layout');
		$tpl = JRequest::getVar('tpl');
		$this->setLayout($layout);
		switch ($tpl) {
			case "config":
				parent::display($tpl);
				return;
				break;

		}


        $model_Website=$this->getModel();
        $layout=$this->getLayout();

		if ($this->getLayout() == 'pagebreak')
		{
			// TODO: This is really dogy - should change this one day.
			$eName    = JRequest::getVar('e_name');
			$eName    = preg_replace('#[^A-Z0-9\-\_\[\]]#i', '', $eName);
			$document = JFactory::getDocument();
			$document->setTitle(JText::_('com_website_PAGEBREAK_DOC_TITLE'));
			$this->eName = &$eName;
			parent::display($tpl);
			return;
		}

		$this->form		= $this->get('Form');
		$this->item		= $this->get('Item');
		$this->state	= $this->get('State');

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

		if ($this->getLayout() == 'modal')
		{
			$this->form->setFieldAttribute('language', 'readonly', 'true');
			$this->form->setFieldAttribute('catid', 'readonly', 'true');
		}
        $this->layoutOfCurrentStep=$this->getLayoutOfCurrentStep();
        $this->progress_bar_success=$model_Website->getProgressBarSuccess($this->layoutOfCurrentStep);
        if(!$this->progress_bar_success)
            $this->progress_bar_success=2;
		parent::display($tpl);
	}
    function getLayoutOfCurrentStep()
    {
        $model_Website=$this->getModel();
        $listStep=$model_Website->getListStep();
        $layout=reset($listStep);
        $input=JFactory::getApplication()->input;
        $firstsetup=$input->getInt('firstsetup',0);
        if($firstsetup)
            return $layout;
        $layout=$model_Website->getLayoutOfCurrentStep();
        $this->errors=$model_Website->getErrors();
        return $layout;
    }
    public function  parentDisPlay($tpl)
    {
        parent::display($tpl);
    }


}
