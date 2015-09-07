
<?php 
JHtml::_('jquery.framework');
JHtml::_('jquery.ui');
$cart = &JModelLegacy::getInstance('FlightCart', 'bookpro');
$cart->load();
$user = JFactory::getUser();
?>
<div class="flight_confirm">
	
	
	<div class="passenger">
	<?php 
		
		$layout = new JLayoutFile('passenger_form', $basePath = JPATH_ROOT .'/components/com_bookpro/layouts/flight');
		$html = $layout->render($this->cart);
		echo $html;
		 ?>
	</div>

	
	<?php 
	
	
	$layout = new JLayoutFile('customer', $basePath = JPATH_ROOT .'/components/com_bookpro/layouts/flight');
	$html = $layout->render($this->customer);
	echo $html;
	 ?>
	 
	 <?php
            if ($this->plugins) {
                foreach ($this->plugins as $plugin) {
                    ?>
                    <input value="<?php echo $plugin->element; ?>" class="payment_plugin"
                           onclick="getPaymentForm('<?php echo $plugin->element; ?>', 'payment_form_div');"
                           name="payment_plugin" type="radio"
                           <?php echo (!empty($plugin->checked)) ? "checked" : ""; ?> />

                    <?php
                    $params = new JRegistry;
                    $params->loadString($plugin->params);
                    $title = $params->get('display_name', '');
                    if (!empty($title)) {
                        echo $title;
                    } else {
                        echo JText::_($plugin->name);
                    }
                    ?>
                    <br />
                    <?php
                }
            }
            ?>

	<div class="center-button">
		<button type="submit" class="btn btn-primary"><?php echo JText::_('COM_BOOKPRO_CONTINUE')?></button>
		
	</div>
</div>
<script>
jQuery(document).ready(function($) {    
   
jQuery.validator.addMethod( 
      "select", 
      function(value, element) {       
        if (element.value == "0") 
        { 
          return false; 
        } 
        else return true; 
      }
    );     
});
</script>
<script type="text/javascript">
jQuery(document).ready(function($) {
	
	
				// Flight Rate Bô
		$.ajax({
	                type: "GET",
	                url: 'index.php?option=com_bookpro&controller=flight&task=addcartconfirm&tmpl=component&format=raw',
	                data: $.param($('.passenger-form').find(':input'), false),
	                beforeSend: function() {
	                	 
	                },
	                success: function($result) {
	                	jQuery('#summary-booking').html($result);
	                }
	            });
				
		$('.baggage-flight').chosen().change(function(){

			 
			 $.ajax({
	                type: "GET",
	                url: 'index.php?option=com_bookpro&controller=flight&task=addcartconfirm&tmpl=component&format=raw',
	                data: $.param($('.passenger-form').find(':input'), false),
	                beforeSend: function() {
	                	
	                },
	                success: function($result) {
	                	jQuery('#summary-booking').html($result);
	                }
	            });
			});
	
		
		
	
   });
</script>