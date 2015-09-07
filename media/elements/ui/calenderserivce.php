<?php
class elementCalenderSerivceHelper extends  elementHelper
{
    function initElement($TablePosition)
    {
        $path=$TablePosition->ui_path;
        $pathInfo = pathinfo($path);
        $filename=$pathInfo['filename'];
        $dirName=$pathInfo['dirname'];
        $doc=JFactory::getDocument();
        $lessInput = JPATH_ROOT . "/$dirName/$filename.less";
        $cssOutput =  JPATH_ROOT . "/$dirName/$filename.css";
        JUtility::compileLess($lessInput, $cssOutput);

    }
    function getHeaderHtml($block,$enableEditWebsite)
    {
        $app=JFactory::getApplication();
        $path=$block->ui_path;
        $pathInfo = pathinfo($path);
        $filename=$pathInfo['filename'];
        $dirName=$pathInfo['dirname'];
        $doc=JFactory::getDocument();
        $doc->addStyleSheet(JUri::root() . "/$dirName/$filename.css");
        $doc->addScript(JUri::root() ."/$dirName/$filename.js");
        $params = new JRegistry;
        $params->loadString($block->params);

        $size=$params->get('size','');

        $label=$params->get('label','');
        $name=$params->get('name','');
        $enable_droppable=$params->get('enable_droppable',0);
        $enable_resizable_for_control=$params->get('enable_resizable_for_control',0);
        $id=$params->get('id','');
        $text=$params->get('text','');
        $placeholder=$params->get('placeholder','placeholder_'.$block->id);
        $items=$params->get('items','');
        $bindingSource=$params->get('data')->bindingSource;
        $key=$params->get('key','id');
        $value=$params->get('value','title');
        if(!$items&&$bindingSource){
            $items=parent::getValueDataSourceByKey($bindingSource);
        }
        $html='';
        ob_start();
        if($enableEditWebsite) {
            ?>
            <div  class="control-element control-element-calendar_service item_control item_control_<?php echo $block->parent_id ?>" get-data-from="<?php  echo $data_text?'datasource':'text'?>" <?php echo $enable_resizable_for_control==1?'enabled-resizable="true"':'' ?>  data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" element-type="<?php echo $block->type ?>" data-gs-x="<?php echo $block->gs_x ?>" data-gs-y="<?php echo $block->gs_y ?>" data-gs-width="<?php echo $block->width ?>" data-gs-height="<?php echo $block->height ?>">
            <script type="text/javascript">
                jQuery(document).ready(function ($) {
                    file_js='<?php echo "/$dirName/$filename.js" ?>';
                    element_ui_element.load_file_js_then_call_back_function(file_js,"element_ui_div.init_ui_div",'');

                });
            </script>
            <span data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" class="drag label label-default  element-move-handle element-move-handle_<?php echo $block->parent_id ?>"><i class="glyphicon glyphicon-move"></i></span>
            <a data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" class="menu label config-block label-danger menu-list" href="javascript:void(0)"><i class="im-menu2"></i></a>
            <a data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>"class="remove label label-danger remove-element" href="javascript:void(0)"><i class="glyphicon-remove glyphicon"></i></a>
            <div class="block-item block-item-calendar_service "  <?php echo $enable_droppable==1?'enabled-droppable="true"':'' ?> data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" id="<?php echo $id; ?>" element-type="<?php echo $block->type ?>"><?php echo $text ?>
        <?php
            echo elementCalenderSerivceHelper::render_calendar_service();

        }else{
        ?>
            <script type="text/javascript">
                jQuery(document).ready(function ($) {
                    element_ui_div.init_ui_div();
                });
            </script>
            <div class="block-item block-item-calendar_service" data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" id="<?php echo $id; ?>" element-type="<?php echo $block->type ?>"><?php echo $text ?>

        <?php
            echo elementCalenderSerivceHelper::render_calendar_service();

        }
        $html.=ob_get_clean();
        return $html;
    }
    public function render_calendar_service()
    {
        $html='';
        ob_start();
        ?>

        <?php
        $calendar = new PN_Calendar_Element_service();
        echo $calendar->draw();
        $html.=ob_get_clean();
        return $html;


    }
    function getFooterHtml($block,$enableEditWebsite)
    {
        $html='';
        ob_start();
        if($enableEditWebsite) {
            ?>
            </div>
            </div>
        <?php
        }else{
            ?>
            </div>
        <?php
        }
        $html.=ob_get_clean();
        return $html;
    }



}


class PN_Calendar_Element_service {

    public $first_day_of_week = 0; //0 - sunday, 1 - monday
    public $current_year = null;
    public $current_month = null;
    public $current_day = null;
    public $show_year_selector = true;
    public $min_select_year = 2013;
    public $max_select_year = 2016;
    public $show_month_selector = true;

    public function __construct($atts = array()) {
        if (isset($atts['first_day_of_week'])) {
            $this->first_day_of_week = $atts['first_day_of_week'];
        }

        if (!isset($atts['year'])) {
            $this->current_year = date('Y');
        } else {
            $this->current_year = $atts['year'];
        }

        if (!isset($atts['month'])) {
            $this->current_month = date('m');
        } else {
            $this->current_month = $atts['month'];
        }

        if (!isset($atts['day'])) {
            $this->current_day = date('d');
        } else {
            $this->current_day = $atts['day'];
        }
        //***
        if (isset($atts['show_year_selector'])) {
            $this->show_year_selector = $atts['show_year_selector'];
        }

        if (isset($atts['show_month_selector'])) {
            $this->show_month_selector = $atts['show_month_selector'];
        }

        if (isset($atts['min_select_year'])) {
            $this->min_select_year = $atts['min_select_year'];
        }

        if (isset($atts['max_select_year'])) {
            $this->max_select_year = $atts['max_select_year'];
        }
    }

    /*
     * Month calendar drawing
    */

    public function draw($data = array(), $y = 0, $m = 0) {
        //***

        if ($m == 0 AND $m == 0) {
            $y = $this->current_year;
            $m = $this->current_month;
        }
        //***

        $data['week_days_names'] = $this->get_week_days_names(true);

        $data['cells'] = $this->generate_calendar_cells($y, $m);

        $data['month_name'] = $this->get_month_name($m);
        $data['year'] = $y;
        $data['month'] = $m;
        $data['events'] = array();//here you can transmit events from database (look PN_CalendarCell::draw($events))

        return $this->draw_html( $data);
    }

    private function generate_calendar_cells($y, $m) {
        $y = intval($y);
        $m = intval($m);
        //***
        $first_week_day_in_month = date('w', mktime(0, 0, 0, $m, 1, $y)); //from 0 (sunday) to 6 (saturday)
        $days_count = $this->get_days_count_in_month($y, $m);
        $cells = array();
        //***
        if ($this->first_day_of_week == $first_week_day_in_month) {
            for ($d = 1; $d <= $days_count; $d++) {
                $cells[] = new PN_CalendarCell($y, $m, $d);
            }
            //***
            $cal_cells_left = 5 * 7 - $days_count;
            $next_month_data = $this->get_next_month($y, $m);
            for ($d = 1; $d <= $cal_cells_left; $d++) {
                $cells[] = new PN_CalendarCell($next_month_data['y'], $next_month_data['m'], $d, false);
            }
        } else {
            //***
            if ($this->first_day_of_week == 0) {
                $cal_cells_prev = 6 - (7 - $first_week_day_in_month); //checked, is right
            } else {
                if ($first_week_day_in_month == 1) {
                    $cal_cells_prev = 0;
                } else {
                    if ($first_week_day_in_month == 0) {
                        $cal_cells_prev = 6 - 1;
                    } else {
                        $cal_cells_prev = 6 - (7 - $first_week_day_in_month) - 1;
                    }
                }
            }
            //***
            $prev_month_data = $this->get_prev_month($y, $m);
            $prev_month_days_count = $this->get_days_count_in_month($prev_month_data['y'], $prev_month_data['m']);

            for ($d = $prev_month_days_count - $cal_cells_prev; $d <= $prev_month_days_count; $d++) {
                $cells[] = new PN_CalendarCell_calendar_service($prev_month_data['y'], $prev_month_data['m'], $d, false);
            }

            //***
            for ($d = 1; $d <= $days_count; $d++) {
                $cells[] = new PN_CalendarCell_calendar_service($y, $m, $d);
            }
            //***
            //35(7*5) or 42(7*6) cells
            $busy_cells = $cal_cells_prev + $days_count;
            $cal_cells_left = 0;
            if ($busy_cells < 35) {
                $cal_cells_left = 35 - $busy_cells - 1;
            } else {
                $cal_cells_left = 42 - $busy_cells - 1;
            }
            //***
            if ($cal_cells_left > 0) {
                $next_month_data = $this->get_next_month($y, $m);
                for ($d = 1; $d <= $cal_cells_left; $d++) {
                    $cells[] = new PN_CalendarCell_calendar_service($next_month_data['y'], $next_month_data['m'], $d, false);
                }
            }
        }
        //***
        return $cells;
    }

    public function get_next_month($y, $m) {
        $y = intval($y);
        $m = intval($m);

        //***
        $m++;
        if ($m % 13 == 0 OR $m > 12) {
            $y++;
            $m = 1;
        }

        return array('y' => $y, 'm' => $m);
    }

    public function get_prev_month($y, $m) {
        $y = intval($y);
        $m = intval($m);

        //***
        $m--;
        if ($m <= 0) {
            $y--;
            $m = 12;
        }

        return array('y' => $y, 'm' => $m);
    }

    public function get_days_count_in_month($year, $month) {
        return $month == 2 ? ($year % 4 ? 28 : ($year % 100 ? 29 : ($year % 400 ? 28 : 29))) : (($month - 1) % 7 % 2 ? 30 : 31);
    }

    public static function get_month_name($m) {
        $names = self::get_monthes_names();
        return $names[intval($m)];
    }

    public function get_week_day_name($num, $shortly = false) {
        $names = $this->get_week_days_names($shortly);
        return $names[intval($num)];
    }

    public function get_week_days_names($shortly = false) {
        if ($this->first_day_of_week == 1) {
            if ($shortly) {
                return array(
                    1 => 'Mo',
                    2 => 'Tu',
                    3 => 'We',
                    4 => 'Th',
                    5 => 'Fr',
                    6 => 'Sa',
                    7 => 'Su'
                );
            }

            return array(
                1 => 'Monday',
                2 => 'Tuesday',
                3 => 'Wednesday',
                4 => 'Thursday',
                5 => 'Friday',
                6 => 'Saturday',
                7 => 'Sunday'
            );
        } else {
            if ($shortly) {
                return array(
                    0 => 'Su',
                    1 => 'Mo',
                    2 => 'Tu',
                    3 => 'We',
                    4 => 'Th',
                    5 => 'Fr',
                    6 => 'Sa'
                );
            }

            return array(
                0 => 'Sunday',
                1 => 'Monday',
                2 => 'Tuesday',
                3 => 'Wednesday',
                4 => 'Thursday',
                5 => 'Friday',
                6 => 'Saturday'
            );
        }
    }

    public static function get_monthes_names() {
        return array(
            1 => 'January',
            2 => 'February',
            3 => 'March',
            4 => 'April',
            5 => 'May',
            6 => 'June',
            7 => 'July',
            8 => 'August',
            9 => 'September',
            10 => 'October',
            11 => 'November',
            12 => 'December'
        );
    }

    public function draw_html( $data = array()) {

        @extract($data);
        ob_start();
        ?>




        <div id="pn_calendar">
    <?php
    $prev_data = $this->get_prev_month($year, $month);
    $next_data = $this->get_next_month($year, $month);
    ?>
    <center><a id="pn_get_prev_month" data-month="<?php echo $prev_data['m'] ?>" data-year="<?php echo $prev_data['y'] ?>" href="javascript:void(0);">&DoubleLeftArrow;</a>&nbsp;&nbsp;&nbsp;<a id="pn_get_next_month" data-month="<?php echo $next_data['m'] ?>" data-year="<?php echo $next_data['y'] ?>" href="javascript:void(0);">&DoubleRightArrow;</a></center><br />
    <div class="pn_calendar_list_container">
        <ul class="pn_calendar_list">
            <?php foreach ($week_days_names as $week_day_num => $name) : ?>
            <li style="width:<?php echo 100/count($week_days_names); ?>%"><a class="daynames cellday pn_other_month" href="#" data-week-day="<?php echo $week_day_num ?>"><?php echo $name ?></a></li>
        <?php endforeach; ?>

            <?php foreach ($cells as $cell) : ?>
            <li style="width:<?php echo 100/7; ?>%"><?php $cell->draw($events); ?></li>
        <?php endforeach; ?>
        </ul>
    </div>

    <input type="hidden" id="pn_cal_current_month" value="<?php echo $month ?>" />
    <input type="hidden" id="pn_cal_current_year" value="<?php echo $year ?>" />

</div>
<?php

        return ob_get_clean();
    }

}

//---------------------------------------------------------------------------------------
class PN_CalendarCell_calendar_service {

    public $cell_year = null;
    public $cell_month = null;
    public $cell_day = null;
    public $in_current_month = true;
    public $adult=null;
    public $child=null;
    public $rate_price = null;
    public $flight_id = 0;

    public function __construct($y, $m, $d, $in_current_month = true) {
        $this->cell_year = $y;
        $this->cell_month = $m;
        $this->cell_day = $d;
        $this->in_current_month = $in_current_month;

        $dd=new JDate();
        $dd->setDate($y, $m, $d);
        $flight_id=JFactory::getApplication()->input->get('flight_id');
        $this->flight_id = $flight_id;
        if ($flight_id) {

            $db=JFactory::getDbo();
            $query=$db->getQuery(true);
            $query->select('count(id)')->from('#__bookpro_flightrate')->where(array('flight_id='.$flight_id,'DATE_FORMAT(date,"%Y-%m-%d")='.$db->quote($dd->format('Y-m-d'))));
            $db->setQuery($query);
            $count=$db->loadResult();

        }
        //echo $query;
        //var_dump($roomrate);
        $this->rate_price = false;
        if($count){
            $this->rate_price = true;
            //$this->adult=$roomrate->adult;
            //$this->child=$roomrate->child;
            //$this->id = $roomrate->id;
        }
    }

    public function get_week_day_num() {
        return date('w', mktime(0, 0, 0, $this->cell_month, $this->cell_day, $this->cell_year)); //from 0 (sunday) to 6 (saturday);
    }

    public function draw($events) {
        $this_day_events = 0;
        if (is_array($events)) {
            if (isset($events[$this->cell_year][$this->cell_month][$this->cell_day])) {
                $this_day_events = count($events[$this->cell_year][$this->cell_month][$this->cell_day]);
            }
        } else {
            $events = array();
        }

        ?>

        <span class="pn_cal_cell_ev_counter"
            <?php if ($this_day_events <= 0): ?> style="display: none;"
            <?php endif; ?>><?php echo $this_day_events ?> </span>
        <div data-year="<?php echo $this->cell_year ?>" data-month="<?php echo $this->cell_month ?>" data-day="<?php echo $this->cell_day ?>" data-week-day-num="<?php echo $this->get_week_day_num() ?>" class="<?php echo $this->in_current_month?' pn_this_month ':' other_month ' ?> <?php echo $this->rate_price?' hasrate ':''; ?>">
            <span class="cell_day <?php echo $this->in_current_month?' pn_this_month ':' other_month ' ?>"><?php echo $this->cell_day ?></span>
            <?php
            $input = JFactory::getApplication();
            $flight_id = $input->get('flight_id',0);
            $dd=new JDate();
            $dd->setDate($this->cell_year, $this->cell_month, $this->cell_day);
            $date = $dd->format('Y-m-d');
            ?>
            <?php if ($this->rate_price){

                ?>
                <a class="btn btn-small" onclick="SqueezeBox.fromElement(this, {handler:'iframe', size: {x: 900, y: 600}, url:'<?php echo JUri::base(); ?>index.php?option=com_bookpro&view=ratedetail&flight_id=<?php echo $this->flight_id; ?>&date=<?php echo $date; ?>&tmpl=component'})">
                    View rate
                </a>
            <?php } ?>
            <?php /* ?>
	<div style="width: 100%;">
		<div class="row-fluid">

			<div class="span7">
				<div style="display: block;">
					<?php

					echo $this->cell_day ?>
				</div>
				<?php if ($this->adult){?>
				<div style="display: block;float: left; width: 100%">
					Adult: <?php echo $this->adult ?>
				</div>
				<div style="display: block;float: left;">
					Child: <?php echo $this->child ?>
				</div>
				<?php } ?>
			</div>
			<?php if ($this->adult){?>
			<div class="span4">
				<button style="font-size: 10px;" type="button" onclick="deleteRate(<?php echo $this->id ?>,<?php echo $this->cell_month ?>,<?php echo $this->cell_year ?>)">Delete</button>
			</div>

		</div>

	</div>
	<?php }*/ ?>
        </div>


    <?php
    }

}
?>