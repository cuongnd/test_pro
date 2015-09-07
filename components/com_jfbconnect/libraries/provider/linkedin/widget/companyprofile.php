<?php
/**
 * @package        JFBConnect
 * @copyright (C) 2009-2013 by Source Coast - All rights reserved
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

class JFBConnectProviderLinkedinWidgetCompanyprofile extends JFBConnectWidget
{
    var $name = "Company Profile";
    var $systemName = "companyprofile";
    var $className = "jlinkedCompanyProfile";

    var $examples = array (
        '{JLinkedCompanyProfile}',
        '{JLinkedCompanyProfile companyid=365848 display_mode=inline related=false display_width=300}',
        '{JLinkedCompanyProfile companyid=365848 display_mode=icon_name display_behavior=click display_text=SourceCoast related=true}',
        '{JLinkedCompanyProfile companyid=365848 display_mode=icon display_behavior=hover related=1}'
    );

    protected function getTagHtml()
    {
        $tag = '<style type="text/css">.IN-canvas-company iframe{left:20px !important; top:135px !important;}</style>';
        $tag .= '<span class="IN-canvas-company"><script type="IN/CompanyProfile"';
        $tag .= $this->getField('companyid', null, null, '', 'data-id');
        $tag .= $this->getField('related', null, 'boolean', 'true', 'data-related');

        $displayMode = $this->getParamValue('display_mode');
        if ($displayMode == 'inline')
        {
            $tag .= ' data-format="inline"';
        }
        else if ($displayMode == 'icon_name')
        {
            $tag .= $this->getField('display_behavior', null, null, '', 'data-format');
            $tag .= $this->getField('display_text', null, null, '', 'data-text');
        }
        else if ($displayMode == 'icon')
        {
            $tag .= $this->getField('display_behavior', null, null, '', 'data-format');
        }

        $tag .= '></script></span>';

        return $tag;
    }
}
