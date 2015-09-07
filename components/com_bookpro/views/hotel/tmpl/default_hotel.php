<?php

    defined('_JEXEC') or die('Restricted access');

?>

<style type="text/css">
    .tooltip {
        display: none;
        position: absolute;
        border: 1px solid #333;
        background-color: #161616;
        border-radius: 5px;
        padding: 10px;
        color: #fff;
        font-size: 12px Arial;
    }
    .content_facility{

    }
    .content_facility h3{
        line-height:20px;
        margin:0px;
        color:#cb0000;
        text-transform:uppercase;
        padding-left:10px;
        font-weight:normal;
        padding-top:8px;
        border-bottom:1px solid #cb0000;
        padding-bottom:5px;
    }
    .content_facility .facilitiesicon li{
        float:none!important;
        display:inline;
    }
    .current .tabs{
        height:260px;
        overflow-x:scroll;
    }
    #hotel_tab_group_id{
        margin:0px;
    }
    .content_facility .facilities li:first-child
    {

    }
    .content_facility {
        padding: 0 8px;
    }
    #myCarousel
    {
        margin-bottom: 0px;
    }
    .content_facility .facilities li{
        display: inline-block;
        height: 21px;
        margin: 5px 10px 0 0;
        padding: 0 7px 0 14px;
        white-space: nowrap;
        position: relative;

        background: -moz-linear-gradient(top, #fed970 0%, #febc4a 100%);
        background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#fed970), color-stop(100%,#febc4a));
        background: -webkit-linear-gradient(top, #fed970 0%,#febc4a 100%);
        background: -o-linear-gradient(top, #fed970 0%,#febc4a 100%);
        background: linear-gradient(to bottom, #fed970 0%,#febc4a 100%);
        background-color: #FEC95B;

        color: #963;
        font: bold 11px/21px Arial, Tahoma, sans-serif;
        text-decoration: none;
        text-shadow: 0 1px rgba(255,255,255,0.4);

        border-top: 1px solid #EDB14A;
        border-bottom: 1px solid #CE922E;
        border-right: 1px solid #DCA03B;
        border-radius: 1px 3px 3px 1px;
        box-shadow: inset 0 1px #FEE395, 0 1px 2px rgba(0,0,0,0.21);

    }
    .content_facility .facilities li:before {
        content: '';
        position: absolute;
        top: 5px;
        left: -6px;
        width: 10px;
        height: 10px;

        background: -moz-linear-gradient(45deg, #fed970 0%, #febc4a 100%);
        background: -webkit-gradient(linear, left bottom, right top, color-stop(0%,#fed970), color-stop(100%,#febc4a));
        background: -webkit-linear-gradient(-45deg, #fed970 0%,#febc4a 100%);
        background: -o-linear-gradient(45deg, #fed970 0%,#febc4a 100%);
        background: linear-gradient(135deg, #fed970 0%,#febc4a 100%);
        background-color: #FEC95B;

        border-left: 1px solid #EDB14A;
        border-bottom: 1px solid #CE922E;
        border-radius: 0 0 0 2px;
        box-shadow: inset 1px 0 #FEDB7C, 0 2px 2px -2px rgba(0,0,0,0.33);
    }
    .content_facility .facilities li:before {
        -webkit-transform: scale(1, 1.5) rotate(45deg);
        -moz-transform: scale(1, 1.5) rotate(45deg);
        -ms-transform: scale(1, 1.5) rotate(45deg);
        transform: scale(1, 1.5) rotate(45deg);
    }
    .content_facility .facilities li:after {
        content: '';
        position: absolute;
        top: 7px;
        left: 1px;
        width: 5px;
        height: 5px;
        background: #FFF;
        border-radius: 4px;
        border: 1px solid #DCA03B;
        box-shadow: 0 1px 0 rgba(255,255,255,0.2), inset 0 1px 1px rgba(0,0,0,0.21);
    }
    .content_facility .facilities li:hover {
        color: #FFF;
        text-shadow: -1px -1px 0 rgba(153,102,51,0.3);
    }


    .facilities{
        margin:0px!important;
        padding:0!important;
    }
    #tabhotelTabs >li > a
    {
        font-size: 12px !important;
        text-transform: none !important;
    }
    .hotel_desc #tabhotelTabs li a{
        background: #ffffff; /* Old browsers */
        background: -moz-linear-gradient(top,  #ffffff 30%, #efefef 100%); /* FF3.6+ */
        background: -webkit-gradient(linear, left top, left bottom, color-stop(30%,#ffffff), color-stop(100%,#efefef)); /* Chrome,Safari4+ */
        background: -webkit-linear-gradient(top,  #ffffff 30%,#efefef 100%); /* Chrome10+,Safari5.1+ */
        background: -o-linear-gradient(top,  #ffffff 30%,#efefef 100%); /* Opera 11.10+ */
        background: -ms-linear-gradient(top,  #ffffff 30%,#efefef 100%); /* IE10+ */
        background: linear-gradient(to bottom,  #ffffff 30%,#efefef 100%); /* W3C */
        filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#ffffff', endColorstr='#efefef',GradientType=0 ); /* IE6-9 */
        border:1px solid #e1e1e1;
        font-weight:bold;
        color:#313131;
    }
    .hotel_desc span6 .nav-tabs > .active > a, .nav-tabs > .active > a:hover, .nav-tabs > .active > a:focus{
        background:#fff!important;
    }
    .nav-tabs > .active > a, .nav-tabs > .active > a:hover, .nav-tabs > .active > a:focus{
        background:#fff!important;
        border-bottom-color:transparent!important;
    }
    .hotel_desc #tabhotelTabs li a:hover{
        background:#fff;
    }
    #tab1.table th, .table td{
        line-height:14px;
    }
    #tab1.table-bordered th, .table-bordered td{
        border:none!important;
    }
    .table-bordered{
        border:none!important;
    }
    #tab1.table{
        margin-top:20px;
    }
    #tabhotelContent h4
    {
        font-size: 12px;
    }
    #hotelBook .hoteltitle{
        font-size:60px;
        line-height:56px;
        color:#cf0f16;
        font-family: 'Akronim', cursive;
        text-shadow: 4px 4px 4px #aaa;
        font-family: 'Akronim', cursive;
        text-shadow: 4px 4px 4px #aaa;
    }

    .hotel_desc p{
        font-size:16px;
    }
    .contact_us tr td{
        font-size:16px;
    }
    .table_hotel_desc tr td:first-child, .contact_us tr td:first-child
    {
        font-weight: bold;
    }

	.hotel_desc p{
		font-size:16px;
		padding:5px;
	}
	.contact_us tr td{
		font-size:16px;
	}

</style>


<div class="info">

    <div class="row-fluid">


        <div class="span12">

            <div class="span8">
                <?php $rankstar=JURI::base()."/components/com_bookpro/assets/images/". $this->hotel->rank.'star.png'; ?>
                <h1 class="hoteltitle">
                    <?php echo $this->hotel->title ?>
                    <span><img src="<?php echo $rankstar; ?>"> </span>
                </h1>
                <p class="hoteladd">
                    <?php echo $this->hotel->address1.', '. $this->city->title.', '.$this->city->country ?>
                    <a
                        href="index.php?option=com_bookpro&task=displaymap&tmpl=component&hotel_id=<?php echo $this->hotel->id ?>"
                        class='modal_hotel'
                        rel="{handler: 'iframe', size: {x: 570, y: 530}}"><?php echo JText::_("COM_BOOKPRO_VIEW_MAP")?>
                    </a>
                </p>
            </div>
            <div class="span4">

                <?php if($this->event->afterDisplayTitle){?>
                    <div class="pull-right">
                        <?php echo $this->event->afterDisplayTitle ?>
                    </div>
                    <?php } ?>

            </div>

        </div>


    </div>
    <div class="row-fluid">
        <div class="span12">
            <?php
                if ($this->config->displayGallery) {
                    $images = BookProHelper::getSubjectImages($this->hotel);
                    $pcount = count($images);
                    if($pcount){
                    ?>

                    <div id="myCarousel" class="carousel slide span7" >
                        <div class="carousel-inner">
                            <?php
                                $image = $images[0];
                                $ipath = BookProHelper::getIPath($image);
                                $slide = AImage::thumb($ipath, 780, 330);
                            ?>

                            <div class="item  active">
                                <img src="<?php echo $slide ?>" border="0"  />    
                            </div>
                            <?php for ($i = 1; $i < $pcount; $i++) {?>
                                <?php
                                    $image = $images[$i];
                                    $ipath = BookProHelper::getIPath($image);
                                    $slide = AImage::thumb($ipath, 780, 330);
                                ?>
                                <div class="item">
                                    <img src="<?php echo $slide ?>" border="0"  />    
                                </div>
                                <?php } ?>
                        </div>
                        <!-- Indicators -->
                        <ol class="carousel-indicators">
                            <?php for ($i = 0; $i < $pcount; $i++) {?>
                                <li data-target="#myCarousel" data-slide-to="<?php echo $i ?>" <?php echo $i==0?'class="active"':'' ?> ></li>
                                <?php } ?>

                        </ol>


                    </div>
                    <?php
                    }
            } ?> 

            <div class="hotel_desc span5">



                <?php $options = array(
                        'onActive' => 'function(title, description){
                        description.setStyle("display", "block");
                        title.addClass("open").removeClass("closed");
                        }',
                        'onBackground' => 'function(title, description){
                        description.setStyle("display", "none");
                        title.addClass("closed").removeClass("open");
                        }',
                        'useCookie' => 'true', // note the quotes around true, since it must be a string. But if you put false there, you must not use qoutes otherwise JHtmlTabs will handle it as true
                    );
                    echo JHtml::_('bootstrap.startTabSet', 'tabhotel',array('active'=>'tab1'));

                    echo JHtml::_('bootstrap.addTab', 'tabhotel', 'tab1', JText::_('COM_BOOKPRO_HOTEL_DESCRIPTION'));
                    echo '<div class="hotel_desc">'.$this->hotel->desc.'</div>';
                    echo "<br/>";
                ?>    
                <table style="width: 100%;" class="table table-bordered table_hotel_desc table-striped">
                    <tr>
                        <td><?php echo JText::_('COM_BOOKPRO_ACCOMMONDATION_TYPE') ?></td>
                        <td><?php echo $this->hotel->accommondation_type ?></td>
                    </tr>

                    <tr>
                        <td><?php echo JText::_('COM_BOOKPRO_HOTEL_CHECKIN') ?></td>
                        <td><?php echo $this->hotel->checkin_time ?></td>
                    </tr>
                    <tr>
                        <td><?php echo JText::_('COM_BOOKPRO_HOTEL_CHECKOUT') ?></td>
                        <td><?php echo $this->hotel->checkout_time ?></td>
                    </tr>
                    <tr>
                        <td><?php echo JText::_('COM_BOOKPRO_HOTEL_HITS') ?></td>
                        <td><?php echo $this->hotel->hits ?></td>
                    </tr>
                    <tr>
                        <td><?php echo JText::_('COM_BOOKPRO_HOTEL_LAST_BOOKING_DATE') ?></td>
                        <td><?php echo DateHelper::formatDate($lastbooking->created) ?></td>
                    </tr>
                </table>
                <?php
                    echo JHtml::_('bootstrap.endTab');

                    echo JHtml::_('bootstrap.addTab', 'tabhotel', 'tab2', JText::_('COM_BOOKPRO_HOTEL_TERM_CONDITIONS'));
                    echo $this->hotel->term_conditions;
                    echo "<br/>";
                    echo '<h4>'.Jtext::_('COM_BOOKPRO_CANCEL_POLICY').'</h4>';
                    echo $this->hotel->cancel_policy;
                    echo JHtml::_('bootstrap.endTab');


                    echo JHtml::_('bootstrap.addTab', 'tabhotel', 'tab4', JText::_('COM_BOOKPRO_HOTEL_CONTACT_US'));
                ?>
                <table  style="width: 100%;" class="table contact_us table-bordered table-striped">
                    <tr>
                        <td><span class="address"><?php echo Jtext::_('COM_BOOKPRO_HOTEL_ADDRESS') ?></span> 
                        </td>
                        <td>
                            <?php echo $this->hotel->address1.', '. $this->city->title.', '.$this->city->country ?>
                        </td>
                    </tr>
                    <tr>
                        <td><span class="phone"> <?php echo Jtext::_('COM_BOOKPRO_HOTEL_PHONE') ?></span> 
                        </td>
                        <td>
                            <?php echo $this->hotel->phone ?>
                        </td>
                    </tr>
                    <tr>
                        <td><span class="email"> <?php echo Jtext::_('COM_BOOKPRO_HOTEL_EMAIL') ?> </span> 
                        </td>
                        <td>
                            <?php echo $this->hotel->email ?>
                        </td>
                    </tr>
                    <tr>
                        <td><span class="website"> <?php echo Jtext::_('COM_BOOKPRO_HOTEL_WEBSITE') ?> </span> 
                        </td>
                        <td>
                            <?php echo $this->hotel->website ?>
                        </td>
                    </tr>
                </table>
                <?php
                    echo JHtml::_('bootstrap.endTab');
                    echo JHtml::_('bootstrap.endTabSet');



                ?>
            </div>

        </div>
    </div>
    <div class="row-fluid">
        <div class="content_facility">



            <?php
                $layout = new JLayoutFile('facilitytext', $basePath = JPATH_ROOT .'/components/com_bookpro/layouts');
                $html = $layout->render($this->facilities);
                echo $html;
            ?>


        </div>
    </div>
</div>
<script>
    !function ($) {

        $(function(){
            $('#myCarousel').carousel({
                interval:2000
            });
            // carousel demo


            $('#myCarousel .carousel-inner .item').width(780);
            $('#myCarousel .carousel-inner .item img').width(780);
            $('#myCarousel .carousel-inner .item').height(330);
            $('#myCarousel .carousel-inner .item img').height(330);

        });
    }(window.jQuery)   
</script>
<style type="text/css">
    #tabhotelTabs li
    {
        text-transform: capitalize;
    }

    #tabhotelContent
    {
        border: 1px solid #dadada;
        border-radius: 3px;
        min-height: 288px;
        border-top: none ;
        padding: 5px;
        height: 288px;
        overflow-y:scroll;
        overflow-x:hidden;
        
        
    }
    #tabhotelContent td
    {
        border-top: 0px;
        font-size:12px;
    }
    #tabhotelTabs
    {
        margin-bottom: 0px;
    }
    .tablecontact tr td
    {
        border-top: none;
        font-size:14px;
    } 
    .carousel-indicators
    {
        position: absolute;
        bottom: 10px;
        left: 70%;
        z-index: 15;
        width: 60%;
        top: auto;
        padding-left: 0;
        margin-left: -30%;
        text-align: center;
        list-style: none;

    }
</style>
<script type="text/javascript">
    jQuery(document).ready(function($){
        $('.tablecontact input[name="send"]').on('click',function(){
            if($('.tablecontact input[name="subject"]').val().trim()=='')
            {
                alert('<?php echo Jtext::_('COM_BOOKPRO_INPUT_SUBJECT') ?>');
                $('.tablecontact input[name="subject"]').focus();
            }
            if($('.tablecontact input[name="content"]').val().trim()=='')
            {
                alert('<?php echo Jtext::_('COM_BOOKPRO_INPUT_CONTENT') ?>');
                $('.tablecontact input[name="content"]').focus();
            }
        });
    });
</script>