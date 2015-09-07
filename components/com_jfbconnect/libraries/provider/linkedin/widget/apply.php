<?php
/**
 * @package        JFBConnect
 * @copyright (C) 2009-2013 by Source Coast - All rights reserved
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

class JFBConnectProviderLinkedinWidgetApply extends JFBConnectWidget
{
    var $name = "Apply";
    var $systemName = "apply";
    var $className = "jlinkedApply";
    var $examples = array (
        '{JLinkedApply companyid=365848 email=jlinkeddemo@sourcecoast.com jobid=12345 jobtitle=Software Developer joblocation=Telecommute logo=http://www.sourcecoast.com/templates/sourcecoast/images/logo.png themecolor=#5573b7 phone=hidden coverletter=optional question=Do you know Joomla? question=Do you know PHP? href=http://www.sourcecoast.com/rest/jobPostingXml urlformat=xml meta=source:1,site:2}'
    );

    protected function getTagHtml()
    {
        $tag = '<script type="IN/Apply"';
        $tag .= $this->getField('companyid', null, null, '', 'data-companyid');
        $tag .= $this->getField('companyname', null, null, '', 'data-companyname');
        $tag .= $this->getField('email', null, null, '', 'data-email');

        $url = $this->getField('href', 'url', null, null, 'data-url');
        if($url)
        {
            $tag .= $url;
            $tag .= $this->getField('urlformat', null, null, '', 'data-urlFormat');
        }

        $tag .= $this->getField('jobtitle', null, null, '', 'data-jobtitle');
        $tag .= $this->getField('joblocation', null, null, '', 'data-joblocation');
        $tag .= $this->getField('logo', null, null, '', 'data-logo');
        $tag .= $this->getField('themecolor', null, null, '', 'data-themecolor');
        $tag .= $this->getField('phone', null, null, '', 'data-phone');
        $tag .= $this->getField('coverletter', null, null, '', 'data-coverLetter');
        $tag .= $this->getField('jobid', null, null, '', 'data-jobid');
        $tag .= $this->getField('meta', null, null, '', 'data-meta');
        $tag .= $this->getField('showtext', null, 'boolean', 'false', 'data-showText');
        $tag .= $this->getField('size', null, null, '', 'data-size');
        $tag .= $this->getQuestions();
        $tag .= '></script>';
        return $tag;
    }

    private function getQuestions()
    {
        $questionText = '';
        $question1 = $this->getParamValue("question1");
        $question2 = $this->getParamValue("question2");

        $questions = array();
        if($question1)
            $questions[] = '{"question":"' . $question1 . '"}';
        if($question2)
            $questions[] = '{"question":"' . $question2 . '"}';

        if (count($questions) > 0)
            $questionText = " data-questions='[" . implode(',', $questions) . "]'";

        return $questionText;
    }

}
