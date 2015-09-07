

<div id="pn_calendar">
    <center><?php
            if ($this->show_month_selector) {
                ?>
                <select id="pn_calendar_month_selector" class="input-medium">
                    <?php foreach ($this->get_monthes_names() as $m => $month_name) : ?>
                        <option value="<?php echo $m ?>" <?php if ($month == $m): ?>selected=""<?php endif; ?>><?php echo $month_name ?></option>
                    <?php endforeach; ?>
                </select>
                <?php
            } else {
                echo $month_name;
            }
            ?>, <?php
            if ($this->show_year_selector) {
                ?>
                <select id="pn_calendar_year_selector" class="input-medium">
                    <?php for ($y = $this->min_select_year; $y <= $this->max_select_year; $y++): ?>
                        <option value="<?php echo $y ?>" <?php if ($year == $y): ?>selected=""<?php endif; ?>><?php echo $y ?></option>
                    <?php endfor; ?>
                </select>
                <?php
            } else {
                echo $year;
            }
            ?></center>
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
