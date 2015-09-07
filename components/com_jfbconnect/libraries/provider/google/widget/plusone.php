<?php
/**
 * @package        JFBConnect
 * @copyright (C) 2009-2013 by Source Coast - All rights reserved
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

class JFBConnectProviderGoogleWidgetPlusone extends JFBConnectWidget
{
    var $name = "Plus One";
    var $systemName = "plusone";
    var $className = "sc_gplusone";
    var $examples = array (
        '{SCGooglePlusOne}',
        '{SCGooglePlusOne href=http://www.sourcecoast.com annotation=inline size=standard width=475 align=left expandTo=top,right recommendations=true}'
    );

    protected function getTagHtml()
    {
        $tag = '<g:plusone';
        if($this->fields->exists('layout'))
        {
            $tag .= SCEasyTags::getShareButtonLayout('google', $this->fields->get('layout'), '"');
        }
        else
        {
            $tag .= $this->getField('size', null, null, '', 'data-size');
            $tag .= $this->getField('annotation', null, null, '', 'data-annotation');

        }
        $tag .= $this->getField('href', 'url', null, SCSocialUtilities::getStrippedUrl(), 'data-href');
        $tag .= $this->getField('width', null, null, '', 'data-width');
        $tag .= $this->getField('align', null, null, '', 'data-align');
        $tag .= $this->getField('expandTo', null, null, '', 'expandTo');
        $tag .= $this->getField('recommendations', null, 'boolean', 'true', 'data-recommendations');
        $tag .= '></g:plusone>';

        return $tag;
    }
}
