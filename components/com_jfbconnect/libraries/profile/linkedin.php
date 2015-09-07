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
class JFBConnectProfileLinkedin extends JFBConnectProfile
{
    protected function setProviderFields()
    {
        $this->providerFields = array(
            '0' => 'None',
            'first-name' => 'Basic Info - First Name',
            'last-name' => 'Basic Info - Last Name',
            'maiden-name' => 'Basic Info - Maiden Name',
            'formatted-name' => 'Basic Info - Formatted Name',
            'phonetic-first-name' => 'Basic Info - Phonetic First Name',
            'phonetic-last-name' => 'Basic Info - Phonetic Last Name',
            'formatted-phonetic-name' => 'Basic Info - Formatted Phonetic Name',
            'headline' => 'Basic Info - Headline',
            'location.name' => 'Basic Info - Current Location',
            'location.country.code' => 'Basic Info - Current Country Code',
            'industry' => 'Basic Info - Industry',
            'current-share.comment' => 'Basic Info - Current Status/Last Share',
            'num-connections' => 'Basic Info - Number of Connections',
            //        'num-connections-capped' => 'Basic Info - Number of Connections (Capped)',
            'summary' => 'Basic Info - Summary',
            'specialties' => 'Basic Info - Specialties',
            'picture-url' => 'Basic Info - Profile Picture URL',
            'site-standard-profile-request' => 'Basic Info - Authenticated Profile URL',
            'public-profile-url' => 'Basic Info - Public Profile URL',
            //Email Fields - r_emailaddress permission
            'email-address' => 'Email Fields - Primary Email Address',
            //Full Profile Fields - f_fullprofile permission
            'proposal-comments' => 'Full Info - Proposal Approach',
            'associations' => 'Full Info - Associations',
            'honors' => 'Full Info - Honors',
            'interests' => 'Full Info - Interests',
            'num-recommenders' => 'Full Info - Number of Recommendations',
            'date-of-birth' => 'Full Info - Date of Birth',
            /* Collections */
            //        'positions.0' => 'Current Position - 0 - ??',
            'three-current-positions.0' => 'Current Position - 0 - Summary',
            'three-current-positions.1' => 'Current Position - 1 - Summary',
            'three-current-positions.2' => 'Current Position - 2 - Summary',
            'three-past-positions.0' => 'Past Position - 0 - Summary',
            'three-past-positions.1' => 'Past Position - 1 - Summary',
            'three-past-positions.2' => 'Past Position - 2 - Summary',
            'publications.0' => 'Publication - 0 - Summary',
            'publications.1' => 'Publication - 1 - Summary',
            'publications.2' => 'Publication - 2 - Summary',
            'patents.0' => 'Patents - 0 - Summary',
            'patents.1' => 'Patents - 1 - Summary',
            'patents.2' => 'Patents - 2 - Summary',
            'languages.0' => 'Language - 0 - Summary',
            'languages.1' => 'Language - 1 - Summary',
            'languages.2' => 'Language - 2 - Summary',
            'skills.0' => 'Skills - 0 - Summary',
            'skills.1' => 'Skills - 1 - Summary',
            'skills.2' => 'Skills - 2 - Summary',
            'certifications.0' => 'Certifications - 0 - Summary',
            'certifications.1' => 'Certifications - 1 - Summary',
            'certifications.2' => 'Certifications - 2 - Summary',
            'educations.0' => 'Educations - 0 - Summary',
            'educations.1' => 'Educations - 1 - Summary',
            'educations.2' => 'Educations - 2 - Summary',
            'courses.0' => 'Courses - 0 - Summary',
            'courses.1' => 'Courses - 1 - Summary',
            'courses.2' => 'Courses - 2 - Summary',
            'volunteer.0' => 'Volunteer Experience - 0 - Summary',
            'volunteer.1' => 'Volunteer Experience - 1 - Summary',
            'volunteer.2' => 'Volunteer Experience - 2 - Summary',
            'recommendations-received.0' => 'Recommendations - 0 - Summary',
            'recommendations-received.1' => 'Recommendations - 1 - Summary',
            'recommendations-received.2' => 'Recommendations - 2 - Summary',
            //Contact Info - r_contactinfo permission
            'main-address' => 'Contact Info - Main Address',
            'phone-numbers.0' => 'Phone Number - 0 - Summary',
            'phone-numbers.1' => 'Phone Number - 1 - Summary',
            'phone-numbers.2' => 'Phone Number - 2 - Summary',
            'im-accounts.0' => 'IM Accounts - 0 - Summary',
            'im-accounts.1' => 'IM Accounts - 1 - Summary',
            'im-accounts.2' => 'IM Accounts - 2 - Summary',
            'twitter-accounts.0' => 'Twitter Accounts - 0 - Summary',
            'twitter-accounts.1' => 'Twitter Accounts - 1 - Summary',
            'twitter-accounts.2' => 'Twitter Accounts - 2 - Summary',
            'primary-twitter-account' => 'Twitter Accounts - Primary'
        );
    }


    public function getPermissionsForFields($fields)
    {
        $scope = array();
        if (!$fields)
            return $scope;

        foreach ($fields as $field)
        {
            if ($field == 'proposal-comments' ||
                    $field == 'associations' ||
                    $field == 'honors' ||
                    $field == 'interests' ||
                    $field == 'num-recommenders' ||
                    $field == 'date-of-birth' ||
                    strpos($field, 'three-current-positions') !== false ||
                    strpos($field, 'three-past-positions') !== false ||
                    strpos($field, 'publications') !== false ||
                    strpos($field, 'patents') !== false ||
                    strpos($field, 'languages') !== false ||
                    strpos($field, 'skills') !== false ||
                    strpos($field, 'certifications') !== false ||
                    strpos($field, 'educations') !== false ||
                    strpos($field, 'courses') !== false ||
                    strpos($field, 'volunteer') !== false ||
                    strpos($field, 'recommendations-received') !== false
            )
            {
                $scope[] = 'r_fullprofile';
            }
            else if ($field == 'main-address' ||
                    $field == 'primary-twitter-account' ||
                    strpos($field, 'phone-numbers') !== false ||
                    strpos($field, 'im-accounts') !== false ||
                    strpos($field, 'twitter-accounts') !== false
            )
            {
                $scope[] = 'r_contactinfo';
            }
        }
        return $scope;
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
        self::$requiredScope[] = "r_emailaddress";

        JPluginHelper::importPlugin('socialprofiles');
        $app = JFactory::getApplication();
        $args = array('linkedin');
        $perms = $app->triggerEvent('socialProfilesGetRequiredScope', $args);
        if ($perms)
        {
            foreach ($perms as $permArray)
                self::$requiredScope = array_merge(self::$requiredScope, $permArray);
        }

        $customPermsSetting = JFBCFactory::config()->getSetting('linkedin_perm_custom');
        if ($customPermsSetting != '')
        {
            //Separate into an array to be able to merge and then take out duplicates
            $customPerms = explode(',', $customPermsSetting);
            foreach ($customPerms as $customPerm)
                self::$requiredScope[] = trim($customPerm);
        }

        self::$requiredScope = array_unique(self::$requiredScope);
        self::$requiredScope = implode(",", self::$requiredScope);

        return self::$requiredScope;
    }

    /* Fetch a user's profile based on the passed in array of fields
    * @return JRegistry with profile field values
    */
    public function fetchProfile($userId, $fields)
    {
        if (!is_array($fields))
            $fields = array($fields);

        if (in_array('first_name', $fields))
        {
            $fields[] = 'first-name';
            unset($fields[array_search('first_name', $fields)]);
        }
        if (in_array('last_name', $fields))
        {
            $fields[] = 'last-name';
            unset($fields[array_search('last_name', $fields)]);
        }
        if (in_array('full_name', $fields))
        {
            $fields[] = 'first-name';
            $fields[] = 'last-name';
            unset($fields[array_search('full_name', $fields)]);
        }
        if (in_array('email', $fields))
        {
            $fields[] = 'email-address';
            unset($fields[array_search('email', $fields)]);
        }
        if (in_array('middle_name', $fields))
            unset($fields[array_search('middle_name', $fields)]);

        $fields = array_unique($fields);

        $profile = new JFBConnectProfileDataLinkedin();
        if (!empty($fields))
        {
            $url = 'https://api.linkedin.com/v1/people/' . $userId . ':(' . implode(',', $fields) . ')';
            try
            {
                    $data = $this->provider->client->query($url);
                    $data = json_decode($data->body, true);
                    $profile->loadObject($data);
            }
            catch (Exception $e)
            {
                    if (JFBCFactory::config()->get('facebook_display_errors'))
                            JFactory::getApplication()->enqueueMessage($e->getMessage());
            }
        }
        return $profile;
    }

    /* Fetch a user's profile based on the passed in array of fields
    * @return JRegistry with profile field values
    */
    public function fetchStatus($providerId)
    {
        $status = $this->fetchProfile($providerId, 'current-share');
        return $status->get('current-share.comment');
    }


    // nullForDefault - If the avatar is the default image for the social network, return null instead
    // Prevents the default avatars from being imported
    function getAvatarUrl($providerId, $nullForDefault = true, $params = null)
    {
        $data = $this->fetchProfile($providerId, 'picture-urls::(original)');
        $avatarUrl = $data->get('picture-urls.values.0');
        if (!$avatarUrl)
            return null;

        return $avatarUrl;
    }

    function getProfileUrl($memberId)
    {
        return 'http://www.linkedin.com/x/profile/' . $this->provider->appId . '/' . $memberId;
    }

}

class JFBConnectProfileDataLinkedin extends JFBConnectProfileData
{
    /*    private function checkForTimestampError()
        {
            if ($this->rawData['linkedin']['oauth_problem'] == 'timestamp_refused')
            {
                $liTime = str_replace("+-300", "", $this->rawData['linkedin']['oauth_acceptable_timestamps']);
                $offset = time() - $liTime;
                $configModel = JFBCFactory::config();
                $configModel->update('linkedin_oauth_timestamp_offset', $offset);
    //            $jlinkedLibrary->setTimestampOffset($offset);
                if ($configModel->getSetting('linkedin_display_errors', false))
                {
                    $app = JFactory::getApplication();
                    $app->enqueueMessage('JFBConnect Notice: Server time not in sync with LinkedIn. Setting correction offset to: ' . $offset . 's', 'error');
                }
                $this->errorRecoverable = true;
                return true;
            }
            else
                return false;
        }*/

    public function get($path, $default = "")
    {
        $value = $default;

        if ($path == 'full_name')
            return parent::get('firstName') . ' ' . parent::get('lastName');
        else if ($path == 'email')
            return parent::get('emailAddress');
        else if ($path == "middle_name")
            return "";

        if ($path != 'id')
        {
            if ($path == "first_name" || $path == "last_name")
                $path = str_replace('_', '-', $path);
            // Make the path into a JSON key value
            $parts = explode('-', $path);
            $parts = array_map('ucfirst', $parts);
            $path = implode('', $parts);
            $path = lcfirst($path);
        }

        if ($this->exists($path))
            $value = parent::get($path, $default);

        $valueParts = explode('.', $path);
        $element = parent::get($valueParts[0]);
        if (array_key_exists(1, $valueParts))
            $index = intval($valueParts[1]);

        switch ($valueParts[0])
        {
            case 'positions':
                return $this->getPosition($element, $index);
            case 'threeCurrentPositions':
            case 'threePastPositions':
                return $this->getPosition($element, $index);
            case 'publications':
                return $this->getPublication($element, $index);
            case 'patents':
                return $this->getPatent($element, $index);
            case 'languages':
                return $this->getLanguage($element, $index);
            case 'skills':
                return parent::get('skills.values.' . $index . '.skill.name');
            case 'certifications':
                return $this->getCertification($element, $index);
            case 'educations':
                return $this->getEducation($element, $index);
            case 'courses':
                return $this->getCourse($element, $index);
            case 'volunteer':
                return $this->getVolunteer($element, $index);
            case 'recommendationsReceived':
                return $this->getRecommendation($element, $index);
            case 'phoneNumbers':
                return $this->getPhoneNumber($element, $index);
            case 'imAccounts':
                return $this->getIMAccount($element, $index);
            case 'twitterAccounts':
                return parent::get($valueParts[0] . '.values.' . $index . '.providerAccountName');
            case 'primaryTwitterAccount':
                return parent::get($valueParts[0] . '.providerAccountName');
            case 'dateOfBirth':
                return $this->getDOB($value);
            case 'siteStandardProfileRequest':
                return parent::get('siteStandardProfileRequest.url', '');
        }

        return $value;
    }

    private function getDOB($element)
    {
        $newValue = '';
        $year = (string)($element->year);
        $month = (string)($element->month);
        $day = (string)($element->day);

        if ($month != '' && $day != '' && $year != '')
            $newValue = $month . '/' . $day . '/' . $year;

        return $newValue;
    }

    private function getPosition($element, $index)
    {
        if (!property_exists($element->values, $index))
            return null;

        $position = $element->values->$index;
        $dateString = $this->getDateRange($position);

        $newValue = $position->title . ' at ' . $position->company->name . ': ';
        $newValue .= $dateString;

        return $newValue;
    }

    private function getPublication($element, $index)
    {
        $newValue = '';
        if (isset($element->publication[$index]))
        {
            $publication = $element->publication[$index];
            $dateString = $this->getDateString($publication->date, ', ', '.');

            $newValue = $publication->author->name . '. ' . $publication->title . '. ' . $publication->publisher->name;
            $newValue .= $dateString;
        }
        return $newValue;
    }

    private function getPatent($element, $index)
    {
        $newValue = '';
        if (isset($element->patent[$index]))
        {
            $patent = $element->patent[$index];
            $dateString = $this->getDateString($patent->date, ', ', '.');

            $newValue = $patent->inventor->name . '. "' . $patent->title . '." ' . $patent->office->name . ' ' . $patent->number;
            $newValue .= $dateString;
        }
        return $newValue;
    }

    private function getLanguage($element, $index)
    {
        $newValue = '';
        if (isset($element->language[$index]))
        {
            $language = $element->language[$index];
            $newValue = $language->name;
        }
        return $newValue;
    }

    private function getCertification($element, $index)
    {
        $newValue = '';
        if (isset($element->certification[$index]))
        {
            $certification = $element->certification[$index];
            $newValue = $certification->name;
            if ($certification->number)
                $newValue .= ' ' . $certification->number;
        }
        return $newValue;
    }

    private function getEducation($element, $index)
    {
        $newValue = '';
        if (isset($element->values->$index))
        {
            $education = $element->values->$index;
            $dateString = $this->getDateRange($education);

            $schoolNameString = 'schoolName';
            $fieldOfStudyString = 'fieldOfStudy';

            $newValue = $education->$schoolNameString . ': ' .
                    $education->$fieldOfStudyString . ', ' .
                    $dateString;
        }
        return $newValue;
    }

    private function getCourse($element, $index)
    {
        $newValue = '';
        if (isset($element->course[$index]))
        {
            $course = $element->course[$index];
            $newValue = $course->number . ": " . $course->name;
        }
        return $newValue;
    }

    private function getVolunteer($element, $index)
    {
        $newValue = '';
        $volunteerString = 'volunteerExperience';
        if (isset($element->$volunteerString[$index]))
        {
            $volunteer = $element->$volunteerString[$index];
            $newValue = $volunteer->role . " at " .
                    $volunteer->organization->name . ', ' .
                    $volunteer->cause->name;
        }
        return $newValue;
    }

    private function getRecommendation($element, $index)
    {
        $newValue = '';
        if (isset($element->recommendation[$index]))
        {
            $recommendation = $element->recommendation[$index];
            $recommendationTypeString = 'recommendationType';
            $newValue = $recommendation->recommender . ' - ' . $recommendation->$recommendationTypeString;
        }
        return $newValue;
    }

    private function getPhoneNumber($element, $index)
    {
        $newValue = '';
        $phoneNumberString = 'phoneNumber';
        $phoneTypeString = 'phoneType';

        if (isset($element->values->$index->$phoneNumberString))
        {
            $newValue = $element->values->$index->$phoneNumberString . ' ' .
                    $element->values->$index->$phoneTypeString;
        }
        return $newValue;
    }

    private function getIMAccount($element, $index)
    {
        $newValue = '';
        $imAccountType = 'imAccountType';
        $imAccountName = 'imAccountName';

        if (isset($element->values->$index->$imAccountName))
        {
            $newValue = $element->values->$index->$imAccountName . ' ' .
                    $element->values->$index->$imAccountType;
        }

        return $newValue;
    }

    private function getDateRange($element)
    {
        $dateRange = "";
        $startDateString = 'startDate';
        $endDateString = 'endDate';
        $start = '';
        $end = '';
        if (property_exists($element, $startDateString))
            $start = $this->getDateString($element->$startDateString, null, null);
        if (property_exists($element, $endDateString))
            $end = $this->getDateString($element->$endDateString, null, null);

        if ($start)
            $dateRange = $start . ' to ';

        if ($end)
            $dateRange .= $end;
        else
            $dateRange .= 'Present';
        return $dateRange;
    }

    private function getDateString($date, $prefix, $suffix)
    {
        $dateValue = '';
        if (is_object($date))
        {
            if (property_exists($date, 'month'))
                $dateValue = $date->month . '-' . $date->year;
            else if (property_exists($date, 'year'))
                $dateValue = $date->year;

            if ($dateValue != '')
            {
                if ($prefix)
                    $dateValue = $prefix . $dateValue;
                if ($suffix)
                    $dateValue = $dateValue . $suffix;
            }
        }
        return $dateValue;
    }

}