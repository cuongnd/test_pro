<?php
$doc=JFactory::getDocument();
$doc->addScript(JUri::root().'/media/system/js/ion.rangeSlider-1.9.1/js/ion-rangeSlider/ion.rangeSlider.min.js');
$doc->addScript(JUri::root().'/modules/mod_custom/includefiles/script.js');
$doc->addStyleSheet(JUri::root().'/media/system/js/ion.rangeSlider-1.9.1/css/ion.rangeSlider.css');
$user=JFactory::getUser();
?>
<div class="full-width-image-banner--become-an-author" id="top">
    <div class="container">
        <h1 class="e-change-lang">Become an author</h1>
        <h2 class="e-change-lang">Sell your work to millions of buyers on the websitetemplatepro.com Marketplaces</h2>
        <p class="e-change-lang">
            websitetemplatepro.com's top authors make over $20,000 per month <br>
            selling digital products and stock to a thriving community of over<br>
            4,094,254 users across eight marketplaces and counting.
        </p>
        <div class="full-width-image-banner__call-to-action">
            <a class="btn btn-primary" href="index.php?option=com_virtuemart&controller=user&task=becomevendor"><?php if(!$user->id){  ?> Sign in to become an author <?php }else{ ?> Become an author <?php } ?></a>
        </div>
    </div>
</div>
<div class="static-page__heading" id="payment_rates">
    <div class="static-page__sub-header">
        <h2 class="e-change-lang">Calculating your payment rates</h2>
        <h4 class="e-change-lang">As an author, you'll receive up to 70% of the sale price on your items</h4>
    </div>

    <p class="e-change-lang">
        Every time an item is sold on the websitetemplatepro.com Marketplaces a percentage of the sale
        price goes to the author. The amount is determined by whether the author is
        selling the item only on the websitetemplatepro.com Marketplaces and by the total cumulative sales they
        have had in the past.
    </p>

</div>
<div class="panel panel-primary l-become-author__exclusivity-rate">
    <div class="panel-heading e-change-lang">Non-Exclusive author</div>
    <div class="panel-body row">
        <div class="l-become-author__exclusivity-rate col-sm-6">
            <h1>33%</h1>
            <h4 class="e-change-lang">as a non-exclusive author</h4>
        </div>

        <div class="l-become-author__exclusivity-details col-sm-6">
            <p class="e-change-lang">You can sell the items you sell with us in other places too.</p>
        </div>
    </div>

    <!-- Table -->
</div>


<div class="static-page__heading--secondary">
    <h4>or</h4>
</div>
<div class="panel panel-primary l-become-author__exclusivity-rate">
    <div class="panel-heading e-change-lang"> Exclusive author</div>
    <div class="panel-body">
        <div class="row">
            <div class="l-become-author__exclusivity-rate col-sm-6">
                <h1>50 - 70%</h1>
                <h4 class="e-change-lang">
                    as an exclusive author<br>
                    (based on sales volume)
                </h4>
            </div>

            <div class="l-become-author__exclusivity-details e-change-lang col-sm-6">
                <p>As an exclusive author, any items you sell on websitetemplatepro.com's marketplaces cannot be sold elsewhere. You can still sell other items elsewhere of course. Over 80% of our authors are exclusive.</p>
            </div>
        </div>
        <div class="row info-box__body--secondary">
            <div data-view="authorCommission" class="l-become-author__rates">
                <p class="e-change-lang">
                    With a total cumulative sales of <strong class="js-rates-volume">$2,000</strong> your commission rate will be <strong class="js-rates-commission">50%</strong>
                    <br>
                    Your commission rate will adjust according to your earnings amount:
                </p>
                <div class="l-become-author__rates-slider">
                    <input id="author-rates-slider" class="is-hidden" type="text" data-hideminmax="false" data-type="single" data-from="37500" data-step="100" data-view="rangeSlider" value="37500" name="author-rates-slider" style="display: none;">                </div>
            </div>

            <div class="l-become-author__rates-schedule">
                <a target="_blank" href="//d2mdw063ttlqtq.cloudfront.net/resources/rates/websitetemplatepro.com_marketplaces_payment_rates_schedule_for_exlcusive_authors_(oct_2013).pdf">
                    <i class="glyph--document-pdf"></i>Download the rates schedule (2013)
                </a>
            </div>
        </div>

    </div>

</div>


<div class="static-page__call-to-action">
    <a class="btn btn-info" href="index.php?option=com_virtuemart&controller=user&task=becomevendor"><?php if(!$user->id){  ?> Sign in to become an author <?php }else{ ?> Become an author <?php } ?></a></a>
</div>
