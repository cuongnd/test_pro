<?php 

    AImporter::css('tour_content_overview');
    AImporter::helper('tour');
?>
<div class="div2_overview_nav_tabs text-left">

    <p class="itinerary_head"><?php echo JText::_('COM_BOOKPRO_TOUR_ITINERARY_BRIEF') ?></p>

    <?php 
        $sl = 0;
        $classpage = "itinerarybrief";
        $pagelimit = ceil(count($displayData->itis) / 5);    
        if($displayData->itis){   
            foreach ($displayData->itis as $t => $data) { 
                if ($t!=0 && $t % 5 == 0) {
                     $sl++;
                }                
                ?>

            <div class="itinerary-row <?php echo $classpage.$sl;?> <?php echo $classpage;?>"> 
                <div class="content_div2_overview ">
                    <p class="itinerary-title"><?php echo $data->title?></p>
                    <p class="intinerary-desc"><?php echo $data->short_desc ?></p>
                    <span class="pull-right">

                        <span class="dest-title">
                            <i class="dest-icon"></i>
                            <?php echo $data->city_title; ?>
                        </span>

                        <span class="meal-title"><i class="meal-icon"></i><?php echo TourHelper::getMealOverview($data->meal); ?></span>
                    </span>
                    <div class="clr"></div>                                    
                </div>       
            </div>   
            <?php 
            }
    } ?>
</div>

<div class="pull-right" style="padding-top:10px;padding-bottom:10px; padding-right:10px;">
    
    <div class="slide-up pull-left action_item" key="<?php echo $classpage;?>next"></div>
    <div class="slide-down pull-left action_item" key="<?php echo $classpage;?>previous"></div>    
</div>

<script type="text/javascript">
    //paging
    jQuery(document).ready(function() {
        var <?php echo $classpage;?>min = 0;
        var <?php echo $classpage;?>max = <?php echo $pagelimit;?>;
        var <?php echo $classpage;?>i = 0;
        jQuery(".<?php echo $classpage;?>").hide();
        jQuery(".<?php echo $classpage;?>0").show();
        jQuery('.action_item').click(function() {
            var checksl = jQuery(this).attr('key');
            if (checksl == '<?php echo $classpage;?>previous') {
                <?php echo $classpage;?>i--;
            }
            if (checksl == '<?php echo $classpage;?>next') {
                <?php echo $classpage;?>i++;
            }
            if (checksl == '<?php echo $classpage;?>all') {
                jQuery(".<?php echo $classpage;?>").show();
            } else {
                if (<?php echo $classpage;?>i < <?php echo $classpage;?>min) {
                    <?php echo $classpage;?>i = <?php echo $classpage;?>min;
                }
                if (<?php echo $classpage;?>i > (<?php echo $classpage;?>max - 1)) {
                    <?php echo $classpage;?>i = <?php echo $classpage;?>max - 1;
                }
                jQuery(".<?php echo $classpage;?>").hide();
                jQuery(".<?php echo $classpage;?>" + <?php echo $classpage;?>i).show();
            }
        });
    });
    </script>