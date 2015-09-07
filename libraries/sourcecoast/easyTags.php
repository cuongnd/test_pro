<?php
/**
 * @package SourceCoast Extensions (JFBConnect, JLinked)
 * @copyright (C) 2009-2013 by Source Coast - All rights reserved
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die(__FILE__);

jimport('sourcecoast.utilities');
jimport('sourcecoast.articleContent');

class SCEasyTags
{
    /*
     * Determines if the Easy-Tag can be rendered. If it can, then remove the render key
     */
    static function cannotRenderEasyTag(&$easyTag, $renderKey)
    {
        $key = 'key=' . $renderKey;

        $renderKeyCheck = strtolower($easyTag);
        $params = SCEasyTags::_splitIntoTagParameters($renderKeyCheck);

        //Add extra space to allow for option with key in the name for blahkey=
        $hasKey = in_array($key, $params);
        $canRender = (($renderKey == '' && (strpos(' ' . $renderKeyCheck, ' key=') === false) || $hasKey) ||
                ($renderKey != '' && $hasKey));

        if ($canRender && $renderKey != '')
        {
            $easyTag = str_replace($key . ' ', '', $easyTag); //Key with blank space
            $easyTag = str_replace($key, '', $easyTag);
            $easyTag = SCStringUtilities::trimNBSP($easyTag);
        }

        return (!$canRender);
    }

    static function _splitIntoTagParameters($paramList)
    {
        $paramList = SCStringUtilities::replaceNBSPWithSpace($paramList);
        $params = explode(' ', $paramList);

        $count = count($params);
        for ($i = 0; $i < $count; $i++)
        {
            $params[$i] = str_replace('"', '', $params[$i]);
            if (strpos($params[$i], '=') === false && $i > 0)
            {
                $previousIndex = SCEasyTags::_findPreviousParameter($params, $i - 1);
                //Combine this with previous entry and space
                $combinedParamValue = $params[$previousIndex] . ' ' . $params[$i];
                $params[$previousIndex] = $combinedParamValue;
                unset($params[$i]);
            }
        }
        return $params;
    }

    static function _findPreviousParameter($params, $i)
    {
        for ($index = $i; $index >= 0; $index--)
        {
            if (isset($params[$index]))
                return $index;
        }
        return 0;
    }

    static function extendJoomlaUserForms($htmlTag, $selView = 'login', $isConnected = false, $userFormPosition = '1')
    {
        $option = JRequest::getCmd('option');
        $view = JRequest::getCmd('view');
        $user = JFactory::getUser();

        if ($option == 'com_users' && $view == $selView)
        {
            if (($view == 'login' && $user->guest) ||
                ($view == 'registration' && $user->guest) ||
                ($view == 'profile' && $isConnected)
            )
            {
                $document = JFactory::getDocument();
                $output = $document->getBuffer('component');

                if($userFormPosition == SC_VIEW_TOP || $userFormPosition == SC_VIEW_BOTH)
                    $output = $htmlTag . $output;
                if($userFormPosition == SC_VIEW_BOTTOM || $userFormPosition == SC_VIEW_BOTH)
                    $output = $output . $htmlTag;

                $document->setBuffer($output, 'component');
            }
        }
    }

    /*
     * Given JFBCLike layout of box_count, button_count or standard, returns
     * the parameters to set the same layout for the other share buttons
     */
    public static function getShareButtonLayout($provider, $layout, $addQuotes='')
    {
        $layoutParams = '';
        if($layout == 'box_count')
        {
            if($provider == 'facebook')
                $layoutParams = SCEasyTags::quoteParam($addQuotes, 'layout', 'box_count');
            else if($provider == 'google')
            {
                $layoutParams = SCEasyTags::quoteParam($addQuotes, 'annotation','bubble');
                $layoutParams .= SCEasyTags::quoteParam($addQuotes, 'size','tall');
            }
            else if($provider == 'twitter')
                $layoutParams = SCEasyTags::quoteParam($addQuotes, 'data-count', 'vertical');
            else if($provider == 'linkedin')
                $layoutParams = SCEasyTags::quoteParam($addQuotes, 'data-counter', 'top');
            else if($provider == 'pinterest')
                $layoutParams = SCEasyTags::quoteParam($addQuotes, 'data-pin-config','above');

        }
        else if($layout == 'button_count')
        {
            if($provider == 'facebook')
                $layoutParams = SCEasyTags::quoteParam($addQuotes, 'layout', 'button_count');
            else if($provider == 'google')
            {
                $layoutParams = SCEasyTags::quoteParam($addQuotes, 'annotation','bubble');
                $layoutParams .= SCEasyTags::quoteParam($addQuotes, 'size','medium');
            }
            else if($provider == 'twitter')
                $layoutParams = SCEasyTags::quoteParam($addQuotes, 'data-count', 'horizontal');
            else if($provider == 'linkedin')
                $layoutParams = SCEasyTags::quoteParam($addQuotes, 'data-counter', 'right');
            else if($provider == 'pinterest')
                $layoutParams = SCEasyTags::quoteParam($addQuotes, 'data-pin-config','beside');
        }
        else //if($layout == 'standard' || $layout == 'button')
        {
            if($provider == 'facebook')
            {
                if($layout == 'standard')
                {
                    $layoutParams = SCEasyTags::quoteParam($addQuotes, 'layout', 'standard');
                    $layoutParams .= SCEasyTags::quoteParam($addQuotes, 'width', '50');
                    $layoutParams .= SCEasyTags::quoteParam($addQuotes, 'show_faces', 'false');
                }
                else if($layout == 'button')
                    $layoutParams = SCEasyTags::quoteParam($addQuotes, 'layout', 'button');
            }
            else if($provider == 'google')
            {
                $layoutParams = SCEasyTags::quoteParam($addQuotes, 'annotation','none');
                $layoutParams .= SCEasyTags::quoteParam($addQuotes, 'size','standard');
            }
            else if($provider == 'twitter')
                $layoutParams = SCEasyTags::quoteParam($addQuotes, 'data-count', 'none');
            else if($provider == 'linkedin')
                $layoutParams = SCEasyTags::quoteParam($addQuotes, 'data-counter', 'no_count');
            else if($provider == 'pinterest')
                $layoutParams = SCEasyTags::quoteParam($addQuotes, 'data-pin-config','none');
        }

        return $layoutParams;
    }

    private static function quoteParam($quote, $option, $value)
    {
        return ' '.$option.'='.$quote.$value.$quote;
    }
}