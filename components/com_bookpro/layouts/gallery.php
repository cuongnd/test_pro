<?php
AImporter::model('galleries');

AImporter::helper('tour');

$type = $displayData->type;
$obj_id = $displayData->id;


$images = explode(";", $this->tour->images);
$galleriesModel = new BookProModelGalleries();
$galleriesModel->setState('filter.obj_id', $obj_id);
$galleriesModel->setState('filter.type', $type);
$galleries = $galleriesModel->getItems();
?>


<?php if (count($galleries) > 1) { ?> 
    <div class="row-fluid right-content">

        <div class="span12 trip">


            <p class="text-photo-gallery">Trip Photo Gallery</p>

        </div>

        <div class="span12 content_img_carousel">

            <div class="carousel slide" id="myCarousel">
                <ol class="carousel-indicators" style="top:256px;">
                    <?php
                    $k = 0;
                    for ($i = 0; $i < count($galleries); $i++) {
                        ?>
                        <li class="<?php
                if ($k == 0) {
                    echo 'active';
                }
                        ?>" data-slide-to="<?php echo $i; ?>" data-target="#myCarousel" style="border-radius:inherit;"></li>             
                            <?php
                            $k++;
                        }
                        ?>
                </ol>
                <!-- Carousel items -->
                <div class="carousel-inner">
    <?php
    $k = 0;
    for ($i = 0; $i < count($galleries); $i++) {
        ?>
                        <div class="item <?php
                        if ($k == 0) {
                            echo 'active';
                        }
                        ?>">

                            <img src="<?php echo JUri::root() . $galleries[$i]->path; ?>" style="height:281px; width:378px!important">
                            <div class="carousel-caption" style="padding: 7px;">    
                                <p><?php echo $galleries[$i]->title; ?></p>
                            </div>   
                        </div>
        <?php
        $k++;
    }
    ?>
                </div>    
            </div>  
        </div>

    </div>  
<?php } ?>
    