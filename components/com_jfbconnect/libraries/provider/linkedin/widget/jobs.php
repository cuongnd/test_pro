<?php
/**
 * @package        JFBConnect
 * @copyright (C) 2009-2013 by Source Coast - All rights reserved
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

class JFBConnectProviderLinkedinWidgetJobs extends JFBConnectWidget
{
    var $name = "Jobs";
    var $systemName = "jobs";
    var $className = "jlinkedJobs";
    var $examples = array (
        '{JLinkedJobs}',
        '{JLinkedJobs companyid=365848}'
    );

    protected function getTagHtml()
    {
        $tag = '<script type="IN/JYMBII"';
        $tag .= $this->getField('companyid', null, null, '', 'data-companyid');
        $tag .= ' data-format="inline"';
        $tag .= '></script>';
        return $tag;
    }
}
