<?php
/**
 * @package        JFBConnect
 * @copyright (C) 2009-2014 by Source Coast - All rights reserved
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

class JFBConnectProviderPinterestWidget extends JFBConnectWidget
{
    public static $needsJavascript = false;

    public function getHeadData()
    {
        $javascript = '';

        if(self::$needsJavascript)
        {
            $javascript =
            <<<EOT
                            <script type="text/javascript">
(function(d){
  var f = d.getElementsByTagName('SCRIPT')[0], p = d.createElement('SCRIPT');
  p.type = 'text/javascript';
  p.async = true;
  p.src = '//assets.pinterest.com/js/pinit.js';
  f.parentNode.insertBefore(p, f);
}(document));
</script>
EOT;
        }
        return $javascript;
    }
}
