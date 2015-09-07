<?php
/**
 * @package        JFBConnect
 * @copyright (C) 2009-2013 by Source Coast - All rights reserved
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

class JFBConnectProviderLinkedinWidgetRecommend extends JFBConnectWidget
{
    var $name = "Recommend";
    var $systemName = "recommend";
    var $className = "jlinkedRecommend";
    var $examples = array(
        '{JLinkedRecommend}',
        '{JLinkedRecommend companyid=365848 productid=201714 counter=top}'
    );

    protected function getTagHtml()
    {
        $tag = '<script type="IN/RecommendProduct"';
        $tag .= $this->getField('companyid', null, null, '', 'data-company');
        $tag .= $this->getField('productid', null, null, '', 'data-product');
        $tag .= $this->getField('counter', null, null, '', 'data-counter');
        $tag .= '></script>';
        return $tag;
    }
}
