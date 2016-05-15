<?php

/**
 * copy_d by PhpStorm.
 * User: cuongnd
 * Date: 15/04/2016
 * Time: 4:38 CH
 */
class alert_warning_website_config
{

    public static  $error=null;
    public static function alert($website_id)
    {
        $user=JFactory::getUser();
        //alert_warning_website_config::remove_duplicate_row();
        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $enableEditWebsite = UtilityHelper::getEnableEditWebsite();
        if(!$enableEditWebsite || !$user->id)
        {
            return false;
        }
        $doc=JFactory::getDocument();
        $scriptId = "script_alert_warning_website_config";
        ob_start();
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                $.alert_warning_website_config=function(reset,current_step,count_error_ajax) {
                    if(typeof reset=='undefined')
                    {
                        reset=0;
                    }
                    var data_submit = {};
                    var option_click = {
                        enable_load_component:1,
                        option: "com_website",
                        task: "utility.ajax_alert_warning_website_config",
                        reset:reset,
                        current_step:current_step
                    };
                    option_click = $.param(option_click);
                    $.ajax({
                        contentType: 'application/json',
                        type: "POST",
                        dataType: "json",
                        url: this_host + '/index.php?' + option_click,
                        data: JSON.stringify(data_submit),
                        beforeSend: function () {
                            $('.div-loading').css({
                                display: "block"


                            });
                        },
                        success: function (response) {
                            $('.div-loading').css({
                                display: "none"


                            });
                            if (response.e == 0) {
                                if (response.finish == 0)
                                {
                                    current_step=response.current_step;
                                    $.alert_warning_website_config('',current_step,count_error_ajax);
                                }
                            } else if (response.e == 1) {
                                var notify = $.notify(response.m, {
                                    allow_dismiss: false,
                                    type:"warning"
                                }
                                );
                            }
                        },
                        error: function(request, status, err) {
                            if (status == "timeout") {
                                // timeout -> reload the page and try again
                                console.log("timeout");
                                $.alert_warning_website_config();
                            } else {
                                if(count_error_ajax>10)
                                {
                                    console.log('too many error ajax');
                                }else {
                                    // another error occured
                                    count_error_ajax++;
                                    $.alert_warning_website_config(1, current_step, count_error_ajax);
                                }
                            }
                        }
                    });

                };
                $.alert_warning_website_config('','',0);
            });
        </script>
        <?php
        $script = ob_get_clean();
        $script = JUtility::remove_string_javascript($script);
        $doc->addScriptDeclaration($script, "text/javascript", $scriptId);
        return;
    }


    public static function next_step()
    {
        $response=new stdClass();
        $response->e=0;
        $response->finish=0;
        $app=JFactory::getApplication();
        $reset=$app->input->getInt('reset',0);
        $steps=alert_warning_website_config::getListStep();
        $session=JFactory::getSession();
        $first_step=reset($steps);
        if($reset)
        {
            $session->clear('function_alert_warning_website_config');
        }
        $function=$session->get('function_alert_warning_website_config','');

        if($function)
        {
            $last_step=end($steps);
            if($function==$last_step)
            {
                $session->clear('function_alert_warning_website_config');
                $response->finish=1;
                return $response;
            }
            for($i=0;$i<count($steps);$i++)
            {
                $step=$steps[$i];
                if($step==$function)
                {
                    $next_function=$steps[$i+1];
                    break;
                }
            }
        }else{
            $next_function=$first_step;
        }
        $ok=true;
        //$next_function='rebuild_blocks';
        if(method_exists('alert_warning_website_config',$next_function))
        {
            $website=JFactory::getWebsite();
            $ok= call_user_func_array(array('alert_warning_website_config', $next_function), array($website->website_id));
        }
        //die;
        if($ok)
        {
            $session->set('function_alert_warning_website_config',$next_function);
        }else{
            $response->e=1;
            $response->m=self::$error;
        }
        $response->current_step=$next_function;
        return $response;

    }

    private static function set_error($ErrorMsg)
    {
        self::$error=$ErrorMsg;
    }


    private function check_exists_menu_item($website_id){
        $exists_home_page=false;
        $list_menu_item=MenusHelperFrontEnd::get_list_menu_item_by_website_id($website_id);
        foreach($list_menu_item as $menu_item)
        {
            if($menu_item->home==1)
            {
                $exists_home_page=true;
                break;
            }
        }
        if(!$exists_home_page)
        {
            self::set_error('there is not exists menu item home page, please set home page');
            return false;
        }
        return true;
    }
    private function check_admin_dashboard($website_id){
        $exists_admin_dashboard=false;
        $list_menu_item=MenusHelperFrontEnd::get_list_menu_item_by_website_id($website_id);
        foreach($list_menu_item as $menu_item)
        {
            if($menu_item->is_main_dashboard==1)
            {
                $exists_admin_dashboard=true;
                break;
            }
        }
        if(!$exists_admin_dashboard)
        {
            self::set_error('there is not exists menu item admin dashboard, please set admin dashboard');
            return false;
        }
        return true;
    }
    private function finish($website_id){
        return true;
    }
    private function getListStep()
    {
        $steps = array();
        $steps[] = 'check_exists_menu_item';
        $steps[] = 'check_home_page';
        $steps[] = 'check_admin_dashboard';
        $steps[] = 'finish';
        return $steps;
    }



}