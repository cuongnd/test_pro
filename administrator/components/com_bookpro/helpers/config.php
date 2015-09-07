<?php

    /**
    * Bookpro config class
    *
    * @package Bookpro
    * @author Nguyen Dinh Cuong
    * @link http://ibookingonline.com
    * @copyright Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
    * @version $Id: config.php 56 2012-07-21 07:53:28Z quannv $
    */
    defined('_JEXEC') or die('Restricted access');

    class BookProConfig
    {
        // Main
        var $offlineMode;
    	var $mainCurrency;
        var $currencySymbol;
        var $currencyDisplay;
        var $currencySeperator;

        var $allowReservations;
        var $displayWhoReserve;
        var $useCaptcha;
        var $unRegisteregCanReserve;
        var $anonymous;
        var $sale_group;

        var $templatesIcons;
        var $dateTypeJoomla;
        var $dateLong;
        var $dateNormal;
        var $dateDay;
        var $dateDayShort;
        var $jpgQuality;
        var $pngQuality;
        var $pngFilter;

        var $firstDaySunday;
        var $quickNavigator;
        var $calendarFutureDays;
        var $calendarDeep;
        var $calendarDeepMonth;
        var $calendarNumMonths;
        var $calendarDeepWeek;
        var $calendarDeepDay;
        var $showFullWeek;
        var $bookCurrentDay;
        var $hideDaysNotBeginFixLimit;
        var $nightsStyle;
        var $colorCalendarFieldReserved;
        var $colorCalendarFieldFree;
        var $colorCalendarUnavailable;
        var $colorCalendarBoxReserved;

        //objects

        var $displaySubjectBack;

        // Main image
        var $displayImage;
        var $subjectThumbWidth;
        var $subjectThumbHeight;

        // Gallery
        var $displayGallery;
        var $galleryThumbWidth;
        var $galleryThumbHeight;
        var $galleryPreviewWidth;
        var $galleryPreviewHeight;
        var $displayProperties;

        // Account
        var $customersUsergroup;
        var $supplierUsergroup;
        var $agentUsergroup;
        var $registerPopup;
        var $autoLogin;

        // Notifications - Registrations
        var $sendRegistrationsEmails;
        var $sendRegistrationsEmailsFrom;
        var $sendRegistrationsEmailsFromname;
        var $sendRegistrationsEmailsSubjectCustomer;
        var $sendRegistrationsEmailsSubjectAdmin;
        var $sendRegistrationsMode;
        var $sendRegistrationsBodyCustomer;
        var $sendRegistrationsBodyAdmin;
        //expedia config
        var $api_key;
        var $minor_rev;
        var $locale;
        var $currency_code;
        var $search_result_count;
        var $sort_default;

        // Reservations setting
        var $rsFirstname;
        var $rsLastname;
        var $rsAddress;
        var $rsCity;
        var $rsCountry;
        var $rsZip;
        var $rsEmail;
        var $rsTelephone;
        var $point_money;

        var $rsFax;
        var $rsState;
        var $rsMobile;
        //Passenger Setting
        var $passengerStatus;
        var $psFirstname;
        var $psLastname;
        var $psPassport;
        var $psPassportValid;
        var $psBirthday;
        var $psCountry;
        var $psGroup;
        var $psGender;

        //add by tuanva
        var $hostname;
        var $dateFormat;
        var $termContent;
        var $bookpro_key;
        //
        var $invoice_header;
        var $invoice_footer;

        function __construct()
        {
            $this->init();
        }

        function init()
        {
            $user = 	JFactory::getUser();
            /* @var $user JUser */

            $mainframe = JFactory::getApplication();
            /* @var $mainframe JApplication */

            //$params = AParameter::loadComponentParams();


            $params = JComponentHelper::getParams(OPTION);

            if ($mainframe->isSite()) {
                $menu = $mainframe->getMenu();
                /* @var $menu JMenuSite */
                $active = $menu->getActive();
                if (is_object($active)) {
                    $activeParams = new JRegistry();
                    $activeParams->loadString($active->params);
                    $params->merge($activeParams);
                }
            }


            $this->images = $params->get('images');
            $this->sale_group = $params->get('sale_group');

            $this->offlineMode=$params->get('offline');
            $this->templatesIcons = $params->get('templates_icons');
            $this->dateTypeJoomla = $params->get('date_type') == 0;
            $this->dateLong = JString::trim($params->get('date_long'));
            $this->dateNormal = JString::trim($params->get('date_normal'));
            $this->dateDay = JString::trim($params->get('date_day'));
            $this->dateDayShort = JString::trim($params->get('date_day_short'));
            $this->point_money = JString::trim($params->get('point_money'));

            $this->jpgQuality = (int) $params->get('jpg_quality', 85);
            $this->pngQuality = (int) $params->get('png_quality', 9);
            $this->pngFilter = (int) $params->get('png_filter');

            $this->displayImage = (int) $params->get('display_image_subject_detail');
            $this->subjectThumbWidth = (int) $params->get('display_thumbs_subject_detail_width');
            $this->subjectThumbHeight = (int) $params->get('display_thumbs_subject_detail_height');

            $this->displayGallery = (int) $params->get('display_gallery_subject_detail');
            $this->galleryThumbWidth = (int) $params->get('display_gallery_thumbs_subject_detail_width');
            $this->galleryThumbHeight = (int) $params->get('display_gallery_thumbs_subject_detail_height');

            $this->galleryPreviewWidth = (int) $params->get('display_gallery_preview_subject_detail_width');
            $this->galleryPreviewHeight = (int) $params->get('display_gallery_preview_subject_detail_height');


            $this->displayWhoReserve = (int) $params->get('display_who_reserve');
            $this->mainCurrency = $params->get('main_currency');

            $this->allowReservations = (int) $params->get('allow_reservations');
            $this->unRegisteregCanReserve = $this->allowReservations == 1;
            $this->customersUsergroup = (int) $params->get('customers_usergroup', CUSTOMER_GID);
            $this->supplierUsergroup = (int) $params->get('supplier_usergroup');
            $this->agentUsergroup = (int) $params->get('agent_usergroup', CUSTOMER_GID);
            $this->anonymous = ! $user->id && $this->unRegisteregCanReserve;
            $this->useCaptcha = (int) $params->get('use_captcha');
            $this->displaySubjectBack = (int) $params->get('display_subject_back', 1);

            $this->displayThumbs = (int) $params->get('display_thumbs_subjects_list');
            $this->thumbWidth = (int) $params->get('display_thumbs_subjects_list_width');
            $this->thumbHeight = (int) $params->get('display_thumbs_subjects_list_height');
            $this->displayReadmore = (int) $params->get('display_readmore_subjects_list');
            $this->displaySubjectsProperties = (int) $params->get('subjects_properties', 1);
            $this->readmoreLength = (int) $params->get('display_readmore_subjects_list_length');
            $this->displayProperties = (int) $params->get('display_properties_subject_detail');
            $this->displayFilter = (int) $params->get('subjects_list_filter', 1);

            // Notifications - Registrations
            $this->sendRegistrationsEmails = (int) $params->get('send_registrations_emails');
            $this->sendRegistrationsEmailsFrom = JString::trim($params->get('send_registrations_emails_from'));
            $this->sendRegistrationsEmailsFromname = JString::trim($params->get('send_registrations_emails_fromname'));
            $this->sendRegistrationsEmailsSubjectCustomer = JString::trim($params->get('send_registrations_emails_subject_customer'));
            $this->sendRegistrationsEmailsSubjectAdmin = JString::trim($params->get('send_registrations_emails_subject_admin'));
            $this->sendRegistrationsMode = JString::trim($params->get('send_registrations_mode'));
            $this->sendRegistrationsBodyCustomer = JString::trim($params->get('send_registrations_body_customer'));
            $this->sendRegistrationsBodyAdmin = JString::trim($params->get('send_registrations_body_admin'));
            //
            $this->rsFirstname = (int) $params->get('rs_firstname', 1);
            $this->rsLastname = (int) $params->get('rs_lastname', 1);
            $this->rsAddress = (int) $params->get('rs_address', 1);
            $this->rsCity = (int) $params->get('rs_city', 1);
            $this->rsCountry = (int) $params->get('rs_country', 1);
            $this->rsZip = (int) $params->get('rs_zip', 1);
            $this->rsEmail = (int) $params->get('rs_email', 1);
            $this->rsTelephone = (int) $params->get('rs_telephone', 1);
            $this->rsMobile = (int) $params->get('rs_mobile', 1);
            $this->rsState = (int) $params->get('rs_states', 1);
            //
            $this->passengerStatus = (int) $params->get('passenger_status', 1);
            $this->psFirstname = (int) $params->get('ps_firstname', 1);
            $this->psLastname = (int) $params->get('ps_lastname', 1);
            $this->psPassport = (int) $params->get('ps_passport', 1);
            $this->psPassportValid = (int) $params->get('ps_ppvalid', 1);
            $this->psBirthday = (int) $params->get('ps_birthday', 1);
            $this->psCountry = (int) $params->get('ps_country', 1);
            $this->psGroup = (int) $params->get('ps_group', 1);
            $this->psGender = (int) $params->get('ps_gender', 1);

            // account - reservations
            $this->autoLogin = (int) $params->get('auto_login');
            $this->registerPopup = JString::trim($params->get('register_popup'));
            $this->serviceCost = (int) $params->get('service_cost', 1);
            $this->hostname =JString::trim($params->get('hostname'));
            $this->dateFormat= JString::trim($params->get('date_format'));
            $this->currencySymbol= JString::trim($params->get('currency_symbol'));
            $this->currencyDisplay= JString::trim($params->get('currency_display'));
            $this->currencySeperator= JString::trim($params->get('currency_seperator'));
            $this->termContent= JString::trim($params->get('term_content_id'));
            $this->bookpro_key= JString::trim($params->get('bookpro_key'));
            $this->invoice_footer=JString::trim($params->get('invoice_footer'));
            $this->invoice_header=JString::trim($params->get('invoice_header'));

            //calendar
            $this->firstDaySunday = (int) $params->get('first_day', 0);
            $this->quickNavigator = (int) $params->get('quick_navigator', 1);
            $this->calendarFutureDays = $params->get('calendar_future_days', 0);
            $this->calendarDeepMonth = (int) $params->get('calendar_deep_month', 5);
            $this->calendarNumMonths = (int) $params->get('calendar_num_months', 1);
            $this->calendarDeepWeek = (int) $params->get('calendar_deep_week', 20);
            $this->calendarDeepDay = (int) $params->get('calendar_deep_day', 100);
            $this->showFullWeek = (int) $params->get('show_full_week',0);
            $this->bookCurrentDay = (int) $params->get('book_current_day', 0);
            $this->hideDaysNotBeginFixLimit = (int) $params->get('hide_days_not_begin_fix_limit', 0);
            $this->nightsStyle = (int) $params->get('nights_style', 1);
            $this->colorCalendarFieldReserved = $params->get('color_calendar_field_reserved', 0);
            $this->colorCalendarFieldFree = $params->get('color_calendar_field_free', 0);
            $this->colorCalendarUnavailable = $params->get('color_calendar_unavailable', 0);
            $this->colorCalendarBoxReserved = $params->get('color_calendar_box_reserved', 0);

            if ($this->colorCalendarFieldReserved && JString::strpos($this->colorCalendarFieldReserved, '#') !== 0)
                $this->colorCalendarFieldReserved = '#' . $this->colorCalendarFieldReserved; // color picker does not fill color code with #

            if ($this->colorCalendarFieldFree && JString::strpos($this->colorCalendarFieldFree, '#') !== 0)
                $this->colorCalendarFieldFree = '#' . $this->colorCalendarFieldFree; // color picker does not fill color code with #

            if ($this->colorCalendarUnavailable && JString::strpos($this->colorCalendarUnavailable, '#') !== 0)
                $this->colorCalendarUnavailable = '#' . $this->colorCalendarUnavailable; // color picker does not fill color code with #

            if ($this->colorCalendarBoxReserved && JString::strpos($this->colorCalendarBoxReserved, '#') !== 0)
                $this->colorCalendarBoxReserved = '#' . $this->colorCalendarBoxReserved;

        }
    }

?>