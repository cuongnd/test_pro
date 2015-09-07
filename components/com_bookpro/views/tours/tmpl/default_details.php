<?php
AImporter::css('tour-list');
?>
<div class="row-fluid">
    <div class="span12">


        <?php
        if (count($this->tours) > 0) {
            $total = count($this->tours);
            ?>

            <?php
            $i = 0;
            foreach ($this->tours as $tour) {
                $rankstar = JURI::root() . "components/com_bookpro/assets/images/" . $tour->rank . 'star.png';
                $link = JRoute::_('index.php?option=com_bookpro&view=tour&id=' . $tour->id . '&controller=tour&Itemid=' . JRequest::getVar('Itemid'));

                if ($tour->image) {
                    $ipath = BookProHelper::getIPath($tour->image);
                } else {
                    $ipath = BookProHelper::getIPath('components/com_bookpro/assets/images/no_image.jpg');
                }
                $thumb = AImage::thumb($ipath, 150, 132);
                ?>
                <div class="row-fluid tour-details">

                    <div class="span<?php echo (12 / $this->products_per_row) ?>">

                        <div class="row-fluid tour-list-header">
                            <div class="span6">
                                <h3 class="details-title">
                                    <a href="<?php echo $link ?>"><?php echo JText::sprintf('COM_BOOKPRO_TOUR_DETAILS_TITLE', $tour->title, $tour->code) ?> </a>
                                </h3>
                                <div class="tour-departuredate">
                                    <a href="#">Departure Date
                                        <i class="img-tour-departuredate"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="span6">
                                <div class="tours-duration">
                                    <?php echo JText::sprintf('COM_BOOKPRO_TOUR_DETAILS_DURATION_STYLE', $tour->days, TourHelper::buildCategoryTour($tour->id)) ?>
                                </div>
                                <div class="tours-destination">
                                    <?php echo JText::sprintf('COM_BOOKPRO_TOUR_DETAILS_DESTINATION', TourHelper::getCountryByTour($tour->id)); ?>
                                </div>
                            </div>
                        </div>
                        <div class="row-fluid list-details">

                            <div class="span2 details-right-border">
                                <div class="img-tour-list">
                                    <a href="<?php echo $link ?>" class="thumbnail">
                                        <img src="<?php echo $thumb ?>" alt="<?php echo $tour->title ?>"> 
                                    </a>
                                    <div class="price-tour-list"><div class="price-tour-list-text">PRICE FR. $ 1192/PER</div></div>
                                </div>
                            </div>

                            <div class="span8 details-right-border">
                                <div class="detail-midde ">
                                    <div class="row-fluid highlights-tour-details">
                                        <?php echo JText::_('HIGHLIGHTS:') ?>
                                    </div>
                                    <div class="row-fluid tour-destination">
                                        <?php echo TourHelper::getTourDestination($tour->id) ?>
                                    </div>
                                    <div class="reviews-recommend">
                                        <fieldset class="field-reviews-recommend">
                                            <legend align="right" class="reviews-legend"><span class="number-reviews-recommend">140</span> Reviews <span class="number-reviews-recommend">,   80%</span> Recommend</legend>
                                        </fieldset>
                                    </div>
                                </div>    

                                

                            </div>
                            <div class="span2 right-details-tour">
                                (&) promotion
                            </div>
                        </div>
                        <div class="row-fluid tour-list-bottom right-tour-list-bottom">
                            <div class="span2 details-right-border tour-bookmark">
                                <a href="#">BOOKMARK</a>
                            </div>
                            <div class="span8 details-right-border">
                                <div class="pull-left tours-map">
                                    <a href="#">TOUR MAP <i class="img-tourmap"></i></a>
                                </div>
                                <div class="pull-left tours-activity">
                                    <?php echo JText::sprintf('COM_BOOKPRO_TOUR_ACTIVITY', TourHelper::buildActivityTour($tour->id)) ?>
                                </div>
                                <div class="pull-right tours-grade">
                                    Grade
                                </div>       
                            </div>
                            <div class="span2 tours-detail">
                                <div class="tours-detail-center">
                                    <a href="#">Tour details<i class="img-tours-detail"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>		
                <?php
                $i++;
            }
        } else {
            ?>

            <div><?php echo JText::sprintf('COM_BOOKPRO_NO_RECORD', JText::_('COM_BOOKPRO_TOUR')) ?></div>

        <?php } ?>

    </div>
</div>