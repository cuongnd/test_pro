<?php
/**
 * @package        JFBConnect
 * @copyright (C) 2009-2013 by Source Coast - All rights reserved
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

class JFBConnectProviderLinkedinWidgetFollowcompany extends JFBConnectWidget
{
    var $name = "Follow Company";
    var $systemName = "followcompany";
    var $className = "jlinkedFollowCompany";
    var $examples = array (
        '{JLinkedFollowCompany companyid=365848}',
        '{JLinkedFollowCompany companyid=365848 counter=right}'
    );

    protected function getTagHtml()
    {
        $tag = '<script type="IN/FollowCompany"';
        $tag .= $this->getField('companyid', null, null, '', 'data-id');
        $tag .= $this->getField('counter', null, null, 'none', 'data-counter');
        $tag .= '></script>';
        return $tag;
    }
}
