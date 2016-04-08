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
        <input type="button"  class="btn" value="Get image from template monter" name="getimagefromtemplatemonter">

    </div>
    <div class="row-fuid">
        <div><span>downloaded completed media id </span><span class="media_id"></span></div>
        <img class="path_image" src="">
    </div>
	<div class="clearfix"> </div>
	<?php AdminUIHelper::endAdminArea(true); ?>
</form>
<script type="text/javascript">
    jQuery(document).ready(function ($) {
        $(document).on('click','input[name="getimagefromtemplatemonter"]',function(){
            $(this).attr('disabled','disabled');
            getimagefromtemplatemonter(0);
        });
        function getimagefromtemplatemonter()
        {
            var delay=1000;//1 seconds
            setTimeout(function(){
                $.ajax({
                    type: "GET",
                    url: '<?php echo JUri::root() ?>index.php',
                    data: (function() {
                        $data = {
                            option: 'com_virtuemart',
                            controller: 'downloadimagetemplatemonter',
                            task: 'getImageFromTemplateMonter'
                        }
                        return $data;
                    })(),
                    beforeSend: function() {
                        $('.media_id').html('downloading');
                    },
                    success: function(respone_array) {
                        respone_array = $.parseJSON(respone_array);
                        virtuemart_media_id=respone_array.virtuemart_media_id;
                        if(virtuemart_media_id!=0)
                        {
                            $('.media_id').html(virtuemart_media_id.toString());
                            $('.path_image').attr('src',respone_array.file_url);
                            getimagefromtemplatemonter();
                        }
                        else
                        {
                            $('.media_id').html('download image from template monter completed');
                            $(this).removeAttr('disabled');



                        }
                    }
                });


            },delay);


        }
    });
</script>


