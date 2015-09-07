<?php

defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );
AImporter::helper('bookpro','date');
class DateHelper {
	
	public static function formatDate($date,$joomla_format="DATE_FORMAT_LC3"){
		 
		$format = JText::_($joomla_format);
		if($date=="0000-00-00 00:00:00"){
			
			return JText::_("COM_BOOKPRO_UNAVAILABLE");
		}
		else 
		 return JHTML::_('date', $date, $format);
	}
	public static function formatMultiDate($date,$joomla_format="DATE_FORMAT_LC3"){
		$datearr=explode(';', $date);
		$format = JText::_($joomla_format);
		$result=array();
		for ($i = 0; $i < count($datearr); $i++) {
			$dateObj=JFactory::getDate($datearr[$i]);
			$result[]= JHTML::_('date', $dateObj, $format);
		}
		return $result;
	
	}
    static function dayofweek(){
        $days = array(
        	0 => DateHelper::getObjectDayOfWeek('S', 'Sun'),
        	1 => DateHelper::getObjectDayOfWeek('M', 'Mon'),
            2 => DateHelper::getObjectDayOfWeek('T', 'Tue'),
            3 => DateHelper::getObjectDayOfWeek('W', 'Wed'),
            4 => DateHelper::getObjectDayOfWeek('T', 'Thu'),
            5 => DateHelper::getObjectDayOfWeek('F', 'Fri'),
            6 => DateHelper::getObjectDayOfWeek('S', 'Sat')
            );
        return $days;

    }
    static function getObjectDayOfWeek($value,$text){
    	$day = new stdClass();
    	$day->value = $value;
    	$day->text = $text;
    	return $day;
    }

    public static function getArrayTabDate($date){
		$app		= JFactory::getApplication();
		$tzoffset = $app->getCfg('offset');
		$date_arr=array();
	
		$currendate=new JDate($date);
	
		$nowdate = new JDate('now');
	
		$days = $currendate->diff($nowdate);
	
		if ($days->days >=2) {
			$int_start = -2;
			$int_end = 3;
		}
	
		if ($days->days < 2 && $days->days >0) {
			$int_start = -$days->days;
				
			$int_end = 5 - $days->days;
				
		}
	
		if ($days->days == 0) {
			$int_start = 0;
			$int_end = 5;
		}
	
		for ($i = $int_start; $i < $int_end; $i++) {
				
			$sdate=JFactory::getDate($date,$tzoffset);
				
			if($i<0) {
				$sdate->sub(new DateInterval('P'.abs($i).'D'));
			}else{
				$sdate->add(new DateInterval('P'.abs($i).'D'));
			}
	
			$date_arr[]=$sdate;
		}
			
		return $date_arr;
	}
	function getOffsetDay($count,$start){
			
		$date = $start + $count*24*60*60;
		return $date;
	}
	public static function  getCountDay($start,$end){
		
		//$start = strtotime($start);
		$start = new JDate($start);
		$end = new JDate($end);
		//$end = strtotime($end);
		//$days_between = ceil(abs($end - $start) / 86400);
		$days = $start->diff($end);
		return $days->days;
		//return $days_between;
	}
	public static function dateBeginDay($date, $tzoffset = 0)
	{
		$day = date('Y-m-d',$date);
		
		$date = strtotime($day.' 00:00:00');
		return $date;
	}
	public static function dateBeginWeek($date, $tzoffset = 0)
	{
		$date = strtotime('last Monday',$date);
		var_dump(JFactory::getDate($date));
		return $date;
	}
	public static function dateEndWeek($date, $tzoffset = 0)
	{
		$date = strtotime('next Sunday',$date);
		return $date;
	}
	function startMonth($m,$y){
		$date = date('Y-m-d H:i:s',mktime(0,0,0,$m,01,$y));
		return $date;
		
	}
	function endMonth($d,$m,$y){
		$date = date('Y-m-d H:i:s',mktime(23,59,59,$m,$d,$y));
		return $date;
	}
	public static function dateBeginMonth($date, $tzoffset = 0)
	{
		
		
		
		$fromdate = date('01-m-Y 00:00:00',$date);
		
		
		
		//$date = strtotime('first day this month',$date);
		//$fromdate = strtotime($fromdate);
		return $fromdate;
	}
	public static function dateEndMonth($date, $tzoffset = 0)
	{
		$todate = date('t-m-Y 23:59:59',$date);
		
		//$todate = strtotime($todate);
		return $todate;
	}
	
 /**
     * Convert date into given format with given time zone offset.
     * 
     * @param $date string date to convert
     * @param $format string datetime format
     * @param $tzoffset int time zone offset
     * @return BookProDate
     */
    public static function convertDate($date, $format = '%Y-%m-%d %H:%M:%S', $tzoffset = false)
    {
        static $cache;
        $key = $date . $format . $tzoffset;
        if (! isset($cache[$key])) {
        	if ($tzoffset){
        		$mainframe = JFactory::getApplication();
        		/* @var $mainframe JApplication */
        		$jdate = JFactory::getDate($date, $mainframe->getCfg('config'));
        		/* @var $date JDate */
        		$jdate->setOffset($mainframe->getCfg('config')); 
        	} else {
        		$jdate = JFactory::getDate($date);
        		/* @var $jdate JDate */
        	}
            $output = new BookProDate();
            $output->orig = $date;
            $output->uts = $jdate->toUnix();
            $output->dts = $jdate->toFormat($format, $tzoffset);
            $cache[$key] = $output;
        }
        return $cache[$key];
    }
    public static function dateEndDay($date, $tzoffset = 0)
    {
    	$date = date('Y-m-d',$date);
    	$date = strtotime($date.' 23:59:59');
    	return $date;
    }
    
    function getCalendar($field,$offset=0,$format='Y-m-d') {
    	return '<input type="text" class="inputbox" name="start" id="start"
										value="'.JFactory::getDate()->format($format).'"
										size="13" maxlength="10" />'.
									'<script type="text/javascript">
         					Calendar.setup({ inputField  : '.$field.',
             				ifFormat    : "%Y-%m-%d", button      : "start_img",
             				singleClick:true,dateStatusFunc  :   function (date) {
              				var myDate = new Date();
             				if (date.getTime() < myDate.setDate(myDate.getDate() - '.$offset.')) return true;
             				},weekNumbers : true   });
             				
	    </script>';
    }
}