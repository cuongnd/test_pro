<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_users
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

// Include the component HTML helpers.
JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');
require_once JPATH_ROOT.'/administrator/components/com_website/helpers/website.php';


?>
<div class="control-group">
    <div class="control-label">
        <label title="" class="hasTooltip" for="jform_website_id" id="jform_website_id-lbl" >Backend Template Style</label>							</div>
    <div class="controls">
        <?php echo websiteHelperFrontEnd::getGenericlistWebsite('jform[website_id]',' onchange="changeWebsite()" ',$this->item->website_id); ?>
    </div>
</div>
<div class="control-groups">
    <?php
    echo JHtml::_('access.usergroups', 'jform[groups]', $this->groups, true,$this->item->website_id);
    ?>
</div>
<script type="text/javascript">
    function changeWebsite()
    {
        $=jQuery;
        user_id=$('#jform_id').val();
        website_id=$('#jformwebsite_id').val();
        $.ajax({
            type: "GET",
            url: 'index.php',
            data: (function() {
                dataPost = {
                    option: 'com_users',
                    task: 'user.ajaxGetSelectUserGroup',
                    website_id:website_id,
                    user_id: user_id
                }
                return dataPost;
            })(),
            beforeSend: function() {
                $('.div-loading').css({
                    display: "block",
                    position: "fixed",
                    "z-index": 1000,
                    top: 0,
                    left: 0,
                    height: "100%",
                    width: "100%"

                });
                // $('.loading').popup();
            },
            success: function(result) {
                sethtmlfortag(result);

            }
        });



    }
    function sethtmlfortag(respone_array)
    {
        $=jQuery;
        if(respone_array !== null && typeof respone_array !== 'object')
            respone_array = $.parseJSON(respone_array);
        $.each(respone_array, function(index, respone) {
            if(typeof(respone.type) !== 'undefined')
            {
                $(respone.key.toString()).val(respone.contents);
            }else {
                $(respone.key.toString()).html(respone.contents);
            }
        });
    }


    jQuery(document).ready(function($){
        $(document).on('change','input[name="jform[website_id]"]',function(){
           console.log('hello');
        });
    });
</script>
