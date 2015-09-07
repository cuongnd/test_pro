<?php
/**
 * @package 	Bookpro
 * @author 		Nguyen Dinh Cuong
 * @link 		http://ibookingonline.com
 * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: default.php  23-06-2012 23:33:14
 **/
// No direct access to this file
JHtml::_('jquery.framework');
JHtml::_('jquery.ui');
$cart = &JModelLegacy::getInstance('FlightCart', 'bookpro');
$cart->load();


$doc=JFactory::getDocument();

$doc->addScript(JURI::root().'components/com_bookpro/assets/js/ui111n/jquery.ui.slider.js');
$doc->addScript(JURI::root().'components/com_bookpro/assets/js/ui111n/jquery.ui.widget.js');

$doc->addScript(JURI::root().'components/com_bookpro/assets/js/ui111n/jquery.ui.mouse.js');
$doc->addStyleSheet(JURI::root().'components/com_bookpro/assets/css/jquery.datepick.css');
$doc->addStyleSheet(JURI::root().'components/com_bookpro/assets/css/flight.css');
$doc->addStyleSheet(JURI::root().'components/com_bookpro/assets/css/jquery.ui.slider.css');
$doc->addStyleSheet(JURI::root().'components/com_bookpro/assets/css/jquery.ui.datepicker.css');
$doc->addStyleSheet(JURI::root().'components/com_bookpro/assets/css/rcarousel.css');
$doc->addScript(JURI::root().'components/com_bookpro/assets/js/jquery.ui.rcarousel.js');


$today=JFactory::getDate()->getTimestamp();


$depart_url=JURI::base().'index.php?option=com_bookpro&controller=flight&task=ajaxsearch&tmpl=component&format=raw&start='.JFactory::getDate($this->cart->start)->format('Y-m-d',true).'&roundtrip='.$this->cart->roundtrip;
$return_url =JURI::base().'index.php?option=com_bookpro&controller=flight&task=ajaxsearch&layout=return&tmpl=component&format=raw&end='.JFactory::getDate($this->cart->end)->format('Y-m-d',true).'&roundtrip='.$this->cart->roundtrip;



$airport_from=$this->from_to[0]->title." (".$this->from_to[0]->code.")";
$airport_to=$this->from_to[1]->title." (".$this->from_to[1]->code.")";
$action='index.php?Itemid='.JRequest::getVar("Itemid");


?>




	
	
	

	<div id="flight_depart">
		
		<div id="depart_content">
			
		</div>
		<div id="loading" style="display: none;"><?php echo JText::_('COM_BOOKPRO_LOADING') ?></div>
	</div>

	
	
	<?php if ($this->cart->roundtrip=='1') {?>
	

	<div id="flight_return">
		
		<div id="return_content"></div>
		<div id="return_loading" style="display: none;"><?php echo JText::_('COM_BOOKPRO_LOADING') ?></div>
	</div>
	<?php } ?>

	
	

<?php 
	
?>
<script>

function getDepartDay(aurl){
	jQuery.ajax({
	  	url:aurl,
	  	beforeSend: function() {
	  	    jQuery("#loading").show();
	  	  	jQuery("#depart_content").hide();
	  	  },
	  	success:function(data){
	  		
	  		setTimeout(function(){

	  			jQuery("#loading").hide();
		  	  	jQuery("#depart_content").show();
		  		
		  		jQuery('#depart_content').html(data);
	  		}, 1000);
	  		 
		 } 
	 });
	jQuery.ajax({
        type: "GET",
        url: 'index.php',
        data: (function() {
            $data = {
                option: 'com_bookpro',
                controller: 'flight',
                task: 'allairline',
                tmpl:'component',
                format:'raw'
              
            }
            return $data;
        })(),
        beforeSend: function() {
        	 
        },
        success: function($result) {
        	jQuery('#all_airline').html($result);
        }
    });
}

function getReturnDay(aurl){
	jQuery.ajax({
	  	url:aurl,
	  	beforeSend: function() {
	  	    jQuery("#return_loading").show();
	  	  	jQuery("#return_content").hide();
	  	  },
	  	success:function(data){
	  		
	  		setTimeout(function(){

	  			jQuery("#return_loading").hide();
		  	  	jQuery("#return_content").show();
		  		
		  		jQuery('#return_content').html(data);
	  		}, 1000);
	  		 
		 } 
	 });
	jQuery.ajax({
        type: "GET",
        url: 'index.php',
        data: (function() {
            $data = {
                option: 'com_bookpro',
                controller: 'flight',
                task: 'allairline',
                tmpl:'component',
                format:'raw'
              
            }
            return $data;
        })(),
        beforeSend: function() {
        	 
        },
        success: function($result) {
        	jQuery('#all_airline').html($result);
        }
    });
}
jQuery(document).ready(function($) {
	
	
//Tim kiem flight theo khoang gia
	 $( "#slider-price-range" ).slider({
		 range: true,
		 min: <?php echo $cart->min_price; ?>,
		 max: <?php echo $cart->max_price; ?>,
		 values: [ <?php echo $cart->min_price ?>, <?php echo $cart->max_price; ?> ],
		 slide: function( event, ui ) {
			
			 $( "#amount_price" ).val( "$" + ui.values[ 0 ] + " - $" + ui.values[ 1 ] );
		 },
		 change: function (event, ui) {
	           
	            var minRate = ui.values[0];
	            var maxRate = ui.values[1];
	            $.ajax({
	                type: "GET",
	                url: 'index.php',
	                data: (function() {
	                    $data = {
	                        option: 'com_bookpro',
	                        controller: 'flight',
	                        task: 'ajaxsearch',
	                        tmpl:'component',
	                        format:'raw',
	                        min_price:minRate,
	                        max_price:maxRate
	                    }
	                    return $data;
	                })(),
	                beforeSend: function() {
	                	 jQuery("#loading").show();
	         	  	  	jQuery("#depart_content").hide();
	                },
	                success: function($result) {
	                	setTimeout(function(){

	        	  			jQuery("#loading").hide();
	        		  	  	jQuery("#depart_content").show();
	        		  		
	        		  		jQuery('#depart_content').html($result);
	        	  		}, 1000);
	                }
	            });
	            <?php if ($this->cart->roundtrip=='1') {?>
	            $.ajax({
	                type: "GET",
	                url: 'index.php',
	                data: (function() {
	                    $data = {
	                        option: 'com_bookpro',
	                        controller: 'flight',
	                        task: 'ajaxsearch',
	                        layout:'return',
	                        tmpl:'component',
	                        format:'raw',
	                        min_price:minRate,
	                        max_price:maxRate
	                    }
	                    return $data;
	                })(),
	                beforeSend: function() {
	                	 jQuery("#return_loading").show();
	         	  	  	jQuery("#return_content").hide();
	                },
	                success: function($result) {
	                	setTimeout(function(){

	        	  			jQuery("#return_loading").hide();
	        		  	  	jQuery("#return_content").show();
	        		  		
	        		  		jQuery('#return_content').html($result);
	        	  		}, 1000);
	                }
	            });
	            <?php } ?>
	            $.ajax({
	                type: "GET",
	                url: 'index.php',
	                data: (function() {
	                    $data = {
	                        option: 'com_bookpro',
	                        controller: 'flight',
	                        task: 'allairline',
	                        tmpl:'component',
	                        format:'raw'
	                      
	                    }
	                    return $data;
	                })(),
	                beforeSend: function() {
	                	 
	                },
	                success: function($result) {
	                	jQuery('#all_airline').html($result);
	                }
	            });
	        }
		 });
	 	
		 $( "#amount_price" ).val( "$" + $( "#slider-price-range" ).slider( "values", 0 ) +
		 " - $" + $( "#slider-price-range" ).slider( "values", 1 ) );

/*Tiem kiem flight theo thoi gian khoi hanh*/
		 $( "#slider-time-range" ).slider({
			 range: true,
			 min: <?php echo $cart->min_time; ?>,
			 max: <?php echo $cart->max_time; ?>,
			 values: [ <?php echo $cart->min_time ?>, <?php echo $cart->max_time ?> ],
			 slide: function( event, ui ) {
				 $( "#amount_time" ).val( ui.values[ 0 ]+" hrs" + "-" + ui.values[ 1 ]+" hrs" );
			 },
			 change: function (event, ui) {
		           
		            var minTime = ui.values[0];
		            var maxTime = ui.values[1];
		            $.ajax({
		                type: "GET",
		                url: 'index.php',
		                data: (function() {
		                    $data = {
		                        option: 'com_bookpro',
		                        controller: 'flight',
		                        task: 'ajaxsearch',
		                        tmpl:'component',
		                        format:'raw',
		                        min_time:minTime,
		                        max_time:maxTime
		                    }
		                    return $data;
		                })(),
		                beforeSend: function() {
		                	 jQuery("#loading").show();
		         	  	  	jQuery("#depart_content").hide();
		                },
		                success: function($result) {
		                	setTimeout(function(){

		        	  			jQuery("#loading").hide();
		        		  	  	jQuery("#depart_content").show();
		        		  		
		        		  		jQuery('#depart_content').html($result);
		        	  		}, 1000);
		                }
		            });
		            <?php if ($this->cart->roundtrip=='1') {?>
		            $.ajax({
		                type: "GET",
		                url: 'index.php',
		                data: (function() {
		                    $data = {
		                        option: 'com_bookpro',
		                        controller: 'flight',
		                        task: 'ajaxsearch',
		                        layout:'return',
		                        tmpl:'component',
		                        format:'raw',
		                        min_time:minTime,
		                        max_time:maxTime
		                    }
		                    return $data;
		                })(),
		                beforeSend: function() {
		                	 jQuery("#return_loading").show();
		         	  	  	jQuery("#return_content").hide();
		                },
		                success: function($result) {
		                	setTimeout(function(){

		        	  			jQuery("#return_loading").hide();
		        		  	  	jQuery("#return_content").show();
		        		  		
		        		  		jQuery('#return_content').html($result);
		        	  		}, 1000);
		                }
		            });
		            <?php } ?>
		            $.ajax({
		                type: "GET",
		                url: 'index.php',
		                data: (function() {
		                    $data = {
		                        option: 'com_bookpro',
		                        controller: 'flight',
		                        task: 'allairline',
		                        tmpl:'component',
		                        format:'raw'
		                      
		                    }
		                    return $data;
		                })(),
		                beforeSend: function() {
		                	 
		                },
		                success: function($result) {
		                	jQuery('#all_airline').html($result);
		                }
		            });
		            
		        }
			 });
			 $( "#amount_time" ).val( $( "#slider-time-range" ).slider( "values", 0 )+ " hrs" +
			 " -" + $( "#slider-time-range" ).slider( "values", 1 ) +"hrs" );


//Search flight khi chon airline cot ben phai
			 $('input[name="airline[]"]').click(function(){
				 var airlines = new Array();
				 
				 $('input[name="airline[]"]').each(function () {
					if($(this).is(":checked")){
						airlines.push($(this).val());
					}
			  	      
				  });	
				 $.ajax({
		                type: "GET",
		                url: 'index.php',
		                data: (function() {
		                    $data = {
		                        option: 'com_bookpro',
		                        controller: 'flight',
		                        task: 'ajaxsearch',
		                        tmpl:'component',
		                        format:'raw',
		                        airline:airlines
		                      
		                    }
		                    return $data;
		                })(),
		                beforeSend: function() {
		                	 jQuery("#loading").show();
		         	  	  	jQuery("#depart_content").hide();
		                },
		                success: function($result) {
		                	setTimeout(function(){

		        	  			jQuery("#loading").hide();
		        		  	  	jQuery("#depart_content").show();
		        		  		
		        		  		jQuery('#depart_content').html($result);
		        	  		}, 1000);
		                }
		            });
				 <?php if ($this->cart->roundtrip=='1') {?>
				 $.ajax({
		                type: "GET",
		                url: 'index.php',
		                data: (function() {
		                    $data = {
		                        option: 'com_bookpro',
		                        controller: 'flight',
		                        task: 'ajaxsearch',
		                        layout:'return',
		                        tmpl:'component',
		                        format:'raw',
		                        airline:airlines
		                      
		                    }
		                    return $data;
		                })(),
		                beforeSend: function() {
		                	 jQuery("#return_loading").show();
		         	  	  	jQuery("#return_content").hide();
		                },
		                success: function($result) {
		                	setTimeout(function(){

		        	  			jQuery("#return_loading").hide();
		        		  	  	jQuery("#return_content").show();
		        		  		
		        		  		jQuery('#return_content').html($result);
		        	  		}, 1000);
		                }
		            });
				 <?php } ?>
				 $.ajax({
		                type: "GET",
		                url: 'index.php',
		                data: (function() {
		                    $data = {
		                        option: 'com_bookpro',
		                        controller: 'flight',
		                        task: 'allairline',
		                        tmpl:'component',
		                        format:'raw'
		                      
		                    }
		                    return $data;
		                })(),
		                beforeSend: function() {
		                	 
		                },
		                success: function($result) {
		                	jQuery('#all_airline').html($result);
		                }
		            });
			});	
//Add cart for flight
		$.ajax({
	                type: "GET",
	                url: 'index.php',
	                data: (function() {
	                    $data = {
	                        option: 'com_bookpro',
	                        controller: 'flight',
	                        task: 'addcart',
	                        tmpl:'component',
	                        format:'raw'
	                      
	                    }
	                    return $data;
	                })(),
	                beforeSend: function() {
	                	 
	                },
	                success: function($result) {
	                	jQuery('#cart_summary').html($result);
	                }
	            });
				
		

// Ajax All Airline
		$.ajax({
                type: "GET",
                url: 'index.php',
                data: (function() {
                    $data = {
                        option: 'com_bookpro',
                        controller: 'flight',
                        task: 'allairline',
                        tmpl:'component',
                        format:'raw'
                      
                    }
                    return $data;
                })(),
                beforeSend: function() {
                	 
                },
                success: function($result) {
                	jQuery('#all_airline').html($result);
                }
            });
// Update Cart after select rate in flight

		$("input:radio[name=rate_id]").live('click',function(){
			var flight_rate_id = $(this).val();
			
			 $.ajax({
	                type: "GET",
	                url: 'index.php',
	                data: (function() {
	                    $data = {
	                        option: 'com_bookpro',
	                        controller: 'flight',
	                        task: 'addcart',
	                        tmpl:'component',
	                        format:'raw',
	                        rate_id:flight_rate_id,
	                        roundtrip:0
	                      
	                    }
	                    return $data;
	                })(),
	                beforeSend: function() {
	                	 jQuery("#loading_summary").show();
	         	  	  	jQuery("#cart_summary").hide();
	                },
	                success: function($result) {
	                	setTimeout(function(){

	        	  			jQuery("#loading_summary").hide();
	        		  	  	jQuery("#cart_summary").show();
	        		  		
	        		  		jQuery('#cart_summary').html($result);
	        	  		}, 1000);
	                }
	            });
		});		
// Update Cart after select rate in return flight
		$("input:radio[name=return_rate_id]").live('click',function(){
			
			var flight_return_rate_id = $(this).val();
			 $.ajax({
	                type: "GET",
	                url: 'index.php',
	                data: (function() {
	                    $data = {
	                        option: 'com_bookpro',
	                        controller: 'flight',
	                        task: 'addcart',
	                        tmpl:'component',
	                        format:'raw',
	                        return_rate_id:flight_return_rate_id,
	                        roundtrip:1
	                      
	                    }
	                    return $data;
	                })(),
	                beforeSend: function() {
	                	 jQuery("#loading_summary").show();
	         	  	  	jQuery("#cart_summary").hide();
	                },
	                success: function($result) {
	                	setTimeout(function(){

	        	  			jQuery("#loading_summary").hide();
	        		  	  	jQuery("#cart_summary").show();
	        		  		
	        		  		jQuery('#cart_summary').html($result);
	        	  		}, 1000);
	                }
	            });
		});		
		getDepartDay('<?php echo $depart_url; ?>');
		getReturnDay('<?php echo $return_url; ?>');
	
		
	
	
   });
   
</script>
<script type="text/javascript">

function checkPackage(package){
	var form= document.frontForm;
	form.package.value=package;
}
function checkReturnPackage(package){
	var form= document.frontForm;
	form.return_package.value=package;
}


function changeDesfrom(select){
	var form= document.frontForm;
	
	for(i=0; i < form.desto.options.length; i++ ){
		option = form.desto.options[i];
		if(select.value != option.value){
			//option.removeAttribute('disabled');
			option.disabled = false;
		}else{
			option.disabled = true;
			if(form.desto.options[form.desto.selectedIndex].value == select.value){
				form.desto.selectedIndex=0;
			}
			
		}
	}
}


function submitForm(){

	var form= document.frontForm;
	if(jQuery("input:radio[name='rate_id']").is(":checked")==false)
	{
		alert("<?php echo JText::_('COM_BOOKPRO_FLIGHT_SELECT_WARN')?>");
	 		return false; 
	}
	if(jQuery("input:radio[name='return_rate_id']").is("*")){
		if(jQuery("input:radio[name='return_rate_id']").is(":checked")==false)
		{
			alert("<?php echo JText::_('COM_BOOKPRO_FLIGHT_SELECT_WARN')?>");
		 		return false; 
		}
	}
	


	form.task.value ='reserve';
	form.submit();
}

</script>

