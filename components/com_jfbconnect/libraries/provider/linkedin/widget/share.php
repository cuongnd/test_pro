<?php
/**
 * @package        JFBConnect
 * @copyright (C) 2009-2013 by Source Coast - All rights reserved
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

class JFBConnectProviderLinkedinWidgetShare extends JFBConnectWidget
{
    var $name = "Share";
    var $systemName = "share";
    var $className = "jlinkedShare";
    var $examples = array(
        '{JLinkedShare}',
        '{JLinkedShare counter=top}',
        '{JLinkedShare href=http://www.sourcecoast.com/jlinked/ counter=right showzero=0}'
    );

    protected function getTagHtml()
    {
        $tag = '<script type="IN/Share"';
        $tag .= $this->getField('href', 'url', null, SCSocialUtilities::getStrippedUrl(), 'data-url');
        $tag .= $this->getField('showzero', null, 'boolean', 'false', 'data-showzero');
        if($this->fields->exists('layout'))
            $tag .= SCEasyTags::getShareButtonLayout('linkedin', $this->fields->get('layout'), '"');
        else
            $tag .= $this->getField('counter', null, null, '', 'data-counter');
        $tag .= '></script>';
        return $tag;
    }
}
