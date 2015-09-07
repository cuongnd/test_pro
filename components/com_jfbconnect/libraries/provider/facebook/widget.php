<?php
/**
 * @package        JFBConnect
 * @copyright (C) 2009-2013 by Source Coast - All rights reserved
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

class JFBConnectProviderFacebookWidget extends JFBConnectWidget
{
    public function render()
    {
        $class[] = "sourcecoast facebook";
        $class[] = $this->systemName;

        //Facebook javascript is added automatically
        $tag = $this->provider->getStylesheet();
        $tag .= $this->getTagHtml();

        if($tag)
        {
            $this->provider->widgetRendered = true;
            if($this->className)
                $class[] = $this->className;

            $classString = implode(' ', $class);
            $tag = '<div class="'.$classString.'">' . $tag . '</div>';
        }

        return $tag;
    }
}
