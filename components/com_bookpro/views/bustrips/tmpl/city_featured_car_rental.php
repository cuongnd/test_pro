<?php
$numberItemInRow=2;

?>
<?php for($i=0;$i<count($this->feature_car_rental);$i=$i+$numberItemInRow){ ?>
<div class="row-fluid">
    <?php for($j=0;$j<$numberItemInRow;$j++){ ?>
    <?php
        $feature_car_rental=$this->feature_car_rental[$i+$j];
        $feature_car_rental->dest_image=$feature_car_rental->dest_image?$feature_car_rental->dest_image:'/components/com_bookpro/assets/images/no_image.jpg';
    ?>
    <div class="span6">
        <img src="<?php echo $feature_car_rental->dest_image ?>">
        <?php echo $feature_car_rental->title ?>
    </div>
    <?php } ?>
</div>
<?php } ?>
