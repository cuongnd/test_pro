<?php
/**
 * @package        JFBConnect
 * @copyright (C) 2009-2013 by Source Coast - All rights reserved
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

class JFBConnectProviderFacebookWidgetFacepile extends JFBConnectProviderFacebookWidget
{
    var $name = "Facepile";
    var $systemName = "facepile";
    var $className = "jfbcfriends";
    var $examples = array (
        '{JFBCFriends}',
        '{JFBCFriends href=http://www.sourcecoast.com max_rows=5 width=400 height=100 colorscheme=dark size=small show_count=true action=vote,comment}'
    );

    protected function getTagHtml()
    {
        $tag = '<div class="fb-facepile"';
        $tag .= $this->getField('href', 'url', null, '', 'data-href');
        $tag .= $this->getField('width', null, null, '', 'data-width');
        $tag .= $this->getField('height', null, null, '', 'data-height');
        $tag .= $this->getField('max_rows', null, null, '', 'data-max-rows');
        $tag .= $this->getField('colorscheme', null, null, '', 'data-colorscheme');
        $tag .= $this->getField('size', null, null, '', 'data-size');
        $tag .= $this->getField('show_count', null, 'boolean', 'true', 'data-show-count');
        $tag .= $this->getField('action', null, null, '', 'data-action');
        $tag .= '></div>';
        return $tag;
    }
}
