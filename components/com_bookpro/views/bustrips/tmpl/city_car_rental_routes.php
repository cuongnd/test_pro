<?php
$number_show=5;
?>
<div class="noo-slider" id="car-rental-routes">
    <div class="noo-slider-control">
        <a id="btn_next_car_rental_routes" href="javascript:void(0)" class="noo-slider-btn btn-next">Next</a>
        <a id="btn_prev_car_rental_routes" href="javascript:void(0)" class="noo-slider-btn btn-prev">Prev</a>
    </div>
    <div id="wapper" class="noo-slider-wapper" style="height: 145px">
        <ul class="noo-slider-inner">
            <?php for($i=0;$i<count($this->car_retal_routes);$i=$i+$number_show){ ?>
                <li  class="noo-slider-item">
                    <?php for($j=0;$j<$number_show;$j++){ ?>
                    <?php
                        $item=$this->car_retal_routes[$i+$j];
                    ?>
                    <div class="row-fluid bestdeal-row">
                        <div class="span5">
                            ha noi
                        </div>
                        <div class="span5">
                            can tho
                        </div>
                        <div class="span2">
                            4 offer
                        </div>
                    </div>
                    <?php } ?>
                </li>
            <?php } ?>
        </ul>

    </div>
</div>
