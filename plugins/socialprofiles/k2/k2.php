<?php
/**
 * @package JFBConnect - K2 Profile Integration
 * @copyright (C) 2010-2013 by SourceCoast - All rights reserved
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
defined('_JEXEC') or die('Restricted access');

jimport('sourcecoast.plugins.socialprofile');

class plgSocialProfilesK2 extends SocialProfilePlugin
{
    var $_userTableRow;

    function __construct(&$subject, $params)
    {
        $this->_componentFolder = JPATH_SITE . '/components/com_k2';
        $this->_componentFile = '';
        parent::__construct($subject, $params);

        if ($this->componentLoaded())
            JTable::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_k2/tables');

        $this->defaultSettings->set('import_avatar', '1');
        $this->defaultSettings->set('import_always', '0');
        $this->defaultSettings->set('registration_show_fields', '0'); //0=None, 1=Required, 2=All
        $this->defaultSettings->set('imported_show_fields', '0'); //0=No, 1=Yes
        $this->defaultSettings->set('registration_show_fields', '0');
    }

    protected function createUser($profileData)
    {
        // Setup new user
        $jUser = JUser::getInstance($this->joomlaId);
        $this->saveProfileField('userId', $jUser->id);
        $this->saveProfileField('userName', $jUser->name);
        $k2params = & JComponentHelper::getParams('com_k2');
        $this->saveProfileField('group', $k2params->get('K2UserGroup', 1));

        foreach ($this->getProfileFields() as $field)
            $this->saveProfileField($field->id, $profileData->getFieldWithUserState($field->id));
    }

    /*     * * End Trigger Overrides ** */
    protected function saveProfileField($fieldId, $value)
    {
        $k2Row = $this->getK2UserRow();
        if ($fieldId == "gender")
        {
            if ($value == 'male')
                $value = "m";
            else if ($value == 'female')
                $value = 'f';
        }
        $k2Row->set($fieldId, $value);
        $k2Row->store();
    }

    protected function setAvatar($socialAvatar)
    {
        $k2Row = $this->getK2UserRow();
        $k2params = & JComponentHelper::getParams('com_k2');

        require_once(JPATH_ADMINISTRATOR . '/components/com_k2/lib/class.upload.php');
        $savepath = JPATH_ROOT . '/media/k2/users/';

        $this->loadK2UserTable();

        $handle = new Upload($this->getAvatarPath() . '/' . $socialAvatar);
        $handle->file_auto_rename = true;
        $handle->file_overwrite = false;
        $handle->file_new_name_body = $this->_userTableRow->id;
        $handle->image_resize = true;
        $handle->image_ratio_y = true;
        $handle->image_x = $k2params->get('userImageWidth', '100');
        $handle->Process($savepath);
        $handle->Clean();
        $k2Row->image = $handle->file_dst_name;

        $k2Row->store();
        return true;
    }

    protected function setDefaultAvatar()
    {
        $this->loadK2UserTable();

        jimport('joomla.filesystem.file');
        if (JFile::exists(JPATH_ROOT . '/media/k2/users/' . $this->_userTableRow->image))
        {
            JFile::delete(JPATH_ROOT . '/media/k2/users/' . $this->_userTableRow->image);
        }
        $k2Row = $this->getK2UserRow();
        $k2Row->image = '';
        $k2Row->store();
    }

    protected function getProfileFields()
    {
        $k2Fields = array();
        $k2Fields[] = (object)array('id' => "gender", "name" => "Gender");
        $k2Fields[] = (object)array('id' => "description", "name" => "Description");
        $k2Fields[] = (object)array('id' => "url", "name" => "URL");

        return $k2Fields;
    }

    private function getK2UserRow()
    {
        $query = $this->db->getQuery(true);
        $query->select("id");
        $query->from("#__k2_users");
        $query->where('userId=' . $query->quote($this->joomlaId));
        $this->db->setQuery($query);

        $k2id = $this->db->loadResult();

        $row = & JTable::getInstance('K2User', 'Table');
        $row->load($k2id);

        return $row;
    }

    /**
     * Taken from K2 System plugin (GNU/GPL)
     */
    function loadK2UserTable()
    {
        $this->_userTableRow = & JTable::getInstance('K2User', 'Table');

        $db = JFactory::getDBO();
        $query = "SELECT id FROM #__k2_users WHERE userID=" . $this->joomlaId;
        $db->setQuery($query);
        $result = $db->loadResult();

        $this->_userTableRow->load($result);
    }

}