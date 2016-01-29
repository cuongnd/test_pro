<?php
/**
 * @package         JFBConnect
 * @copyright (c)   2009-2014 by SourceCoast - All Rights Reserved
 * @license         http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @version         Release v6.2.4
 * @build-date      2014/12/15
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

class JFBConnectToolbarButton
{
    var $order;
    var $displayName;
    var $systemName;

    public function html()
    {
        return '';
    }

    public function javascript()
    {
        $js = $this->generateJavascript();
        if ($js)
        {
            $js = 'var ' . $this->systemName . " = {" . $js . "};";
        }
        return $js;
    }

    protected function generateJavascript()
    {
        return "";
    }
}