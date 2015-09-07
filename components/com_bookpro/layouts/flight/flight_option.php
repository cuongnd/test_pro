<?php 
$flights = $displayData;
//$keys = array_rand($flights,4);
//$keys = $flights;
AImporter::model('airport');
AImporter::helper('currency');
?>


<div class="row-fluid right-content">
    <div class="span12">
        <div class="top_discount_tour">

            <p class="title_top_discount">MORE FLIGHT OPTION</p>
        	<?php foreach ($flights as $flight){
	 
		$model = new BookProModelAirport();
// 		var_dump($flight);
		
		
		$city = $model->getObject($flight->desfrom);
		?>
		 
            <div class="row-fluid">
                <div class="span12">
                    <div class="row-fluid content-feature">
                        <div class="span8 content-text">
                            <h3 class="ltour-title"><a href="#"><?php echo $flights->title; ?></a></h3>

                            <div class="duration">
                                One way fare
                            </div>
                            <div class="discount">
                                <div class="sale">
                                    <div class="sale-text">50%</div>
                                </div>
                                <div class="discount-right">
                                    DISCOUNT
                                </div>
                            </div>

                        </div>
                        <div class="span4 content-img">

                            <div class="imglist">
                                <div class="discount-search">

                                </div>
                                <div class="content-price">
                                    <div class="fro">Fro.</div>
                                    <div class="cprice">
                                       <?php echo JText::sprintf('COM_BOOKPRO_FLIGHT_CITY_OPTION_PER_PRICE',CurrencyHelper::displayPrice($flights->price));?>
                                    </div>

                                </div>
                               <img src="<?php echo $city->image; ?>" />
                            </div>


                        </div>
                    </div>
                </div>
            </div>
 <?php } ?>
        </div>
    </div>

</div>
