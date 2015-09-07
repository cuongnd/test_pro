<?php 
$nights = $this->tour->days - 1;
$price = "$ 1520";
AImporter::helper('tour');

$country = TourHelper::getCountryByTour($this->tour->id);
?>
<div class="div1_overview_nav_tabs text-left">   
        <div class="row-fluid">
	            <div class="span8">
	                <p style="color: #333366; font-size: 18px; font-weight: bold; text-transform:uppercase;"><?php echo $this->tour->title; ?></p>

                <p class="text_div1_overview">Tour code : <?php echo $this->tour->code; ?> - <?php 
                if ($this->tour->stype == 'shared') {
                	echo JText::_('COM_BOOKPRO_OVERVIEW_SHARED');
                }else{
                	echo JText::_('COM_BOOKPRO_OVERVIEW_PRIVATE');
                }
                

                ?></p>
                <p class="text_div1_overview" style="padding-bottom: 10px;">Country
                    visited: <?php echo TourHelper::getHTMLInline($country); ?></p>
                <span class="content_img"> 
                    <?php echo $this->imagesAct; ?>
                </span>
            </div>
            <div class="span4 content_tour_length" style="padding-top:30px;">
                <p class="text2_div1_overview">
                
                <?php 
                if ($this->tour->days > 1) {
                	echo JText::sprintf('COM_BOOKPRO_TOUR_LENGTH_DAYS',$this->tour->days,$nights,$price); 
                }else{
                	echo JText::sprintf('COM_BOOKPRO_TOUR_LENGTH_OF_DAY',$this->tour->days,$price);
                }
                 ?>
                </p>
                <button type="button" class="btn button_div1_overview" onclick="jQuery('a[href=#date_price]').tab('show');">CHECK
                    THE AVAILABILITY</button>
            </div>
        </div>
</div>