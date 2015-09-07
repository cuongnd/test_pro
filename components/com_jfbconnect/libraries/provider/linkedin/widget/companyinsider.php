<?php
/**
 * @package        JFBConnect
 * @copyright (C) 2009-2013 by Source Coast - All rights reserved
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

class JFBConnectProviderLinkedinWidgetCompanyinsider extends JFBConnectWidget
{
    var $name = "Company Insider";
    var $systemName = "companyinsider";
    var $className = "jlinkedCompanyInsider";
    var $examples = array (
        '{JLinkedCompanyInsider companyid=1441}',
        '{JLinkedCompanyInsider companyid=365848}'
    );

    protected function getTagHtml()
    {
        $tag = '<script type="IN/CompanyInsider"';
        $tag .= $this->getField('companyid', null, null, '', 'data-id');

/*        $modules = array();

        if($this->getParamValueEx('in_network', null, 'boolean', 'false') == 'true')
            $modules[] = 'innetwork';

        if($this->getParamValueEx('new_hires', null, 'boolean', 'false') == 'true')
            $modules[] = 'newhires';

        if($this->getParamValueEx('promotions_changes', null, 'boolean', 'false') == 'true')
            $modules[] = 'jobchanges';

        if (count($modules) > 0)
            $tag .= ' data-modules="' . implode(',', $modules) . '"';
*/
        $tag .= '></script>';

        return $tag;
    }
}
