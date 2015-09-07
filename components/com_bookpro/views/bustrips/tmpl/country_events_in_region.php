<div class="row-fluid right-content">
    <div id="events_in_region">
        <div class="noo-slider-control">
            <a id="btn_next_event" href="javascript:void(0)" class="noo-slider-btn btn-next">Next</a>
            <a id="btn_prev_event" href="javascript:void(0)" class="noo-slider-btn btn-prev">Prev</a>
        </div>
        <div id="wapper" class="noo-slider-wapper">
            <ul class="noo-slider-inner">
                <?php foreach($this->top_destination as $item){ ?>
                    <li  class="noo-slider-item">
                        <div class="noo-content-slider">
                            <div class="noo-slider-image">
                                image
                            </div>
                            <div class="noo-slider-info">
                                <h2>
                            <span>
                                tieu de
                            </span>
                                </h2>
                                <div class="content">
                                    content
                                </div>
                            </div>
                        </div>
                    </li>
                <?php } ?>
            </ul>

        </div>
    </div>

</div>

