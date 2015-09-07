<?php

/**
 * @package		JFBConnect
 * @copyright (C) 2009-2014 by Source Coast - All rights reserved
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

defined('JPATH_PLATFORM') or die;

jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');

class JFormFieldSocialProfileRegistration extends JFormFieldList
{
	public $type = 'SocialProfileRegistration';

	protected function getOptions()
	{
        JPluginHelper::importPlugin('socialprofiles');
        $plugins = JFactory::getApplication()->triggerEvent('socialProfilesGetPlugins');

        $options = array();
        $options[] = JHtml::_('select.option', "jfbconnect", "JFBConnect");
        foreach ($plugins as $p)
        {
            $regUrl = $p->registration_url;
            if ($regUrl)
            {
                $options[] = JHtml::_('select.option', $p->getName(), $p->getName());
            }
        }
        return $options;
	}

    protected function getInput()
    {
        if (count($this->getOptions()) == 0)
            return "";

        return parent::getInput();
    }

    protected function getLabel()
    {
        if (count($this->getOptions()) == 0)
            return "<label>There are no Profile Plugins enabled which support Alternative Registration</label>";

        return parent::getLabel();
    }
}
