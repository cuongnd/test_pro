<style>
.menu-icon{
    border:1px solid gray;
}
.tour-manager{
    background:#EDEDEE;
    padding:10px;
    margin:10px;
    border:1px solid #EDEDEE;
    border-top-left-radius: 10px;
    border-top-right-radius: 10px;
}
img{

}
a:hover{
    text-decoration: none;
    color:#10223E;
}
    .icon{
        border:3px solid gray;
        border-radius: 10px;
        margin-top:10px;
        text-align:center;
    }
label{
    font-weight: bold;
    font-size: 17px;
    text-align:center;
    margin-top:5px ;
}
</style>

<div class="container">
    <div class="row-fluid">
                <h2 class="tour-manager">Tour Manager</h2>
                <div class="span2 icon">
                    <a href="<?php echo JUri::root() ?>administrator/index.php?option=com_tour&view=tours">
                    <img class="img-rounded" src="<?php echo JUri::root() ?>administrator/components/com_tour/asset/images/icons/tour.png" />
                    <label>Tour</label>
                    </a>
                </div>
                <div class="span2 icon">
                    <a href="<?php echo JUri::root() ?>administrator/index.php?option=com_tour&view=tourtariffs">
                    <img class="img-rounded" src="<?php echo JUri::root() ?>administrator/components/com_tour/asset/images/icons/tourtariffs.png" />
                    <label>Tour tariffs</label>
                    </a>
                </div>
                <div class="span2 icon">
                    <a href="<?php echo JUri::root() ?>administrator/index.php?option=com_tour&view=tourstyles">
                    <img class="img-rounded" src="<?php echo JUri::root() ?>administrator/components/com_tour/asset/images/icons/tourstyle.png" />
                    <label>Tour Styles</label>
                    </a>
                </div>
                <div class="span2 icon">
                    <a href="<?php echo JUri::root() ?>administrator/index.php?option=com_tour&view=tourphotos">
                    <img class="img-rounded" src="<?php echo JUri::root() ?>administrator/components/com_tour/asset/images/icons/tourphoto.png" />
                    <label>Tour Photos</label>
                    </a>
                </div>
                <div class="span2 icon">
                    <a href="<?php echo JUri::root() ?>administrator/index.php?option=com_tour&view=touractivities">
                    <img class="img-rounded" src="<?php echo JUri::root() ?>administrator/components/com_tour/asset/images/icons/touractivity.png" />
                    <label>Tour Activity</label>
                    </a>
                </div>
                <div class="span2 icon">
                    <a href="<?php echo JUri::root() ?>administrator/index.php?option=com_tour&view=countries">
                    <img class="img-rounded" src="<?php echo JUri::root() ?>administrator/components/com_tour/asset/images/icons/country.png" />
                    <label>Country</label>
                    </a>
                </div>
                <div class="span2 icon">
                    <a href="<?php echo JUri::root() ?>administrator/index.php?option=com_tour&view=cities">
                    <img class="img-rounded" src="<?php echo JUri::root() ?>administrator/components/com_tour/asset/images/icons/city.png" />
                    <label>City</label>
                    </a>
                </div>
    </div>
</div>