<?php
/**
 * @package        JFBConnect
 * @copyright (C) 2009-2013 by Source Coast - All rights reserved
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

// Probably should work toward extending the JFBConnectFacebookLibrary for this class.
// Too much intertwined in the root library right now.
class JFBConnectProfileFacebook extends JFBConnectProfile
{
    protected function setProviderFields()
    {
        $this->providerFields = array(
            '0' => 'None',
            'name' => 'User - Full Name',
            'first_name' => 'User - First Name',
            'middle_name' => 'User - Middle Name',
            'last_name' => 'User - Last Name',
            'profile_url' => 'User - Profile Link',
            'friend_count' => 'User - Friend Count',
            'hometown_location.city' => 'Basic Info - Hometown City',
            'hometown_location.state' => 'Basic Info - Hometown State',
            'hometown_location.country' => 'Basic Info - Hometown Country',
            'hometown_location.name' => 'Basic Info - Hometown City/State', // user_hometown
            'current_location.city' => 'Basic Info - Current City',
            'current_location.state' => 'Basic Info - Current State',
            'current_location.country' => 'Basic Info - Current Country',
            'current_location.name' => 'Basic Info - Current City/State', // user_location
            'timezone' => 'Basic Info - Timezone',
            'sex' => 'Basic Info - Sex (Male / Female)',
            'birthday' => 'Basic Info - Birthday', // user_birthday
            'political' => 'Basic Info - Political View', // user_religion_politics
            'religion' => 'Basic Info - Religious Views', // user_religion_politics
            'about_me' => 'Basic Info - Bio', // user_about_me
            'profile_blurb' => 'Basic Info - Profile Blurb', // user_about_me
            'quotes' => 'Basic Info - Favorite Quotes', // user_about_me
            'music' => 'Likes & Interests - Music', // user_likes
            'books' => 'Likes & Interests - Books', // user_likes
            'movies' => 'Likes & Interests - Movies', // user_likes
            'tv' => 'Likes & Interests - TV', // user_likes
            'games' => 'Likes & Interests - Games', //user_likes
            'activities' => 'Likes & Interests - Activities', // user_activities
            'interests' => 'Likes & Interests - Interests', // user_interests
            'relationship_status' => 'Relationship - Relationship Status', // user_relationships
            //'significant_other_id' => 'Relationship - Significant Other',
            //'meeting_sex' => 'Relationship - Type of Relationship Looking For', // 3.0.2
            //'meeting_for' => 'Relationship - Reasons For Looking', // 3.0.2
            //'affiliations' => 'Network Affiliations', // 3.0.2
            'work.0.employer.name' => 'Education and Work - Employer', // user_work_history
            'work.0.location.name' => 'Education and Work - Location', // user_work_history
            'work.0.position.name' => 'Education and Work - Position', // user_work_history
            'work.0.start_date' => 'Education and Work - Start Date', // user_work_history
            'work.0.end_date' => 'Education and Work - End Date', // user_work_history
            'education.College.school.name' => 'Education and Work - College Name',
            'education.College.concentration.0.name' => 'Education and Work - College Degree',
            'education.College.year.name' => 'Education and Work - College Year',
            'education.High School.school.name' => 'Education and Work - High School', // user_education_history
            'education.High School.year.name' => 'Education and Work - High School Year', // user_education_history
            'email' => 'Contact - Email', // email
            'website' => 'Contact - Website' // user_website
        );
    }

    public function getPermissionsForFields($fields)
    {
        $perms = array();
        if (!$fields)
            return $perms;

        foreach ($fields as $field)
        {
            if (strpos($field, "current_location") !== false)
                $perms[] = "user_location";
            else if (strpos($field, "hometown_location") !== false)
                $perms[] = "user_hometown";
            else if ($field == "activities" || $field == "birthday" || $field == "interests" || $field == "website")
                $perms[] = "user_" . $field;
            else if ($field == "about_me" || $field == "quotes" || $field == "profile_blurb")
                $perms[] = "user_about_me";
            else if ($field == "religion" || $field == "political")
                $perms[] = "user_religion_politics";
            else if ($field == "relationship_status")
                $perms[] = "user_relationships";
            else if ($field == "music" || $field == "books" || $field == "movies" || $field == "tv" || $field == "games")
                $perms[] = "user_likes";
            else if (strpos($field, "work") !== false)
                $perms[] = "user_work_history";
            else if (strpos($field, "education") !== false)
                $perms[] = "user_education_history";
        }
        return $perms;
    }

    /**
     *  Get all permissions that are required by Facebook for email, status, and/or profile, regardless
     *    of whether they're set to required in JFBConnect
     * @return string Comma separated list of FB permissions that are required
     */
    static private $requiredScope;

    public function getRequiredScope()
    {
        if (self::$requiredScope)
            return self::$requiredScope;

        self::$requiredScope = array();
        self::$requiredScope[] = "email";
        // Query to see if any actions are enabled to automatically post (reads, listen, etc). If so, request the proper permission at login.
        // Otherwise, should be requested at time of action.
        require_once(JPATH_SITE . '/components/com_jfbconnect/models/opengraphaction.php');
        $ogActionModel = new JFBConnectModelOpenGraphAction();
        $ogActions = $ogActionModel->getActions(true);
        if ($ogActions)
        {
            foreach ($ogActions as $action)
            {
                if ($action->params->get('og_auto_type', "none") == "page_load")
                {
                    self::$requiredScope[] = "publish_actions";
                    break;
                }
            }
        }

        JPluginHelper::importPlugin('socialprofiles');
        $app = JFactory::getApplication();
        $args = array('facebook');
        $perms = $app->triggerEvent('socialProfilesGetRequiredScope', $args);
        if ($perms)
        {
            foreach ($perms as $permArray)
                self::$requiredScope = array_merge(self::$requiredScope, $permArray);
        }

        $customPermsSetting = JFBCFactory::config()->getSetting('facebook_perm_custom');
        if ($customPermsSetting != '')
        {
            $customPermsSetting = str_replace("\r\n", ',', $customPermsSetting);
            //Separate into an array to be able to merge and then take out duplicates
            $customPerms = explode(',', $customPermsSetting);
            foreach ($customPerms as $customPerm)
                self::$requiredScope[] = trim($customPerm);
        }

        self::$requiredScope = array_unique(self::$requiredScope);
        self::$requiredScope = implode(",", self::$requiredScope);

        return self::$requiredScope;
    }

    /*
     * Fetch a user's profile based on a profile plugin field-mapping
     * @return JRegistry with profile field values
     */
    public function fetchProfileFromFieldMap($fieldMap, $permissionGranted = true)
    {
        $fields = array();
        if (is_object($fieldMap))
        {
            foreach ($fieldMap as $field)
            {
                $fieldArray = explode('.', $field);
                if (!empty($fieldArray[0]))
                    $fields[] = $fieldArray[0]; // Get the root field to grab from FB
            }
        }
        $fbUserId = JFBCFactory::provider('facebook')->getProviderUserId();

        $fields = array_unique($fields);
        $profile = $this->fetchProfile($fbUserId, $fields);
        $profile->setFieldMap($fieldMap);
        return $profile;

    }

    /* Fetch a user's profile based on the passed in array of fields
    * @return JRegistry with profile field values
    */
    public function fetchProfile($fbUserId, $fields)
    {
        if (in_array('full_name', $fields))
        {
            $fields[] = 'name';
            unset($fields[array_search('full_name', $fields)]);
        }

        $profile = new JFBConnectProfileDataFacebook();
        if (!empty($fields))
        {
            $colFields = implode(",", $fields);
            $fql = "SELECT " . $colFields . " FROM user WHERE uid=" . $fbUserId;
            $params = array(
                'method' => 'fql.query',
                'query' => $fql,
            );
            $data = JFBCFactory::provider('facebook')->rest($params, TRUE);

            $profile->loadObject($data[0]);
        }
        return $profile;
    }

    public function fetchStatus($providerUserId)
    {
        $response = JFBCFactory::provider('facebook')->api('/me/statuses');
        if (!isset($response['data'][0]))
            return;
        $socialStatus = $response['data'][0]['message'];
        return $socialStatus;
    }


    // Created for parity with JLinked/SourceCoast library
    // nullForDefault - If the avatar is the default image for the social network, return null instead
    // Prevents the default avatars from being imported
    function getAvatarUrl($providerUserId, $nullForDefault = false, $params = null)
    {
        $savedAvatar = JFactory::getApplication()->getUserState('com_jfbconnect.facebook.avatar.' . $providerUserId, null);
        if ($savedAvatar)
        {
            if ($savedAvatar == "blank")
                return null;
            else
                return $savedAvatar;
        }

        if (!$params)
            $params = new JRegistry();

        $secure = $params->get('secure', false);
        $width = $params->get('width', 300);
        $height = $params->get('height', 300);

        $avatarUrl = 'graph.facebook.com/' . $providerUserId . '/picture?width=' . $width . "&height=" . $height;
        if ($secure)
            $avatarUrl = 'https://' . $avatarUrl . '&return_ssl_resources=1';
        else
            $avatarUrl = 'http://' . $avatarUrl;

        // To check for blank avatar, we actually have to make a call to Facebook.
        if ($nullForDefault)
        {
            $type = 'pic_big'; // Can also do 'pic_big_with_logo'. Need a setting for that one day (Trac #407)
            $fql = "SELECT " . $type . " FROM user WHERE uid = " . $providerUserId;
            $params = array(
                'method' => 'fql.query',
                'query' => $fql,
            );
            $profileUrl = JFBCFactory::provider('facebook')->rest($params, FALSE);
            $avatarUrl = $profileUrl[0][$type];

            //No avatar ends with .gif
            if ($nullForDefault && SCStringUtilities::endswith($avatarUrl, '.gif'))
                $avatarUrl = null;
        }

        if (empty($avatarUrl))
            JFactory::getApplication()->setUserState('com_jfbconnect.facebook.avatar.' . $providerUserId, 'blank');
        else
            JFactory::getApplication()->setUserState('com_jfbconnect.facebook.avatar.' . $providerUserId, $avatarUrl);


        return $avatarUrl;
    }

    function getCoverPhoto($providerId)
    {
        $response = JFBCFactory::provider('facebook')->api('/' . $providerId . '/?fields=cover');
        if (array_key_exists('cover', $response) && array_key_exists('source', $response['cover']))
        {
            $cover = new JRegistry();
            $url = $response['cover']['source'];
            $cover->set('url', $url);
            $cover->set('offsetY', isset($response['cover']) && isset($response['cover']['offset_y']) ? $response['cover']['offset_y'] : 0);
            $cover->set('offsetX', isset($response['cover']) && isset($response['cover']['offset_x']) ? $response['cover']['offset_x'] : 0);
            return $cover;
        }

        return null;
    }


    function getProfileUrl($fbUserId)
    {
        return 'https://www.facebook.com/profile.php?id=' . $fbUserId;
    }

}

class JFBConnectProfileDataFacebook extends JFBConnectProfileData
{
    var $fieldMap;

    function get($path, $default = null)
    {
        if ($this->exists($path))
            $data = parent::get($path, $default);
        else if ($path == "full_name") // standardized provider value for full name
        $data = parent::get('name', $default);
        else
        {
            // Alternative fields that require extra parsing
            $parts = explode('.', $path);
            if ($parts[0] == 'education')
            {
                $edu = $this->data->education;
                foreach ($edu as $k => $node)
                {
                    if ($node->type == $parts[1])
                    {
                        unset($parts[0]);
                        unset($parts[1]);
                        $newPath = 'education.' . $k . '.' . implode('.', $parts);
                        $data = parent::get($newPath, $default);
                        break;
                    }
                }
            }
            else
                return $default;
        }

        if (!empty($data))
        {
            if (is_array($data))
            { // This is a field with multiple, comma separated values
                // Remove empty values to prevent blah, , blah as output
                unset($data['id']); // Remove id key which is useless to import
                $data = SCStringUtilities::r_implode(', ', $data);
            }
            // add custom field handlers here
            switch ($path)
            {
                case 'website':
                    $websites = explode("\n", $data);
                    if (count($websites) > 0)
                        $data = trim($websites[0]);
                    break;
            }
        }

        return $data;
    }

}