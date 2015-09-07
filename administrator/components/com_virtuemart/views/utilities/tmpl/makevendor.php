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
    <?php AdminUIHelper::startAdminArea(); ?>
    <div class="row-fluid" style="text-align: center">
        <input type="button"  class="btn" value="bat dau makevendor" name="makevendor">

    </div>
    <div class="row-fuid">
        <span class="makevendor_loading"></span>
        <div><span>list makevendor </span><span class="set_makevendor"></span></div>
    </div>
    <div class="clearfix"> </div>
    <?php AdminUIHelper::endAdminArea(true); ?>
</form>
<script type="text/javascript">
    jQuery(document).ready(function ($) {
        $(document).on('click','input[name="makevendor"]',function(){
            $(this).attr('disabled','disabled');
            makevendor(0);
        });
        function makevendor()
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
                            task: 'makevendor'
                        }
                        return $data;
                    })(),
                    beforeSend: function() {
                        $('.makevendor_loading').html('dang makevendor');

                    },
                    success: function(respone_array) {
                        $('.set_makevendor').html(respone_array);
                        makevendor();
                    }
                });


            },delay);


        }
    });
</script>


