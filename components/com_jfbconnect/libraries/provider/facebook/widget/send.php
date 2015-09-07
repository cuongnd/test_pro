<?php
/**
 * @package        JFBConnect
 * @copyright (C) 2009-2013 by Source Coast - All rights reserved
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

class JFBConnectProviderFacebookWidgetSend extends JFBConnectProviderFacebookWidget
{
    var $name = "Send";
    var $systemName = "send";
    var $className = "jfbcsend";
    var $examples = array (
        '{JFBCSend}',
        '{JFBCSend href=http://www.sourcecoast.com width=75 height=50 colorscheme=light ref=homepage kid_directed_site=true}'
    );

    protected function getTagHtml()
    {
        $tag = '<div class="fb-send"';
        $tag .= $this->getField('href', 'url', null, SCSocialUtilities::getStrippedUrl(), 'data-href');
        $tag .= $this->getField('colorscheme', null, null, '', 'data-colorscheme');
        $tag .= $this->getField('ref', null, null, '', 'data-ref');
        $tag .= $this->getField('width', null, null, '', 'data-width');
        $tag .= $this->getField('height', null, null, '', 'data-height');
        $tag .= $this->getField('kid_directed_site', null, 'boolean', 'false', 'data-kid-directed-site');
        $tag .= '></div>';
        return $tag;
    }
}
