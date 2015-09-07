<?php
/**
 * @package JFBConnect - Virtuemart Integration
 * @copyright (C) 2010-2012 by SourceCoast - All rights reserved
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
defined('_JEXEC') or die('Restricted access');

jimport('sourcecoast.plugins.socialprofile');

class plgSocialProfilesVirtuemart2 extends SocialProfilePlugin
{
    function __construct(&$subject, $params)
    {
        $this->_componentFolder = JPATH_SITE . '/components/com_virtuemart';
        parent::__construct($subject, $params);

        //$this->defaultSettings->set('import_always', '0');
        $this->defaultSettings->set('registration_show_fields', '0'); //0=None, 1=Required, 2=All
        $this->defaultSettings->set('imported_show_fields', '0'); //0=No, 1=Yes

        if ($this->componentLoaded())
        {
            if (!class_exists('VmConfig'))
                require(JPATH_ADMINISTRATOR . '/components/com_virtuemart/helpers/config.php');
            VmConfig::loadConfig();
        }
    }

    protected function getRegistrationForm($profileData)
    {
        require_once(JPATH_VM_ADMINISTRATOR . '/models/user.php');
        $lang = JFactory::getLanguage();
        $lang->load('com_virtuemart');
        $lang->load('com_virtuemart_shoppers', JPATH_SITE);
        $doc = JFactory::getDocument();
        $doc->addScript(JURI::root(true) . '/components/com_virtuemart/assets/js/jquery.min.js');
        $doc->addScript(JURI::root(true) . '/components/com_virtuemart/assets/js/vmsite.js');
        $doc->addScript(JURI::root(true) . '/components/com_virtuemart/assets/js/jquery.ui.datepicker.min.js');
        $doc->addScript(JURI::root(true) . '/components/com_virtuemart/assets/js/jquery-ui.min.js');
        $doc->addScript(JURI::root(true) . '/components/com_virtuemart/assets/js/jquery.ui.autocomplete.html.js');
        JFactory::getApplication()->set('jquery', TRUE);

        $showRegistrationFields = $this->settings->get('registration_show_fields');
        $showImportedFields = $this->settings->get('imported_show_fields');

        JTable::addIncludePath(JPATH_VM_ADMINISTRATOR . '/tables');
        $userModel = new VirtueMartModelUser();
        $userFields = $userModel->getUserInfoInUserFields('edit', 'BT', 0);
        $this->userFields = $userFields[0];
        $this->vmfield_title = JText::_('COM_VIRTUEMART_USER_FORM_EDIT_BILLTO_LBL');

        // Nasty code, mostly taken from Virtuemart
        ob_start();
        $corefields = VirtueMartModelUserfields::getCoreFields();
        $_set = false;
        $_table = false;
        $_hiddenFields = '';
        $fieldHtml = '';
        $sectionStart = '';

        //             for ($_i = 0, $_n = count($this->userFields['fields']); $_i < $_n; $_i++) {
        for ($_i = 0, $_n = count($this->userFields['fields']); $_i < $_n; $_i++)
        {
            // Do this at the start of the loop, since we're using 'continue' below!
            if ($_i == 0)
            {
                $_field = current($this->userFields['fields']);
            } else
            {
                $_field = next($this->userFields['fields']);
            }

            if ($_field['hidden'] == true)
            {
                $_hiddenFields .= $_field['formcode'] . "\n";
                continue;
            }
            if ($_field['type'] == 'delimiter')
            {
                if ($_set)
                {
                    // We're in Fieldset. Close this one and start a new
                    if ($fieldHtml != '')
                        echo $sectionStart . $fieldHtml;

                    if ($_table)
                    {
                        echo '	</dl>' . "\n";
                        $_table = false;
                    }
                    echo '</fieldset>' . "\n";
                }
                $_set = true;
                $sectionStart = '<fieldset>' . "\n" .
                        '	<legend>' . "\n" .
                        '		' . $_field['title'] .
                        '	</legend>' . "\n";
                $fieldHtml = '';
                continue;
            }

            $fieldName = $this->settings->get('field_map.' . $_field['name'], 0);

            // Show All/Required Fields. Hide mapped fields if not showing imported fields
            $showField = ($showRegistrationFields == '2' || ($_field['required'] && $showRegistrationFields == '1')) &&
                    ($showImportedFields == "1" || ($showImportedFields == "0" && $fieldName == '0'));
            if (!$showField)
            {
                if ($fieldName != '0')
                    $this->set('performsSilentImport', 1);
                continue;
            }
            $fieldValue = $profileData->getFieldWithUserState($_field['name']);
            $_field = $this->getFieldFormData($fieldValue, $_field);

            if (!(in_array($_field['name'], $corefields)) && $_field['name'] != 'email' && $_field['name'] != 'agreed')
            {
                if (!$_table)
                {
                    $fieldHtml .= '<span class="userfields_info">' . $this->vmfield_title . "</span><dl>\n";
                    $_table = true;
                }

                $fieldHtml .= '<dt><label class="' . $_field['name'] . '" for="' . $_field['name'] . '_field">' . "\n" .
                        $_field['title'] . ($_field['required'] ? ' *' : '') . "\n" .
                        ' </label></dt>' . "\n" .
                        "<dd>\n" .
                        $_field['formcode'] . "\n";
                "</dd>\n";
            }
        }

        if ($fieldHtml != '')
            echo $sectionStart . $fieldHtml;

        if ($_table)
        {
            echo '	</dl>' . "\n";
        }
        if ($_set)
        {
            echo '</fieldset>' . "\n";
        }
        //$_hiddenFields = '';
        reset($this->userFields['fields']);

        echo $_hiddenFields;

        $html = ob_get_clean();

        // Get the agree box and show here
        return $html;
    }

    private function getFieldFormData($fieldValue, $_field)
    {
        if ($_field['name'] == 'virtuemart_state_id')
        {
            $fieldValue = $this->getStateValue($fieldValue);
            $_field['formcode'] = shopFunctions::renderStateList($fieldValue, '', false);
            $_field['value'] = $fieldValue;

            $document = JFactory::getDocument();
            // Pretty ugly way to set the state, but VM uses a static list that we can't alter after created.
            $document->addScriptDeclaration('jQuery(function() {
                        setTimeout(function(){
                                jQuery("#virtuemart_state_id").val("' . $fieldValue . '");
                        }, 2000);
                });
            ');
        } else if ($_field['name'] == 'virtuemart_country_id')
        {
            $fieldValue = $this->getCountryValue($fieldValue);
            $_field['formcode'] = ShopFunctions::renderCountryList($fieldValue, false, array(), '');
            $_field['value'] = $fieldValue;
        } else if ($_field['type'] == 'date')
        {
            jimport('joomla.utilities.date');
            $date = new JDate($fieldValue);
            $fieldValue = $date->format("Y-m-d");
            $currentYear = date('Y');
            $calendar = vmJsApi::jDate($fieldValue, $_field['name'], $_field['name'] . '_field', false, ($currentYear - 100) . ':' . $currentYear);
            $_field['formcode'] = $calendar;
            $_field['value'] = $fieldValue;
        } else
        {
            $_field['formcode'] = str_replace('value=""', 'value="' . $fieldValue . '"', $_field['formcode']);
            $_field['value'] = $fieldValue;
        }
        return $_field;
    }

    protected function createUser($profileData)
    {
        $jUser = JFactory::getUser($this->joomlaId);

        // User's profile is already filled out, included fields imported from FB
        JTable::addIncludePath(JPATH_VM_ADMINISTRATOR . '/tables');
        JModelLegacy::addIncludePath(JPATH_VM_ADMINISTRATOR . '/models');
        $userModel = JModelLegacy::getInstance('User', 'VirtueMartModel');

        // Have to skip the Token check since the Redirect can be coming from Facebook (no POST)
        JRequest::setVar(JSession::getFormToken(), 1, 'POST');

        $data = $profileData->toArray();
        $data['username'] = $jUser->get('username');
        $data['email'] = $jUser->get('username');

        $userModel->_id = $this->joomlaId;
        $userModel->saveUserData($data);

        // Create the userinfos row for this user so that profile data can be imported
        $query = "INSERT INTO `#__virtuemart_userinfos` SET `virtuemart_user_id`=" . $jUser->get('id') . ",`address_type`='BT'";
        $this->db->setQuery($query);
        $this->db->execute();

        // Save the profile data
        require_once(JPATH_VM_ADMINISTRATOR . '/models/user.php');
        //$userModel = new VirtueMartModelUser();
        $userFields = $userModel->getUserInfoInUserFields('edit', 'BT', 0);
        $userFields = $userFields[0];
        $corefields = VirtueMartModelUserfields::getCoreFields();

        // Loop through each field and see if it's in the post
        foreach ($userFields['fields'] as $userField)
        {
            if (!(in_array($userField['name'], $corefields)) && $userField['name'] != 'email' && $userField['name'] != 'agreed')
            {
                $value = $profileData->getFieldWithUserState($userField['name'], null);
                if ($value)
                    $this->saveProfileField($userField['name'], $value);
            }
        }

        // Get default shopper group and add the user to it
        $query = "SELECT `virtuemart_shoppergroup_id` FROM `#__virtuemart_shoppergroups` WHERE `default` = 1";
        $this->db->setQuery($query);
        $shopperGroup = $this->db->loadResult();
        $query = "INSERT INTO `#__virtuemart_vmuser_shoppergroups` SET `virtuemart_user_id`=" . $jUser->get('id') . ",`virtuemart_shoppergroup_id`=" . $shopperGroup;
        $this->db->setQuery($query);
        $this->db->execute();
    }

    protected function saveProfileField($fieldId, $value)
    {
        jimport('joomla.utilities.date');

        #format field, if necessary
        $query = 'SELECT type FROM #__virtuemart_userfields WHERE name=' . $this->db->quote($fieldId);
        $this->db->setQuery($query);
        $type = $this->db->loadResult();

        if ($type == "date")
        {
            $date = new JDate($value);
            $value = $date->toSql();
        } else if ($fieldId == 'virtuemart_state_id' && $type == 'select')
        {
            $value = $this->getStateValue($value);
        } else if ($fieldId == 'virtuemart_country_id' && $type == 'select')
        {
            $value = $this->getCountryValue($value);
        }

        // The models for VM2 are atrocious and not welcoming to data from 3rd parties.
        // Don't like doing raw queries, but this is how it's got to be for now.
        $query = "UPDATE #__virtuemart_userinfos SET " . $fieldId . "=" . $this->db->quote($value) . " WHERE virtuemart_user_id=" . $this->joomlaId;
        $this->db->setQuery($query);
        $this->db->execute();
    }

    private function getStateValue($stateString)
    {
        $query = 'SELECT virtuemart_state_id FROM #__virtuemart_states WHERE state_name=' . $this->db->quote($stateString);
        $this->db->setQuery($query);
        $stateId = $this->db->loadResult();
        if ($stateId)
            $value = $stateId;
        else
            $value = $stateString;
        return $value;
    }

    private function getCountryValue($countryString)
    {
        $query = 'SELECT virtuemart_country_id FROM #__virtuemart_countries WHERE country_name=' . $this->db->quote($countryString);
        $this->db->setQuery($query);
        $countryId = $this->db->loadResult();
        if ($countryId)
            $value = $countryId;
        else
            $value = $countryString;

        return $value;
    }

    protected function getProfileFields()
    {
        $lang = JFactory::getLanguage();
        $lang->load('com_virtuemart');
        $lang->load('com_virtuemart_shoppers', JPATH_SITE);

        $query = 'SELECT `name` id, `title` name FROM #__virtuemart_userfields WHERE (`name`="virtuemart_state_id" OR `name`="virtuemart_country_id") OR ((`type`="text" OR `type`="date") AND `name`!="username" AND `name` NOT LIKE "extra_field_%")';
        $this->db->setQuery($query);
        $vmFields = $this->db->loadObjectList();
        return $vmFields;
    }
}