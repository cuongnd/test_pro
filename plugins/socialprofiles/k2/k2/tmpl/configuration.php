<?php
/**
 * @package        JFBConnect
 * @copyright (C) 2009-2013 by Source Coast - All rights reserved
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
defined('_JEXEC') or die('Restricted access');
?>
<div class="sourcecoast">
    <?php
    if ($this->network == 'facebook')
        echo 'We recommend visiting the <a href="http://www.sourcecoast.com/jfbconnect/docs/third-party-integration/facebook-integration-for-k2" target="_blank" title="Facebook Integration for K2">Facebook Integration for K2</a> page for common support questions.';
    else if ($this->network == 'linkedin')
        echo 'We recommend visiting the <a href="http://www.sourcecoast.com/jlinked/docs/third-party-integration/linkedin-integration-for-k2" target="_blank" title="LinkedIn Integration for K2">LinkedIn Integration for K2</a> page for common support questions.';
    ?>
    <div>
        <div class="config_row">
            <div class="config_setting header">General Setting</div>
            <div class="config_option header">Options</div>
            <div style="clear:both"></div>
        </div>
        <div class="config_row">
            <div class="config_setting hasTip" title="If set to yes, a user's avatar and field mappings (as set below) will be imported each and every time they login to your site. If set to No, the import
            will only take place on their initial registration. After that, any updates to their Facebook profile will not be reflected on your site.">Always
                Import Profile Data:
            </div>
            <div class="config_option">
                <fieldset id="profiles_k2_import_always" class="radio btn-group">
                    <input type="radio" id="profiles_k2_import_always1" name="profiles_k2_import_always"
                           value="1" <?php echo $this->settings->get('import_always') == '1' ? 'checked="checked"' : ""; ?> />
                    <label for="profiles_k2_import_always1">Yes</label>
                    <input type="radio" id="profiles_k2_import_always0" name="profiles_k2_import_always"
                           value="0" <?php echo $this->settings->get('import_always') == '0' ? 'checked="checked"' : ""; ?> />
                    <label for="profiles_k2_import_always0">No</label>
                </fieldset>
            </div>
            <div style="clear:both"></div>
        </div>
        <div class="config_row">
            <div class="config_setting hasTip"
                 title="Set this option to Yes to automatically set the user's K2 avatar to their current social network profile picture.">Import Avatar:
            </div>
            <div class="config_option">
                <fieldset id="profiles_k2_import_avatar" class="radio btn-group">
                    <input type="radio" id="profiles_k2_import_avatar1" name="profiles_k2_import_avatar"
                           value="1" <?php echo $this->settings->get('import_avatar') == '1' ? 'checked="checked"' : ""; ?> />
                    <label for="profiles_k2_import_avatar1">Yes</label>
                    <input type="radio" id="profiles_k2_import_avatar0" name="profiles_k2_import_avatar"
                           value="0" <?php echo $this->settings->get('import_avatar') == '0' ? 'checked="checked"' : ""; ?> />
                    <label for="profiles_k2_import_avatar0">No</label>
                </fieldset>
            </div>
            <div style="clear:both"></div>
        </div>
        <div class="config_row">
            <div class="config_setting hasTip" title="On the Login/Registration page, show K2 profile fields in the registration section.">Show Profile
                Fields:
            </div>
            <div class="config_option">
                <fieldset id="profiles_k2_registration_show_fields" class="radio btn-group">
                    <input type="radio" id="profiles_k2_registration_show_fields1" name="profiles_k2_registration_show_fields"
                           value="1" <?php echo $this->settings->get('registration_show_fields') == '1' ? 'checked="checked"' : ""; ?> />
                    <label for="profiles_k2_registration_show_fields1">All</label>
                    <input type="radio" id="profiles_k2_registration_show_fields0" name="profiles_k2_registration_show_fields"
                           value="0" <?php echo $this->settings->get('registration_show_fields') == '0' ? 'checked="checked"' : ""; ?> />
                    <label for="profiles_k2_registration_show_fields0">None</label>
                </fieldset>
            </div>
            <div style="clear:both"></div>
        </div>
        <div class="config_row">
            <div class="config_setting hasTip" title="On the Login/Registration page, show K2 profile fields in the registration section which will be imported from the Social Network.<br/>
                If set to Show, any fields that will be imported from the Social Network will be shown. If set to Hide, Social Network fields will be hidden.<br/>
                Use this option with 'Show Profile Fields' to limit the fields shown on registration.">Show Imported Fields:
            </div>
            <div class="config_option">
                <fieldset id="profiles_k2_imported_show_fields" class="radio btn-group">
                    <input type="radio" id="profiles_k2_imported_show_fields1" name="profiles_k2_imported_show_fields"
                           value="1" <?php echo $this->settings->get('imported_show_fields') == '1' ? 'checked="checked"' : ""; ?> />
                    <label for="profiles_k2_imported_show_fields1">Show</label>
                    <input type="radio" id="profiles_k2_imported_show_fields0" name="profiles_k2_imported_show_fields"
                           value="0" <?php echo $this->settings->get('imported_show_fields') == '0' ? 'checked="checked"' : ""; ?> />
                    <label for="profiles_k2_imported_show_fields0">Hide</label>
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
            <legend>K2 <-> Social Network field mapping</legend>
            <table>
                <tr>
                    <th>K2 Field</th>
                    <th>Social Network Field</th>
                </tr>
                <?php
                foreach ($this->profileFields as $profileField)
                {
                    $selectedValue = $this->settings->get('field_map.' . $profileField->id, '0'); // default to 0

                    echo '<tr><td>';
                    echo $profileField->name;
                    echo '</td><td>';
                    echo '<select name="profiles_k2_field_map' . $profileField->id . '">';
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