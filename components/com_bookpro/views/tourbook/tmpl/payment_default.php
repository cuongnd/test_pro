
<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
AImporter::helper('currency', 'form');
$document = JFactory::getDocument();
JHtmlBehavior::framework();
JHtml::_('jquery.ui');
JHtml::_('jquery.framework');
JHtml::_('behavior.calendar');
JHtml::_('behavior.formvalidation');
JHtmlBehavior::formvalidation();
AImporter::js('customer');
$config = AFactory::getConfig();

$lang = JFactory::getLanguage();
$local = substr($lang->getTag(), 0, 2);
$document = JFactory::getDocument();
$document->addScript("http://ajax.aspnetcdn.com/ajax/jquery.validate/1.10.0/jquery.validate.min.js");
if ($local != 'en') {
    $document->addScript(JURI::root() . 'components/com_bookpro/assets/js/validatei18n/messages_' . $local . '.js');
}
$document->addStyleSheet(JUri::root() . 'components/com_bookpro/assets/css/jquery-ui.css');
$document->addScript(JUri::root() . 'components/com_bookpro/assets/js/jquery-ui.js');
$document->addScript(JUri::root() . 'components/com_bookpro/assets/js/jquery-ui-timepicker-addon.js');

$document->addStyleSheet(JUri::root() . 'components/com_bookpro/assets/css/view-tourbook.css');
?>

<style type="text/css">
    label.error{
        color: red;
        font-style: italic;
    }
    .form-horizontal .control-label
    {
        width: auto;
        padding-right: 5px;


    }
    .form-horizontal .controls
    {
        margin-left: auto;
    }
    .form-horizontal .controls input
    {
        width: auto;
        padding: 4px 2px;
    }
    .form-horizontal .controls input.bridthday
    {
        width: 105px;
    }
    .form-horizontal.airpost_transfer input
    {
        width: 70px !important;
    }

</style>
<?php $this->currentstep = 4 ?>
<?php echo $this->loadTemplate("currentstep") ?>
<div class="widgetbookpro-loading"></div>
<form name="frontTourForm"  method="post" action='index.php' id="frontTourForm">
    <div class="mainfarm">
        <div class="span8">
            <div class="span12">
            <?php
			 $user=JFactory::getUser();
			 if(!$user->id)
			 {
			 	?>
			 	<div style="display: none" class="wellcome-customer"><h2><?php echo JText::_('COM_BOOKPRO_WELLCOME_YOU_COMEBACK') ?></h2></div>
			 	<div class="row-fluid pp-box customer-login-register" style="padding-top: 10px">
			 		<div class="span6"><?php echo $this->loadTemplate("login") ?></div>
			 		<div class="span6"><?php echo $this->loadTemplate("social") ?></div>
			 	</div>
			 	<?php
			 }
			?>
                <div class="form-horizontal row-fluid">
                    <h3 style="text-transform: uppercase; color: #fff; padding:5px; text-align: left; background: #95A5A5"><?php echo JText::_('COM_BOOKPRO_PASSENGER_INFOMATION') ?></h3>
                    <div><?php echo JText::_('COM_BOOKPRO_PASSENGER_INFOMATION_DESCRIPTION') ?></div>
                    <?php echo $this->loadTemplate("item") ?>
                </div>
                <?php echo $this->loadTemplate("sumary") ?>
                <?php echo $this->loadTemplate("paymentdetail") ?>
            </div>





            <?php echo FormHelper::bookproHiddenField(array('controller' => 'tourbook', 'task' => 'confirmbooking', 'Itemid' => JRequest::getInt('Itemid'))) ?>


        </div>
        <div class="span4 block_right">
            <?php
            $this->setLayout('option');
            ?>
            <div class="checkinandcheckout">
                <?php echo $this->loadTemplate("checkinandcheckout") ?>
            </div>
            <?php echo $this->loadTemplate("listpassenger") ?>
            <div class="roomselected">
                <?php echo $this->loadTemplate("roomselected") ?>
            </div>

            <div class="tripprice pre_trip_acommodaton">
                <?php echo $this->loadTemplate("pretripprice") ?>
            </div>
            <div class="tripprice post_trip_acommodaton">
                <?php echo $this->loadTemplate("posttripprice") ?>
            </div>
            <div class="triptransfer pre_airport_transfer">
                <?php echo $this->loadTemplate("pretriptransferprice") ?>
            </div>
            <div class="triptransfer post_airport_transfer">
                <?php echo $this->loadTemplate("posttriptransferprice") ?>
            </div>
            <div class="additionnaltripprice">
                <?php echo $this->loadTemplate("additionnaltripprice") ?>
            </div>
            <div class="totaltripprice">
                <?php echo $this->loadTemplate("totaltripprice") ?>
            </div>
        </div>

    </div>

</form>
<style type="text/css">
    .form-horizontal .control-label
    {
        width: auto;
        text-align: left;


    }
    .form-horizontal .controls
    {
        margin-left: auto;
    }
    .form-horizontal .controls input
    {
        width: auto;
        padding: 4px 2px;
    }
    .form-horizontal .controls input.bridthday
    {
        width: 105px;
    }
</style>

<script type="text/javascript">
    jQuery(document).ready(function($) {

        function checkAuthType(object)
        {
        	$("input[name*='userType']").prop('checked',false);
        	object.find("input[name*='userType']").prop('checked',true);
           if(object.find("input[name*='userType']:checked").val()=='userTypeNew')
           {
               $("#userTypeReturningDiv").css('display','none');
               $("#projectLoginUserCreate").css('display','block');
               $("#projectLoginUserCreate").find('input').each(function(){
					attr_type=$(this).attr('type');
            	   $(this).rules( "add", {
                    	required: true,
                    	minlength:function(){
                        	if(attr_type=='password')
                            	return 6
                    	}
                    });
                   if(attr_type=='email')
                   {
                       email_address=$(this).val();
                	   $(this).rules( "add", {
	               	   		remote:{
	               	   		url: "index.php",
	               	        type: "POST",
	               	        cache: false,
	               	        dataType: "json",
	               	        data: {
	               	        	option: 'com_bookpro'
                                    , controller: 'customer'
                                    , task: 'ajaxIsExistsEmailInUserSystem'	               	        },
	               	        dataFilter: function(response) {
	               	        	var json = jQuery.parseJSON(response);
	                            if(json.error == "true") {
	                                return "\"" + json.errorMessage + "\"";
	                            } else {
	                                return true;

	                            }
	               	        }
               	   		}
                       });
                   }
                   if($(this).attr('name')=='newusername')
                   {
                	   $(this).rules( "add", {
	               	   		remote:{
	               	   		url: "index.php",
	               	        type: "POST",
	               	        cache: false,
	               	        dataType: "json",
	               	        data: {
	               	        	option: 'com_bookpro'
                                    , controller: 'customer'
                                    , task: 'ajaxIsUsernameExistsInUserSystem'	               	        },
	               	        dataFilter: function(response) {
	               	        	var json = jQuery.parseJSON(response);
	                            if(json.error == "true") {
	                                return "\"" + json.errorMessage + "\"";
	                            } else {
	                                return true;

	                            }
	               	        }
               	   		}
                       });
                   }
               });

               $("#userTypeReturningDiv").find('input').each(function(){
               	$(this).rules( "remove");
               });
           }
           if($("input[name*='userType']:checked").val()=='userTypeReturning')
           {
               $("#userTypeReturningDiv").css('display','block');
               $("#projectLoginUserCreate").css('display','none');
               $("#userTypeReturningDiv").find('input').each(function(){
            	   $(this).rules( "add", {
                   	required: true
                   	});
            	   if($(this).attr('name')=='username')
                   {
                	   $(this).rules( "add", {
	               	   		remote:{
	               	   		url: "index.php",
	               	        type: "POST",
	               	        cache: false,
	               	        dataType: "json",
	               	        data: {
	               	        	option: 'com_bookpro'
                                    , controller: 'customer'
                                    , task: 'ajaxCheckAllowLoginWithUsernameSystem'
                                                  	        },
	               	        dataFilter: function(response) {
	               	        	var json = jQuery.parseJSON(response);
	                            if(json.error == "true") {
	                                return "\"" + json.errorMessage + "\"";
	                            } else {
	                            	ajax_login();
	                            	//$( "#frontTourForm" ).validate().element( "#post-proj-pwd" );
	                            	return true;

	                            }
	               	        }
               	   		}
                       });
                   }
            	   if($(this).attr('name')=='passwd')
                   {
                	   $(this).rules( "add", {
	               	   		remote:{
	               	   		url: "index.php",
	               	        type: "POST",
	               	        cache: false,
	               	        dataType: "json",
	               	        data: {
	               	        	option: 'com_bookpro'
                                    , controller: 'customer'
                                    , task: 'ajax_login'
                                    , username:function(){
                                        return $('#post-proj-username').val();
                                    }
                            },
                            beforeSend: function() {
                                $('.widgetbookpro-loading').css({
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
	               	        dataFilter: function(response) {
	               	         $('.widgetbookpro-loading').css({
                                 display: "none",
	               	         });
               	        	var json = jQuery.parseJSON(response);
                            if(json.error == "true") {
                                return "\"" + json.errorMessage + "\"";
                            } else {
                            	$('.customer-login-register').fadeOut(3000);
								$('.wellcome-customer').fadeIn(3000);
								$('.wellcome-customer').fadeOut(3000);


                            }
	               	        }
               	   		}
                       });
                   }
              });

              $("#projectLoginUserCreate").find('input').each(function(){
              	$(this).rules( "remove");
              });
           }
        }
        function ajax_login()
		{
        	 $.ajax({
                 type: "POST",
                 url: 'index.php',
                 data: (function() {
                     $data = {
                         option: 'com_bookpro'
                                 , controller: 'customer'
                                 , task: 'ajax_login'
                                 ,username:$('#post-proj-username').val()
                                 ,passwd:$('#post-proj-pwd').val()

                     }
                     return $data;
                 })(),
                 beforeSend: function() {
                     $('.widgetbookpro-loading').css({
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
                 success: function(response) {
                     $('.widgetbookpro-loading').css({
                         display: "none"
                     });
                     var json = jQuery.parseJSON(response);
                     if(json.error == "true") {
                         return "\"" + json.errorMessage + "\"";
                     } else {
                     	$('.customer-login-register').fadeOut(3000);
						$('.wellcome-customer').fadeIn(3000);
						$('.wellcome-customer').fadeOut(3000);


                     }
                 }
             });
		}
    	$(document).on('click', '.authTypeBtn', function() {

    		checkAuthType($(this));


       });

        function sethtmlfortag($respone_array)
        {
            $respone_array = $.parseJSON($respone_array);
            $.each($respone_array, function($index, $respone) {

                $($respone.key.toString()).html($respone.contents);
            });
        }

        groupcheckbox($('input[name="payment"]'));
        groupcheckbox($('input[name="cardtype"]'));
        function groupcheckbox($object)
        {
            $object.click(function() {
                if ($object.is(":checked")) {
                    var group = "input:checkbox[name='" + $object.attr("name") + "']";
                    $(group).prop("checked", false);
                    $(this).prop("checked", true);
                } else {
                    $(this).prop("checked", false);
                }
            });
        }
        $(document).on('click', 'input.btn_gone[name="go"]', function() {
            $passenger_form = $(this).closest('.passenger_form');
            $key_sec_person = $passenger_form.find('input[name="key_sec_person"]').val();
            $code_discount = $passenger_form.find('input.input_go[name="discount"]');
            if ($code_discount.val().trim() == '')
            {
                var btns = {};
                btns['yes'] = function() {
                    $(this).dialog("close");
                    $code_discount.focus();
                    return false;
                };
                $("<div><?php echo Jtext::_('COM_BOOKPRO_COUPON_INVALID') ?></div>").dialog({
                    autoOpen: true,
                    title: '<?php echo Jtext::_('COM_BOOKPRO_WARNING') ?>',
                    modal: true,
                    buttons: btns,
                    closeOnEscape: false
                });
                return false;
            }
            $.ajax({
                type: "GET",
                url: 'index.php',
                data: (function() {
                    $data = {
                        option: 'com_bookpro'
                                , controller: 'tourbook'
                                , task: 'ajax_check_coupon'
                                , code: $code_discount.val().trim()
                                , key_sec_person: $key_sec_person
                    }
                    return $data;
                })(),
                beforeSend: function() {
                    $('.widgetbookpro-loading').css({
                        display: "none",
                        position: "fixed",
                        "z-index": 1000,
                        top: 0,
                        left: 0,
                        height: "100%",
                        width: "100%"
                    });
                    // $('.loading').popup();
                },
                success: function($result) {
                    $('.widgetbookpro-loading').css({
                        display: "none"
                    });
                    sethtmlfortag($result);
                    if ($('.total_discount').html() != '')
                    {
                        $passenger_form.find('.total_price_person').addClass('discount');
                    }
                }
            });
        });
        $(document).on('click', 'ul.passengers a.passenger_edit', function() {
            $li_passenger = $(this).closest('li.passenger');
            $indexoflipassenger = $li_passenger.index();
            $('div.passenger_form').each(function($index) {

                if ($indexoflipassenger == $index)
                {
                    $(this).css({
                        display: "block"
                    });
                }
                else
                {
                    $(this).css({
                        display: "none"
                    });
                }
            });

        });
        function stylewidthcontrol($object)
        {
            $maxwidth = 0;
            $object.find('.control-group .control-label').each(function($index) {
                if ($maxwidth < $(this).width())
                    $maxwidth = $(this).width();
            });
            $object.find('.control-group .control-label').css({
                width: $maxwidth + 10
            });
        }
        stylewidthcontrol($('.passenger_form'));

        $('#frontTourForm').validate({// initialize the plugin


        });
        checkAuthType($('.authTypeBtn').first());
        $(document).on('click', '#fb_connect_btn', function() {
            window.open('index.php?option=com_bookpro&view=tourbook&layout=social&tpl=facebook&tmpl=component', "popupWindow", "width=800,height=600,scrollbars=yes");

        });


    });

</script>



