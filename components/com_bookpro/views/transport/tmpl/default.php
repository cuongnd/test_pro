<?php /**
 * @package 	Bookpro
 * @author 		Nguyen Dinh Cuong
 * @link 		http://ibookingonline.com
 * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: default.php 30 2012-07-09 15:23:13Z quannv $
 **/
defined('_JEXEC') or die('Restricted access');
AImporter::helper('bookpro', 'date', 'currency', 'form');
JHtml::_('jquery.framework');
JHtml::_('jquery.ui');
AImporter::css('transport','jquery-ui','jquery.ui.datepicker');

AImporter::js('jquery.ui.datepicker');
$config = AFactory::getConfig();
$service = JRequest::getInt('service', 3);
?>
<script type="text/javascript">
	changeTrip();
	jQuery(document).ready(function($) {

		
		$("input#start").datepicker({
			minDate : +2,
			maxDate : "+2M +10D",
			showOn : "button",
			buttonImage : "components/com_bookpro/assets/images/calendar.gif",
			buttonImageOnly : true
		});
		$("#start").datepicker("option", "dateFormat", "dd-mm-yy");

		$("input#rstart").datepicker({
			minDate : +2,
			maxDate : "+2M +10D",
			showOn : "button",
			buttonImage : "components/com_bookpro/assets/images/calendar.gif",
			buttonImageOnly : true
		});
		$("#rstart").datepicker("option", "dateFormat", "dd-mm-yy");
	});

	jQuery(document).ready(function($) {

		function getprice(){
			$.ajax({
				type : "GET",
				url : "index.php?option=com_bookpro&controller=transport&task=getRoutePrice&format=raw",
				data : {
					to: $('select#to').val(),
					from: $('select#from').val(),
					pax: $('select[name=adult]').val(),
					type: $('input[name=type]:checked').val()
				},
				success : function(result) {
					$("span#price").html(result);
				}
			});
		}
		function getrprice(){
			$.ajax({
				type : "GET",
				url : "index.php?option=com_bookpro&controller=transport&task=getRoutePrice&format=raw",
				data : {
					to : $('select#rto').val(),
					from : $('select#rfrom').val(),
					pax : $('select[name=radult]').val(),
					type: $('input[name=rtype]:checked').val()
				},
				success : function(result) {
					$("span#rprice").html(result);
				}
			});
		}
		
		$("#from").change(function() {
			$.ajax({
				type : "GET",
				url : "index.php?option=com_bookpro&controller=transport&task=findDestination&format=raw",
				data : "from=" + $(this).val(),
				success : function(result) {
					$("#to").html(result);
					setSelected();

				}
			});

		});

		$("#rfrom").change(function() {
			$.ajax({
				type : "GET",
				url : "index.php?option=com_bookpro&controller=transport&task=findDestination&format=raw",
				data : "from=" + $(this).val(),
				success : function(result) {
					$("#rto").html(result);
				}
			});

		});

		$("select#to").change(function() {
			getprice();
		});
		$("input[name=type]").change(function() {
			getprice();
		});
		$("input[name=rtype]").change(function() {
			getrprice();
		});

		$("select#adult").change(function() {
			if ($('select#to').val()) {
				getprice();
			}
		});

		$("select#rto").change(function() {
			getrprice();
		});

		$("select#radult").change(function() {
			if ($('select#rto').val()) {
				getrprice();
			}
		});

	});

	function setSelected() {

		jQuery(document).ready(function($) {
			var type = $('input:radio[name=roundtrip]:checked').val();

			if (type == 1 || type == 3) {
				$('select#to option').each(function() {
					if ($(this).val() == '79' || $(this).val() == '80') {
						$(this).remove();
					}
				});
			}

			if (type == 4) {

				var from = jQuery('select#from').val();
				if (from == 80)
					$('select#to option[value="79"]').attr("selected", true);
				if (from == 79)
					$('select#to option[value="80"]').attr("selected", true);

				$('select#to option:not(:selected)').each(function() {
					$(this).remove();
				});
				
			}
		});

	}


	function changeTrip() {
		jQuery(document).ready(function($) {
			var type = $('input:radio[name=roundtrip]:checked').val();
			$("#private_div").show();
			if (type == 1) {
				$("#oneway").show();
				
				$("#oneway").removeClass('span6').addClass('span12');
				$("#return").hide();
			

			} else if ($('input:radio[name=roundtrip]:checked').val() == 2) {
				$("div#oneway").hide();
				$("div#return").show();
				$("div#return").removeClass('span6').addClass('span12');
			} else if ($('input:radio[name=roundtrip]:checked').val() == 3) {
				$("#oneway").show();
				$("#return").show();
				$("div#return").addClass('span6');
				$("div#oneway").addClass('span6');
			}

			$.ajax({
				type : "GET",
				url : "index.php?option=com_bookpro&controller=transport&task=findDestination&format=raw",
				data : "from=" + $('#from').val(),
				success : function(result) {
					$("#to").html(result);
					setSelected();

				}
			});

			$.ajax({
				type : "GET",
				url : "index.php?option=com_bookpro&controller=transport&task=findDestination&format=raw",
				data : "from=" + $('#rfrom').val(),
				success : function(result) {
					$("#rto").html(result);
				}
			});

		});

	}

	function changedroptype() {
		jQuery(document).ready(function($) {
			if ($('input:radio[name=droptype]:checked').val() == 1) {
				$("#private").hide();
				$("#listed").show();
			} else {
				$("#private").show();
				$("#listed").hide();
			}
		});
	}

	function checkInput() {

		var type = jQuery('input:radio[name=roundtrip]:checked').val();
		if (type == 1 || type == 4 || type == 3) {
			if (!jQuery('select#from').val()) {
				alert("Please select Airport");
				//$("select#from").focus();
				return false;
			}
			if (!jQuery("input#flight_number").val()) {
				alert("Please enter flight number");
				jQuery("input#flight_number").focus();
				return false;
			}

			if (type != 4) {
				if (!jQuery("input#private_address").val()) {
					alert("Please enter drop-off address");
					jQuery("input#private_address").focus();
					return false;
				}
			}
			if (!jQuery('select#to').val()) {
				alert("Please select Suburb");
				//$("select#to").focus();
				return false;
			}

		}
		if (type == 2 || type == 3) {
			if (!jQuery("input#rprivate_address").val()) {
				alert("Please enter pickup address");
				jQuery("input#rprivate_address").focus();
				return false;
			}
			if (!jQuery('select#rfrom').val()) {
				alert("Please select Suburb");
				//$("select#from").focus();
				return false;
			}
			if (!jQuery('select#rto').val()) {
				alert("Please select Airport");
				//$("select#to").focus();
				return false;
			}
			if (!jQuery("input#rflight_number").val()) {
				alert("Please enter flight number");
				jQuery("input#rflight_number").focus();
				return false;
			}

		}
		return true;

	}

</script>
<form name="pickupForm" id="pickupForm" method="post" action="index.php"
	onSubmit="return checkInput()">


	<div class="span12">
	<fieldset>
	<div class="control-group">
		<div class="form-inline">
			<?php $options[] = JHtml::_('select.option', 1, JText::_('COM_BOOKPRO_TRANSPORT_FROM_AIRPORT') );
			$options[] = JHtml::_('select.option', 2, JText::_('COM_BOOKPRO_TRANSPORT_TO_AIRPORT'));
			$options[] = JHtml::_('select.option', 3, JText::_('COM_BOOKPRO_TRANSPORT_RETURN'));
			echo JHTML::_('select.radiolist', $options, 'roundtrip', 'onchange="changeTrip()"', 'value', 'text', $service, 0);
			?>	
		</div>
		
	</div>	
  </fieldset>
 
  </div>	
	 <br/>
	<div class="row-fluid">
		
		<div class="span12 form-horizontal" id="oneway" style="border: 1px solid #e3e3e3;" >	
			
				<div class="control-group">
					<label class="control-label"><?php echo JText::_('COM_BOOKPRO_TRAVELER'); ?>
					</label>
					<div class="controls">
						<?php echo JHtmlSelect::integerlist(1,100, 1,'adult','class="input-mini"')?><span class="help-inline" id="price"></span>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label"><?php echo JText::_('COM_BOOKPRO_TRANSPORT_TYPE'); ?>
					</label>
						<div class="form-inline">
							<?php echo JHtmlSelect::booleanlist('type','', 1,JText::_('COM_BOOKPRO_PRIVATE') ,JText::_('COM_BOOKPRO_SHARED'))?>
						</div>
				</div>
				
				<div class="control-group">
				<div class="control-label"><strong>
				<?php echo JText::_('COM_BOOKPRO_TRANSPORT_PICKUP_LOCATION'); ?></strong>
				</div>
				</div>
				
				<div class="control-group">
					<label class="control-label" for="from"><?php echo JText::_('COM_BOOKPRO_TRANSPORT_AIRPORT'); ?>
					</label>
					<div class="controls">
						<?php 
						$param=array('d.air'=>1,'d.state'=>1);
						echo $this->createPickup('from',$param,JText::_('COM_BOOKPRO_SELECT_AIRPORT'))?>
					</div>
				</div>
				
				<div class="control-group">
					<label class="control-label" for="flight_number"><?php echo JText::_('COM_BOOKPRO_TRANSPORT_FLIGHT_NUMBER'); ?>
					</label>
					<div class="controls">
						<input class="input-small" type="text" name="flight_number" id="flight_number" placeholder="<?php echo JText::_('COM_BOOKPRO_TRANSPORT_FLIGHT_NUMBER'); ?>">
					</div>
				</div>
				
				<div class="control-group">
					<label class="control-label" for="from"><?php echo JText::_('COM_BOOKPRO_TRANSPORT_PICKUP_DATETIME'); ?>
					</label>
					<div class="controls">
						<input type="text" class="input-small inline" autocomplete="off" name="start" id="start"	value="<?php echo $start  ?>" size="13" maxlength="10"/>
						
						<?php echo $this->createTimeSelectBox('depart_time'); ?>
					</div>
				</div>
				
				<div class="control-group">
				<div class="control-label"><strong>
				<?php echo JText::_('COM_BOOKPRO_TRANSPORT_DROP_LOCATION'); ?></strong>
				</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="from"><?php echo JText::_('COM_BOOKPRO_TRANSPORT_PRIVATE_ADDRESS'); ?>
					</label>
					<div class="controls">
						<input type="text" name="private_address" id="private_address" class="span12">
					</div>
				</div>
				
				<div class="control-group">
					<label class="control-label" for="from"><?php echo JText::_('COM_BOOKPRO_TRANSPORT_DROP_SUBURB'); ?>
					</label>
					<div class="controls">
						<?php echo $this->createDropLocation('to','12',JText::_('COM_BOOKPRO_TRANSPORT_DROP_SUBURB'))?>
					</div>
				</div>
			</div>
			
		<div class="span6 form-horizontal" id="return" style="border: 1px solid #e3e3e3;">
				
									
					<div class="control-group">
						<label class="control-label"><?php echo JText::_('COM_BOOKPRO_TRAVELER'); ?>
						</label>
						<div class="controls">
							<?php echo JHtmlSelect::integerlist(1,100, 1,'radult','class="input-mini"')?><span class="help-inline" id="rprice"></span>
						</div>
					</div>
					
					<div class="control-group">
					<label class="control-label"><?php echo JText::_('COM_BOOKPRO_TRANSPORT_TYPE'); ?>
					</label>
						<div class="form-inline">
							<?php echo JHtmlSelect::booleanlist('rtype','', 1,JText::_('COM_BOOKPRO_PRIVATE') ,JText::_('COM_BOOKPRO_SHARED'))?>
						</div>
				</div>
				<div class="control-group">
			<div class="control-label"><strong>
				<?php echo JText::_('COM_BOOKPRO_TRANSPORT_PICKUP_LOCATION'); ?>
						</strong></div>
					</div>
					<div class="control-group">
						<label class="control-label" for="from"><?php echo JText::_('COM_BOOKPRO_TRANSPORT_PRIVATE_ADDRESS'); ?>
						</label>
						<div class="controls">
							<input type="text" name="rprivate_address" id="rprivate_address" class="span12">
						</div>
					</div>
					
					<div class="control-group">
						<label class="control-label" for="from"><?php echo JText::_('COM_BOOKPRO_TRANSPORT_SUBURB'); ?>
						</label>
						<div class="controls">
							<?php 
								$param=array('d.state'=>1,'d.province'=>1);
								echo $this->createPickup('rfrom',$param,JText::_('COM_BOOKPRO_SELECT_SUBURB'))?>
						</div>
					</div>
					<div class="control-group">
					<div class="control-label"><strong>
					<?php echo JText::_('COM_BOOKPRO_TRANSPORT_DROP_LOCATION'); ?>
					</strong></div>
					</div>
					
					<div class="control-group">
						<label class="control-label" for="from"><?php echo JText::_('COM_BOOKPRO_TRANSPORT_AIRPORT'); ?>
						</label>
						<div class="controls">
							<?php echo $this->createDropLocation('rto','12',JText::_('COM_BOOKPRO_SELECT_AIRPORT'))?>
						</div>
					</div>
					
					<div class="control-group">
						<label class="control-label" for="from"><?php echo JText::_('COM_BOOKPRO_TRANSPORT_FLIGHT_NUMBER'); ?>
						</label>
						<div class="controls">
							<input type="text" name="rflight_number" id="rflight_number" class="input-small">
						</div>
					</div>
					
					<div class="control-group">
						<label class="control-label" for="from"><?php echo JText::_('COM_BOOKPRO_TRANSPORT_PICKUP_DATETIME'); ?>
						</label>
						<div class="controls">
							<input type="text" class="input-small" autocomplete="off" name="rstart" id="rstart"	value="<?php echo $start  ?>" size="13" maxlength="10" />
						
						<?php //echo JHtml::calendar(JFactory::getDate()->format('d-m-Y'), 'rstart', 'rstart_id',$config->dateNormal,'style="width:100px;"') ?>
							<?php echo $this -> createTimeSelectBox('rdepart_time'); ?>
						</div>
					</div>
				</div>
			</div>

		
		<div class="form-actions">
		<input type="submit" value="<?php echo JText::_('COM_BOOKPRO_BOOK_NOW') ?>" class="btn btn-primary">
		</div>
	
	<input type="hidden" name="controller" value="transport" /> 
	<input	type="hidden" name="task" value="book" /> 
    <input type="hidden" name="option" value="com_bookpro" />
	<input type="hidden" name="Itemid" value="<?php echo JRequest::getVar('Itemid')?>">
	<?php echo JHtml::_('form.token')?>

</form>


