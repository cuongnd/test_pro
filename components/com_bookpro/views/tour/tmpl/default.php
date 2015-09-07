<?php
$document = JFactory::getDocument();
$lessInput=JPATH_ROOT.'/components/com_bookpro/assets/less/view-tour-default.less';
$cssOutput=JPATH_ROOT.'/components/com_bookpro/assets/css/view-tour-default.css';
BookProHelper::compileLess($lessInput,$cssOutput);
$document->addStyleSheet(JUri::root().'/components/com_bookpro/assets/css/view-tour-default.css');
JHtml::_('bootstrap.framework')
?>
<div class="row wrapper-top">

    <div class="col-md-8" style="padding-right: 0px !important;">
        <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
            <!-- Indicators -->
            <ol class="carousel-indicators">
                <li data-target="#carousel-example-generic" data-slide-to="0" class=""></li>
                <li data-target="#carousel-example-generic" data-slide-to="1" class=""></li>
                <li data-target="#carousel-example-generic" data-slide-to="2" class="active"></li>
            </ol>
            <!-- Wrapper for slides -->
            <div class="carousel-inner">
                <div class="item">
                    <img src="components/com_bookpro/assets/images/layout-default-tour/sl-1.jpg" alt="slider 1">
                    <div class="carousel-caption">
                        <h3>Mô tả ảnh 1</h3>
                    </div>
                </div>
                <div class="item">
                    <img src="components/com_bookpro/assets/images/layout-default-tour/sl-2.jpg" alt="slider 1">
                    <div class="carousel-caption">
                        <h3>Mô tả ảnh 2</h3>
                    </div>
                </div>
                <div class="item active">
                    <img src="components/com_bookpro/assets/images/layout-default-tour/sl-3.jpg" alt="slider 1">
                    <div class="carousel-caption">
                        <h3>Mô tả ảnh 3</h3>
                    </div>
                </div>
            </div>
            <!-- Controls -->
            <a class="left carousel-control" href="#carousel-example-generic" data-slide="prev">
                <span class="glyphicon glyphicon-chevron-left"></span>
            </a>
            <a class="right carousel-control" href="#carousel-example-generic" data-slide="next">
                <span class="glyphicon glyphicon-chevron-right"></span>
            </a>
        </div>
     </div>


    <div class="col-md-4 left-info">


        <div class="col-md-12" style="padding-left: 0px !important;">
            <div class="title-price-tour"><label><i class="b-price">PRICE FR. $  </i><b>1000</b> - YOUR SAVING 15%</label></div>
        </div>
        <div class="col-md-12" style="padding-left: 0px !important;">
            <div class="info-tour">
                <div class="col-md-12 title"> <h4><i class="fa-comments"></i>WHAT TO SEE ?<h4></div>

                <div class="col-md-12 columns">
                    <div class="col-md-12"><h6>VIET NAM-LAO-CAMBODIA-THAILAND<h6></div>
                    <div class="col-md-6">
                        <div class="columns1">
                            <ul>
                                <li>Hanoi City</li>
                                <li>Halong Bay</li>
                                <li>Hoa Lu  & Tam Coc</li>
                                <li>Luang Prabang</li>
                                <li>Chiang Mai City</li>
                                <li>Chiang Mai City</li>
                                <li>Bangkok City</li>
                                <li>Bangkok City</li>
                            </ul>
                        </div>

                    </div>
                    <div class="col-md-6">
                        <div class="columns2">
                            <ul>
                                <li>Phnom Penh City</li>
                                <li>Siem Reap Town</li>
                                <li>Angkor Temples</li>
                                <li>Kompong Cham</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="col-md-12 activity">
                    <div class="col-md-5 activity-title">
                        <h5><i class="me-cloudy3"></i>ACTIVITY</h5>
                    </div>

                    <div class="col-md-7 icons">
                        <i class="br-skype"></i>
                        <i class="br-coffee"></i>
                        <i class="br-gift"></i>
                        <i class="im-tux"></i>
                    </div>

                </div>
            </div>
        </div>
    </div>


</div>

<div class="row tour">
        <div class="col-md-3">
            <h5>Tour Type : Small group</h5>
            <h5>Group Size : Min 4, Max 16 </h5>
        </div>
        <div class="col-md-3">
            <h5>Passenger Age : 3 - 99 years </h5>
            <h5>Service Class :  Multiple  </h5>
        </div>
        <div class="col-md-3">
            <h5>Tour Style: Classic Culture</h5>
            <h5>Physical Grade:  Moderate  </h5>
        </div>
        <div class="col-md-3">
            <h5>Start: Hanoi, Vietnam</h5>
            <h5>Finish : Bangkok, Thailand</h5>
        </div>
</div>



<div class="row tab-infomation">
    <div class="col-md-8">
        <div class="bs-example bs-example-tabs" role="tabpanel" data-example-id="togglable-tabs">

            <ul id="myTab" class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active"><a href="#" id="home-tab" role="tab" data-toggle="tab" aria-controls="home" aria-expanded="true">OVERVIEW</a></li>
                <li role="presentation" class=""><a href="#itinerary" role="tab" id="itinerary-tab" data-toggle="tab" aria-controls="profile" aria-expanded="false">ITINERARY</a></li>
                <li role="presentation" class=""><a href="#price-date" role="tab" id="price-date-tab" data-toggle="tab" aria-controls="price-date" aria-expanded="false">PRICE & DATE</a></li>
                <li role="presentation" class=""><a href="#documents" role="tab" id="documents-tab" data-toggle="tab" aria-controls="documents" aria-expanded="false">DOCUMENTS</a></li>
                <li role="presentation" class=""><a href="#reviews" role="tab" id="reviews-tab" data-toggle="tab" aria-controls="reviews" aria-expanded="false">REVIEWS</a></li>

            </ul>
            <div id="myTabContent" class="tab-content">
<!--info tab review-->
                <div role="tabpanel" class="col-md-12 tab-pane fade" id="reviews" aria-labelledby="reviews-tab" style="padding-right: 0 !important;padding-left: 0 !important;">
                    <div class="border-top">&nbsp;
                        <div class="col-md-7 title-left">
                            <h4>PASSION OF  ASIA</h4>
                            <h5>Vietnam - Laos - Cambodia - Thailand</h5>

                            <div class="col-md-6 this-tour">
                                <h5><i class="im-mail2"></i>E-MAIL THIS TOUR</h5>
                            </div>

                            <div class="col-md-6 this-tour">
                                <h5><i class="en-sharable"></i>BOOK NOW</h5>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="col-md-8 rating">
                                <h5>Traveler Rating</h5>
                                <h1>9.8/10</h1>
                            </div>
                            <div class="col-md-4 icon-request">
                                <i class="im-notebook"></i>
                            </div>
                        </div>

                        <div class="col-md-12 trip-reviews">
                            <h5>TRIP REVIEWS</h5>
                            <h6>You will find thousands of our tour members' uncensored reviews and opinions on their guide's performance, "magic moments" during the tour, and much more. We rely on these tour reviews to continually improve  the itineraries, hotels   transportation,  foods , tour guide skills and more. If you like what you see, we'd love to have you as a travel partner on one of our Asianventure Tours!</h6>
                        </div>

                        <div class="col-md-3"></div>

                        <div class="col-md-9">
                            <div class="col-md-7 overall-score">
                                <h6>OVERALL SCORE: <sub>9.8</sub>/10 reviews</h6>
                            </div>
                            <div class="col-md-5 write-review">
                                <h5><i class="im-pen"></i>WRITE A REVIEW </h5>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 news">
                        <div class="col-md-3" style="padding-left: 0;padding-right: 0" >
                            <img src="components/com_bookpro/assets/images/layout-default-tour/people.png" style="border: 1px solid #808080">
                            <h6>Peter Smith</h6>
                            <h5><i class="fa-flag-alt"></i>United States</h5>
                            <div>
                            <h5 class="review">THIS REVIEW HELPFUL</h5>
                            </div>
                        </div>
                        <div class="col-md-9">
                            <div class="col-md-8 experience">
                                <h5>GREAT EXPERIENCE IN LIFE</h5>
                                <div class="icon-star">
                                <i class="im-star3"></i><i class="im-star3"></i><i class="im-star3"></i><i class="im-star3"></i><i class="im-star3"></i>
                                </div>
                            </div>
                            <div class="col-md-4 date">
                                <h6>Written on 19 Dec, 2014 </h6>
                            </div>

                            <div class="col-md-12">
                                <p>Would highly recommend this tour. It combined such a variety of experiences! The guides were knowledgeable and so friendly. Pace was good and the itinerary arranged so that there was adequate free time. Like all tours, you can opt out of planned visits if you so choose. I can't believe how much we did in the time,...</p>
                            </div>

                            <div class="col-md-3">
                                <img src="components/com_bookpro/assets/images/layout-default-tour/woman.png">
                            </div>
                            <div class="col-md-3">
                                <img src="components/com_bookpro/assets/images/layout-default-tour/woman.png">
                            </div>
                            <div class="col-md-3">
                                <img src="components/com_bookpro/assets/images/layout-default-tour/woman.png">
                            </div>
                            <div class="col-md-3">
                                <img src="components/com_bookpro/assets/images/layout-default-tour/woman.png">
                            </div>

                            <div class="col-md-12">
                                <div class="col-md-2 icon-ok">
                                    <i class="fa-ok-sign"></i>
                                </div>
                                <div class="col-md-10 read-full-review">
                                    <h5>READ FULL REVIEW<i class="fa-circle-arrow-down"></i></h5>
                                </div>
                            </div>
                        </div>



                    </div>   <div class="col-md-12 news">
                        <div class="col-md-3" style="padding-left: 0;padding-right: 0" >
                            <img src="components/com_bookpro/assets/images/layout-default-tour/people.png" style="border: 1px solid #808080">
                            <h6>Peter Smith</h6>
                            <h5><i class="fa-flag-alt"></i>United States</h5>
                            <div>
                            <h5 class="review">THIS REVIEW HELPFUL</h5>
                            </div>
                        </div>
                        <div class="col-md-9">
                            <div class="col-md-8 experience">
                                <h5>GREAT EXPERIENCE IN LIFE</h5>
                                <div class="icon-star">
                                <i class="im-star3"></i><i class="im-star3"></i><i class="im-star3"></i><i class="im-star3"></i><i class="im-star3"></i>
                                </div>
                            </div>
                            <div class="col-md-4 date">
                                <h6>Written on 19 Dec, 2014 </h6>
                            </div>

                            <div class="col-md-12">
                                <p>Would highly recommend this tour. It combined such a variety of experiences! The guides were knowledgeable and so friendly. Pace was good and the itinerary arranged so that there was adequate free time. Like all tours, you can opt out of planned visits if you so choose. I can't believe how much we did in the time,...</p>
                            </div>

                            <div class="col-md-3">
                                <img src="components/com_bookpro/assets/images/layout-default-tour/woman.png">
                            </div>
                            <div class="col-md-3">
                                <img src="components/com_bookpro/assets/images/layout-default-tour/woman.png">
                            </div>
                            <div class="col-md-3">
                                <img src="components/com_bookpro/assets/images/layout-default-tour/woman.png">
                            </div>
                            <div class="col-md-3">
                                <img src="components/com_bookpro/assets/images/layout-default-tour/woman.png">
                            </div>

                            <div class="col-md-12">
                                <div class="col-md-2 icon-ok">
                                    <i class="fa-ok-sign"></i>
                                </div>
                                <div class="col-md-10 read-full-review">
                                    <h5>READ FULL REVIEW<i class="fa-circle-arrow-down"></i></h5>
                                </div>
                            </div>
                        </div>



                    </div>

                </div>
<!--end info tab review-->

<!--info tab overview-->
                <div role="tabpanel" class="col-md-12 tab-pane fade active in" id="overview" aria-labelledby="overview-tab">
                    <div class="border-top">&nbsp;
                        <div class="col-md-7 title-left">
                            <h4>PASSION OF  ASIA</h4>
                            <h5>Vietnam - Laos - Cambodia - Thailand</h5>

                            <div class="col-md-6 this-tour">
                                <h5><i class="im-mail2"></i>E-MAIL THIS TOUR</h5>
                            </div>

                            <div class="col-md-6 this-tour">
                                <h5><i class="en-sharable"></i>BOOK NOW</h5>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="col-md-8 rating">
                                <h5>Traveler Rating</h5>
                                <h1>9.8/10</h1>
                            </div>
                            <div class="col-md-4 icon-request">
                                <i class="im-notebook"></i>
                            </div>
                        </div>

                        <div class="col-md-12 trip-reviews">
                            <p>This new designed trip  brings a great combination of exploreation and relax ation as we start the trip in Vietnam, seeing the beautiful national park of Cat Ba, unwining on comfortable travel junk. We continue south to see the busting  Ho Chi Minh, enjoy cyclying in Mekong delta, keeping contact with local people. The tranquil Laos is in contradiction with the south of Vietnam.  You spend last few days on beautiful beach of Phuket and do the sea kayaking.</p>
                        </div>

                    </div>

                    <div class="col-md-12 info-tour-days">
                        <h5>DAY 1: ARRIVAL - HO CHI MINH</h5>
                        <p>On arrival at Ho Chi Minh Airport, you are met by our guide and transferred to your hotel. </p>
                    </div>
                    <div class="col-md-12 location-foot">
                        <div class="col-md-6"></div>
                        <div class="col-md-3">
                            <h6><i class="im-home"></i>Ho Chi Minh</h6>
                        </div>
                        <div class="col-md-3">
                            <h6><i class="im-pacman"></i>1D</h6>
                        </div>
                    </div>
                    <div class="col-md-12 info-tour-days">
                        <h5>DAY 2 : CU CHI TUNNELS - CHO CHI MINH CITY TOUR  </h5>
                        <p>Morning excursion to visit  Cu Chi Tunnels. Drive back and city tour with  Opera House, Notre Dame Cathedral ,  Old Sai Gon Post Office, Reunification Palace , War Remnant Exhibit and  Ben Thanh Market. </p>
                    </div>
                    <div class="col-md-12 location-foot">
                        <div class="col-md-6"></div>
                        <div class="col-md-3">
                            <h6><i class="im-home"></i>Ho Chi Minh</h6>
                        </div>
                        <div class="col-md-3">
                            <h6><i class="im-pacman"></i>1B, 1L, 1D</h6>
                        </div>
                    </div>
                    <div class="col-md-12 info-tour-days">
                        <h5>DAY 3: HO CHI MINH - MEKONG DELTA - CAN THO   </h5>
                        <p>Overland to My Tho Town in Mekong Delta. Take a boat trip on Mekong River and canals  to explore Mekong Delta , seeing fruit gardens and local industry cottages. Continue driving to Can Tho City. </p>
                    </div>
                    <div class="col-md-12 location-foot">
                        <div class="col-md-6"></div>
                        <div class="col-md-3">
                            <h6><i class="im-home"></i>Can Tho</h6>
                        </div>
                        <div class="col-md-3">
                            <h6><i class="im-pacman"></i>1B, 1L, 1D</h6>
                        </div>
                    </div>
                    <div class="col-md-12 info-tour-days">
                        <h5>DAY 4: CAN THO - CAI RANG FLOATING MARKET - CHAU DOC  </h5>
                        <p>Early morning boat trip to see Cai Rang Floating Market. Drive to Chau Doc. Visit  Ba Chua Temple,  Tay An Sanctuary and Thoai Ngoc Hau Tomb  and admire sunset from  Sam Mountain. </p>
                    </div>
                    <div class="col-md-12 location-foot">
                        <div class="col-md-6"></div>
                        <div class="col-md-3">
                            <h6><i class="im-home"></i>Chau Doc</h6>
                        </div>
                        <div class="col-md-3">
                            <h6><i class="im-pacman"></i>1B, 1L, 1D</h6>
                        </div>
                    </div>
                    <div class="col-md-12 info-tour-days">
                        <h5>DAY 5: CHAU DOC - HO CHI MINH </h5>
                        <p>Boat trip to explore Cham Village and Fish Breeding. Drive  via Cao Lanh Town to Ho Chi Minh. En route visit  Vinh Trang Pagoda and China Town.</p>
                    </div>
                    <div class="col-md-12 location-foot">
                        <div class="col-md-6"></div>
                        <div class="col-md-3">
                            <h6><i class="im-home"></i>Ho Chi Minh</h6>
                        </div>
                        <div class="col-md-3">
                            <h6><i class="im-pacman"></i>1B, 1L, 1D</h6>
                        </div>
                    </div>
                    <div class="col-md-12 info-tour-days">
                        <h5>DAY  6: HO CHI MINH - VUNG TAU - HO CHI MINH  </h5>
                        <p>Transfer to Vung Tau, famous beach in southern Vietnam. On arrival, visit Linh Son Pagoda, White Palace, WOman Rock, Jesus Statue. Relax on beach and return to Ho Chi Minh </p>
                    </div>
                    <div class="col-md-12 location-foot">
                        <div class="col-md-6"></div>
                        <div class="col-md-3">
                            <h6><i class="im-home"></i>Ho Chi Minh</h6>
                        </div>
                        <div class="col-md-3">
                            <h6><i class="im-pacman"></i>1B, 1L, 1D</h6>
                        </div>
                    </div>
                    <div class="col-md-12 info-tour-days">
                        <h5>DAY 7:  HO CHI MINH - DEPARTURE </h5>
                        <p>Trip ends after transfer to airport for departure </p>
                    </div>
                    <div class="col-md-12 location-foot">
                        <div class="col-md-6"></div>
                        <div class="col-md-3">
                            <h6><i class="im-home"></i>No</h6>
                        </div>
                        <div class="col-md-3">
                            <h6><i class="im-pacman"></i>1B</h6>
                        </div>
                    </div>

                    <div class="col-md-12 icon-arrow-up-dow">
                        <div class="col-md-10"></div>
                        <div class="col-md-2">
                            <i class="im-arrow-up3"></i><i class="im-arrow-down2"></i>
                        </div>
                    </div>

                    <div class="col-md-8 wraper-include">
                        <h5>WHAT IS INCLUDED IN THIS  TOUR ?</h5>
                        <div class="col-md-12 info-note-tour">
                            <h5>TOUR TRANSPORT :</h5>
                            <p>Minivan, bus, taxi, public vehicle, private and joint boat, train, mentioned flights ( 20kg luggage allowance)</p>
                        </div>
                        <div class="col-md-12 info-note-tour">
                            <h5>ACCOMMODATION :</h5>
                            <p>7 nights at hotels and 1 night on boat ( twin sharing), 1 night on train ( 4 pers. sharing cabin) </p>
                        </div>
                        <div class="col-md-12 info-note-tour">
                            <h5>INCLUSIVE MEALS :</h5>
                            <p>8 breakfasts, 2 lunches , 2 dinners at local restaurants</p>
                        </div>
                        <div class="col-md-12 info-note-tour">
                            <h5>TOUR TRANSPORT :</h5>
                            <p>Service of English speaking guide/Tour leader + Boat trips in Mekong Delta , Siem Reap , Luang Prabang , Bangkok + Specified sightseeing tour with entrance fees: Siem Reap (Three Day Angkor Pass) - Phnom penh (Tuol Sleng Genocide Museum (S21) and Choeung Ek Killing) - Ho Chi Minh ( Cu Chi Tunnels, War Remnant Museum, Reunification Palace) - Nha Trang ( Ponagar Cham Tower) - Hoian ( Ancient Town, Tra Que Village) - Hue ( Imperial Citadel, Thien Mu Pagoda, Minh Mang Mausoleum) - Danang ( Cham Museum - Hanoi (Ho Chi Minh </p>
                        </div>

                        <div class="col-md-12 icon-arrow-up-dow">
                            <div class="col-md-9"></div>
                            <div class="col-md-3">
                                <i class="im-arrow-up3"></i><i class="im-arrow-down2"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 last-overview">
                        <div class="price-from">
                            <label>PRICE FROM</label>
                            <div class="details-price-from">
                                US$ <sub>1000 </sub><i class="br-info"></i>
                                <h6>Daily Price Fr.: US$ 80</h6>
                            </div>
                        </div>
                        <button type="button" class="btn btn-primary btn-sm">BOOK NOW</button>

                        <img src="components/com_bookpro/assets/images/layout-default-tour/logo.png">

                    </div>
                </div>
<!--end tab overview-->

<!--info tab itinerary-->
                <div role="tabpanel" class="col-md-12 tab-pane fade" id="itinerary" aria-labelledby="itinerary-tab">
                    <div class="border-top">&nbsp;
                        <div class="col-md-7 title-left">
                            <h4>PASSION OF  ASIA</h4>
                            <h5>Vietnam - Laos - Cambodia - Thailand</h5>

                            <div class="col-md-6 this-tour">
                                <h5><i class="im-mail2"></i>E-MAIL THIS TOUR</h5>
                            </div>

                            <div class="col-md-6 this-tour">
                                <h5><i class="en-sharable"></i>BOOK NOW</h5>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="col-md-8 rating">
                                <h5>Traveler Rating</h5>
                                <h1>9.8/10</h1>
                            </div>
                            <div class="col-md-4 icon-request">
                                <i class="im-notebook"></i>
                            </div>
                        </div>

                    </div>

                    <h5 class="big-title">DETAILED ITINERARY</h5>
                    <div class="col-md-12 info-itinerary">
                        <h5>DAY  1: ARRIVAL - HO CHI MINH</h5>
                        <p>ruggle for independence. These extensive tunnels of over 200 km, have specially contained living areas, storage facilities, weapon factories, filed hospitals, command centres, plus accommodation. Today we head make our way to  the Cu Chi Tunnels where we see an exposition hall and a widened section of the tunnels to get a feel of the underground life. The more adventurous may explore the deeper second, and even third level tunnels. There are also  possibilities to fire off rounds from an AK47 or MK16 at the nearby rifle range. Upon leaving Cu Chi we drive back to Ho Chi Minh Town and enjoy seeing some of its essential highlights. We catch a look at the ornate City Hall and the old Opera House before we pay a quick visit the Notre Dame Cathedral and the Old Sai Gon Post Office. Close to these striking Eu</p>
                        <div class="col-md-3"></div>
                        <div class="col-md-4">
                            <h6>OVERNIGHT : HO CHI MINH</h6>
                        </div>
                        <div class="col-md-5">
                            <h6>MEALS: 1DINNER</h6>
                        </div>

                    </div>

                    <div class="col-md-12 info-itinerary">
                        <h5> MEALS : 1BREAKFAST, 1 LUNCH, 1 DINNER</h5>
                        <p>ruggle for independence. These extensive tunnels of over 200 km, have specially contained living areas, storage facilities, weapon factories, filed hospitals, command centres, plus accommodation. Today we head make our way to  the Cu Chi Tunnels where we see an exposition hall and a widened section of the tunnels to get a feel of the underground life. The more adventurous may explore the deeper second, and even third level tunnels. There are also  possibilities to fire off rounds from an AK47 or MK16 at the nearby rifle range. Upon leaving Cu Chi we drive back to Ho Chi Minh Town and enjoy seeing some of its essential highlights. We catch a look at the ornate City Hall and the old Opera House before we pay a quick visit the Notre Dame Cathedral and the Old Sai Gon Post Office. Close to these striking Euruggle for independence. These extensive tunnels of over 200 km, have specially contained living areas, storage facilities, weapon factories, filed hospitals, command centres, plus accommodation. Today we head make our way to  the Cu Chi Tunnels where we see an exposition hall and a widened section of the tunnels to get a feel of the underground life. The more adventurous may explore the deeper second, and even third level tunnels. There are also  possibilities to fire off rounds from an AK47 or MK16 at the nearby rifle range. Upon leaving Cu Chi we drive back to Ho Chi Minh Town and enjoy seeing some of its essential highlights. We catch a look at the ornate City Hall and the old Opera House before we pay a quick visit the Notre Dame Cathedral and the Old Sai Gon Post Office. Close to these striking Eu</p>
                        <div class="col-md-3"></div>
                        <div class="col-md-4">
                            <h6>OVERNIGHT : HO CHI MINH</h6>
                        </div>
                        <div class="col-md-5">
                            <h6> MEALS : 1BREAKFAST, 1 LUNCH, 1 DINNER</h6>
                        </div>

                        <div class="col-md-12 icon-note">
                            <div class="col-md-1 icon">
                                <i class="im-notebook"></i>
                            </div>
                            <div class="col-md-11 note">
                                <p> The  flight time from Danang to Ho Chi Minh City is approx 1 hour and may departs Ho Chi Minh in early morning. Therefore , a breakfast box is required and pre-payement of personal services at hotel need to be paid the day before.  The transfer from Hoi An to the airport in Danang is 45 minutes on good road conditions, plus stop time to visit the Marble Mountain. </p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12 info-itinerary">
                        <h5>OVERNIGHT : CAN THO</h5>
                        <p>ruggle for independence. These extensive tunnels of over 200 km, have specially contained living areas, storage facilities, weapon factories, filed hospitals, command centres, plus accommodation. Today we head make our way to  the Cu Chi Tunnels where we see an exposition hall and a widened section of the tunnels to get a feel of the underground life. The more adventurous may explore the deeper second, and even third level tunnels. There are also  possibilities to fire off rounds from an AK47 or MK16 at the nearby rifle range. Upon leaving Cu Chi we drive back to Ho Chi Minh Town and enjoy seeing some of its essential highlights. We catch a look at the ornate City Hall and the old Opera House before we pay a quick visit the Notre Dame Cathedral and the Old Sai Gon Post Office. Close to these striking Eu</p>
                        <div class="col-md-3"></div>
                        <div class="col-md-4">
                            <h6>OVERNIGHT : HO CHI MINH</h6>
                        </div>
                        <div class="col-md-5">
                            <h6>MEALS: 1DINNER</h6>
                        </div>

                    </div>


                    <div class="col-md-12 info-itinerary">
                        <h5>DAY  1: ARRIVAL - HO CHI MINH</h5>
                        <p>ruggle for independence. These extensive tunnels of over 200 km, have specially contained living areas, storage facilities, weapon factories, filed hospitals, command centres, plus accommodation. Today we head make our way to  the Cu Chi Tunnels where we see an exposition hall and a widened section of the tunnels to get a feel of the underground life. The more adventurous may explore the deeper second, and even third level tunnels. There are also  possibilities to fire off rounds from an AK47 or MK16 at the nearby rifle range. Upon leaving Cu Chi we drive back to Ho Chi Minh Town and enjoy seeing some of its essential highlights. We catch a look at the ornate City Hall and the old Opera House before we pay a quick visit the Notre Dame Cathedral and the Old Sai Gon Post Office. Close to these striking Eu</p>
                        <div class="col-md-3"></div>
                        <div class="col-md-4">
                            <h6>OVERNIGHT : HO CHI MINH</h6>
                        </div>
                        <div class="col-md-5">
                            <h6>MEALS: 1DINNER</h6>
                        </div>

                    </div>

                    <div class="col-md-12 icon-arrow-up-dow">
                        <div class="col-md-9"></div>
                        <div class="col-md-3">
                            <i class="im-arrow-up3"></i><i class="im-arrow-down2"></i>
                        </div>
                    </div>

                </div>
<!--end tab itinerary-->


<!--tab price-date-->
                <div role="tabpanel" class="col-md-12 tab-pane fade" id="price-date" aria-labelledby="price-date-tab">
                    <div class="border-top">&nbsp;
                        <div class="col-md-7 title-left">
                            <h4>PASSION OF  ASIA</h4>
                            <h5>Vietnam - Laos - Cambodia - Thailand</h5>

                            <div class="col-md-6 this-tour">
                                <h5><i class="im-mail2"></i>E-MAIL THIS TOUR</h5>
                            </div>

                            <div class="col-md-6 this-tour">
                                <h5><i class="en-sharable"></i>BOOK NOW</h5>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="col-md-8 rating">
                                <h5>Traveler Rating</h5>
                                <h1>9.8/10</h1>
                            </div>
                            <div class="col-md-4 icon-request">
                                <i class="im-notebook"></i>
                            </div>
                        </div>

                    </div>
                    <div class="col-md-12 date-input">
                        <div class="col-md-4 date-input-childen">
                            <div class="col-md-2 date"><i class="im-calendar"></i></div>
                            <div class="col-md-10 input"><input type="text" class="form-control input-sm" id="exampleInputName2" placeholder="Jane Doe"></div>
                        </div>
                        <div class="col-md-4 date-input-childen">
                            <div class="col-md-2 date"><i class="im-calendar"></i></div>
                            <div class="col-md-10 input"><input type="text" class="form-control input-sm" id="exampleInputName2" placeholder="Jane Doe"></div>
                        </div>
                        <div class="col-md-4 date-input-childen">
                            <div class="col-md-2 date"><i class="im-calendar"></i></div>
                            <div class="col-md-10 input"><input type="text" class="form-control input-sm" id="exampleInputName2" placeholder="Jane Doe"></div>
                        </div>
                    </div>

                    <div class="col-md-12 table">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>GROUP</th>
                                <th>START DATE</th>
                                <th>FINISH DATE</th>
                                <th>SER CLASS</th>
                                <th>PRICE FROM</th>
                                <th>STATUS</th>
                                <th>BOOK</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>

                                <td>PAD1267</td>
                                <td>16 Jul, 2015</td>
                                <td>26 Jul, 2015</td>
                                <td>Deluxe</td>
                                <td>US$ 1700 <i class="br-info"></i><i class="im-fire"></i></td>
                                <td>Available</td>
                                <td><i class="im-arrow-right2"></i></td>
                            </tr>
                            <tr>

                                <td>PAD1267</td>
                                <td>16 Jul, 2015</td>
                                <td>26 Jul, 2015</td>
                                <td>Deluxe</td>
                                <td>US$ 1700 <i class="br-info"></i></td>
                                <td>Available</td>
                                <td><i class="im-arrow-right2"></i></td>
                            </tr>
                            <tr>

                                <td>PAD1267</td>
                                <td>16 Jul, 2015</td>
                                <td>26 Jul, 2015</td>
                                <td>Deluxe</td>
                                <td>US$ 1700 <i class="br-info"></i></td>
                                <td>Available</td>
                                <td><i class="im-arrow-right2"></i></td>
                            </tr>
                            <tr>

                                <td>PAD1267</td>
                                <td>16 Jul, 2015</td>
                                <td>26 Jul, 2015</td>
                                <td>Deluxe</td>
                                <td>US$ 1700 <i class="br-info"></i></td>
                                <td>Available</td>
                                <td><i class="im-arrow-right2"></i></td>
                            </tr>
                            <tr>

                                <td>PAD1267</td>
                                <td>16 Jul, 2015</td>
                                <td>26 Jul, 2015</td>
                                <td>Deluxe</td>
                                <td>US$ 1700 <i class="br-info"></i></td>
                                <td>Available</td>
                                <td><i class="im-arrow-right2"></i></td>
                            </tr>
                            <tr>

                                <td>PAD1267</td>
                                <td>16 Jul, 2015</td>
                                <td>26 Jul, 2015</td>
                                <td>Deluxe</td>
                                <td>US$ 1700 <i class="br-info"></i></td>
                                <td>Available</td>
                                <td><i class="im-arrow-right2"></i></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="col-md-3 table-option">
                            <h5>REFINE SERVICE <i class="im-paragraph-justify"></i></h5>
                    </div>
                    <div class="col-md-3 table-option">
                         <h5>REFINE SERVICE <i class="im-calendar"></i></h5>
                    </div>
                    <div class="col-md-3 table-option">
                        <h5>REFINE SERVICE <i class="im-fire"></i></h5>
                    </div>
                    <div class="col-md-3 table-option" style="text-align: right;">
                         <div class="col-md-12"><i class="im-arrow-up2"></i></div>
                         <div class="col-md-12"><i class="im-paragraph-justify"></i></div>
                         <div class="col-md-12"><i class="im-arrow-down"></i></div>

                    </div>

                    <div class="col-md-12 icon-note-price-date" style="margin-top: 10px !important;">
                        <div class="col-md-1 icon">
                            <i class="im-notebook"></i>
                        </div>
                        <div class="col-md-11 note">
                            <h6>TOUR PRICE NOTICE</h6>
                            <p> The  flight time from Danang to Ho Chi Minh City is approx 1 hour and may departs Ho Chi Minh in early morning. Therefore , a breakfast box is required and pre-payement of personal services at hotel need to be paid the day before.  The transfer from Hoi An to the airport in Danang is 45 minutes on good road conditions, plus stop time to visit the Marble Mountain. </p>
                        </div>
                    </div>

                    <div class="col-md-12 icon-note-price-date" style="margin-top: 10px !important;">
                        <div class="col-md-1 icon">
                            <i class="im-notebook"></i>
                        </div>
                        <div class="col-md-11 note">
                            <h6>TOUR PRICE NOTICE</h6>
                            <p> The  flight time from Danang to Ho Chi Minh City is approx 1 hour and may departs Ho Chi Minh in early morning. Therefore , a breakfast box is required and pre-payement of personal services at hotel need to be paid the day before.  The transfer from Hoi An to the airport in Danang is 45 minutes on good road conditions, plus stop time to visit the Marble Mountain. </p>
                        </div>
                    </div>

                    <div class="col-md-6 class-tour">
                        <h5>WHERE TO STAY ON  DELUXE CLASS TOUR ?</h5>
                    </div>

                    <div class="col-md-6 class-tour last-child">ss
                        <h5>CHANGE CLASS<i class="im-paragraph-justify"></i></h5>
                    </div>

                    <div class="col-md-6 class-tour-info-wrapper">
                        <div class="col-md-12 class-tour-info">
                            <div class="col-md-6 img">
                           <img src="components/com_bookpro/assets/images/layout-default-tour/pic-alo.png" >
                            </div>
                                <div class="col-md-6 info">
                                    <h5 class="hanoi">HANOI</h5>
                                    <div class="rating">
                                        <h6>OUR RATING</h6>
                                        <h5 class="deluxe">deluxe</h5>
                                    </div>
                                    <div class="star">
                                        <h1>5</h1><h5>STARS</h5><i class="im-calendar"></i> </p>
                                    </div>
                            </div>
                        </div>

                        <div class="col-md-12 class-tour-info">
                            <div class="col-md-6 img">
                                <img src="components/com_bookpro/assets/images/layout-default-tour/pic-alo.png" >
                            </div>
                            <div class="col-md-6 info">
                                <h5 class="hanoi">HANOI</h5>
                                <div class="rating">
                                    <h6>OUR RATING</h6>
                                    <h5 class="deluxe">deluxe</h5>
                                </div>
                                <div class="star">
                                    <h1>5</h1><h5>STARS</h5><i class="im-calendar"></i> </p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12 class-tour-info">
                            <div class="col-md-6 img">
                                <img src="components/com_bookpro/assets/images/layout-default-tour/pic-alo.png" >
                            </div>
                            <div class="col-md-6 info">
                                <h5 class="hanoi">HANOI</h5>
                                <div class="rating">
                                    <h6>OUR RATING</h6>
                                    <h5 class="deluxe">deluxe</h5>
                                </div>
                                <div class="star">
                                    <h1>5</h1><h5>STARS</h5><i class="im-calendar"></i> </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 class-tour-info-wrapper-last">
                        <div class="col-md-12 class-tour-info">
                            <div class="col-md-6 img">
                                <img src="components/com_bookpro/assets/images/layout-default-tour/pic-alo.png" >
                            </div>
                            <div class="col-md-6 info">
                                <h5 class="hanoi">HANOI</h5>
                                <div class="rating">
                                    <h6>OUR RATING</h6>
                                    <h5 class="deluxe">deluxe</h5>
                                </div>
                                <div class="star">
                                    <h1>5</h1><h5>STARS</h5><i class="im-calendar"></i> </p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12 class-tour-info">
                            <div class="col-md-6 img">
                                <img src="components/com_bookpro/assets/images/layout-default-tour/pic-alo.png" >
                            </div>
                            <div class="col-md-6 info">
                                <h5 class="hanoi">HANOI</h5>
                                <div class="rating">
                                    <h6>OUR RATING</h6>
                                    <h5 class="deluxe">deluxe</h5>
                                </div>
                                <div class="star">
                                    <h1>5</h1><h5>STARS</h5><i class="im-calendar"></i> </p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12 class-tour-info">
                            <div class="col-md-6 img">
                                <img src="components/com_bookpro/assets/images/layout-default-tour/pic-alo.png" >
                            </div>
                            <div class="col-md-6 info">
                                <h5 class="hanoi">HANOI</h5>
                                <div class="rating">
                                    <h6>OUR RATING</h6>
                                    <h5 class="deluxe">deluxe</h5>
                                </div>
                                <div class="star">
                                    <h1>5</h1><h5>STARS</h5><i class="im-calendar"></i> </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12 icon-note-price-date">
                        <div class="col-md-1 icon">
                            <i class="im-notebook"></i>
                        </div>
                        <div class="col-md-11 note">
                            <h6>TOUR PRICE NOTICE</h6>
                            <p> The  flight time from Danang to Ho Chi Minh City is approx 1 hour and may departs Ho Chi Minh in early morning. Therefore , a breakfast box is required and pre-payement of personal services at hotel need to be paid the day before.  The transfer from Hoi An to the airport in Danang is 45 minutes on good road conditions, plus stop time to visit the Marble Mountain. </p>
                        </div>
                    </div>
                </div>
<!--end tab price date-->

<!---tab document-->
                <div role="tabpanel" class="col-md-12 tab-pane fade" id="documents" aria-labelledby="documents-tab">
                    <div class="border-top">&nbsp;
                        <div class="col-md-7 title-left">
                            <h4>PASSION OF  ASIA</h4>
                            <h5>Vietnam - Laos - Cambodia - Thailand</h5>

                            <div class="col-md-6 this-tour">
                                <h5><i class="im-mail2"></i>E-MAIL THIS TOUR</h5>
                            </div>

                            <div class="col-md-6 this-tour">
                                <h5><i class="en-sharable"></i>BOOK NOW</h5>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="col-md-8 rating">
                                <h5>Traveler Rating</h5>
                                <h1>9.8/10</h1>
                            </div>
                            <div class="col-md-4 icon-request">
                                <i class="im-notebook"></i>
                            </div>
                        </div>

                    </div>

                    <div class="col-md-7">
                        <img src="components/com_bookpro/assets/images/layout-default-tour/qa.png">
                    </div>
                    <div class="col-md-5 info-right">
                        <div class="col-md-12 download-document">
                            <h6>DOWNLOAD DOCUMENT</h6>
                            <h3>CLICK</h3>
                        </div>

                        <div class="col-md-12 readings">
                            <h6><i class="en-book"></i> Recommended Readings</h6>
                        </div>

                        <div class="col-md-12 list">
                            <ul>
                                <li>1.Story of Vietnam</li>
                                <li>2. Learn Thai language</li>
                                <li>3. Angkor History</li>
                                <li>4. Laos Culture</li>
                            </ul>
                        </div>

                        <div class="col-md-12 readings">
                            <h6><i class="br-info"></i>Thailand  Travel Guide</h6>
                        </div>

                        <div class="col-md-12 readings">
                            <h6><i class="br-info"></i> Laos Travel Guide</h6>
                        </div>
                        <div class="col-md-12 readings">
                            <h6><i class="br-info"></i> Cambodia  Travel Guide</h6>
                        </div>
                    </div>


                    <div class="col-md-12 readings">
                        <h6>FREQUENT ASK QUESTIONS</h6>
                    </div>
                    <div class="col-md-12 info-question">
                        <h6>Can i have an appointement call to an expert in person?</h6>
                        <p>Yes! We welcome the opportunity to speak to our experts . To have a call appointment, you just need to fill up the enquiry form  with  the subject you are planning to discuss. Our expert will call you in soonest time.
                        </p>
                    </div>

                    <div class="col-md-12 info-question">
                        <h6>What is the destination expert ?</h6>
                        <p>Our expert provide a unique holiday-planning service. Each expert is focused on a specific destination so you get the benefit of their knowledge and experience. Our expert travel extensively to their destinations - they speak from personal experience. You will have contact with the same specialist throughout the planning </p>
                    </div>

                    <div class="col-md-12 info-question">
                        <h6>Will i be travelling in a part of group ?</h6>
                        <p>No, our tailor-made trips are arranged on an individual basis. This means you are not tied to the wishes of a group and can arrange your itinerary at your own pace. If you are interested in a Group Tour, we do offer a range of departures in small groups of up to 16 people. </p>
                    </div>

                    <div class="col-md-12 info-question">
                        <h6>Can i have an appointement call to an expert in person?</h6>
                        <p>Yes! We welcome the opportunity to speak to our experts . To have a call appointment, you just need to fill up the enquiry form  with  the subject you are planning to discuss. Our expert will call you in soonest time.
                        </p>
                    </div>

                    <div class="col-md-12 info-question">
                        <h6>Can i have an appointement call to an expert in person?</h6>
                        <p>Many of our clients like the independence that tailor-made travel brings, without the 'regimentation' of organised group tours, but with the reassurance of our professional planning and back-up services. For example, if the flight that we've booked is delayed, it's down to us to re-arrange your travel in the country.
                            When things go wrong, an independent traveller may find themselves putting in a good deal of time re-organising their plans, when they should be enjoying the trip. We also regularly hear from travellers claiming that they can independently organise the same trip for a lower price. Almost invariably it is not 'like for like' and they may find they incur much more expense during their travels. </p>
                    </div>
                </div>
<!---end tab document-->

            </div>

        </div>
    </div>

    <div class="col-md-4">
        <div class="col-md-12 holiday">
            <img src="components/com_bookpro/assets/images/layout-default-tour/bg-holiday.png" >
            <p>Single click to tell us what you have in mind and your budget. We will do the rest to build a holiday for your private adventure style.</p>
        </div>

        <div class="col-md-12 win-up-to">
            <h2>Win Up To US$ 1000</h2>
            <img src="components/com_bookpro/assets/images/layout-default-tour/fun.png" >
        </div>

        <div class="col-md-12 relative-tour">
            <h5>RELATED TOURS</h5>
            <div class="col-md-12 glimpse">
                <div class="col-md-4 img-glimpse">
                    <img class="img-thumbnail" src="components/com_bookpro/assets/images/layout-default-tour/halong.png">
                </div>
                <div class="col-md-8 glimpse-info">
                    <h6>Glimpse of Vietnam, 8 Days</h6>
                    <div class="col-md-6 icon-star">
                        <i class="fa-star"></i>
                        <i class="fa-star"></i>
                        <i class="fa-star"></i>
                        <i class="fa-star"></i>
                        <i class="fa-star"></i>
                        <p>Fr, <b>1192</b><sup> US$</sup> </p>
                    </div>
                    <div class="col-md-6 reviews">
                        <h6>115 Reviews</h6>
                        <div class="booking-online">Book Online</div>
                    </div>
                </div>

                <div class="col-md-12 travelling">
                    <h6>Travelling arround Vietnam to catch impressive images of Hanoi Cappital City, ancient Hue Town, enchanting Hoian</h6>
                </div>
            </div>

            <div class="col-md-12 glimpse">
                <div class="col-md-4 img-glimpse">
                    <img class="img-thumbnail" src="components/com_bookpro/assets/images/layout-default-tour/halong.png">
                </div>
                <div class="col-md-8 glimpse-info">
                    <h6>Glimpse of Vietnam, 8 Days</h6>
                    <div class="col-md-6 icon-star">
                        <i class="fa-star"></i>
                        <i class="fa-star"></i>
                        <i class="fa-star"></i>
                        <i class="fa-star"></i>
                        <i class="fa-star"></i>
                        <p>Fr, <b>1192</b><sup> US$</sup> </p>
                    </div>
                    <div class="col-md-6 reviews">
                        <h6>115 Reviews</h6>
                        <div class="booking-online">Book Online</div>
                    </div>
                </div>

                <div class="col-md-12 travelling">
                    <h6>Travelling arround Vietnam to catch impressive images of Hanoi Cappital City, ancient Hue Town, enchanting Hoian</h6>
                </div>
            </div>

            <div class="col-md-12 glimpse">
                <div class="col-md-4 img-glimpse">
                    <img class="img-thumbnail" src="components/com_bookpro/assets/images/layout-default-tour/halong.png">
                </div>
                <div class="col-md-8 glimpse-info">
                    <h6>Glimpse of Vietnam, 8 Days</h6>
                    <div class="col-md-6 icon-star">
                        <i class="fa-star"></i>
                        <i class="fa-star"></i>
                        <i class="fa-star"></i>
                        <i class="fa-star"></i>
                        <i class="fa-star"></i>
                        <p>Fr, <b>1192</b><sup> US$</sup> </p>
                    </div>
                    <div class="col-md-6 reviews">
                        <h6>115 Reviews</h6>
                        <div class="booking-online">Book Online</div>
                    </div>
                </div>

                <div class="col-md-12 travelling">
                    <h6>Travelling arround Vietnam to catch impressive images of Hanoi Cappital City, ancient Hue Town, enchanting Hoian</h6>
                </div>
            </div>


        </div>

        <div class="col-md-12 need-some-help">
            <div class="title-help">
                <h5>NEED SOME HELP ?</h5>
            </div>

            <div class="instant-support">
                <h6><i class="im-plus"></i>Instant Support :</h6>
                <div class="info-support">
                    <h6><i class="im-phone"></i>Speak to our expert: 1-866-592-9685 </h6>
                    <h6><i class="st-chat-2"></i>Chat online with our travel expert</h6>
                    <h6><i class="im-mail"></i>Request  for more tour  information </h6>
                </div>
            </div>

            <div class="office-hours">
                <h6><i class="im-plus"></i>Office Hours :</h6>
                <div class="info-office-hours">
                    The office  is open from 8h00 to 18h00, Mon.  through Fri. and 8h00 to 11h30 on  Sat,  24h/7 hotline  support
                </div>
            </div>

            <div class="community">
                <h6><i class="im-plus"></i>Community:</h6>
                <div class="info-support">
                    <textarea cols="42" rows=""></textarea>
                </div>
            </div>
        </div>

    </div>
</div>