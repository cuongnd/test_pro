<?php
/**
 * @package        JFBConnect
 * @copyright (C) 2009-2013 by Source Coast - All rights reserved
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
defined('_JEXEC') or die('Restricted access');
?>
<div class="sourcecoast">
    <div>
        <div class="config_row">
            <div class="config_setting header">General Setting</div>
            <div class="config_option header">Options</div>
            <div style="clear:both"></div>
        </div>
        <div class="config_row">
            <div class="config_setting hasTip" title="If set to yes, a user's avatar and field mappings (as set below) will be imported each and every time they login to your site. If set to No, the import will only
            take place on their initial registration. After that, any updates to their social network profile will not be reflected on your site.">Always Import
                Profile Data:
            </div>
            <div class="config_option">
                <fieldset id="profiles_customdb_import_always" class="radio btn-group">
                    <input type="radio" id="profiles_customdb_import_always1" name="profiles_customdb_import_always"
                           value="1" <?php echo $this->settings->get('import_always') == '1' ? 'checked="checked"' : ""; ?> />
                    <label for="profiles_customdb_import_always1">Yes</label>
                    <input type="radio" id="profiles_customdb_import_always0" name="profiles_customdb_import_always"
                           value="0" <?php echo $this->settings->get('import_always') == '0' ? 'checked="checked"' : ""; ?> />
                    <label for="profiles_customdb_import_always0">No</label>
                </fieldset>
            </div>
            <div style="clear:both"></div>
        </div>
        <div class="config_row">
            <div class="config_setting hasTip" title="Leave blank to use the standard Joomla table">Database Name:</div>
            <div class="config_option">
                <fieldset id="profiles_customdb_db_name">
                    <input type="text" id="profiles_customdb_db_name" name="profiles_customdb_db_name"
                           value="<?php echo $this->settings->get('db_name') ?>" />
                </fieldset>
            </div>
            <div style="clear:both"></div>
        </div>
        <div class="config_row">
            <div class="config_setting hasTip" title="Leave blank to use the standard Joomla table">Database User:</div>
            <div class="config_option">
                <fieldset id="profiles_customdb_db_user">
                    <input type="text" id="profiles_customdb_db_user" name="profiles_customdb_db_user"
                           value="<?php echo $this->settings->get('db_user') ?>" />
                </fieldset>
            </div>
            <div style="clear:both"></div>
        </div>
        <div class="config_row">
            <div class="config_setting hasTip" title="Leave blank to use the standard Joomla table">Database Password:</div>
            <div class="config_option">
                <fieldset id="profiles_customdb_db_password">
                    <input type="text" id="profiles_customdb_table_name" name="profiles_customdb_db_password"
                           value="<?php echo $this->settings->get('db_password') ?>" />
                </fieldset>
            </div>
            <div style="clear:both"></div>
        </div>
        <div class="config_row">
            <div class="config_setting hasTip" title="Select the table from your database to store the data in">Database Table Name:</div>
            <div class="config_option">
                <fieldset id="profiles_customdb_db_table">
                    <input type="text" id="profiles_customdb_db_table" name="profiles_customdb_db_table"
                           value="<?php echo $this->settings->get('db_table') ?>" />
                </fieldset>
            </div>
            <div style="clear:both"></div>
        </div>
        <div class="config_row">
            <div class="config_setting hasTip" title="Table key that is used to look the user up, or insert by">Table Key:</div>
            <div class="config_option">
                <fieldset id="profiles_customdb_db_table">
                    <input type="text" id="profiles_customdb_db_key_column" name="profiles_customdb_db_key_column"
                           value="<?php echo $this->settings->get('db_key_column') ?>" /> =
                    <?php
                    $options = array(0 => array('id' => 'joomla_id', 'name' => 'Joomla User Id'), 1 => array('id' => 'provider_id', 'name' => 'Social Network ID'));
                    echo JHTML::_('select.genericlist', $options, 'profiles_customdb_db_key_value', null, 'id', 'name', $this->settings->get('db_key_value', 'joomla_id'));
                    ?>
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
            <legend>CustomDB <-> Social Network field mapping</legend>
            <table>
                <tr>
                    <th>CustomDB Field</th>
                    <th>Social Network Field</th>
                </tr>
                <?php
                foreach ($this->profileFields as $profileField)
                {
                    $selectedValue = $this->settings->get('field_map.' . $profileField->id, '0'); // default to 0

                    echo '<tr><td>';
                    echo $profileField->name;
                    echo '</td><td>';
                    echo '<select name="profiles_customdb_field_map' . $profileField->id . '">';
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