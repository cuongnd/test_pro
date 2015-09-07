<?php
AImporter::model('packagetypes', 'tourpackages');
AImporter::helper('currency');
$now = JFactory::getDate();
$checkin = JFactory::getDate($now->format('Y-m-d'));
$db = JFactory::getDbo();
$query = $db->getQuery(true);
$query->select('packagerate.*,tour.capacity AS tour_capacity,tour.id AS tour_id,packagetype.id AS packagetype_id,tour_package.id AS tour_package_id
   ,date(packagerate.date)  + INTERVAL ' . $this->tour->days . ' DAY AS date_checkout
   ,packagetype.title AS packagetype_title
        ');
$query->select('(SELECT sum(orderinfo.adult+orderinfo.child) as total_adultandchild FROM #__bookpro_orderinfo AS orderinfo
LEFT JOIN asian_bookpro_orders AS orders ON orders.id=orderinfo.order_id
		WHERE orderinfo.obj_id=packagerate.id AND  orders.pay_status='.$db->quote('SUCCESS') .') AS total_adultandchild');
$query->from('#__bookpro_packagerate as packagerate');
$query->leftJoin('#__bookpro_tour_package  AS tour_package ON tour_package.id=packagerate.tourpackage_id');
$query->leftJoin('#__bookpro_packagetype AS packagetype ON packagetype.id=tour_package.packagetype_id');
$query->leftJoin('#__bookpro_tour AS tour ON tour.id=tour_package.tour_id');

$query->where('packagerate.date >=' . $db->quote($checkin->toSql()));
$query->where('tour_package.tour_id=' . $this->tour->id);

$query->order('packagerate.date ASC');
$query->group('packagerate.id');
$db->setQuery($query, 0, 8);
//echo $db->replacePrefix($query);
$listtourpackagerates = $db->loadObjectList('id');
?>

<div class="content_div2_date_private">
    <table class="table">
        <thead style="background:#95a5a5; color:#fff;">

            <tr><th><?php echo JText::_('COM_BOOKPRO_START_DATE')?></th>
                <th><?php echo JText::_('COM_BOOKPRO_FINISH_DATE')?></th>
                <th><?php echo JText::_('COM_BOOKPRO_TOUR_CLASS')?></th>
                <th><?php echo JText::_('COM_BOOKPRO_STATUS')?> </th>
                <th><?php echo JText::_('COM_BOOKPRO_SPACE')?> </th>
                <th colspan="2"><?php echo JText::_('COM_BOOKPRO_PRICING')?></th>


            </tr></thead>
        <tbody style="background:#f7f7f7;">
            <?php foreach ($listtourpackagerates as $tourpackagerate) { ?>
                <?php //echo "<pre>"; print_r($tourpackagerate);
					$tour_capacity=$tourpackagerate->tour_capacity;
					$tour_capacity=explode('-', $tour_capacity);
					$avaible_text='';
					if($tourpackagerate->total_adultandchild==0)
					{
						$avaible_text='+'.$tour_capacity[0];
					}
					if($tour_capacity[0]>=$tourpackagerate->total_adultandchild&&$tourpackagerate->total_adultandchild!=0)
					{
						$avaible_text=$tour_capacity[0]-$tourpackagerate->total_adultandchild;
					}
					elseif($tourpackagerate->total_adultandchild>$tour_capacity[0]&&$tourpackagerate->total_adultandchild<$tour_capacity[1])
					{
						$avaible_text=$tour_capacity[1]-$tourpackagerate->total_adultandchild;
					}

					$avaible_text=$tourpackagerate->close||$tourpackagerate->request?'':$avaible_text;
				 ?>

                <tr style="border-bottom:2px solid #fff!important;">
                    <td> <?php echo JFactory::getDate($tourpackagerate->date)->format('d M Y') ?>  </td>
                    <td> <?php echo JFactory::getDate($tourpackagerate->date_checkout)->format('d M Y') ?>  </td>
                    <td><?php echo $tourpackagerate->packagetype_title ?></td>
                    <td>
                        <div class="state">
                            <?php echo $tourpackagerate->available ? '<span class="available"></span>' : '' ?>
                            <?php echo $tourpackagerate->request ? '<span class="request"></span>' : '' ?>
                            <?php echo $tourpackagerate->guaranteed ? '<span class="guaranteed"></span>' : '' ?>
                            <?php echo $tourpackagerate->close ? '<span class="	close"></span>' : '' ?>
                            <?php echo $tourpackagerate->adult_promo ? '<span class="promo"></span>' : '' ?>

                        </div>
                    </td>
                    <td>    <?php echo $avaible_text ?>   </td>
                    <td><span class="<?php echo $tourpackagerate->adult_promo?' adult_promo_price ':'' ?>" ><?php echo CurrencyHelper::formatprice($tourpackagerate->adult_promo ? $tourpackagerate->adult_promo : $tourpackagerate->adult) ?></span><span class="<?php echo $tourpackagerate->adult_promo?' adult_promo ':'' ?>"><?php echo $tourpackagerate->adult_promo ? CurrencyHelper::formatprice($tourpackagerate->adult) : '' ?></span> </td>
                    <td class="book_now">
                        <?php if ($tourpackagerate->request || $tourpackagerate->close) { ?>
                            <a href="index.php?option=com_bookpro&controller=tourbook&task=form_request&tour_id=<?php echo $tourpackagerate->tour_id ?>&packagetype_id=<?php echo $tourpackagerate->packagetype_id ?>&tour_package_id=<?php echo $tourpackagerate->tour_package_id ?>&packagerate_id=<?php echo $tourpackagerate->id ?>"><?php echo JText::_('COM_BOOKPRO_REQUEST') ?></a>
                        <?php } else { ?>
                            <a href="index.php?option=com_bookpro&controller=tourbook&task=bookingtourpackage&tour_id=<?php echo $tourpackagerate->tour_id ?>&packagetype_id=<?php echo $tourpackagerate->packagetype_id ?>&checkin=<?php echo JFactory::getDate($tourpackagerate->date)->format('d-m-Y') ?>&tour_package_id=<?php echo $tourpackagerate->tour_package_id ?>&packagerate_id=<?php echo $tourpackagerate->id ?>&stype=nonedaytripshared"><?php echo JText::_('COM_BOOKPRO_BOOKNOW') ?></a>
                        <?php } ?>

                    </td>
                </tr>
            <?php } ?>

        </tbody>
    </table>
</div>
<style type="text/css">


</style>
