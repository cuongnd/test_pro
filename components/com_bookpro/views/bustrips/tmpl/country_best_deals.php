<div id="best-deals">
    <div class="noo-slider-control">
        <a id="btn_next" href="javascript:void(0)" class="noo-slider-btn btn-next">Next</a>
        <a id="btn_prev" href="javascript:void(0)" class="noo-slider-btn btn-prev">Prev</a>
    </div>
    <div id="wapper" class="noo-slider-wapper">
        <ul class="noo-slider-inner">
            <?php foreach ($this->top_destination as $item) { ?>
                <li class="noo-slider-item">
                    <div class="row-fluid bestdeal-row">
                        <div class="span5">
                            <div class="deal-thumbnail">
                                <div class="deal-price">
                                    <span class="dealprice">US$1200</span>
                                    <span class="deal-pers"> /pers</span>
                                </div>
                                <a href="/index.php?option=com_bookpro&amp;controller=tour&amp;view=tour&amp;id=38&amp;Itemid=111">
                                    <img src="<?php echo JUri::root() . '/' . $item->dest_image ?>" alt="">
                                </a>
                            </div>
                        </div>
                        <div class="span7 content-bestdeals">
                            <div class="contair-bestdeals">
                                <h3 class="bestdeal-title">
                                    <a href="/index.php?option=com_bookpro&amp;controller=tour&amp;view=tour&amp;id=38&amp;Itemid=111">Grand
                                        Indochina</a>

                                </h3>

                                <div class="bestdeal-desc">

                                    <div>
                                        from Siem Riep, Cambodia
                                    </div>
                                    <div>
                                        to Da Nang, Vietnam
                                    </div>
                                </div>
                            </div>
                            <div>
                                <a class="view-details text-right" href="#">View Details</a>
                            </div>

                        </div>
                    </div>

                </li>
            <?php } ?>
        </ul>

    </div>
</div>
