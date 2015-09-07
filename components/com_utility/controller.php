<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_users
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Base controller class for Users.
 *
 * @package     Joomla.Site
 * @subpackage  com_users
 * @since       1.5
 */
class UtilityController extends JControllerLegacy
{
    function renderfile()
    {
        require_once JPATH_ROOT.'/'.'administrator/components/com_utility/helpers/utility.php';
        UtilityHelper::renderFile();
    }


    function test123()
    {
        require_once JPATH_ROOT.'/libraries/jsmin-php-master/lib/JSMin.php';
        $js=" $.support.transition = (function () {

      var transitionEnd = (function () {

        var el = document.createElement('bootstrap')
          , transEndEventNames = {
               'WebkitTransition' : 'webkitTransitionEnd'
            ,  'MozTransition'    : 'transitionend'
            ,  'OTransition'      : 'oTransitionEnd otransitionend'
            ,  'transition'       : 'transitionend'
            }
          , name

        for (name in transEndEventNames){
          if (el.style[name] !== undefined) {
            return transEndEventNames[name]
          }
        }

      }());

      return transitionEnd && {
        end: transitionEnd
      }

    })()
";
        echo JSMin::minify($js);
        die;
    }
    function _compress( $data ) {
        $supportsGzip = strpos( $_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip' ) !== false;


        if ( $supportsGzip ) {
            $content = gzencode( trim( preg_replace( '/\s+/', ' ', $data ) ), 9);
        } else {
            $content = $data;
        }
        $offset = 60 * 60;
        $expire = "expires: " . gmdate("D, d M Y H:i:s", time() + $offset) . " GMT";

        header('Content-Encoding: gzip');
        header("content-type: image/jpeg; charset: UTF-8");
        header("cache-control: must-revalidate");
        header( $expire );
        header( 'Content-Length: ' . strlen( $content ) );
        header('Vary: Accept-Encoding');

        echo $content;


    }

}
