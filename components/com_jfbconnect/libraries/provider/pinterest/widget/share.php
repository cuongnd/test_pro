<?php
/**
 * @package        JFBConnect
 * @copyright (C) 2009-2013 by Source Coast - All rights reserved
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

class JFBConnectProviderPinterestWidgetShare extends JFBConnectProviderPinterestWidget
{
    var $name = "Share";
    var $systemName = "share";
    var $className = "pinterest sc_pinterest";

    public function getTagHtml()
    {
        if($this->fields->exists('image') && $this->getParamValue('image') != "")
        {
            self::$needsJavascript = true;

            $url = $this->getParamValueEx('href', 'url', null, SCSocialUtilities::getStrippedUrl());
            $layout = $this->getParamValue('layout');

            $url = rawurlencode($url);
            $image = rawurlencode($this->getParamValue('image'));
            $desc = rawurlencode($this->getParamValue('desc'));

            $tagButtonText = '<a href="//pinterest.com/pin/create/button/?url=' . $url;
            if ($image)
                $tagButtonText .= '&media=' . $image;
            if ($desc)
                $tagButtonText .= '&description=' . $desc;
            $tagButtonText .= '"';
            if($layout)
            {
                $tagButtonText .= SCEasyTags::getShareButtonLayout('pinterest', $layout, '"');
            }
            else
            {
                $tagButtonText .= $this->getField('data-pin-config', 'pin_count', null, 'none', 'data-pin-config');
            }
            $tagButtonText .= ' data-pin-do="buttonPin" ><img src="//assets.pinterest.com/images/pidgets/pin_it_button.png" alt="Share on Pinterest"/></a>';
        } else
            $tagButtonText = '';
        return $tagButtonText;
    }
}
