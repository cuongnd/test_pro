<?php

// No direct access to this file
defined('_JEXEC') or die('Restricted access');
AImporter::helper('date','currency','tour');
$cart = JModelLegacy::getInstance('TourCart', 'bookpro');
$cart->load();
if($cart->filter['period']){
	$datearr=explode(';', $cart->filter['period']);
	$depart_date=$datearr[0];
	$return_date=$datearr[1];
}

$query=JURI::buildQuery(array("option"=>"com_bookpro","controller"=>"tour","view"=>"tour","Itemid"=>JRequest::getVar("Itemid")));

$now = JHtml::date('now');
$date = new JDate($now);
$from_date = JFactory::getDate($date)->format('d-m-Y',true);

$date->add(new DateInterval('P30D'));
$to_date = JFactory::getDate($date)->format('d-m-Y',true);
AImporter::css('tour-search');

?>
<script type="text/javascript">
	function getSearch(layout){
		document.frontForm.layout.value = layout;
		document.frontForm.submit();
	}
</script>
<form name="frontForm" method="post" class="form-horizontal" action='<?php echo JRoute::_("index.php?".$query) ?>'>
<h3 class="tour-search"><?php echo JText::_('COM_BOOKPRO_TOUR_SEARCH') ?></h3>
<div class="box-search">
	<div class="row-fluid box-search-head">
		<div class="pull-left">
			<label class="search-title">
				<i class="search-img"></i>
				<?php echo JText::_('COM_BOOKPRO_SEARCH_RESULT'); ?>
			</label>
			
		</div>
		<div class="pull-right">
			<div class="control-group sort-box">
				<label class="control-label sort-label"><?php echo JText::_('COM_BOOKPRO_SEARCH_TOUR_SORTBY') ?></label>
				<div class="controls">
					<select class="select-search">
						<option value="0"><?php echo JText::_('COM_BOOKPRO_SEARCH_TRAVELLER_RECOMMENTDATION') ?></option>
					</select>
				</div>
			</div>
		</div>
		<div class="clr"></div>
	</div>
	<div class="row-fluid box-search-content">
		<div class="span12">
			<div class="row-fluid">
				
				<div class="span6 total"><?php echo JText::sprintf('COM_BOOKPRO_SEARCH_TOTAL_TEXT',$this->total,strtoupper($this->country_name)); ?></div>
				
				
				<div class="span6">
					<div class="pull-right search-layout">
						
						<ul class="search-menu">
							<li>
								<a href="">

								</a>
							</li>
							<li class="details">
								<a onclick="getSearch('details')"><?php echo JText::_('Details') ?></a>
							</li>
							<li>
								<a href="">

								</a>
							</li>
							<li class="list">
								<a onclick="getSearch('list')"><?php echo JText::_('Lists') ?></a>
							</li>
							<li>
								<a href="">

								</a>
							</li>
						</ul>
					</div>
					
				</div>
			</div>
			
		</div>
		
	</div>
</div>
<div class="row-fluid">
	<div class="span8">
		<?php 
		//var_dump($this->layout);
		//echo $this->loadTemplate($this->layout);
	?>
	<div class="pagination clearfix">
		<?php echo $this->pagination->getPagesLinks() ?>
	</div>
	</div>
	<div class="span4 col_right">
		<div class="row-fluid right-content">
			<div class="span12">
				 <?php
	            $layout = new JLayoutFile('tour_search_detail', $basePath = JPATH_ROOT . '/components/com_bookpro/layouts');
	            $html = $layout->render($this->tour);
	            echo $html;
	            ?>
			</div>
			
		</div>
		<div class="row-fluid right-content">
            	<div class="span12">
            		<div class="sign_up_col_right">
			                <div class="search-title">Join now for deal & discount alert, save up to 75%</div>
			                <form class="form-search">
			                	<table cellpadding="0" cellspacing="0">
			                		<tr>
			                			<td>
			                				<i class="icon_search"></i>		
			                			</td>
			                			<td valign="bottom">
			                				<div class="input-append">
							                    <input type="text"
							                           class="input-medium button_col_right search-query">
							                    <button type="submit" class="btn btn-small">Sign up</button>
							                    </div>
			                			</td>
			                		</tr>
			                	</table>
			                	
			                	
			                </form>
		            </div>		
            	</div>
            </div>
	</div>
</div>	
	
	
	<input type="hidden" name="option"	value="<?php echo JRequest::getVar('option') ?>" />
	<input type="hidden" name="controller" value="tour" />
	
	<input type="hidden" name="task" value="searchadv" />
	
	<input type="hidden" id="layout" name="layout" value="" />
	<input type="hidden" name="keyword" value="<?php echo $cart->filter['keyword']; ?>" />
	<input type="hidden" name="country_id" value="<?php echo $cart->filter['country_id']; ?>" />
	<input type="hidden" name="days" value="<?php echo $cart->filter['days']; ?>" />
	<input type="hidden" name="activity" value="<?php echo $cart->filter['activity']; ?>" />
	<input type="hidden" name="private" value="<?php echo $cart->filter['private']; ?>" />
	<input	type="hidden" name="<?php echo $this->token?>" value="1" />
	<input type="hidden" name="Itemid" value="<?php echo JRequest::getVar('Itemid',0) ?>" />

</form>



