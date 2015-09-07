<?php
/**
 * @package 	Bookpro
 * @author 		Nguyen Dinh Cuong
 * @link 		http://ibookingonline.com
 * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: view.html.php  2-04-2014 6:16:16
 **/
// No direct access
defined ( '_JEXEC' ) or die ( 'Restricted access' );
JHtmlBehavior::framework ();
JHtml::_ ( 'jquery.ui' );
JHtml::_ ( 'jquery.framework' );
JHtml::_ ( 'behavior.calendar' );
JHtml::_ ( 'behavior.formvalidation' );
JHtml::_ ( 'bootstrap.framework' );

$document = JFactory::getDocument ();
$document->addScript ( "http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js" );
if ($local != 'en') {
	$document->addScript ( JURI::root () . 'components/com_bookpro/assets/js/validatei18n/messages_' . $local . '.js' );
}
$document->addScript ( JUri::root () . 'components/com_bookpro/assets/js/view-customtrip.js' );
$document->addScript ( JURI::root () . 'components/com_bookpro/assets/js/jquery.ui.datepicker.js' );
$document->addStyleSheet ( JUri::root () . 'components/com_bookpro/assets/css/jquery-ui.css' );


$config = AFactory::getConfig ();
$input = JFactory::getApplication ()->input;
$group_id = $input->get ( 'group_id' );


$return = 'index.php?option=com_bookpro&view=customtrip';
$url = 'index.php?option=com_bookpro&view=login';
$url .= '&return='.urlencode(base64_encode($return));
?>
<div class="span7">
	<div class='well'>

		<form action="" method="post" id="formCustomtrip"
			name="formCustomtrip" onsubmit="" class="form-validate"
			enctype="multipart/form-data">


	
<?php echo $this->loadTemplate('header'); ?>
<?php echo $this->loadTemplate('country'); ?>
<?php echo $this->loadTemplate('infomation'); ?>


<!--send hidden value -->
			<input type="hidden" name="option" value="com_bookpro"> <input
				type="hidden" name="controller" value="customtrip"> <input
				type="hidden" name="task" value="addcustomtrip"> <input
				type="hidden" name="return"
				value="<?php echo $input->get('return')?>" /> <input type="hidden"
				name="Itemid" value="<?php echo JRequest::getVar(Itemid);?>"
				id="Itemid" />
			<div style="float: right;">
				<input type="submit" value="Submit" /> <input type="reset"
					value="Reset" />
			</div>
			<br>
  <?php echo JHtml::_('form.token'); ?>
</form>
<!-- Modal  -->


</div>
</div>
<div id="my-modal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">The email address is already in use!</h4>
            </div>
            <div class="modal-body">
               Do you want login to submit customtrip or enter another email.
            </div>
        </div>
        <div class="modal-footer">
    <a href="#"  class="btn" data-dismiss="modal" id="closeid" >Close</a>
    <a href="<?php
     $return = 'index.php?option=com_bookpro&view=customtrip';
     echo JUri::root().$url?>" 
     class="btn btn-primary">Login</a>
  </div>
    </div> 
</div>
<!-- javascrip -->

<script type="text/javascript">
jQuery.noConflict();
jQuery(document).ready(function($) {
	$( "#closeid" ).click(function() {
      //
		 jQuery("#email").val('');
		});
    $("#traveldate" ).datepicker({
       dateFormat:"dd-mm-yy",
       changeMonth: true,
       changeYear: true,
       showButtonPanel: false,
       minDate: new Date(),
  });
});
</script>

<script type="text/javascript">


    window.addEvent('domready', function(){
    	  document.formvalidator.setHandler('cemail', function (value) {
              return ($('email').value == value);
          });
        document.formvalidator.setHandler('email', function (value) {
           
            regex = /^[a-zA-Z0-9.!#$%&‚Äô*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/;
            if(regex.test(value))
            {

                jQuery.ajax({
                    type : "GET",
                    url:'index.php',
                    data:{
                        option:"com_bookpro",
                        controller:"customer",
                        task:"checkemail",
                        tmpl:"component",
                        email:value
                    },
                    success : function(result) {
                        if(result>0)
                        {
                        	jQuery('#my-modal').modal('show');
                            jQuery("#email").addClass('required');
                            jQuery("#email").addClass('invalid');
                            jQuery("#email").attr('aria-invalid','true');
                            jQuery('label[for="email"]').attr('aria-invalid','true  ');
                            jQuery('label[for="email"]').removeClass('invalid');
                           // jQuery("#statusEMAIL").html('<span class="invalid"><?php echo JText::_( 'BOOKPRO_CUSTOMER_EMAIL_INVALID' ) ?></span>');
                        }
                        else
                        {
                           // jQuery("#statusEMAIL").html('<span class="invalid"><?php echo JText::_( 'BOOKPRO_CUSTOMER_EMAIL_VALID' ) ?></span>');
                        }
                    }
                });
                return true;
            }
            else
            {
                return false;
            }
        });  
      
    });
</script> 

<?php
$document = JFactory::getDocument ();
$style = '.header-customtrip{
 		text-align:center;
	    }
 		.fieldcustom2{
 		font-weight:bold;
 		font-size:10pt;
	   }
 		.dest{
 		color: #000000;
 		}
 		.title{
 		 padding-top:5px; 
 		 padding-left:2px;
 		 text-align:left;
 		 font:Arial; 
 		 font-weight:bold;
 		 font-size:9pt; 
 		 text-transform:uppercase;
 		 color:#ff6e00;width:100px
 		}
 		.text-info{
 		 padding-top:5px;
 		 text-align:center;
 		 font:Arial;
 		 font-weight:bold;
 		 font-size:10pt;
 		 color:#003366";
 		 background-color:#f6f3f3
 		}
 		.textitle1 {
 		font-weight:bold;
 		}
 		.control-label {
 		font-weight:bold;
 		}
 		.buildexpert{ 
 		 padding-left:10;
 		 font-weight:bold;
 		 font-size: 14pt;
 		 color: #ff6e00;
 		 font-family: arial;line-height:17px;
 		 text-align:center; text-transform:uppercase}
 		.sticksev{
 		padding-top:10px; text-align:center; font:Arial; font-weight:bold; font-size:10pt;color:#406f9f
        }
 		.note1{
 		 padding-top:2px; padding-bottom:5px;text-align:center; font:Arial; font-weight:bold; font-size:9pt; color:#406f9f
		}
 		
        
 		';
$document->addStyleDeclaration ( $style );
?>	