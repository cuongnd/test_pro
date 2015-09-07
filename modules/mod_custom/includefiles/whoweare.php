<?php
$doc=JFactory::getDocument();



$doc->addScript(JUri::root().'/media/system/js/jQuery-Amazing-Scrolling-Presentation-Plugin-scrolldeck/js/jquery.scrollTo-1.4.3.1.min.js');
$doc->addScript(JUri::root().'/media/system/js/jQuery-Amazing-Scrolling-Presentation-Plugin-scrolldeck/js/jquery.easing.1.3.js');
$doc->addScript(JUri::root().'/media/system/js/jQuery-Amazing-Scrolling-Presentation-Plugin-scrolldeck/js/jquery.scrollorama.js');
$doc->addScript(JUri::root().'/media/system/js/jQuery-Amazing-Scrolling-Presentation-Plugin-scrolldeck/js/jquery.scrolldeck.js');
$doc->addStyleSheet(JUri::root().'/media/system/css/ionicons.min.css');
$user=JFactory::getUser();
?>

<div id="why_choose_row_inner2" class="slide" style="position: relative">
    <h3  class="row_title dark  e-change-lang " data-scroll-reveal-id="6"  ><strong>Why</strong> Choose Shape5 and Join?</h3>
    <div  class="why_choose_box animate-in"  data-animation="fly-in-left"    >
        <div class="why_choose_icon one"><span class="ion-ios7-timer-outline icon-whychoose-one"></span></div>
        <div class="why_choose_text">
            <div class="why_choose_text_inner  e-change-lang ">
                <h3 class="">Get Up and Running Fast</h3>
                Setup a website in minutes with our site shaper installations. Simply download and install a site shaper as you would a regular CMS installation, and you can have a copy of our demo running on your servers in a matter of minutes.
            </div>
        </div>
    </div>
    <div  class="why_choose_box animate-in"  data-animation="fly-in-right"    >
        <div class="why_choose_icon two"><span class="ion-ios7-people icon-whychoose-two"></span></div>
        <div class="why_choose_text">
            <div class="why_choose_text_inner  e-change-lang ">
                <h3>A Large Community of Users</h3>
                There are currently  happy community members and counting. We've been in this industry for many years, and we listen to what our members are looking for in design trends, features and more. Join our large and helpful community today!
            </div>
        </div>
    </div>
    <div style="clear:both;height:0px;"></div>
    <div  class="why_choose_box animate-in"  data-animation="fly-in-left"    >
        <div class="why_choose_icon three"><span class="ion-ios7-help-empty icon-whychoose-three"></span></div>
        <div class="why_choose_text">
            <div class="why_choose_text_inner  e-change-lang ">
                <h3>The Very Best Customer Support</h3>
                Shape5 has built a great reputation for customer service. We work hard to help you with questions you may encounter, so that you do not get stuck with a product you cannot use. Our support staff has years of experience to help you along the way.
            </div>
        </div>
    </div>
    <div  class="why_choose_box  animate-in" data-scroll-reveal-id="10" data-animation="fly-in-right"  >
        <div class="why_choose_icon four"><span class="ion-ios7-cog icon-whychoose-four"></span></div>
        <div class="why_choose_text">
            <div class="why_choose_text_inner  e-change-lang ">
                <h3>Free Hire a Coder Program</h3>
                Hire a Coder is a place where anyone can post custom coding jobs for free, and those with developer members can bid and help out other members. Also it's a great way for developer members to make some extra money.
            </div>
        </div>
    </div>
    <div style="clear:both;height:0px;"></div>
    <div  class="why_choose_box  animate-in"  data-animation="fly-in-left"  >
        <div class="why_choose_icon five"><span class="ion-ios7-star icon-whychoose-five"></span></div>
        <div class="why_choose_text">
            <div class="why_choose_text_inner  e-change-lang ">
                <h3>Use The Products For Life</h3>
                With a membership approach, you can download any product you want, without being tied to purchasing a specific design. This not subscription based, once your membership expires you can still use the products as normal with no limitations or fees.
            </div>
        </div>
    </div>
    <div  class="why_choose_box  animate-in"  data-animation="fly-in-right"  >
        <div class="why_choose_icon six"><span class="ion-ios7-star icon-whychoose-five"></span></div>
        <div class="why_choose_text">
            <div class="why_choose_text_inner  e-change-lang ">
                <h3>Free Framework Updates</h3>
                Almost all updates to our themes and templates happen within the Vertex Framework. Any Vertex powered theme or template can be updated to the latest framework version completely free at any time, despite your current membership status.
            </div>
        </div>
    </div>



    <div style="clear:both;height:0px;"></div>
</div>


<script type="text/javascript">
    jQuery(document).ready(function($) {

        var deck = new $.scrolldeck({
            buttons: '.nav-button',
            easing: 'easeInOutExpo'
        });

    });
</script>
<style type="text/css">
    .home-page
    {
        position: relative;
    }
    /* why choose row */
    #why_choose_row {background:#FFFFFF;}
    .why_choose_box .why_choose_icon, .why_choose_box img {float:left;}
    .why_choose_box {width:44%;padding-right:7%;padding-left:3%;padding-right:3%;float:left;margin-top:20px;margin-bottom:30px;}
    .why_choose_text {overflow:hidden;}
    .why_choose_text h3 {font-weight:300;font-size:1.3em;margin:0px;margin-bottom:18px;margin-top:14px;}
    #why_choose_row_inner {padding-bottom:64px;}


    .why_choose_icon {height:48px;width:48px;margin-right:31px;}

    .why_choose_icon.one {background:#ffcb5b;border:2px solid #efbe55;}
    .why_choose_icon.two {background:#7dc6d9;border:2px solid #61afc3;}
    .why_choose_icon.three{background:#96ceb5;border:2px solid #84bea5;}
    .why_choose_icon.four{background:#ff6e69;border:2px solid #dd645c;}
    .why_choose_icon.five{background:#bd9ad2;border:2px solid #a786bb;}
    .why_choose_icon.six{background:#f0b66c;border:2px solid #e0a45e;}


    .icon-whychoose-one { color: #ffffff;font-size: 2.5em;margin-left: 9px; position: relative; top: 5px;}
    .icon-whychoose-two { color: #ffffff;font-size: 2.4em;margin-left: 9px;position: relative;top: 5px;}
    .icon-whychoose-three { color: #ffffff;font-size: 3.9em;margin-left: 17px;position: relative;top: -5px;}
    .icon-whychoose-four { color: #ffffff;font-size: 2.3em;margin-left: 12px;position: relative; top: 8px;}
    .icon-whychoose-five { color: #ffffff;font-size: 2em;margin-left: 12px;position: relative; top: 9px;}
    .icon-whychoose-six { color: #ffffff;font-size: 2em;margin-left: 12px;position: relative; top: 9px;}


    @media screen and (max-width: 970px){
        .why_choose_box {width:94%;}
    }

    #why_choose_row_inner2
    {
        position: relative !important;
        top: 0px !important;
    }
    @media screen and (max-width: 1025px){
        #quotes_row, #get_more_row, #latest_releases_row {background-attachment: scroll;}
    }
    .why_choose_icon
    {
        height: 48px;
        margin-right: 31px;
        width: 48px;
        border-radius: 100px;
    }
</style>
