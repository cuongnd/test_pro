<?php 
$nights = $this->tour->days - 1;
$price = "$ 1520";
?>
<div class="div1_overview_nav_tabs text-left">

                            <div class="row-fluid">
	                                <div class="span8">
	                                	<p style="color: #333366; font-size: 18px; font-weight: bold;">PASSION
                                			OF SOUTH-EASTERN ASIA</p>

                                    <p class="text_div1_overview">Tour code : PSEA 120 - private
                                        daily departures</p>
                                    <p class="text_div1_overview" style="padding-bottom: 10px;">Country
                                        visited: Vietnam, Laos, Cambodia, Thailand</p>
                                    <span class="content_img"> 
                                        <?php echo $this->imagesAct; ?>
                                    </span>
                                </div>
                                <div class="span4 content_tour_length">
                                    <p class="text2_div1_overview">
                                    <?php echo JText::sprintf('COM_BOOKPRO_TOUR_LENGTH',$this->tour->days,$nights,$price); ?>
                                    </p>
                                    <button type="button" class="btn button_div1_overview" onclick="jQuery('a[href=#date_price]').tab('show');">CHECK
                                        THE AVAILABILITY</button>
                                </div>
                            </div>
</div>