jQuery(document).ready(function ($) {

    element_ui_scheduler = {
        init_ui_scheduler: function () {

            list_scheduler={};
            $('.block-item.block-item-scheduler.dhx_cal_container').each(function(index){
                self=$(this);
                attr_id=self.attr('id');
                data= self.find('input[type="hidden"].block-item.block-item-scheduler').val();
                data= $.base64.decode(data);
                data= $.parseJSON(data);
                list_scheduler[index]=scheduler;
                scheduler_item= list_scheduler[index];
                scheduler_item.config.multi_day = true;

                scheduler_item.config.event_duration = 35;

                scheduler_item.config.xml_date = "%Y-%m-%d";
                scheduler_item.config.collision_limit = 2;
                scheduler_item.config.occurrence_timestamp_in_utc = true;
                scheduler_item.config.include_end_by = true;
                scheduler_item.config.time_step  = 3600;
                scheduler_item.config.repeat_precise = true;

                scheduler_item.locale.labels.agenda_tab = "My Agenda";

                //'grid_tab' is the name of our div

                scheduler_item.locale.labels.grid_tab = "Grid";

                //to display dates from 1st June 2013

                scheduler_item.config.agenda_start = new Date();



                //to display dates until 1st June,2014

                scheduler_item.config.agenda_end = new Date();




                var events = [
                    {id: 1, text: "Meeting", start_date: "04/11/2013 14:00", end_date: "04/11/2013 17:00"},
                    {id: 2, text: "Conference", start_date: "04/15/2013 12:00", end_date: "04/18/2013 19:00"},
                    {id: 3, text: "Interview", start_date: "04/24/2013 09:00", end_date: "04/24/2013 10:00"}
                ];
                console.log(data);

                scheduler_item.init(attr_id, new Date(), "month");
                scheduler_item.parse(data, "json");//takes the name and format of the data source
            });


            element_ui_button.list_function_run_befor_submit.push(element_ui_scheduler.update_date);
        },
        update_date:function(data_submit){
            $('.block-item.block-item-scheduler.dhx_cal_container').each(function(index){
                self=$(this);
                input_hiden=self.find('input[type="hidden"].block-item.block-item-scheduler');
                name=input_hiden.attr('name');
                json_scheduler=scheduler.toJSON();
                data_submit[name]= $.parseJSON(json_scheduler);
                $('input[type="hidden"].block-item.block-item-scheduler').val( $.base64.encode(json_scheduler));
            });
            return data_submit;
        },

        show_minical: function () {

            if (scheduler.isCalendarVisible())

                scheduler.destroyCalendar();

            else

                scheduler.renderCalendar({

                    position: "dhx_minical_icon",

                    date: scheduler._date,

                    navigation: true,

                    handler: function (date, calendar) {

                        scheduler.setCurrentView(date);

                        scheduler.destroyCalendar()

                    }

                });

        }


    };


});