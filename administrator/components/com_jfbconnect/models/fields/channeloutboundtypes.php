<?php

/**
 * @package        JFBConnect
 * @copyright (C) 2009-2014 by Source Coast - All rights reserved
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

defined('JPATH_PLATFORM') or die;

jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');

class JFormFieldChannelOutboundTypes extends JFormFieldList
{
    public $type = 'ChannelOutbound';

    protected function getOptions()
    {
        $options = array();
        $options[] = JHtml::_('select.option', "--", "-- Select a Channel --");

        $p = $this->form->getValue('provider');
        if ($p && $p != '--')
        {
            $provider = JFBCFactory::provider($p);
            $channels = $provider->getChannelsOutbound();
            foreach ($channels as $c)
            {
                $options[] = JHtml::_('select.option', strtolower($c->name), $c->name);
            }
        }
        return $options;
    }
}