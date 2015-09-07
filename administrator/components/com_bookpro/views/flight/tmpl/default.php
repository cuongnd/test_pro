<?php
/**
 * @package 	Bookpro
 * @author 		Nguyen Dinh Cuong
 * @link 		http://ibookingonline.com
 * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: default.php 81 2012-08-11 01:16:36Z quannv $
 **/


defined('_JEXEC') or die('Restricted access');

JHtml::_('behavior.calendar');
BookProHelper::setSubmenu(1);
JToolBarHelper::save();
JToolBarHelper::apply();
JToolBarHelper::cancel();
	
?>
<div style="float: left;width: 80%; ">
<form action="index.php" method="post" name="adminForm" id="adminForm">
	<div class="col width-100">
		<fieldset class="adminform">
    		<legend><?php echo JText::_('COM_BOOKPRO_FLIGHT_EDIT'); ?></legend>
    		<table class="admintable">
    			
    			
    			<tr>
    				<td class="key"><label for="From" class="compulsory"><?php echo JText::_('COM_BOOKPRO_FLIGHT_FROM'); ?>: </label></td>
    				<td><?php echo $this->airportfrom; ?></td>
    			</tr>
    			<tr>
    				<td class="key"><label for="To" class="compulsory"><?php echo JText::_('COM_BOOKPRO_FLIGHT_TO'); ?>: </label></td>
    				<td><?php echo $this->airportto; ?></td>
    			</tr>
    			
    			<tr>
    				<td class="key"><label for="To" class="compulsory"><?php echo JText::_('COM_BOOKPRO_FLIGHT_AIRLINE'); ?>: </label></td>
    				<td><?php echo $this->airlines; ?></td>
    			</tr>
    			
    			<tr>
    				<td class="key"><label for="From" ><?php echo JText::_('COM_BOOKPRO_FLIGHT_ECONOMY_ADULT_PRICE'); ?> </label></td>
    				<td><input class="text_area" type="text" name="eco_price" id="eco_price" size="10" maxlength="255" value="<?php echo $this->flight->eco_price; ?>" /></td>
    			</tr>
				<tr>
    				<td class="key"><label for="eco_seat" ><?php echo JText::_('Economy Seat'); ?>: </label></td>
    				<td><input class="text_area" type="text" name="eco_seat" id="eco_seat" size="10" maxlength="255" value="<?php echo $this->flight->eco_seat; ?>" /></td>
    			</tr>
    			
    			<tr>
    				<td class="key"><label for="From" ><?php echo JText::_('COM_BOOKPRO_FLIGHT_BUSINESS_ADULT_PRICE'); ?>: </label></td>
    				<td><input class="text_area" type="text" name="bus_price" id="bus_price" size="10" maxlength="255" value="<?php echo $this->flight->bus_price; ?>" /></td>
    			</tr>
    			<tr>
    				<td class="key"><label for="bus_seat" ><?php echo JText::_('Business Seat'); ?>: </label></td>
    				<td><input class="text_area" type="text" name="bus_seat" id="bus_seat" size="10" maxlength="255" value="<?php echo $this->flight->bus_seat; ?>" /></td>
    			</tr>
    			<!--
                <tr>
    				<td class="key"><label for="roundtrip_eco_price" ><?php echo JText::_('COM_BOOKPRO_FLIGHT_ECONOMY_ROUNDTRIP_PRICE'); ?> </label></td>
    				<td><input class="text_area" type="text" name="roundtrip_eco_price" id="roundtrip_eco_price" size="10" maxlength="255" value="<?php echo $this->flight->roundtrip_eco_price; ?>" /></td>
    			</tr>
                <tr>
    				<td class="key"><label for="roundtrip_bus_price" ><?php echo JText::_('COM_BOOKPRO_FLIGHT_BUSINESS_ROUNDTRIP_PRICE'); ?> </label></td>
    				<td><input class="text_area" type="text" name="roundtrip_bus_price" id="roundtrip_bus_price" size="10" maxlength="255" value="<?php echo $this->flight->roundtrip_bus_price; ?>" /></td>
    			</tr>
    			  
    			<tr>
    				<td class="key"><label for="To" ><?php echo JText::_('Bus children price '); ?>: </label></td>
    				<td><input class="text_area" type="text" name="bus_price_child" id="bus_price_child" size="60" maxlength="255" value="<?php echo $this->flight->bus_price_child; ?>" /></td>
    			</tr>
    			
    			-->
    			<tr>
    				<td class="key"><label for="Frequency" ><?php echo JText::_('COM_BOOKPRO_FLIGHT_FREQUENCY'); ?>: </label></td>
    				<td><input class="text_area" type="text" name="frequency" id="frequency" size="40" maxlength="255" value="<?php echo $this->flight->frequency; ?>" />
    				 <?php echo JText::_("Legend: 1-Monday, 2-Tuesday ...7-Sunday")?></td>
    			</tr>
    			
    			<tr>
    				<td class="key"><label for="flightnumber" ><?php echo JText::_('COM_BOOKPRO_FLIGHT_NUMBER'); ?>: </label></td>
    				<td><input class="text_area" type="text" name="flightnumber" id="flightnumber" size="60" maxlength="255" value="<?php echo $this->flight->flightnumber; ?>" /></td>
    			</tr>
    			<!-- 
    			<tr>
    				<td class="key"><label for="duration" ><?php echo JText::_('Duration'); ?>: </label></td>
    				<td><input class="text_area" type="text" name="duration" id="duration" size="60" maxlength="255" value="<?php echo $this->flight->duration; ?>" /></td>
    			</tr>
    			 -->
    			<tr>
    				<td class="key"><label for="start" ><?php echo JText::_('COM_BOOKPRO_FLIGHT_START'); ?>: </label></td>
    				<td><input class="text_area" type="text" name="start" id="start" size="60" maxlength="255" value="<?php echo $this->flight->start; ?>" /></td>
    			</tr>
    			<tr>
    				<td class="key"><label for="end" ><?php echo JText::_('COM_BOOKPRO_FLIGHT_END'); ?>: </label></td>
    				<td><input class="text_area" type="text" name="end" id="end" size="60" maxlength="255" value="<?php echo $this->flight->end; ?>" /></td>
    			</tr>
    			<!-- 
    			<tr>
    				<td class="key"><label for="Metadesc"><?php echo JText::_('Meta description'); ?>: </label></td>
    				<td><textarea class="text_area"  name="metadesc" id="metadesc"  rows='3' cols='40' ><?php echo $this->flight->metadesc; ?></textarea></td>
    			</tr>
    			<tr>
    				<td class="key"><label for="keyword"><?php echo JText::_('Keywords'); ?>: </label></td>
    				<td><textarea class="text_area"  name="keyword" id="keyword"  rows='3' cols='40' ><?php echo $this->flight->keyword; ?></textarea></td>
    			</tr>
    			 -->
    			
    			<tr>
    				<td class="key"><?php echo JText::_('COM_BOOKPRO_FLIGHT_STATUS'); ?>: </td>
        			<td>
						<?php echo JHtmlSelect::booleanlist('state','',$this->flight->state,'Publish','UnPublish','id_state') ?>       						            						    
        			</td>
        		</tr>
    		</table>
    	</fieldset>
    	
        <div class="clr"></div>
    </div>
   
   	<div class="clr"></div>
   	<div class="compulsory"><?php echo JText::_('Compulsory items'); ?></div>
	<input type="hidden" name="option" value="<?php echo OPTION; ?>"/>
	<input type="hidden" name="controller" value="<?php echo CONTROLLER_FLIGHT; ?>"/>
	<input type="hidden" name="task" value="save"/>
	<input type="hidden" name="boxchecked" value="1"/>
	<input type="hidden" name="cid[]" value="<?php echo $this->flight->id; ?>" id="cid"/>
	
	<?php echo JHTML::_('form.token'); ?>
</form>
</div>