<?php
/**
 * @package        JFBConnect
 * @copyright (C) 2009-2012 by Source Coast - All rights reserved
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
defined('_JEXEC') or die('Restricted access');
$lang = JFactory::getLanguage();
$lang->load('com_virtuemart');
$lang->load('com_virtuemart_shoppers', JPATH_SITE);
?>
<div class="sourcecoast">
    <?php
    if ($this->network == 'facebook')
        echo 'We recommend visiting the <a href="http://www.sourcecoast.com/jfbconnect/docs/third-party-integration/facebook-integration-for-virtuemart" target="_blank" title="Facebook Integration for Virtuemart">Facebook Integration for Virtuemart</a> page for common support questions.';

    ?>
    <div>
        <div class="config_row">
            <div class="config_setting header">General Setting</div>
            <div class="config_option header">Options</div>
            <div style="clear:both"></div>
        </div>
        <div class="config_row">
            <div class="config_setting hasTip" title="On the Login/Registration page, show Virtuemart profile fields in the registration section.<br/>
                This will only show fields which are marked as Published and Registration.<br/>">Show Profile Fields:
            </div>
            <div class="config_option">
                <fieldset id="profiles_virtuemart2_registration_show_fields" class="radio btn-group">
                    <input type="radio" id="profiles_virtuemart2_registration_show_fields2" name="profiles_virtuemart2_registration_show_fields"
                           value="2" <?php echo $this->settings->get('registration_show_fields') == '2' ? 'checked="checked"' : ""; ?> />
                    <label for="profiles_virtuemart2_registration_show_fields2">All</label>
                    <input type="radio" id="profiles_virtuemart2_registration_show_fields1" name="profiles_virtuemart2_registration_show_fields"
                           value="1" <?php echo $this->settings->get('registration_show_fields') == '1' ? 'checked="checked"' : ""; ?> />
                    <label for="profiles_virtuemart2_registration_show_fields1">Required only</label>
                    <input type="radio" id="profiles_virtuemart2_registration_show_fields0" name="profiles_virtuemart2_registration_show_fields"
                           value="0" <?php echo $this->settings->get('registration_show_fields') == '0' ? 'checked="checked"' : ""; ?> />
                    <label for="profiles_virtuemart2_registration_show_fields0">None</label>
                </fieldset>
            </div>
            <div style="clear:both"></div>
        </div>

        <div class="config_row">
            <div class="config_setting hasTip" title="On the Login/Registration page, show Virtuemart profile fields in the registration section which will be imported from the social network.<br/>
                If set to Show, any fields that will be imported from the social network will be shown. If set to Hide, fields be importing into will be hidden.<br/>
                Use this option with 'Show Profile Fields' to limit the fields shown on registration.">Show Imported Fields:
            </div>
            <div class="config_option">
                <fieldset id="profiles_virtuemart2_imported_show_fields" class="radio btn-group">
                    <input type="radio" id="profiles_virtuemart2_imported_show_fields1" name="profiles_virtuemart2_imported_show_fields"
                           value="1" <?php echo $this->settings->get('imported_show_fields') == '1' ? 'checked="checked"' : ""; ?> />
                    <label for="profiles_virtuemart2_imported_show_fields1">Show</label>
                    <input type="radio" id="profiles_virtuemart2_imported_show_fields0" name="profiles_virtuemart2_imported_show_fields"
                           value="0" <?php echo $this->settings->get('imported_show_fields') == '0' ? 'checked="checked"' : ""; ?> />
                    <label for="profiles_virtuemart2_imported_show_fields0">Hide</label>
                </fieldset>
            </div>
            <div style="clear:both"></div>
        </div>
    </div>
    <?php
    if ($this->network == 'facebook')
        echo $this->getFieldMappingHtml(); // New call in JFBConnect v5.1
    else
    {
        ?>
        <fieldset>
            <legend>Virtuemart <-> Social Network field mapping</legend>
            <table>
                <tr>
                    <th>Virtuemart Field</th>
                    <th>Social Network Field</th>
                </tr>
                <?php
                foreach ($this->profileFields as $profileField)
                {
                    $selectedValue = $this->settings->get('field_map.' . $profileField->id, '0'); // default to 0

                    echo '<tr><td>';
                    echo JText::_($profileField->name);
                    echo '</td><td>';
                    echo '<select name="profiles_virtuemart2_field_map' . $profileField->id . '">';
                    foreach ($socialNetworkProfileFields as $name => $providerField)
                    {
                        if ($name == $selectedValue)
                            $selected = 'selected';
                        else
                            $selected = '';
                        echo '<option value="' . $name . '" ' . $selected . '>' . $providerField . '</option>';
                    }
                    echo '</select>';
                    echo '</td></tr>';
                }
                ?>
            </table>
        </fieldset>
    <?php
    }
    ?>
</div>