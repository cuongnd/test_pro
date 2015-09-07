<?php
defined('_JEXEC') or die('Restricted access');

JHtml::_('behavior.framework');
JHtmlBehavior::modal();
AImporter::helper('image', 'bookpro', 'currency', 'form', 'tour');
AImporter::css('bookpro', 'tour');
jimport('joomla.html.html.tabs');
JHtml::_('jquery.framework');
AImporter::css('jquery-ui');
AImporter::css('jquery.ui.datepicker');
$document = JFactory::getDocument();
$document->addScript(JURI::root() . 'components/com_bookpro/assets/js/jquery.ui.datepicker.js');
$document->addScript(JURI::root() . 'components/com_bookpro/assets/js/datetimebookingpicker/jquery.datetimebookingpick.js');
$document->addStyleSheet(JURI::root() . 'components/com_bookpro/assets/js/datetimebookingpicker/jquery.datetimebookingpick.css');

$document->addScript(JURI::root() . 'components/com_bookpro/assets/js/jquery.fancybox.pack.js');
$document->addStyleSheet(JURI::root() . 'components/com_bookpro/assets/css/jquery.fancybox.css');
$document->addStyleSheet(JURI::root() . 'components/com_bookpro/assets/css/view-tour.css');

$action = JURI::base() . 'index.php';
$group_p = explode(';', trim($this->tour->pax_group));
$tour = $this->tour;
$relateds = $tour->tours;

JHtml::_('behavior.modal', 'a.jbmodal');
?>



<div class="row-fluid">
    <div class="span8 col_left">
        <div class="content_col_left">
            <?php
            $layout = new JLayoutFile('header_tour', $basePath = JPATH_ROOT . '/components/com_bookpro/layouts');
            $html = $layout->render($this->tour);
            echo $html;
            ?>
            <div class="div2_col_left row-fluid">

                <?php
                $layout = new JLayoutFile('tour_destination', $basePath = JPATH_ROOT . '/components/com_bookpro/layouts');
                $html = $layout->render($this->tour);
                echo $html;
                ?>

            </div>
            <div class="div3_col_left">
                <p class="text2_span8 text-left">DESCRIPTION</p>
                <div class="tour-desc"><?php echo $this->tour->short_desc ?></div>
                <div class="social-share row-fluid">
                    <div class="span6">
                        <?php echo $this->event->afterDisplayTitle ?>
                    </div>
                    <div class="span6">
                        <span class="spshare pull-right" style="margin-right: 20px!important;">
                            <a href="javascript:void(0);" onclick="MailTo();">
                                <img alt="" src="images/tell.png">
                            </a> 
                            <a target="_blank" href="<?php echo JRoute::_(JUri::base() . 'index.php?option=com_bookpro&controller=tour&task=ExportTourToPDFFile&tour_id=' . $this->tour->id); ?>" >
                                <img alt="" src="images/download_1.png">
                            </a>         

<!--<img alt="" src="images/print_1.png">-->
                        </span>

                    </div>
                </div>
            </div>
        </div>

        <script type="text/javascript">

                                function buildMailTo(address, subject, body) {
                                    var strMail = 'mailto:' + encodeURIComponent(address)
                                            + '?subject=' + encodeURIComponent(subject)
                                            + '&body=' + encodeURIComponent(body);
                                    return strMail;
                                }
                                function MailTo()
                                {
                                    var strTest = buildMailTo('', 'Your friend shared this trip with you.', 'Your friend shared <?php echo $this->tour->title; ?> tour with you: <?php echo JRoute::_(JUri::base() . 'index.php?option=com_bookpro&view=tour&id=' . $this->tour->id . '&controller=tour&Itemid=' . JRequest::getVar('Itemid')); ?>');
                                    window.open(strTest);
                                }
        </script>

        <div class="div4_col_left form-horizontal">
            <?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'general')); ?>

            <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'general', JText::_('OVERVIEW')); ?> 
            <?php echo $this->loadTemplate('overview'); ?>    
            <?php echo JHtml::_('bootstrap.endTab'); ?>

            <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'itineraries_detail', JText::_('ITINERARY')); ?>
            <?php echo $this->loadTemplate('itinerary'); ?>
            <?php echo JHtml::_('bootstrap.endTab'); ?>

            <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'date_price', JText::_('DATE & PRICE')); ?>
            <?php echo $this->loadTemplate('package') ?>
            <?php echo JHtml::_('bootstrap.endTab'); ?>

            <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'trip_document', JText::_('TRIP DOCUMENT')); ?>
            <?php echo $this->loadTemplate('document') ?>
            <?php echo JHtml::_('bootstrap.endTab'); ?>

            <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'reviews', JText::_('REVIEWS')); ?>
            <?php echo $this->loadTemplate('itinerary_head'); ?>  
            sfsfdfd
            <?php echo JHtml::_('bootstrap.endTab'); ?>
			
            <div class="div6_col_left">
                <div class="pull-right menu_div6_col_left">
                    <ul class="nav nav-pills" style="margin:0px;">
                        <li><a href="#">OVERVIEW |</a>
                        </li>
                        <li><a href="#">TRIP DETAILS |</a>
                        </li>
                        <li><a href="#">DATE & PRICE | </a>
                        </li>
                        <li><a href="#">TRAVEL TIPS |</a>
                        </li>
                        <li><a href="#">REVIEWS</a>
                        </li>

                    </ul>
                </div>
            </div>  
            <?php echo JHtml::_('bootstrap.endTabSet'); ?>
        </div>      
        <!-- end tab -->     



    </div>

    <script>
        !function($) {
            $(function() {
                // carousel demo
                $('#myCarousel').carousel();
            });
        }(window.jQuery)
    </script>
    <?php
    $images = explode(";", $this->tour->images);
    ?>     
    <div class="span4 col_right tour-right">     



        <?php if (count($images) > 1) { ?> 
            <div class="row-fluid right-content">
                
					<div class="span12 trip">
						
						
						<p class="text-photo-gallery">Trip Photo Gallery</p>
						
						</div>
					
					<div class="span12 content_img_carousel">

						<div class="carousel slide" id="myCarousel">
							<ol class="carousel-indicators" style="top:256px;">
								<?php
								if ($images) {
									$k = 0;
									for ($i = 0; $i < count($images); $i++) {
										if ($images[$i]) {
											?>
											<li class="<?php
											if ($k == 0) {
												echo 'active';
											}
											?>" data-slide-to="<?php echo $i; ?>" data-target="#myCarousel" style="border-radius:inherit;"></li>             
												<?php
												$k++;
											}
										}
									}
									?>
							</ol>
							<!-- Carousel items -->
							<div class="carousel-inner">
								<?php
								if ($images) {
									$k = 0;
									for ($i = 0; $i < count($images); $i++) {
										if ($images[$i]) {
											?>
											<div class="item <?php
													 if ($k == 0) {
														 echo 'active';
													 }
													 ?>">
													 <?php
													 $ipath = '';
													 $ipath = BookProHelper::getIPath($images[$i]);
													 $thumb = AImage::thumb($ipath, 349, 220);
													 ?>
												<img src="<?php echo $thumb; ?>" style="height:281px; width:378px!important">
												<div class="carousel-caption" style="padding: 7px;">    
													<p><?php echo $this->tour->title; ?></p>
												</div>   
											</div>
											<?php
											$k++;
										}
									}
								}
								?>
							</div>    
						</div>  
					</div>
				
            </div>  
                <?php } ?>

                <?php if ($this->tour->mapimage) { ?>
            <div class="row-fluid right-content">
                <div class="span12 content_enlarger_trip_map" style="margin-top:28px;">
						<div class="enlarger_trip_map">
							<p class="text_enlarger_trip_map">
								Enlarger trip map
							</p>
						</div>
						<?php
						$ipath1 = BookProHelper::getIPath($this->tour->mapimage);
						$thumb1 = AImage::thumb($ipath1, 323, 352);
						?>     
						<a class="fancybox" title="<?php echo $this->tour->title; ?>" relw="510" relh="550" href="index.php?option=com_bookpro&view=mapimage&tmpl=component&tour_src=<?php echo $thumb1; ?>">                                                
							<img src="<?php echo $thumb1; ?>" style="height:380px;" class="image-full">
						</a>	
                </div>   
            </div>
                <?php } ?>

        <div class="row-fluid right-content">
            <div class="span12 relative-trip">
<?php echo $this->loadTemplate('relatedtrip'); ?>
            </div>
        </div>
        <div class="row-fluid right-content">
            <div class="span12">
                <div class="sign_up_col_right">
                    <div class="search-title">Join now for deal & discount alert, save up to 75%</div>
                    <form class="form-search">
                        <table cellpadding="0" cellspacing="0">
                            <tr>
                                <td>
                                    <i class="icon_search"></i>		
                                </td>
                                <td valign="bottom">
                                    <div class="input-append">
                                        <input type="text"
                                               class="input-medium button_col_right search-query">
                                        <button type="submit" class="btn btn-small">Sign up</button>
                                    </div>
                                </td>
                            </tr>
                        </table>


                    </form>
                </div>		
            </div>
        </div>



        <div class="row-fluid right-content">
            <div class="span12">
                <div class="need_some_help">
                    <div class="div1_need_some_help">
                        <p class="title_need_some_help">NEED SOME HELP ?</p>
                        <div class="drop_title_find_tour"> </div>
                        <div class="clr"></div>
                    </div>
                    <div class="div2_need_some_help">
                        <p class="tilte_div2_need_some_help">Instant Support :</p>
                        <div class="container_div2_need_some_help">
                            <p class="phone">Speak to our expert: 1-866-592-9685</p>
                            <p class="chat">Chat online with our travel expert</p>
                            <p class="email">Chat online with our travel expert</p>
                        </div>
                    </div>
                    <div class="div3_need_som_help">
                        <p class="tilte_div3_need_some_help">Office Hours :</p>
                        <div class="container_div3_need_some_help">
                            <p class="title_div3_2">The office is open from 8h00 to 18h00,
                                Mon. through Fri. and 8h00 to 11h30 on Sat, 24h/7 hotline
                                support</p>
                            <p class="tilte_div2_need_some_help">Add On Trips:</p>
                            <p class="bg_div2_need_some_help"></p>

                            <p class="bg_1_need_some_help"></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>


<?php echo FormHelper::bookproHiddenField(array('controller' => 'tour', 'Itemid' => JRequest::getInt('Itemid'))) ?>
<input type="hidden" name="depart" value="" /> <input type="hidden"
                                                      name="view" value="tourbook" /> <input type="hidden" name="id"
                                                      value="<?php echo $this->tour->id ?>" />


<?php
$date_tours = explode(',', $this->tour->start);
$listtour = Array();
foreach ($date_tours as $date_tour) {
    $date_tour = JFactory::getDate($date_tour)->format('Ynj');
    $listtour[$date_tour]['a_class'] = "calendar_avail";
    $listtour[$date_tour]['style'] = "";
    $listtour[$date_tour]['atrrib'] = 'id="' . $date_tour . '"';
    $listtour[$date_tour]['status'] = '<div>A</div>';
    $listtour[$date_tour]['qty'] = '';
}
//echo "<pre>";
//print_r($date_tours);
?>
<script type="text/javascript">

    jQuery(document).ready(function($) {
        $('#pickup').datetimebookingpick({
            htmls:<?php echo json_encode($listtour) ?>,
            dateFormat: 'yy-mm-dd',
            monthsToShow: 2,
            minDate: "today",
            changeMonth: false,
            onSelect: function(dateText, inst) {
                var d = new Date(dateText);
                var fmt2 = $.datepicker.formatDate("yy-mm-dd", d);
                $('input[name="depart"]').val(fmt2);
            }
        });
        $("#alternate").datepicker({altField: "#depart_id", altFormat: "dd-mm-yy", numberOfMonths: 2, minDate: +1});

    });



    function booknow(package_id) {
        jQuery(document).ready(function($) {
            //$("input:hidden[name=package_id]").val(package_id);

            if ($('input[name="depart"]').attr('type') == 'radio') {

                if (!$("input[name='depart']:checked").val())
                {
                    alert('<?php echo JText::_('COM_BOOKPRO_DEPART_DATE_WARN') ?>');
                    return false;
                }

            } else {
                if (!$("input[name='depart']").val()) {
                    alert('<?php echo JText::_('COM_BOOKPRO_DEPART_DATE_WARN') ?>');
                    return false;
                }

            }
            $("#tourBook").submit();
        });
    }
    ;


</script>




