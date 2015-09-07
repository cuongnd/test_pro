<?php
/**
 * @package        JFBConnect
 * @copyright (C) 2009-2013 by Source Coast - All rights reserved
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

class JFBConnectProviderLinkedinWidgetMember extends JFBConnectWidget
{
    var $name = "Member Profile";
    var $systemName = "member";
    var $className = "jlinkedMember";
    var $examples = array(
        '{JLinkedMember}',
        '{JLinkedMember href=http://www.sourcecoast.com/alexandreae/ display_mode=inline related=false width=300}',
        '{JLinkedMember href=http://www.sourcecoast.com/alexandreae/ display_mode=icon_name display_behavior=click display_text=Alex Andreae related=true}',
        '{JLinkedMember href=http://www.sourcecoast.com/alexandreae/ display_mode=icon display_behavior=hover related=1}',
    );

    protected function getTagHtml()
    {
        $tag = '<style type="text/css">.IN-canvas-member iframe{left:20px !important; top:135px !important;}</style>';
        $tag .= '<span class="IN-canvas-member"><script type="IN/MemberProfile"';
        $tag .= $this->getField('href', 'url', null, '', 'data-id');
        $tag .= $this->getField('related', null, 'boolean', 'true', 'data-related');

        $displayMode = $this->getParamValue('display_mode');
        if ($displayMode == 'inline')
        {
            $tag .= ' data-format="inline"';
            $tag .= $this->getField('width', null, null, '', 'data-width');
        }
        else if ($displayMode == 'icon_name')
        {
            $tag .= $this->getField('display_behavior', null, null, '', 'data-format');
            $tag .= $this->getField('display_text', null, null, '', 'data-text');
        } else if ($displayMode == 'icon')
        {
            $tag .= $this->getField('display_behavior', null, null, '', 'data-format');
        }
        $tag .= '></script></span>';

        return $tag;
    }
}
