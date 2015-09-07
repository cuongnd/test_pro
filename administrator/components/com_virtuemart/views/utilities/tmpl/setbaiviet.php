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
        <input type="button"  class="btn" value="bat dau set bai viet" name="setbaiviet">

    </div>
    <div class="row-fuid">
        <span class="setbai_product_loading"></span>
        <div><span>list product da duoc set bai viet </span><span class="setbai_product"></span></div>
    </div>
    <div class="clearfix"> </div>
    <?php AdminUIHelper::endAdminArea(true); ?>
</form>
<script type="text/javascript">
    jQuery(document).ready(function ($) {
        $(document).on('click','input[name="setbaiviet"]',function(){
            $(this).attr('disabled','disabled');
            setbaiviet(0);
        });
        function setbaiviet()
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
                            task: 'setbaiviet'
                        }
                        return $data;
                    })(),
                    beforeSend: function() {
                        $('.setbai_product_loading').html('dang set bai viet');

                    },
                    success: function(respone_array) {
                        $('.setbai_product').html(respone_array);
                        setbaiviet();
                    }
                });


            },delay);


        }
    });
</script>


