<?php
JHtml::_('jquery.ui');
JHtml::_('jquery.framework');
JHtml::_('behavior.calendar');
JHtml::_('behavior.formvalidation');
JHtml::_('bootstrap.framework');
AImporter::helper('image','currency','tour');
AImporter::css('country');
AImporter::model('country');
$app = JFactory::getApplication();
$input = $app->input;
$country_id = $input->get('country_id',0,'int');
$doc=JFactory::getDocument();
$doc->addScript(JUri::root().'/components/com_bookpro/assets/js/FooTable-2/js/footable.js');
$doc->addScript(JUri::root().'/components/com_bookpro/assets/js/FooTable-2/js/footable.paginate.js');
$doc->addScript(JUri::root().'/components/com_bookpro/assets/js/FooTable-2/js/footable.sort.js');
$doc->addScript(JUri::root().'/components/com_bookpro/assets/js/__jquery.tablesorter/jquery.tablesorter.js');


$doc->addScript(JUri::root()."/components/com_bookpro/assets/js/noosliderlite/script_nooSliderLite.js");
$doc->addScript(JUri::root().'/components/com_bookpro/assets/js/view-bustrips.js');


$doc->addStyleSheet(JUri::root().'/components/com_bookpro/assets/js/noosliderlite/css/style.css');
$doc->addStyleSheet(JUri::root().'/components/com_bookpro/assets/js/FooTable-2/css/footable.core.css');
$doc->addStyleSheet(JUri::root().'/components/com_bookpro/assets/js/__jquery.tablesorter/themes/blue/style.css');
$doc->addStyleSheet(JUri::root().'/components/com_bookpro/assets/css/view-bustrips.css');

$doc->addStyleSheet(JURI::root().'components/com_bookpro/assets/css/jquery-ui.css');
$doc->addScript('http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.1/jquery-ui.min.js');


$country = TourHelper::getCountryObject($country_id);

?>

<div class="row-fluid">
    <div class="span8 col_left">
        <?php
        $this->setLayout('img_car_rental');
        echo $this->loadTemplate();
        ?>
        <div class="package_tour">
            <div class="row-fluid">
                <?php foreach($this->articlesContent as $article){ ?>
                <div class="span4 text_title text-left">
                    <p class="title"><?php echo $article->title ?></p>
                    <p><?php echo $article->introtext ?></p>
                </div>
                <?php } ?>
            </div>
        </div>
        <!--start top destination-->
        <?php
        $this->setLayout('country');
        echo $this->loadTemplate('top_destinations');
        ?>
        <!--end top destination-->
        <div class="banner_center" style="padding-top:10px;">
            <img class="image-full" src="images/banner-center.jpg">
        </div>
        <div class="fearture_tours">
            <div class="title_fearture_tours">
                <span class="text-left div_title_fearture_tours">FEATURED TOURS</span>
                <span class="div1_title_fearture_tours text-right">FIND GREAT DEALS IN <?php echo $country->country_name; ?> </span>
            </div>
            <div class="container_fearture_tours">

                <div class="row-fluid">
                    <div class="span4 content_best_deals">
                    <p class="best_deals text-left">Best deals</p>
                        <?php
                        $this->setLayout('country');
                        echo $this->loadTemplate('best_deals');
                        ?>



                    </div>
                    <div class="span8 content_table_span8">
                        <?php
                        $this->setLayout('country');
                        echo $this->loadTemplate('popular_car_rental');
                        ?>


                    </div>
                </div>
            </div>

        </div>
    </div>
						<div class="span4 col_right">
                            <?php
                            $this->setLayout('country');
                            echo $this->loadTemplate('featured_car_rental');
                            ?>



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
				            <div class="row-fluid right-content">
				            	<div class="span12">
				            		<img class="image-full" src="images/lucky.jpg">
				            	</div>
				            </div>
                            <?php
                            $this->setLayout('country');
                            echo $this->loadTemplate('events_in_region');
                            ?>


							<div class="row-fluid right-content">
                                <img class="image-full" src="components/com_bookpro/assets/images/wherther.jpg">
							</div>
							<div class="row-fluid right-content">
                                <img class="image-full" src="/components/com_bookpro/assets/images/banner_country_car1.jpg">
							</div>



						</div>
					</div>