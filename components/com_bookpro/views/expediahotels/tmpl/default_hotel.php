<?php if (count($this->hotels['HotelList']['HotelSummary']) > 0) { ?>

    <?php
    $i = 0;
    $this->array_star=array(
        "star1"=>"1"
        ,"star1.5"=>"star1-5"
        ,"star2"=>"star2"
        ,"star2.5"=>"star2-5"
        ,"star3"=>"star3"
        ,"star3.5"=>"star3-5"
        ,"star4"=>"star4"
        ,"star4.5"=>"star4-5"
        ,"star5"=>"star5"
    );
    foreach ($this->hotels['HotelList']['HotelSummary'] as $hotel) {
        $this->hotel = $hotel;

        ?>
        <div class = "row-fluid show-grid content_details_hotels" style = "padding-bottom: 5px;">
            <div class="wapper">
                <?php echo $this->loadTemplate("item"); ?>
            </div>
        </div>
    <?php
    }
    ?>
<?php } else { ?>

    <div>
        <?php echo JText::sprintf('No destination or hotel found for specified search criteria') ?>
    </div>

<?php } ?>

<div class = "pagination">
    <?php
    $input = JFactory::getApplication()->input;
    $current = $input->get('page', 1, 'int');
    $page = $current == 1 ? 2 : $current;
    $numberPage=$page+1;
    ?>
    <ul>
        <?php for ($i = $page - 1; $i <=$numberPage ; $i++) { ?>
            <?php if($i == $current - 1){ ?>
                <li><a href="index.php?option=com_bookpro&view=expediahotels&page=1"><<</a></li>
                <li><a href="index.php?option=com_bookpro&view=expediahotels&page=<?php echo $current-1 ?>">Prev</a></li>
            <?php } ?>
            <li class="<?php echo $current==$i?'active':'' ?>">

                <a  href = "index.php?option=com_bookpro&view=expediahotels&page=<?php echo $i ?>"><?php echo $i ?></a>
            </li>
            <?php if($i == $numberPage){ ?>
                <li><a href="index.php?option=com_bookpro&view=expediahotels&page=<?php echo $current+1 ?>">Next</a></li>
            <?php } ?>
        <?php } ?>
    </ul>
</div>