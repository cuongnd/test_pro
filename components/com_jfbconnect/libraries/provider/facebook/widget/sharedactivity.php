<?php
/**
 * @package        JFBConnect
 * @copyright (C) 2009-2013 by Source Coast - All rights reserved
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

class JFBConnectProviderFacebookWidgetSharedactivity extends JFBConnectProviderFacebookWidget
{
    var $name = "Shared Activity";
    var $systemName = "sharedactivity";
    var $className = "jfbcsharedactivity";
    var $examples = array (
        '{JFBCSharedActivity}',
        '{JFBCSharedActivity width=300 height=300 font=Arial}'
    );

    protected function getTagHtml()
    {
        $tag = '<div class="fb-shared-activity"';
        $tag .= $this->getField('width', null, null, '', 'data-width');
        $tag .= $this->getField('height', null, null, '', 'data-height');
        $tag .= $this->getField('font', null, null, '', 'data-font');
        $tag .= '></div>';
        return $tag;
    }
}
