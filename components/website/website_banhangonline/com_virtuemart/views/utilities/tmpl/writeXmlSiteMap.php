<?php
/**
 *
 * Lists all the categories in the shop
 *
 * @package	VirtueMart
 * @subpackage Category
 * @author RickG, jseros, RolandD, Max Milbers
 * @link http://www.virtuemart.net
 * @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: default.php 6477 2012-09-24 14:33:54Z Milbo $
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

if (!class_exists ('shopFunctionsF'))
    require(JPATH_VM_SITE . DS . 'helpers' . DS . 'shopfunctionsf.php');
?>
<form action="index.php" method="post" name="adminForm" id="adminForm">
    <div class="row-fluid" style="text-align: center">
        <input type="button"  class="btn" value="writeXmlSiteMap" name="writeXmlSiteMap">

    </div>
    <div class="row-fuid">
        <span class="a_loading"></span>
        <div><span>list writeXmlSiteMap </span><span class="respone"></span></div>
    </div>
    <div class="clearfix"> </div>
</form>
<script type="text/javascript">
    jQuery(document).ready(function ($) {
        $('ul.first li').find('small').remove();
        $(document).on('click','input[name="writeXmlSiteMap"]',function(){
            $(this).attr('disabled','disabled');

            writeXmlSiteMap();
        });

        function writeXmlSiteMap()
        {

            var delay=1000;//1 seconds
            setTimeout(function(){
                $.ajax({
                    type: "GET",
                    url: '<?php echo JUri::root() ?>index.php',
                    data: (function() {
                        $data = {
                            option: 'com_virtuemart',
                            controller: 'utilities',
                            task: 'writeXmlSiteMap'
                        }
                        return $data;
                    })(),
                    beforeSend: function() {
                        $('.a_loading').html('dang chuyen file');

                    },
                    success: function(respone_array) {
                        $('.respone').html(respone_array);
                        writeXmlSiteMap();
                    }
                });


            },delay);


        }
    });
</script>


