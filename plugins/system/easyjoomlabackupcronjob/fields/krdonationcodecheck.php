<?php
/**
 * @Copyright
 * @package     Field - Donation Code Check
 * @author      Viktor Vogel {@link http://www.kubik-rubik.de}
 * @version     Joomla! 3 - 3-6
 * @date        Created on 2013-12-15
 * @link        Project Site {@link http://joomla-extensions.kubik-rubik.de}
 *
 * @license GNU/GPL
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
defined('JPATH_PLATFORM') or die;

/**
 * Form Field class for Kubik-Rubik Joomla! Extensions.
 * Provides a donation code check.
 */
class JFormFieldKRDonationCodeCheck extends JFormField
{
    protected $type = 'krdonationcodecheck';

    protected function getInput()
    {
        $field_set = $this->form->getFieldset();

        if(empty($this->group))
        {
            $donation_code = $field_set['jform_donation_code']->value;
        }
        elseif($this->group == 'params')
        {
            $donation_code = $field_set['jform_params_donation_code']->value;
        }

        $session = JFactory::getSession();
        $field_value_session = $session->get('field_value', null, 'krdonationcodecheck');
        $field_value_head_session = $session->get('field_value_head', null, 'krdonationcodecheck');
        $donation_code_session = $session->get('donation_code', null, 'krdonationcodecheck');

        if($field_value_session == 1 AND ($donation_code == $donation_code_session))
        {
            return;
        }
        elseif(!empty($field_value_session) AND !empty($field_value_head_session) AND ($donation_code == $donation_code_session))
        {
            $this->addHeadData($field_value_head_session);
            return $field_value_session;
        }

        $host = JURI::getInstance()->getHost();

        $field_value = '';
        $donation_code_check = false;

        if($host == 'localhost')
        {
            $field_value = '<div class="'.$this->randomClassName($session).'">'.JTEXT::_('KR_DONATION_CODE_CHECK_DEFAULT_LOCALHOST').'</div>';

            if(!empty($donation_code))
            {
                $field_value .= '<div style="border: 1px solid #F2DB82; border-radius: 2px; padding: 5px; background-color: #F7EECA; font-size: 120%; margin: 4px 0 4px -180px;">'.JTEXT::_('KR_DONATION_CODE_CHECK_ERROR_LOCALHOST').'</div>';
            }
        }
        else
        {
            $donation_code_check = $this->getDonationCodeStatus($host, $donation_code);

            if($donation_code_check != 1)
            {
                $field_value = '<div class="'.$this->randomClassName($session).'">'.JTEXT::sprintf('KR_DONATION_CODE_CHECK_DEFAULT', $host).'</div>';

                if($donation_code_check == -1)
                {
                    $field_value .= '<div style="border: 1px solid #F2DB82; border-radius: 2px; padding: 5px; background-color: #F7EECA; font-size: 120%; margin: 4px 0 4px -180px;">'.JTEXT::_('KR_DONATION_CODE_CHECK_ERROR_SERVER').'</div>';
                }

                if($donation_code_check == -2)
                {
                    $field_value .= '<div style="border: 1px solid #F2DB82; border-radius: 2px; padding: 5px; background-color: #F7EECA; font-size: 120%; margin: 4px 0 4px -180px;">'.JTEXT::_('KR_DONATION_CODE_CHECK_ERROR').'</div>';
                }
            }
        }

        if($donation_code_check == 1)
        {
            $session->set('field_value', 1, 'krdonationcodecheck');
        }
        else
        {
            $session->set('field_value', $field_value, 'krdonationcodecheck');
        }

        $session->set('donation_code', $donation_code, 'krdonationcodecheck');

        return $field_value;
    }

    protected function getLabel()
    {
        return;
    }

    private function getDonationCodeStatus($host, $donation_code)
    {
        $donation_code_check = 0;

        if(!empty($host) AND !empty($donation_code))
        {
            // TODO - Use JHttpFactory::getHttp(); for request
            $url_fopen = ini_get('allow_url_fopen');

            if(function_exists('curl_init') OR !empty($url_fopen))
            {
                $url_check = 'http://joomla-extensions.kubik-rubik.de/scripts/je_kr_donation_code_check/je_kr_check_code.php?key='.rawurlencode($donation_code).'&host='.rawurlencode($host);

                if(function_exists('curl_init'))
                {
                    $ch = curl_init($url_check);
                    curl_setopt($ch, CURLOPT_HEADER, 0);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_NOSIGNAL, 1);
                    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
                    $donation_code_check = curl_exec($ch);
                    $curl_errno = curl_errno($ch);
                    curl_close($ch);

                    if($curl_errno != 0)
                    {
                        $donation_code_check = -1;
                    }
                }
                else
                {
                    $donation_code_check = @file_get_contents($url_check, 'r');
                }
            }
            else
            {
                $donation_code_check = -2;
            }
        }

        if(preg_match('@(error|access denied)@i', $donation_code_check))
        {
            $donation_code_check = -1;
        }

        return $donation_code_check;
    }

    private function randomClassName($session)
    {
        $characters = range('a', 'z');
        $class_name = $characters[mt_rand(0, count($characters) - 1)];
        $class_name_length = mt_rand(6, 12);
        $class_name .= @JUserHelper::genRandomPassword($class_name_length);

        $head_data = '<style type="text/css">div.'.$class_name.'{border: 1px solid #DD87A2; border-radius: 2px; padding: 5px; background-color: #F9CAD9; font-size: 120%; margin: 4px 0 4px -180px;}</style>';

        $this->addHeadData($head_data);
        $session->set('field_value_head', $head_data, 'krdonationcodecheck');

        return $class_name;
    }

    private function addHeadData($data)
    {
        static $data_loaded = false;

        if(empty($data_loaded))
        {
            $document = JFactory::getDocument();
            $document->addCustomTag($data);

            $data_loaded = true;
        }
    }

}
