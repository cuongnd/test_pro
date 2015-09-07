<?php
/**
 * @package        JFBConnect
 * @copyright (C) 2009-2013 by Source Coast - All rights reserved
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

class JFBConnectProviderFacebookWidgetEmbeddedposts extends JFBConnectProviderFacebookWidget
{
    var $name = "Embedded Posts";
    var $systemName = "embeddedposts";
    var $className = "jfbcembeddedposts";
    var $examples = array (
        '{JFBCEmbeddedPosts}',
        '{JFBCEmbeddedPosts href=https://www.facebook.com/FacebookDevelopers/posts/10151471074398553}'
    );

    protected function getTagHtml()
    {
        $tag = '';
        $href = $this->getField('href', 'url', null, '', 'data-href');

        if($href)
            $tag = '<div class="fb-post"' . $href . '></div>';

        return $tag;
    }
}
