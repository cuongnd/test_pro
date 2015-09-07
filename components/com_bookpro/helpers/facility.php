<?php 
class FacilityHelper {
	static function getFacilitiesSelectedByhotelId($hotel_id)
	{
		$db=JFactory::getDbo();
		$query=$db->getQuery(true);
		$query->select('facility.*');
		$query->from('#__bookpro_facility AS facility');
		$query->where('facility.ftype=0');
		$query->where('facility.state=1');
		$db->setQuery($query);
		$listfacilities=$db->loadObjectList();
		if ($hotel_id){
			$db=JFactory::getDbo();
			$query=$db->getQuery(true);
			$query->select('hotelfac.*');
			$query->from('#__bookpro_hotelfacility AS hotelfac');
			$query->where('hotelfac.hotel_id='.$hotel_id);
			$db->setQuery($query);
			$listfacilitiesselect=$db->loadObjectList('facility_id');
		}

		ob_start();
		?>
<table>
	<thead>
		<th><?php echo JText::_('COM_BOOKPRO_FACILITY_SELECT') ?></th>
		<th><?php echo JText::_('COM_BOOKPRO_IMAGE') ?></th>
		<th><?php echo JText::_('COM_BOOKPRO_TITLE') ?></th>
		<th><?php echo JText::_('COM_BOOKPRO_PRICE') ?></th>

	</thead>
	<tbody>
		<?php foreach($listfacilities as $facility){ ?>
		<tr>
			<td><input type="checkbox"
			<?php echo $listfacilitiesselect[$facility->id]->price!=''?'checked="checked"':'' ?>
				value="1" name="facilityselect[<?php echo $facility->id ?>]">
			</td>
			<td><span><img src="<?php echo JUri::root().$facility->image ?>"
					alt="<?php echo $facility->title ?>"> </span>
			</td>
			<td><?php echo $facility->title ?>
			</td>
			<td><input style="width: auto;" type="text"
				name="facilities[<?php echo $facility->id ?>]"
				value="<?php echo $listfacilitiesselect[$facility->id]->price ?>">
			</td>

		</tr>
		<?php } ?>
	</tbody>
</table>
<?php
$contents=ob_get_contents();
ob_end_clean();
return $contents;
	}
    static function getListFacilitiesSelectedByRoomid($room_id)
	{

		$db=JFactory::getDbo();
		$query=$db->getQuery(true);
		$query->select('facility.*');
		$query->from('#__bookpro_facility AS facility');
		$query->where('facility.ftype=1');
		$query->where('facility.state=1');
		$db->setQuery($query);
		$listfacilities=$db->loadObjectList();
		if($room_id){
			$db=JFactory::getDbo();
			$query=$db->getQuery(true);
			$query->select('roomfacility.*');
			$query->from('#__bookpro_roomfacility AS roomfacility');
			$query->where('roomfacility.room_id='.$room_id);
			$db->setQuery($query);
			$listfacilitiesselect=$db->loadObjectList('facility_id');
		}

		ob_start();
		?>

<table>
	<thead>
		<th><?php echo JText::_('COM_BOOKPRO_FACILITY_SELECT') ?></th>
		<th><?php echo JText::_('COM_BOOKPRO_IMAGE') ?></th>
		<th><?php echo JText::_('COM_BOOKPRO_TITLE') ?></th>
		<th><?php echo JText::_('COM_BOOKPRO_PRICE') ?></th>


	</thead>
	<tbody>
		<?php foreach($listfacilities as $facility){ ?>

		<tr>
			<td><input type="checkbox" value="1"
			<?php echo $listfacilitiesselect[$facility->id]->price!=''?'checked="checked"':'' ?>
				name="facilityselect[<?php echo $facility->id ?>]">
			</td>
			<td><span><img src="<?php echo JUri::root().$facility->image ?>"
					alt="<?php echo $facility->title ?>"> </span>
			</td>
			<td><?php echo $facility->title ?>
			</td>
			<td><input style="width: auto;" type="text"
				name="facilities[<?php echo $facility->id ?>]"
				value="<?php echo $listfacilitiesselect[$facility->id]->price ?>">
			</td>

		</tr>
		<?php } ?>
	</tbody>
</table>
<?php
$contents=ob_get_contents();
ob_end_clean();


return $contents;
	}

	static function getFacilitiesSelectedByRoomId($objroom){
            if($room_id)
            {
            	$hotel_id=$objroom->hotel_id;
            	$db=JFactory::getDbo();
            	$query=$db->getQuery(true);
            	$query->select('*')->from('#__bookpro_facility AS f');
            	$query->leftJoin('#__bookpro_roomfacility AS rf ON f.id=rf.facility_id');
            	$query->where('rf.hotel_id='.(int) $hotel_id);
            	$query->where('f.ftype=0');

            }
            $db=JFactory::getDbo();
            $query=$db->getQuery(true);
            $query->select('*')->from('#__bookpro_facility AS f');
            $query->leftJoin('#__bookpro_roomfacility AS rf ON f.id=rf.facility_id');
            $room_id?$query->where('rf.room_id='.(int) $room_id):null;
            $db->setQuery($query);
            $items=$db->loadObjectList();
            if(count($items)>0){
                foreach ($items as $item){
                    $themes.= '<span class="label label-info">'. $item->title . '</span>&nbsp;';
                }
            }
            return $themes;
        }
        static function getAdultSelectBox($room,$field,$attribute){

            $options=array();
            $adult=$room->adult+1;
            for ($i = 1; $i < $adult; $i++) {
                $options[]=JHtml::_('select.option',$i,$i);
            }
            if($room->adult_price){

                $adult_price=explode(',', $room->adult_price);

                for ($i = 0; $i < count($adult_price); $i++) {

                    $txt=($adult+$i).'(+'.$adult_price[$i].')';
                    $options[]=JHtml::_('select.option',$adult+$i,$txt);

                }
            }
            return JHtmlSelect::genericlist($options, $field,$attribute,'value','text',1);

        }
        static function getChildSelectBox($room,$field,$attribute){

            $options=array();
            for ($i = 0; $i <= $room->child; $i++) {
                $options[]=JHtml::_('select.option',$i,$i);
            }
            return JHtmlSelect::genericlist($options, $field,$attribute);

        }
        static function getFacilities($room_id){

            $db=JFactory::getDbo();
            $query=$db->getQuery(true);
            $query->select('*')->from('#__bookpro_facility AS f');
            $query->leftJoin('#__bookpro_roomfacility AS rf ON f.id=rf.facility_id');
            $query->where('rf.room_id='.(int) $room_id);
            $db->setQuery($query);
            $items=$db->loadObjectList();
            if(count($items)>0){
                foreach ($items as $item){
                    $themes.= '<span class="label label-info">'. $item->title . '</span>&nbsp;';
                }
            }
            return $themes;
        }

}

?>