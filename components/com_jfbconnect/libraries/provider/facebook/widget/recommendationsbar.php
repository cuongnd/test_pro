<?php
/**
 * @package        JFBConnect
 * @copyright (C) 2009-2013 by Source Coast - All rights reserved
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

class JFBConnectProviderFacebookWidgetRecommendationsbar extends JFBConnectProviderFacebookWidget
{
    var $name = "Recommendations Bar";
    var $systemName = "recommendationsbar";
    var $className = "jfbcrecommendationsbar";
    var $examples = array (
        '{JFBCRecommendationsBar}',
        '{JFBCRecommendationsBar href=http://www.sourcecoast.com trigger=onvisible read_time=30 action=like side=right}'
    );

    protected function getTagHtml()
    {
        $trigger = $this->getParamValue('trigger');
        $triggerPercent = $this->getParamValue('trigger_percent');
        if ($trigger == 'percent' && $triggerPercent != '')
            $trigger = $triggerPercent . '%';
        if ($trigger)
            $trigger = ' data-trigger="' . $trigger . '"';

        $tag = '<div class="fb-recommendations-bar"';
        $tag .= $this->getField('href', 'url', null, SCSocialUtilities::getStrippedUrl(), 'data-href');
        $tag .= $trigger;
        $tag .= $this->getField('read_time', null, null, '', 'data-read-time');
        $tag .= $this->getField('action', null, null, '', 'data-action');
        $tag .= $this->getField('side', null, null, '', 'data-side');
        $tag .= $this->getField('site', null, null, '', 'data-site');
        $tag .= $this->getField('ref', null, null, '', 'data-ref');
        $tag .= $this->getField('num_recommendations', null, null, '', 'data-num-recommendations');
        $tag .= $this->getField('max_age', null, null, '', 'data-max-age');
        $tag .= '></div>';
        return $tag;
    }
}
