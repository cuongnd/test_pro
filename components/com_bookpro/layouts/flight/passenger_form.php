<?php
JHtml::_('formbehavior.chosen', 'select.select');
JHtml::_('jquery.framework');
JHtml::_('jquery.ui');
$lang=JFactory::getLanguage();
$local=substr($lang->getTag(),0,2);
$doc=JFactory::getDocument();
$doc->addScript(JURI::root().'components/com_bookpro/assets/js/jquery.selectboxes.js');
$doc->addScript(JURI::root().'components/com_bookpro/assets/js/jquery.dateSelectBoxes.js');
$config = AFactory::getConfig();
AImporter::helper('flight');
$cart = &JModelLegacy::getInstance('FlightCart', 'bookpro');
$cart->load();
$passengers = FlightHelper::getPassengerForm($cart->adult, $cart->children, $cart->infant);
$date = new JDate();
?>

<h2 class="pass-title">
	<span><?php echo JText::_('COM_BOOKPRO_PASSENGER_INFO')?> </span>
</h2>
<div class="form-horizontal">
<div class="passenger-form">
<?php 
$i = 0;
foreach ($passengers as $passenger){

	?>	
  <div class="passenger-row">
  	<div class="passenger-box">
  		<div class="passenger-title">
  			
  			<?php echo $passenger->title; ?>
  		</div>
  		<div class="row-fluid">
  			<div class="span6">
  				<div class="row-fluid">
  					<div class="span9 offset3">
  						<div class="row-fluid">
  							<div class="span3">
  								<?php if ($config->psGender){?>
							 	<?php echo JHtml::_('select.genericlist',FlightHelper::getGender(), 'psform[gender][]','required="true" class="select gender input-small validate-select"','value','text',null,'pGender'.$i) ?>
								<?php } ?>
  							</div>
  							<div class="span9">
  								<?php if ($config->psFirstname){?>
								<input type="text" name="psform[firstname][]" required class="inputbox span12" placeholder="<?php echo JText::_('COM_BOOKPRO_FLIGHT_PASSENGER_FIRSTNAME')?>" />
								<?php } ?>
  							</div>
  						</div>
  						
						
  					</div>
  				</div>
  			</div>
  			<div class="span6">
  				<div class="row-fluid">
  					<div class="span6">
  						<input type="text" name="psform[middlename][]" required class="inputbox span12" placeholder="<?php echo JText::_('COM_BOOKPRO_FLIGHT_PASSENGER_MIDDLENAME')?>" />
  					</div>
  					<div class="span6">
  						<input type="text" name="psform[lastname][]" required class="inputbox span12" placeholder="<?php echo JText::_('COM_BOOKPRO_FLIGHT_PASSENGER_LASTNAME')?>" />
  					</div>
  				</div>
  				
  				
  			</div>
  		</div>
  		<div class="row-fluid">
  			<div class="span6">
  				<div class="row-fluid">
  					<div class="span9 offset3">
  						<label><?php echo JText::_('COM_BOOKPRO_PASSENGER_BIRTHDAY') ?></label>
		  				<div class="row-fluid">
		  					<div class="span3">
		  						<div class="row-fluid">
		  							<?php echo FlightHelper::getBirthMonth('pBirthMonth[]','required="true" class="select span12"','pBirthMonth'.$i); ?>	
		  						</div>
		  						
		  					</div>
		  					<div class="span9">
		  						<div class="row-fluid">
		  							<div class="span6">
		  							<?php echo FlightHelper::getBirthDay('pBirthDay[]','required="true" class="select flight-select"','pBirthDay'.$i); ?>
		  							</div>
		  							<div class="span6">
		  							<?php echo FlightHelper::getBirthYear('pBirthYear[]','required="true" class="select flight-select"','pBirthYear'.$i); ?>	
		  							</div>
		  							<input type="hidden" name="psform[birthday][]" value="" id="birthday<?php echo $i ?>" />
		  						</div>
		  						
		  					</div>
		  					
		  					
		  				</div>
  					</div>
  				</div>
  			</div>
  			<div class="span6">
  				<label>&nbsp;</label>
  				<div class="row-fluid">
  					<div class="span6" align="right"><?php echo JText::_('COM_BOOKPRO_FLIGHT_PASSENGER_BAGGAGE') ?></div>
  					<div class="span6">
  						<?php //echo JHtmlSelect::integerlist(0, 5, 1, 'pBag[]','class="select span12"'); 
							echo FlightHelper::getBaggageSelectBox($passenger->bagName,$passenger->bagId);
							
						?>
						
  					</div>
  				</div>
  				
  			</div>
  		</div>
  	</div>
  
   
    
  </div>
  <script type="text/javascript">
		jQuery().ready(function ($) {
			 
			$().dateSelectBoxes({
				monthElement: $('#pBirthMonth<?php echo $i ?>'),
				dayElement: $('#pBirthDay<?php echo $i; ?>'),
				yearElement: $('#pBirthYear<?php echo $i; ?>'),
				birthElement:$('#birthday<?php echo $i; ?>'),
				generateOptions: true,
				keepLabels: true,
				monthFormat:'short',
				yearLabel : '<?php echo JText::_('YY') ?>',
				monthLabel : '<?php echo JText::_('MM'); ?>',
				dayLabel : '<?php echo JText::_('DD') ?>'
					
			});

				
			
			
			
		});
		</script>
  <?php 
	$i++;
	} ?>
  
</div>
</div>

		
