<?php
defined('_JEXEC') or die('Restricted access');
AImporter::helper('currency');
$cart = & JModelLegacy::getInstance('BusCart', 'bookpro');
$cart->load();

$session_select_seat = $cart->listseat;
$session_select_seat = explode(',', trim($cart->listseat));
//AImporter::helper('curency');
?>
<div class = "row-fluid">
    <div class = "span9">
        <?php
        $layout = new JLayoutFile('default_specialcar', $basePath = JPATH_ROOT .'/components/com_bookpro/views/ajaxbustrip/tmpl');
        $html = $layout->render($this->going_trips);
        echo $html;
        ?>


        <?php echo $this->loadtemplate('specialcar') ?>

<ul class="nav nav-tabs" id="myTab">
  <li class="active"><a data-toggle="tab" href="#home"><?php echo JText::_('COM_BOOKPRO_BUS_TAB_CAR_RENTAL') ?></a></li>
</ul>
        <?php
        // Example tab usage
        echo JHtml::_('bootstrap.startPane', 'myTab', array('active' => 'home'));
        echo JHtml::_('bootstrap.addPanel', 'myTab', 'home');
       ?>
        <div class="row-fluid"><div class="span7"><?php echo JText::printf('COM_BOOKPRO_CAR_RENTAL_FROM_TO',$this->cart->from,$this->cart->pickup,$this->cart->to,$this->cart->dropoff) ?></div><div class="span5 "><span class="btn"><?php echo JText::_('COM_BOOKPRO_PREV_DAY'); ?></span><span class="btn"><?php echo JText::_('COM_BOOKPRO_NEXT_DAY'); ?></span></div></div>
        <div class = "row-fluid ">
        <div class = "span12 ">
            <div class = "row-fluid background-gray">
                <div class = "span3"></div>
                <div class = "span1">
                    <strong><h6 class = "blue"><?php echo JText::_('COM_BOOKPRO_BUS_VEHICLE') ?></h6></strong>
                </div>
                <div class = "span2">
                    <h6 class = "blue"><?php echo JText::_('COM_BOOKPRO_BUS_FROM') ?></h6>
                </div>
                <div class = "span2">
                    <h6 class = "blue"><?php echo JText::_('COM_BOOKPRO_BUS_TO') ?></h6>
                </div>
                <div class = "span2">
                    <h6 class = "blue"><?php echo JText::_('COM_BOOKPRO_BUS_TRIP_TIME') ?></h6>

                </div>
                <div class = "span2"></div>
            </div>
            <?php foreach($this->going_trips as $item){ ?>
                <div id = "list1">


                <div class = "row-fluid">
                    <div class = "span3">
                        <img src = "<?php echo $item->bus_image ?>">
                    </div>
                    <div class = "span1">
                        <ul>
                            <?php foreach($item->bus_facilities as $facility){ ?>
                                <li><a title="<?php echo $facility->title ?>" href = "#"> <img src = "<?php echo $facility->image ?>">
                            </a></li>
                            <?php } ?>
                        </ul>
                    </div>
                    <div class = "span2">
                        <?php echo $item->fromName ?>
                        <br/>
                        <?php echo $this->cart->pickup ?>
                    </div>
                    <div class = "span2">
                          <?php echo $item->toName ?>
                        <br/>
                        <?php echo $this->cart->dropoff ?>
                    </div>
                    <div class = "span2 small-width">
                        <?php if($item->roundtrip){ ?>
                            <img src = "components/com_bookpro/assets/images/icon-twist.png">
                        <?php }else{ ?>
                            <img src = "components/com_bookpro/assets/images/icon-arrow.png">
                        <?php } ?>
                        <strong><?php echo $item->duration ?></strong>
                    </div>
                    <div class = "span2">
                        <strong><?php echo CurrencyHelper::displayPrice($item->price,0) ?> /</strong> <strong><?php echo JText::_('COM_BOOKPRO_BUSTRIP_SELECT') ?><input name = "bustrip_id" value = "<?php $item->id ?>" type = "checkbox">
                        </strong>
                        <div class = "blue">
                            <img src = "components/com_bookpro/assets/images/icon-dubble-arrow.png"> <a href="javascript:void(0)" data-toggle="modal" data-target="#modal_summary_<?php echo $item->id ?>" >Summary</a> | Earn <img src = "components/com_bookpro/assets/images/icon-money.png">
                        </div>
                        <!-- Modal -->
                        <div class="modal fade" id="modal_summary_<?php echo $item->id ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                          <div class="modal-dialog">
                            <div class="modal-content">
                              <div class="modal-header">
                                <h4 class="modal-title" id="myModalLabel"><?php echo JText::_('COM_BOOKPRO_SUMMARY') ?></h4>
                              </div>
                              <div class="modal-body">
                                <?php echo $item->summary ?>
                              </div>
                              <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                              </div>
                            </div>
                          </div>
                        </div>
                        <!--end modal-->
                    </div>
                </div>

                <div class = "row-fluid">
                    <div class = "span12">
                        <div class = "row-fluid">
                            <div class = "span9 text-right">
                                <a data-toggle="collapse" href="#collapse_vehicle-detail_<?php echo $item->id ?>" href = "javascript:void(0)"><h6>
                                        <?php echo JText::_('COM_BOOKPRO_BUS_VEHICLE_DETAILS') ?> <img src = "components/com_bookpro/assets/images/icon-down-arrow.png">
                                    </h6></a>
                                <div id="collapse_vehicle-detail_<?php echo $item->id ?>" class="collapse">
                                    vehicle detail
                                </div>
                            </div>
                            <div class = "span3 text-center">
                                <a  data-toggle="collapse" href="#collapse_trip_information_<?php echo $item->id ?>" href = "javascript:void(0)"><h6>
                                        <?php echo JText::_('COM_BOOKPRO_BUS_TRIP_INFORMATION') ?> <img src = "components/com_bookpro/assets/images/icon-down-arrow.png">
                                    </h6></a>
                                 <div id="collapse_trip_information_<?php echo $item->id ?>" class="collapse">
                                    <?php echo $item->trip_information ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php } ?>
            <!-- end list1 -->

        </div>
</div>

        <?php
        echo JHtml::_('bootstrap.endPanel');

        echo JHtml::_('bootstrap.endPane', 'myTab');
        ?>






    </div>
    <div class = "span3">
        	<div class = "searchdetail">
		<div class = "row-fluid">
				<div class = "span12">
					<h3>SEARCH DETAILS</h3>
				</div>

				<div class = "span12 title">
				<p>Keyword: <b>Ho Chi Minh - Ha Noi cars</b></p>
				</div>
				<div class = "span12 info">
					<div class = "span6">
						<p>Depart:<b>2 May 13</b></p>
						<p>Trip: <b>one way</b></p>
					</div>
					<div class = "span6">
						<p>Return: <b>3 May 13</b></p>
						<p>Results: <b>150 cars</b></p>
					</div>
				</div>

				<div class = "span12 Modify">
					<button><i class = 'iconbt'></i>Modify Your Search</button>
				</div>

				<div class = "span12 bottom">

				</div>
		</div>
	</div>



		<div class = "row-fluid right-content">
            	<div class = "span12">
            		<div class = "sign_up_col_right">
			                <div class = "search-title">Join now for deal & discount alert, save up to 75%</div>
			                <form class = "form-search">
			                	<table cellpadding = "0" cellspacing = "0">
			                		<tr>
			                			<td>
			                				<i class = "icon_search"></i>
			                			</td>
			                			<td valign = "bottom">
			                				<div class = "input-append">
							                    <input type = "text"
                                                       class = "input-medium button_col_right search-query">
							                    <button type = "submit" class = "btn btn-small">Sign up</button>
							                    </div>
			                			</td>
			                		</tr>
			                	</table>


			                </form>
		            </div>
            	</div>
            </div>
    </div>
</div>





<script type = "application/javascript">
 
jQuery(document).ready(function () {
    jQuery('span.dep_middle').hide();
    jQuery("a.detail").live("click", function (e) {
        jQuery(this).next('div.detail').toggle();
    });
    jQuery("a.detail_journey").live("click", function (e) {
        jQuery('span.dep_middle').toggle();
    });
});
</script>
