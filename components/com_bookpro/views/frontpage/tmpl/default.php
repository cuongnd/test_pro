<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

AImporter::helper('bookpro');

$lessInput = JPATH_ROOT . '/components/com_bookpro/assets/less/view-frontpage-default.less';
$cssOutput = JPATH_ROOT . '/components/com_bookpro/assets/css/view-frontpage-default.css';
BookProHelper::compileLess($lessInput, $cssOutput);
$doc = JFactory::getDocument();
$doc->addStyleSheet(JUri::root() . '/components/com_bookpro/assets/css/view-frontpage-default.css');
$doc->addScript(JUri::root() . '/components/com_bookpro/assets/js/jquery.min.js');

?>
<script src="../components/com_bookpro/assets/js/jquery_002.js"></script>
<script src="../components/com_bookpro/assets/js/customize.js"></script>

<div class="view-frontpage-default row">
    <div class="container-fluid">
        <div class="row clearfix">
            <div class="col-md-4 column ui-sortable">
                <div class="box box-element">
                    <div class="view">
                        <h3><img src="../components/com_bookpro/assets/images/icons/icon-puzzle-pieces-1.png" style="margin: 0 10px 0 0"><?php echo JText::_("COM_BOOKPRO_FRONTPAGE_CUSTOMIZED_HOLIDAYS") ?></h3>
                        <p>Create your dream holiday with the help of our expert. The entire holidays are designed
                            around requirement you send us. You explore your interests at your own speed and select your
                            preferred style of accommodation. <a href="#">Find out more</a></p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 column ui-sortable" style="border-left: 1px #ccc solid;border-right: 1px #ccc solid">
                <div class="box box-element">
                    <div class="view">
                        <h3><img src="../components/com_bookpro/assets/images/icons/icon-puzzle-pieces-2.png" style="margin: 0 10px 0 0"><?php echo JText::_("COM_BOOKPRO_FRONTPAGE_EXPERT_KNOWLEDGE") ?></h3>

                        <p>The travel experts have had in depth background on their specialist regions. You will have
                            suggested holday in timely manner get the great save of your time and budget. You will have
                            the same specialist throughout the planning process. <a href="#">Find out more</a></p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 column ui-sortable">
                <div class="box box-element">
                    <div class="view">
                        <h3><img src="../components/com_bookpro/assets/images/icons/icon-puzzle-pieces-3.png" style="margin: 0 10px 0 0"><?php echo JText::_("COM_BOOKPRO_FRONTPAGE_INSPIRATION_IDEAS") ?></h3>

                        <p>You are not sure where to plan your upcoming trips. The inspiration section provides
                            highlights of month and destinations. You find hundreds of itinerary and destination ideas
                            based on your travel preferences. <a href="#">Find out more</a></p>
                    </div>
                </div>
            </div>
        </div>
        <div class="row clearfix">
            <div class="col-md-3 column ui-sortable">
                <div class="box box-element-caption">
                    <div class="view">
                        <h4><?php echo JText::_("COM_BOOKPRO_FRONTPAGE_SEASONAL_HOTDEAL_TOUR_FLIGHT") ?></h4>
                        <img src="../components/com_bookpro/assets/images/seasonal-img-1.jpg" class="margin-img">
                        <img src="../components/com_bookpro/assets/images/seasonal-img-2.jpg" class="margin-img">

                        <div class="caption">
                            <p>Hanoi , Vietnam to Yangon, Myanmar , we have a range of brilliant offers and deals to
                                tempt every traveller; tour discounts , promotion flights, freebies plus lots more!</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 column ui-sortable">
                <div class="border-img-label">
                    <div class="box box-element-caption">
                        <div class="view border-caption">
                            <img src="../components/com_bookpro/assets/images/seasonal-img-3.jpg">

                            <div class="caption-center">
                                <p>Discover the unique cultural heritage, Meet hill tribes, Explore rain forest, Relax
                                    on beach, Sample local cuisine or Cruise along a busy waterway. For over 10 years we
                                    have worked hard to offer the widest selection of affordable tours for single
                                    traveller, family and groups to explore across the Mekong River sub region: Yunnan,
                                    Laos, Myanmar, Thailand, Cambodia and Vietnam.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <img src="../components/com_bookpro/assets/images/seasonal-img-4.jpg">
            </div>
            <div class="col-md-3 column ui-sortable">
                <div class="box box-element-caption">
                    <div class="view">
                        <h4><?php echo JText::_("COM_BOOKPRO_FRONTPAGE_SPECIAL_OFFERS_HOTELS_CARS") ?></h4>
                        <img src="../components/com_bookpro/assets/images/seasonal-img-5.jpg" class="margin-img">
                        <img src="../components/com_bookpro/assets/images/seasonal-img-6.jpg" class="margin-img">

                        <div class="caption">
                            <p>Find cheap hotels and discounts when you book on Asianventure. Compare hotel deals,
                                offers and read unbiased reviews on hotels, multiple car rental services!</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="container-fluid">
            <h3 class="destination"><img src="../components/com_bookpro/assets/images/icon-group-private.png"><?php echo JText::_("COM_BOOKPRO_FRONTPAGE_GROUP_PRIVATE_TOURS") ?></h3>
                <div class=" jcarousel-skin-tango">
                    <div class="jcarousel-container jcarousel-container-horizontal" style="position: relative; display: block;">
                        <div class="jcarousel-clip jcarousel-clip-horizontal" style="position: relative;">
                            <ul id="mycarousel" class="jcarousel-list jcarousel-list-horizontal"
                                style="overflow: hidden; position: relative; top: 0px; margin: 0px; padding: 0px; left: -460px; width: 3000px;">
                                <li class="jcarousel-item jcarousel-item-horizontal" jcarouselindex="1"
                                    style="float: left; list-style: none;"><img src="../components/com_bookpro/assets/images/group-img-1.jpg" alt="top destinations">
                                    <span class="details-destination">Glimpse of Vietnam, 8 days, from Hanoi to Ho Chi Minh, Vietnam<br>
                                        <h5><small>Fr. US$ 500/pers.</small></h5>
                                    </span>
                                </li>
                                <li class="jcarousel-item jcarousel-item-horizontal" jcarouselindex="2"style="float: left; list-style: none;">
                                    <img src="../components/com_bookpro/assets/images/group-img-1.jpg" alt="top destinations">
                                    <span>Glimpse of Vietnam, 8 days, from Hanoi to Ho Chi Minh, Vietnam<br>
                                        <h5><small>Fr. US$ 500/pers.</small></h5>
                                    </span>
                                </li>
                                <li class="jcarousel-item jcarousel-item-horizontal" jcarouselindex="3" style="float: left; list-style: none;">
                                    <img src="../components/com_bookpro/assets/images/group-img-1.jpg" alt="top destinations">
                                    <span>Glimpse of Vietnam, 8 days, from Hanoi to Ho Chi Minh, Vietnam<br>
                                        <h5><small>Fr. US$ 500/pers.</small></h5>
                                    </span>
                                </li>
                                <li class="jcarousel-item jcarousel-item-horizontal" jcarouselindex="4"style="float: left; list-style: none;">
                                    <img src="../components/com_bookpro/assets/images/group-img-1.jpg" alt="top destinations">
                                    <span>Glimpse of Vietnam, 8 days, from Hanoi to Ho Chi Minh, Vietnam<br>
                                        <h5><small>Fr. US$ 500/pers.</small></h5>
                                    </span>
                                </li>
                                <li class="jcarousel-item jcarousel-item-horizontal" jcarouselindex="5" style="float: left; list-style: none;">
                                    <img src="../components/com_bookpro/assets/images/group-img-1.jpg" alt="top destinations">
                                    <span>Glimpse of Vietnam, 8 days, from Hanoi to Ho Chi Minh, Vietnam<br>
                                        <h5><small>Fr. US$ 500/pers.</small></h5>
                                    </span>

                                </li>
                                <li class="jcarousel-item jcarousel-item-horizontal" jcarouselindex="6" style="float: left; list-style: none;">
                                    <img src="../components/com_bookpro/assets/images/group-img-1.jpg" alt="top destinations">
                                    <span>Glimpse of Vietnam, 8 days, from Hanoi to Ho Chi Minh, Vietnam<br>
                                        <h5><small>Fr. US$ 500/pers.</small></h5>
                                    </span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 column ui-sortable">
                <div class="box hotel-deals">
                    <div class="row">
                        <h3><i class="im-home"></i><?php echo JText::_("COM_BOOKPRO_FRONTPAGE_HOTEL_DEALS") ?></h3>
                        <div class="col-md-4">
                            <img src="../components/com_bookpro/assets/images/seasonal-img-7.jpg">
                        </div>
                        <div class="col-md-8" style="padding-left: 0">
                            <p>Sofitel Metropole Hanoi Hotel in Hanoi, Vietnam</p>
                            <h5>Fr. $310/Nite</h5>
                        </div>
                        <p align="center"><img src="../components/com_bookpro/assets/images/border-buttom-price.jpg"></p>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <img src="../components/com_bookpro/assets/images/seasonal-img-7.jpg">
                        </div>
                        <div class="col-md-8" style="padding-left: 0">
                            <p>Sofitel Metropole Hanoi Hotel in Hanoi, Vietnam</p>
                            <h5>Fr. $310/Nite</h5>
                        </div>
                        <p align="center"><img src="../components/com_bookpro/assets/images/border-buttom-price.jpg"></p>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <img src="../components/com_bookpro/assets/images/seasonal-img-7.jpg">
                        </div>
                        <div class="col-md-8" style="padding-left: 0">
                            <p>Sofitel Metropole Hanoi Hotel in Hanoi, Vietnam</p>
                            <h5>Fr. $310/Nite</h5>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 column ui-sortable">
                <div class="box hotel-deals">
                    <div class="row">
                        <h3><i class="im-home"></i><?php echo JText::_("COM_BOOKPRO_FRONTPAGE_HOTEL_DEALS") ?></h3>
                        <div class="col-md-4">
                            <img src="../components/com_bookpro/assets/images/seasonal-img-7.jpg">
                        </div>
                        <div class="col-md-4" style="padding-left: 0;padding-right: 0">
                            <p>Flight from Ho Chi Minh, Vietnam to Hanoi, Vietnam</p>
                        </div>
                        <div class="col-md-4">
                            <div class="bg-pice-flight">
                                <h6>Just $40/pers</h6>
                            </div>
                        </div>
                        <p align="center"><img src="../components/com_bookpro/assets/images/border-buttom-price.jpg"></p>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <img src="../components/com_bookpro/assets/images/seasonal-img-7.jpg">
                        </div>
                        <div class="col-md-4" style="padding-left: 0;padding-right: 0">
                            <p>Flight from Ho Chi Minh, Vietnam to Hanoi, Vietnam</p>
                        </div>
                        <div class="col-md-4">
                            <div class="bg-pice-flight">
                                <h6>Just $40/pers</h6>
                            </div>
                        </div>
                        <p align="center"><img src="../components/com_bookpro/assets/images/border-buttom-price.jpg"></p>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <img src="../components/com_bookpro/assets/images/seasonal-img-7.jpg">
                        </div>
                        <div class="col-md-4" style="padding-left: 0;padding-right: 0">
                            <p>Flight from Ho Chi Minh, Vietnam to Hanoi, Vietnam</p>
                        </div>
                        <div class="col-md-4">
                            <div class="bg-pice-flight">
                                <h6>Just $40/pers</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 column ui-sortable">
                <div class="box hotel-deals">
                    <div class="row">
                        <h3><i class="im-home"></i><?php echo JText::_("COM_BOOKPRO_FRONTPAGE_HOTEL_DEALS") ?></h3>
                        <div class="col-md-4">
                            <img src="../components/com_bookpro/assets/images/seasonal-img-7.jpg">
                        </div>
                        <div class="col-md-8" style="padding-left: 0">
                            <p>Car rental including all fee from Hanoi Airport to Hanoi City, Vietnam</p>
                            <h5>Fr. $30/Car</h5>
                        </div>
                        <p align="center"><img src="../components/com_bookpro/assets/images/border-buttom-price.jpg"></p>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <img src="../components/com_bookpro/assets/images/seasonal-img-7.jpg">
                        </div>
                        <div class="col-md-8" style="padding-left: 0">
                            <p>Car rental including all fee from Hanoi Airport to Hanoi City, Vietnam</p>
                            <h5>Fr. $30/Car</h5>
                        </div>
                        <p align="center"><img src="../components/com_bookpro/assets/images/border-buttom-price.jpg"></p>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <img src="../components/com_bookpro/assets/images/seasonal-img-7.jpg">
                        </div>
                        <div class="col-md-8" style="padding-left: 0">
                            <p>Car rental including all fee from Hanoi Airport to Hanoi City, Vietnam</p>
                            <h5>Fr. $30/Car</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 column">
                <div class="box ui-sortable-6">
                    <h4><img src="../components/com_bookpro/assets/images/icon-caption.png">TESTIMONIALS</h4>
                    <p>Quick response and reliable service, as usual. I have used the service of Asianventure tens of times in the past 10 or 15 years, but never experienced any inconvenience.  I will use again your service for more next trip in region.</p>
                    <div class="more-info">More info</div>
                </div>
            </div>
            <div class="col-md-6 column">
                <div class="box ui-sortable-6">
                    <h4><img src="../components/com_bookpro/assets/images/icon-caption.png">TESTIMONIALS</h4>
                    <p>Quick response and reliable service, as usual. I have used the service of Asianventure tens of times in the past 10 or 15 years, but never experienced any inconvenience.  I will use again your service for more next trip in region.</p>
                    <div class="more-info">More info</div>
                </div>
            </div>
        </div>
        <div class="row clearfix">
            <h3 class="destination"><img src="../components/com_bookpro/assets/images/destination-icon.png"> DESTINATION TRAVEL GUIDE </h3>
            <div class="col-md-2 bg-city">
                <img src="../components/com_bookpro/assets/images/destination-1.jpg">
                <h5>Vietnam guide</h5>
                <ul>
                    <li>Hanoi</li>
                    <li>Ho Chi Minh</li>
                    <li>Nha Trang</li>
                    <li>Sapa</li>
                </ul>
            </div>
            <div class="col-md-2 bg-city">
                <img src="../components/com_bookpro/assets/images/destination-2.jpg">
                <h5>China guide</h5>
                <ul>
                    <li>Beijing</li>
                    <li>Kunming</li>
                    <li>Lijiang</li>
                    <li>Shanghai</li>
                </ul>
            </div>
            <div class="col-md-2 bg-city">
                <img src="../components/com_bookpro/assets/images/destination-3.jpg">
                <h5>Thailand guide</h5>
                <ul>
                    <li>Bangkok</li>
                    <li>Chiang Mai</li>
                    <li>Kanchanaburi</li>
                    <li>Phuket</li>
                </ul>
            </div>
            <div class="col-md-2 bg-city">
                <img src="../components/com_bookpro/assets/images/destination-4.jpg">
                <h5>Mianmar guide</h5>
                <ul>
                    <li>Yangon</li>
                    <li>Inle Lake</li>
                    <li>Mandalay</li>
                    <li>Bagan</li>
                </ul>
            </div>
            <div class="col-md-2 bg-city">
                <img src="../components/com_bookpro/assets/images/destination-5.jpg">
                <h5>Laos guide</h5>
                <ul>
                    <li>Vientiane</li>
                    <li>Luang Prabang</li>
                    <li>Pakse</li>
                    <li>Xieng Khouang</li>
                </ul>
            </div>
            <div class="col-md-2 bg-city">
                <img src="../components/com_bookpro/assets/images/destination-6.jpg">
                <h5>Cambodia guide</h5>
                <ul>
                    <li>Phnom Penh</li>
                    <li>Siem Reap</li>
                    <li>Battambang</li>
                    <li>Sihanoukville</li>
                </ul>
            </div>
        </div>
        <div class="row clearfix">
            <div class="container-fluid bg-thumbnail">
                <div class="col-md-4">
                    <div class="box">
                        <div class="view">
                            <h3>Why adventure travel?</h3>
                            <p>Adventure travel is more than a vacation. It’s a chance to get to know your world better by putting yourself on a first-name basis with the people, places, and things that make it worth exploring. This is your planet. Come and get it.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="box">
                        <div class="view">
                            <h3>Why small-group travel?</h3>
                            <p>The world opens up a little more for a small group than it does for a solo traveller or a big-bus tour. Small groups offer security, access, camaraderie, and a stronger affinity with your destination than you’ll get by travelling any other way.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="box">
                        <div class="view">
                            <h3>Why Asianventure ?</h3>
                            <p>We’re the small-group adventure travel experts, and have been for over 20 years. And we got that way by listening to travellers and giving them what they want: Top-notch tours in topplaces with top-notch staff at affordable prices.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>




