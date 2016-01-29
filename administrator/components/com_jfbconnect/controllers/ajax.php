<?php
/**
 * @package         JFBConnect
 * @copyright (c)   2009-2014 by SourceCoast - All Rights Reserved
 * @license         http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @version         Release v6.2.4
 * @build-date      2014/12/15
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

class JFBConnectControllerAjax extends JFBConnectController
{
    function scsocialprovider()
    {
        $input = JFactory::getApplication()->input;
        $modProvider = $input->get('provider');
        $widgets = JFBCFactory::getAllWidgets($modProvider);

        $options = array();
        $options[] = JHtml::_('select.option', "widget", "--Select your widget--");
        foreach ($widgets as $widget)
        {
            $options[] = JHtml::_('select.option', $widget->getSystemName(), $widget->getName());
        }
        $registry = $this->getModuleSettings($input->getInt('id'));

        echo JHTML::_('select.genericlist', $options, 'jform[params][widget_type]', 'onchange="jfbcAdmin.scsocialwidget.fetchSettings(this.value);"', 'value', 'text', $registry->get('widget_type'));
        if ($registry->get('widget_type') != "widget")
            echo "<script>jfbcAdmin.scsocialwidget.fetchSettings('" . $registry->get('widget_type') . "');</script>";

        exit;
    }

    function scsocialwidget()
    {
        $input = JFactory::getApplication()->input;
        $provider = $input->get('provider');
        $widget = $input->get('name');

        // load the name form.xml file here
        SCStringUtilities::loadLanguage('com_jfbconnect', JPATH_ADMINISTRATOR);
        JForm::addFieldPath(JPATH_ROOT .'/components/com_jfbconnect/libraries/provider/'.$provider.'/widget');
        $xmlFile = JPATH_ROOT .'/components/com_jfbconnect/libraries/provider/'.$provider.'/widget/'.$widget.'.xml';
        if(JFile::exists($xmlFile))
        {
            $options = array('control' => 'jform');
            $form = JForm::getInstance('com_jfbconnect_' . $widget, $xmlFile, $options);

            $registry = $this->getModuleSettings($input->getInt('id'));

            $form->bind($registry);
            foreach ($form->getFieldsets() as $fiedsets => $fieldset)
            {
                foreach ($form->getFieldset($fieldset->name) as $field)
                    $this->formShowField($field);
            }
        }
        exit;
    }

    function getModuleSettings($id)
    {
        $table = JTable::getInstance('Module', 'JTable', array());
        // Attempt to load the row.
        $return = $table->load($id);
        $registry = new JRegistry;
        if ($return)
        {
            $table->getProperties(1);
            $registry->loadString($table->params);
            $registry->set('params.widget_settings', $registry->get('widget_settings'));
            $registry->set('widget_settings', null);
        }
        return $registry;
    }

    public function formShowField($field)
    {
        echo "  <div class=\"control-group\">\n";
        echo "    <div class=\"control-label\">\n";
        echo "   " . $field->label . "\n";
        echo "   </div>\n";
        echo "     <div class=\"controls\">\n";
        echo "       " . $field->input . "\n";
        echo "     </div>\n";
        echo "  </div>\n";
    }

    public function channelGetOutboundChannels()
    {
        $input = JFactory::getApplication()->input;
        $p = $input->getString('provider');
        $options = array();
        $options[] = JHtml::_('select.option', "--", "-- Select a Channel --");

        $provider = JFBCFactory::provider($p);
        $channels = $provider->getChannelsOutbound();
        foreach ($channels as $c)
        {
            $options[] = JHtml::_('select.option', strtolower($c->name), $c->name);
        }
        echo JHTML::_('select.genericlist', $options, 'jform[type]', 'onchange="jfbcAdmin.channels.outbound.fetchChannelSettings(this.value);"', 'value', 'text', '--', 'jform_type');

        exit;
    }

    public function channelGetOutboundChannelSettings()
    {
        $input = JFactory::getApplication()->input;
        $p = $input->getString('provider');
        $c = $input->getString('channel');
        $options = array();
        $options[] = JHtml::_('select.option', "--", "-- Select a Channel --");

        $formFile = JPATH_SITE . '/components/com_jfbconnect/libraries/provider/' . $p . '/channel/' . $c . '_outbound.xml';

        if (!$form = JForm::getInstance('com_jfbconnect.' . $p . '.channel.' . $c, $formFile, $options))
            throw new Exception(JText::_('JERROR_LOADFILE_FAILED'));

        ob_start();
        foreach ($form->getFieldsets() as $fiedsets => $fieldset)
        {
            foreach ($form->getFieldset($fieldset->name) as $field)
                $this->formShowField($field);
        }
        $html = ob_get_clean();
        echo $html;
        exit;
    }

    public function channelShowAttributes()
    {
        $input = JFactory::getApplication()->input;
        $app = JFactory::getApplication();
        $providerData = new stdClass();
        $providerData->provider = $input->getString('provider');
        $providerData->type = $input->getString('type');
        $app->setUserState('com_jfbconnect.edit.channel.data', $providerData);

        $this->displayAttributes();
        exit;
    }

    public function channelUpdateUser()
    {
        $input = JFactory::getApplication()->input;

        $app = JFactory::getApplication();
        $providerData = new stdClass();
        $providerData->provider = $input->getString('provider');
        $providerData->type = $input->getString('type');
        $providerData->attribs = new stdClass();
        $providerData->attribs->user_id = $input->getString('userid');
        $app->setUserState('com_jfbconnect.edit.channel.data', $providerData);

        $this->displayAttributes();
        exit;
    }

    private function displayAttributes()
    {
        require_once(JPATH_ADMINISTRATOR .'/components/com_jfbconnect/views/channel/view.html.php');
        $model = JModelLegacy::getInstance('Channel', 'JFBConnectModel');
        $view = new JFBConnectViewChannel();
        $view->setModel($model);
        echo $view->displayAttributes();
    }
}
