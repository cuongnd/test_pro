<?php
/**
 * @package    Bookpro
 * @author        Nguyen Dinh Cuong
 * @link        http://ibookingonline.com
 * @copyright    Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @license    GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version    $Id: view.html.php  2-04-2014 6:16:16
 **/
// No direct access
defined('_JEXEC') or die ('Restricted access');
JHtmlBehavior::framework();
JHtml::_('jquery.ui');
JHtml::_('jquery.framework');
JHtml::_('behavior.calendar');
JHtml::_('behavior.formvalidation');
JHtml::_('bootstrap.framework');

$document = JFactory::getDocument();
$document->addScript("http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js");
if ($local != 'en') {
    $document->addScript(JURI::root() . 'components/com_bookpro/assets/js/validatei18n/messages_' . $local . '.js');
}
$document->addScript(JUri::root() . 'components/com_bookpro/assets/js/view-customtrip.js');
$document->addScript(JURI::root() . 'components/com_bookpro/assets/js/jquery.ui.datepicker.js');
$document->addStyleSheet(JUri::root() . 'components/com_bookpro/assets/css/jquery-ui.css');

?>

<form action = "" method = "post" id = "formCustomtrip" name = "formCustomtrip" onsubmit = "" class = "form-validate" enctype = "multipart/form-data">

    <table width = "600" border = "0" cellspacing = "0" cellpadding = "0">
        <tbody>
        <tr>
            <td width = "5" bgcolor = "#336699" rowspan = "5">&nbsp;</td>
            <td width = "15" bgcolor = "#336699">&nbsp;</td>
            <td bgcolor = "#336699">
                <table width = "100%" border = "0" cellspacing = "0" cellpadding = "0">
                    <tbody>
                    <tr>
                        <td width = "80%" class = "formname" rowspan = "2">CUSTOMIZED TRIP </td>
                        <td width = "270" valign = "bottom" height = "20" colspan = "5"></td>
                    </tr>
                    <tr>
                        <td width = "28">&nbsp;</td>
                        <td width = "50">
                            <a href = "JavaScript:printthis()"><img width = "50" height = "20" border = "0" src = "http://asianventure.com/images/common/dmenu1.gif"></a>
                        </td>
                        <td width = "77">
                            <a href = "javascript:window.external.AddFavorite( location.href,'Asianventure Tours')"><img width = "77" height = "20" border = "0" src = "http://asianventure.com/images/common/dmenu2.gif"></a>
                        </td>
                        <td width = "76">
                            <a href = "javascript:tellfriend();"><img width = "78" height = "20" border = "0" src = "http://asianventure.com/images/common/dmenu3.gif"></a>
                        </td>
                        <td width = "39">&nbsp;</td>
                    </tr>
                    </tbody>
                </table>
            </td>
            <td width = "15" bgcolor = "#336699">&nbsp;</td>
            <td width = "5" bgcolor = "#336699" rowspan = "5"><img width = "5" height = "1" border = "0" src = "../images/common/blank.gif"></td>
        </tr>
        <tr>
            <td width = "15" valign = "top" align = "left"><img width = "15" height = "15" border = "0" src = "http://asianventure.com/images/common/corner31_left.gif"></td>
            <td>&nbsp;</td>
            <td width = "15" valign = "top" align = "right"><img width = "15" height = "15" border = "0" src = "http://asianventure.com/images/common/corner33_right.gif"></td>
        </tr>
        <tr>
            <td width = "15" rowspan = "2">&nbsp;</td>
            <td valign = "top">
                <table width = "100%" border = "0" cellspacing = "0" cellpadding = "0" class = "tableform">
                    <tbody>
                    <tr>
                        <td width = "100%" height = "30" class = "buildexpert">BUILD YOUR DREAM HOLIDAY WITH OUR EXPERTS</td>
                    </tr>

                    <tr>
                        <td width = "100%"></td>
                    </tr>
                    </tbody>
                </table>
            </td>
            <td width = "15" rowspan = "2">&nbsp;</td>
        </tr>
        <tr>
            <td valign = "top" class = "information">
                <form onsubmit = "return validate(this)" name = "formcustom" method = "POST" action = "mailcustomtrip.php">
                    <table width = "100%" border = "0" cellspacing = "0" cellpadding = "0" class = "tableform">
                        <tbody>
                        <tr>
                            <td colspan = "4">
                                <table width = "100%" border = "0" cellspacing = "0" cellpadding = "0">
                                    <tbody>
                                    <tr>
                                        <td style = "padding-top:10px; text-align:center; font:Arial; font-weight:bold; font-size:10pt;color:#406f9f" colspan = "3">STICK YOUR PREFERRED SERVICES<br>
                                            <font style = " padding-top:2px; padding-bottom:5px;text-align:center; font:Arial; font-weight:bold; font-size:9pt; color:#406f9f">( The symbol * is required filed. Your personal data is protected by the terms and conditions)</font>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan = "3">
                                            <table width = "100%" border = "0" cellspacing = "0" cellpadding = "0">
                                                <tbody>
                                                <tr>
                                                    <td style = "padding-top:20px;" class = "fieldcustom2" colspan = "4">Where would you like to visit in Vietnam ?
                                                        <a href = "javascript:popImage('../images/common/vietnam_map_details.png','Vietnam Map');">(Click below sight for reference)</a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width = "25%" valign = "top" class = "fieldcustom3">
                                                        <input type = "checkbox" value = "Ho Chi Minh" name = "vietnam[]">
                                                        Ho Chi Minh
                                                        <br>
                                                        <input type = "checkbox" value = "My Tho" name = "vietnam[]">
                                                        Mytho/Mekong
                                                        <br>
                                                        <input type = "checkbox" value = "Can Tho" name = "vietnam[]">
                                                        Cantho/Mekong
                                                        <br>
                                                        <input type = "checkbox" value = "Chau Doc" name = "vietnam[]">
                                                       Chaudoc/Mekong
                                                        <br>
                                                        <input type = "checkbox" value = "Tay Ninh/Cu Chi Tunnels" name = "vietnam[]">
                                                        Tay Ninh/Cu Chi
                                                        <br>
                                                        <input type = "checkbox" value = "Vung Tau Beach" name = "vietnam[]">
                                                        Vung Tau Beach
                                                        <br>
                                                        <input type = "checkbox" value = "Phu Quoc Island" name = "vietnam[]">
                                                        Phu Quoc Island
                                                        <br>
                                                        <input type = "checkbox" value = "Muine/Phan Thiet" name = "vietnam[]">
                                                        Muine/Phan Thiet
                                                    </td>

                                                    <td width = "25%" valign = "top" class = "fieldcustom3">
                                                        <input type = "checkbox" value = "Nha Trang Beach" name = "vietnam[]">
                                                        <a target = "_blank" href = "/vietnam/guide/khanh_hoa/nha_trang.html">Nha Trang Beach</a>
                                                        <br>
                                                        <input type = "checkbox" value = "Dalat" name = "vietnam[]">
                                                        <a target = "_blank" href = "/vietnam/guide/lam_dong/da_lat.html">Dalat</a>
                                                        <br>
                                                        <input type = "checkbox" value = "Buon Ma Thuot" name = "vietnam[]">
                                                        <a target = "_blank" href = "/vietnam/guide/daklak/buon_ma_thuot.html">Buon Ma Thuot</a>
                                                        <br>
                                                        <input type = "checkbox" value = "Pleiku/Komtum" name = "vietnam[]">
                                                        <a target = "_blank" href = "/vietnam/guide/gia_lai/pleiku.html">Pleiku</a>
                                                        /
                                                        <a target = "_blank" href = "/vietnam/guide/kontum.html">Komtum</a>
                                                        <br>
                                                        <input type = "checkbox" value = "Quy Nhon" name = "vietnam[]">
                                                        <a target = "_blank" href = "/vietnam/guide/binh_dinh/quy_nhon.html">Quy Nhon</a>
                                                        <br>
                                                        <input type = "checkbox" value = "Hoi An" name = "vietnam[]">
                                                        <a target = "_blank" href = "/vietnam/guide/quang_nam/hoi_an.html">Hoi An</a>
                                                        <br>
                                                        <input type = "checkbox" value = "My Son" name = "vietnam[]">
                                                        <a target = "_blank" href = "/vietnam/guide/quang_nam/attraction/my_son_cham_relics.html">My Son</a>
                                                        <br>
                                                        <input type = "checkbox" value = "Da Nang" name = "vietnam[]">
                                                        <a target = "_blank" href = "/vietnam/guide/da_nang.html">Da Nang</a>
                                                    </td>

                                                    <td width = "25%" valign = "top" class = "fieldcustom3">
                                                        <input type = "checkbox" value = "Hue" name = "vietnam[]">
                                                        <a target = "_blank" href = "/vietnam/guide/hue.html">Hue</a>
                                                        <br>
                                                        <input type = "checkbox" value = "Quang Tri/DMZ" name = "vietnam[]">
                                                        <a target = "_blank" href = "/vietnam/guide/quang_tri.html">Quang Tri</a>
                                                        /
                                                        <a target = "_blank" href = "/vietnam/guide/quang_tri/attraction/dmz.html">DMZ</a>
                                                        <br>
                                                        <input type = "checkbox" value = "Hanoi" name = "vietnam[]">
                                                        <a target = "_blank" href = "/vietnam/guide/hanoi.html">Hanoi</a>
                                                        <br>
                                                        <input type = "checkbox" value = "Halong Bay" name = "vietnam[]">
                                                        <a target = "_blank" href = "/vietnam/guide/quang_ninh/halong.html">Halong Bay</a>
                                                        <br>
                                                        <input type = "checkbox" value = "Cat Ba Island" name = "vietnam[]">
                                                        <a target = "_blank" href = "/vietnam/guide/hai_phong/cat_ba.html">Cat Ba Island</a>
                                                        <br>
                                                        <input type = "checkbox" value = "Perfume Pagoda" name = "vietnam[]">
                                                        <a target = "_blank" href = "/vietnam/guide/ha_tay/attraction/perfume_pagoda_area.html">Perfume Pagoda</a>
                                                        <br>
                                                        <input type = "checkbox" value = "Hoa Lu/Tamcoc" name = "vietnam[]">
                                                        <a target = "_blank" href = "/vietnam/guide/ninh_binh/attraction/hoa_lu_ancient_capital.html">Hoa Lu</a>
                                                        /
                                                        <a target = "_blank" href = "/vietnam/guide/ninh_binh/attraction/bich_dong_and_tam_coc.html">Tam Coc</a>
                                                        <br>
                                                        <input type = "checkbox" value = "Mai Chau" name = "vietnam[]">
                                                        <a target = "_blank" href = "/vietnam/guide/hoa_binh/mai_chau.html">Mai Chau</a>
                                                    </td>

                                                    <td width = "25%" valign = "top" class = "fieldcustom3">
                                                        <input type = "checkbox" value = "Dien Bien Phu" name = "vietnam[]">
                                                        <a target = "_blank" href = "/vietnam/guide/dien_bien/attraction/dien_bien_phu_battle_field.html">Dien Bien Phu</a>
                                                        <br>
                                                        <input type = "checkbox" value = "Sapa" name = "vietnam[]">
                                                        <a target = "_blank" href = "/vietnam/guide/lao_cai/sapa.html">Sapa Hillstation</a>
                                                        <br>
                                                        <input type = "checkbox" value = "Ha Giang" name = "vietnam[]">
                                                        <a target = "_blank" href = "/vietnam/guide/ha_giang.html">Ha Giang</a>
                                                        <br>
                                                        <input type = "checkbox" value = "Babe Lakes" name = "vietnam[]">
                                                        <a target = "_blank" href = "/vietnam/guide/bac_kan/attraction/babe_lakes.html">Babe Lakes</a>
                                                        <br>
                                                        <input type = "checkbox" value = "Cao Bang" name = "vietnam[]">
                                                        <a target = "_blank" href = "/vietnam/guide/cao_bang.html">Cao Bang</a>
                                                        <br>
                                                        <input type = "checkbox" value = "Lang Son" name = "vietnam[]">
                                                        <a target = "_blank" href = "/vietnam/guide/lang_son.html">Lang Son</a>
                                                        <br> </td></tr>
                                                <tr>
                                                    <td class = "fieldcustom2">Other</td>
                                                    <td colspan = "3"><input type = "text" class = "field" size = "40" name = "other1"></td>
                                                </tr>
                                                <tr>
                                                    <td class = "fieldcustom2" colspan = "4">Where would you like to visit in Cambodia ?
                                                        <a href = "javascript:popImage('../images/common/cambodia_map_details.png','Cambodia Map');">(Click below sight for reference)</a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td valign = "top" class = "fieldcustom3">
                                                        <input type = "checkbox" value = "Phnompenh" name = "cambodia[]">
                                                        <a target = "_blank" href = "/cambodia/guide/phnom_penh.html">Phnompenh</a>
                                                        <br>
                                                        <input type = "checkbox" value = "Tonle Bati" name = "cambodia[]">
                                                        <a target = "_blank" href = "/cambodia/guide/phnom_penh.html">Tonle Bati</a>
                                                        <br>
                                                        <input type = "checkbox" value = "Kampot" name = "cambodia[]">
                                                        <a target = "_blank" href = "/cambodia/guide/kampot.html">Kampot</a>
                                                        <br>
                                                        <input type = "checkbox" value = "Kep Beach" name = "cambodia[]">
                                                        <a target = "_blank" href = "/cambodia/guide/kep.html">Kep Beach</a>
                                                    </td>

                                                    <td valign = "top" class = "fieldcustom3">
                                                        <input type = "checkbox" value = "Sihanoukville Beach" name = "cambodia[]">
                                                        <a target = "_blank" href = "/cambodia/guide/sihanoukville.html">Sihanoukville
                                                            Beach
                                                        </a>
                                                        <br>
                                                        <input type = "checkbox" value = "Koh Kong Island" name = "cambodia[]">
                                                        <a target = "_blank" href = "/cambodia/guide/koh_kong.html">Koh Kong Island</a>
                                                        <br>
                                                        <input type = "checkbox" value = "Mondulkiri" name = "cambodia[]">
                                                        <a target = "_blank" href = "/cambodia/guide/mondulkiri.html">Mondulkiri</a>
                                                        <br>
                                                        <input type = "checkbox" value = "Ratanakiri" name = "cambodia[]">
                                                        <a target = "_blank" href = "/cambodia/guide/ratanakiri.html">Ratanakiri</a>
                                                    </td>

                                                    <td valign = "top" class = "fieldcustom3">
                                                        <input type = "checkbox" value = "Stung Treng" name = "cambodia[]">
                                                        <a target = "_blank" href = "/cambodia/guide/stung_treng.html">Stung Tren</a>
                                                        g<br>
                                                        <input type = "checkbox" value = "Kratie" name = "cambodia[]">
                                                        <a target = "_blank" href = "/cambodia/guide/kratie.html">Kratie</a>
                                                        <br>
                                                        <input type = "checkbox" value = "Kampong Cham" name = "cambodia[]">
                                                        <a target = "_blank" href = "/cambodia/guide/kampong_chhnang.html">Kampong Cham</a>
                                                        <br>
                                                        <input type = "checkbox" value = "Kampong Thom" name = "cambodia[]">
                                                        <a target = "_blank" href = "/cambodia/guide/kampong_thom.html">Kampong Thom</a>
                                                    </td>

                                                    <td valign = "top" class = "fieldcustom3">
                                                        <input type = "checkbox" value = "Battambang" name = "cambodia[]">
                                                        <a target = "_blank" href = "/cambodia/guide/battambang.html">Battambang</a>
                                                        <br>
                                                        <input type = "checkbox" value = "Siemreap/Angkor Temples" name = "cambodia[]">
                                                        <a target = "_blank" href = "/cambodia/guide/siem_reap.html">Siemreap</a>
                                                        /
                                                        <a target = "_blank" href = "/cambodia/guide/siem_reap/angkor_temples.html">Angkor Temples</a>
                                                        <br>
                                                        <input type = "checkbox" value = "Tonle Sap Lake" name = "cambodia[]">
                                                        <a target = "_blank" href = "/cambodia/guide/siem_reap/attraction/tonle_sap_lake.html">Tonle Sap Lake</a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class = "fieldcustom2">Other</td>
                                                    <td colspan = "3"><input type = "text" class = "field" size = "40" name = "other2"> </td>
                                                </tr>
                                                <tr>
                                                    <td class = "fieldcustom2" colspan = "4">Where would you like to visit in Laos ?
                                                        <a href = "javascript:popImage('../images/common/laos_map_details.png','Laos Map');">(Click below sight for reference)</a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td valign = "top" class = "fieldcustom3">
                                                        <input type = "checkbox" value = "Vientiane" name = "laos[]">
                                                        <a target = "_blank" href = "/laos/guide/vientiane.html">Vientiane</a>
                                                        <br>
                                                        <input type = "checkbox" value = "Nam Ngun Lake" name = "laos[]">
                                                        <a target = "_blank" href = "/laos/guide/vientiane/attraction/nam_ngun_lake.html">Nam Ngun Lake</a>
                                                        <br>
                                                        <input type = "checkbox" value = "Vang Vieng" name = "laos[]">
                                                        <a target = "_blank" href = "/laos/guide/vientiane/attraction/caves_in_vang_vieng.html">Vang Vieng</a>
                                                        <br>
                                                        <input type = "checkbox" value = "Xiengkhouang" name = "laos[]">
                                                        <a target = "_blank" href = "/laos/guide/xieng_khouang.html">Xiengkhouang</a>
                                                        <br>
                                                        <input type = "checkbox" value = "Luang Prabang" name = "laos[]">
                                                        <a target = "_blank" href = "/laos/guide/luang_prabang.html">Luang Prabang</a>
                                                    </td>

                                                    <td valign = "top" class = "fieldcustom3">
                                                        <input type = "checkbox" value = "Muong Ngoi" name = "laos[]">
                                                        <a target = "_blank" href = "/laos/guide/luang_prabang/muang_ngoi.html">Muong Ngoi</a>
                                                        <br>
                                                        <input type = "checkbox" value = "Khouang Si Waterfall" name = "laos[]">
                                                        <a target = "_blank" href = "/laos/guide/luang_prabang/attraction/khouang_si_waterfall.html">Khouang Si Waterfall</a>
                                                        <br>
                                                        <input type = "checkbox" value = "Pakbeng" name = "laos[]">
                                                        <a target = "_blank" href = "#">Pakbeng</a>
                                                        <br>
                                                        <input type = "checkbox" value = "Houei Xai" name = "laos[]">
                                                        <a target = "_blank" href = "/laos/guide/bokeo/houei_xai.html">Houei Xai</a>
                                                        <br>
                                                        <input type = "checkbox" value = "Luang Namtha" name = "laos[]">
                                                        <a target = "_blank" href = "/laos/guide/luang_namtha.html">Luang Namtha</a>
                                                    </td>

                                                    <td valign = "top" class = "fieldcustom3">
                                                        <input type = "checkbox" value = "Muang Sing" name = "laos[]">
                                                        <a target = "_blank" href = "/laos/guide/luang_namtha/muang_sing.html">Muang Sing</a>
                                                        <br>
                                                        <input type = "checkbox" value = "Oudomxay" name = "laos[]">
                                                        <a target = "_blank" href = "/laos/guide/oudomxay.html">Oudomxay</a>
                                                        <br>
                                                        <input type = "checkbox" value = "Phongsaly" name = "laos[]">
                                                        <a target = "_blank" href = " /laos/guide/phong_saly.html">Phongsaly</a>
                                                        <br>
                                                        <input type = "checkbox" value = "Thakhek/Khonglor Cave" name = "laos[]">
                                                        <a target = "_blank" href = "/laos/guide/khammouane/thakhaek.html">Thakhek</a>
                                                        /
                                                        <a target = "_blank" href = "/laos/guide/khammouane/konglor.html">Khonglor Cave</a>
                                                        <br>
                                                        <input type = "checkbox" value = "Savannakhet" name = "laos[]">
                                                        <a target = "_blank" href = "/laos/guide/savannakhet.html">Savannakhet</a>
                                                    </td>

                                                    <td valign = "top" class = "fieldcustom3">
                                                        <input type = "checkbox" value = "Pakse" name = "laos[]">
                                                        <a target = "_blank" href = "/laos/guide/champasak/pakse.html">Pakse</a>
                                                        <br>
                                                        <input type = "checkbox" value = "Watphou Temple" name = "laos[]">
                                                        <a target = "_blank" href = "/laos/guide/champasak/attraction/wat_phou_temple.html">Watphou Temple</a>
                                                        <br>
                                                        <input type = "checkbox" value = "Tadlo Waterfall" name = "laos[]">
                                                        <a target = "_blank" href = "/laos/guide/salavan/tadlo.html">Tadlo Waterfall</a>
                                                        <br>
                                                        <input type = "checkbox" value = "Khong Island" name = "laos[]">
                                                        <a target = "_blank" href = "/laos/guide/champasak/khong.html">Khong Island</a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class = "fieldcustom2">Other</td>
                                                    <td colspan = "3"><input type = "text" class = "field" size = "40" name = "other3"> </td>
                                                </tr>
                                                <tr>
                                                    <td class = "fieldcustom2" colspan = "4">Where would you like to visit in Thailand ?
                                                        <a href = "javascript:popImage('../images/common/thailand_map_details.png','Thailand Map');">(Click below sight for reference)</a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td valign = "top" class = "fieldcustom3">
                                                        <input type = "checkbox" value = "Bangkok" name = "thailand[]">
                                                        <a target = "_blank" href = "/thailand/guide/bangkok.html">Bangkok</a>
                                                        <br>
                                                        <input type = "checkbox" value = "Ayutthaya" name = "thailand[]">
                                                        <a target = "_blank" href = "/thailand/guide/ayutthaya.html">Ayutthaya</a>
                                                        <br>
                                                        <input type = "checkbox" value = "Damnern Saduak Floating Market" name = "thailand[]">
                                                        <a target = "_blank" href = "/thailand/guide/bangkok/attraction/chatuchak_market.html">Damnern Saduak Market</a>
                                                        <br>
                                                        <input type = "checkbox" value = "Kanchanaburi/River of Kwai" name = "thailand[]">
                                                        <a target = "_blank" href = "/thailand/guide/kanchanaburi.html">Kanchanaburi</a>
                                                        /
                                                        <a target = "_blank" href = "/thailand/guide/kanchanaburi/attraction/bridge_on_the_river_khwae_or__death_railway_bridge.html">River Kwai</a>
                                                        <br>
                                                        <input type = "checkbox" value = "Sai Yok National Park" name = "thailand[]">
                                                        <a target = "_blank" href = "/thailand/guide/kanchanaburi/sai_yok.html">Sai Yok Forest Park</a>
                                                        <br>
                                                        <input type = "checkbox" value = "Korat" name = "thailand[]">
                                                        <a target = "_blank" href = "/thailand/guide/nakhon_ratchasima.html">Korat</a>
                                                        <br>
                                                        <input type = "checkbox" value = "Ubon Ratchathani" name = "thailand[]">
                                                        <a target = "_blank" href = "/thailand/guide/ubon_ratchathani.html">Ubon Ratchathani</a>
                                                        <br>
                                                        <input type = "checkbox" value = "Phitsanulok" name = "thailand[]">
                                                        <a target = "_blank" href = "/thailand/guide/phitsanulok.html">Phitsanulok</a>
                                                    </td>

                                                    <td valign = "top" class = "fieldcustom3">
                                                        <input type = "checkbox" value = "Sukhothai" name = "thailand[]">
                                                        <a target = "_blank" href = "/thailand/guide/sukhothai.html">Sukhothai</a>
                                                        <br>
                                                        <input type = "checkbox" value = "Lampang" name = "thailand[]">
                                                        <a target = "_blank" href = "/thailand/guide/lampang.html">Lampang</a>
                                                        <br>
                                                        <input type = "checkbox" value = "Lampun" name = "thailand[]">
                                                        <a target = "_blank" href = "/thailand/guide/lamphun.html">Lampun</a>
                                                        <br>
                                                        <input type = "checkbox" value = "Chiang Mai" name = "thailand[]">
                                                        <a target = "_blank" href = "/thailand/guide/chiang_mai.html">Chiang Mai</a>
                                                        <br>
                                                        <input type = "checkbox" value = "Mae Hong Son" name = "thailand[]">
                                                        <a target = "_blank" href = "/thailand/guide/mae_hong_son.html">Mae Hong Son</a>
                                                        <br>
                                                        <input type = "checkbox" value = "Pai Valley" name = "thailand[]">
                                                        <a target = "_blank" href = "/thailand/guide/mae_hong_son/pai.html">Pai Valley</a>
                                                        <br>
                                                        <input type = "checkbox" value = "Chiang Dao" name = "thailand[]">
                                                        <a target = "_blank" href = "/thailand/guide/chiang_mai/chiang_dao.html">Chiang Dao</a>
                                                        <br>
                                                        <input type = "checkbox" value = "Thaton" name = "thailand[]">
                                                        <a target = "_blank" href = "/thailand/guide/chiang_mai/mae_ai.html">Thaton</a>
                                                    </td>

                                                    <td valign = "top" class = "fieldcustom3">
                                                        <input type = "checkbox" value = "chiang Rai" name = "thailand[]">
                                                        <a target = "_blank" href = "/thailand/guide/chiang_rai.html">Chiang Rai</a>
                                                        <br>
                                                        <input type = "checkbox" value = "Chiang Saen/Golden Triangle" name = "thailand[]">
                                                        <a target = "_blank" href = "/thailand/guide/chiang_rai/chiang_saen.html">Chiang Saen</a>
                                                        /
                                                        <a target = "_blank" href = "/thailand/guide/chiang_rai/attraction/golden_triangle.html">Golden Triangle</a>
                                                        <br>
                                                        <input type = "checkbox" value = "Pattaya Beach" name = "thailand[]">
                                                        <a target = "_blank" href = "/thailand/guide/chon_buri/pattaya.html">Pattaya Beach</a>
                                                        <br>
                                                        <input type = "checkbox" value = "Raynong Beach" name = "thailand[]">
                                                        <a target = "_blank" href = "/thailand/guide/ranong.html">Raynong Beach</a>
                                                        <br>
                                                        <input type = "checkbox" value = "Ko Chang Island" name = "thailand[]">
                                                        <a target = "_blank" href = "/thailand/guide/trat/ko_chang.html">Ko Chang Island</a>
                                                        <br>
                                                        <input type = "checkbox" value = "Cha Am Beach" name = "thailand[]">
                                                        <a target = "_blank" href = "/thailand/guide/phetchaburi/cha_am.html">Cha Am Beach</a>
                                                        <br>
                                                        <input type = "checkbox" value = "Hua Hin Beach" name = "thailand[]">
                                                        <a target = "_blank" href = "/thailand/guide/prachuap_khiri_khan/hua_hin.html">Hua Hin Beach</a>
                                                        <br>
                                                        <input type = "checkbox" value = "Ko Samui Beach" name = "thailand[]">
                                                        <a target = "_blank" href = "/thailand/guide/surat_thani/ko_samui.html">Ko Samui Beach</a>
                                                    </td>


                                                    <td valign = "top" class = "fieldcustom3">
                                                        <input type = "checkbox" value = "Khao Lak Beach" name = "thailand[]">
                                                        <a target = "_blank" href = "/thailand/guide/phang_nga/khao_lak.html">Khao Lak Beach</a>
                                                        <br>
                                                        <input type = "checkbox" value = "Khaio Sok Park" name = "thailand[]">
                                                        <a target = "_blank" href = "/thailand/guide/surat_thani/attraction/khao_sok_national_park.html">Khao Sok Park</a>
                                                        <br>
                                                        <input type = "checkbox" value = "Phuket Beach" name = "thailand[]">
                                                        <a target = "_blank" href = "/thailand/guide/phuket.html">Phuket Beach</a>
                                                        <br>
                                                        <input type = "checkbox" value = "Ko Phi Phi Island" name = "thailand[]">
                                                        <a target = "_blank" href = "/thailand/guide/krabi/ko_phi_phi.html">Ko Phi Phi Island</a>
                                                        <br>
                                                        <input type = "checkbox" value = "Phang Nga Bay" name = "thailand[]">
                                                        <a target = "_blank" href = "/thailand/guide/phang_nga.html">Phang Nga Bay</a>
                                                        <br>
                                                        <input type = "checkbox" value = "Krabi Beach" name = "thailand[]">
                                                        <a target = "_blank" href = "/thailand/guide/krabi.html">Krabi Beach</a>
                                                        <br>
                                                        <input type = "checkbox" value = "Ao Nang Beach" name = "thailand[]">
                                                        <a target = "_blank" href = "/thailand/guide/krabi/ao_nang.html">Ao Nang Beach</a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class = "fieldcustom2">Other</td>
                                                    <td colspan = "3"> <input type = "text" class = "field" size = "40" name = "other4"></td>
                                                </tr>
                                                <tr>
                                                    <td class = "fieldcustom2" colspan = "4">Where would you like to visit in Myanmar ?
                                                        <a href = "javascript:popImage('../images/common/myanmar_map_details.png','Myanmar Map');">(Click below sight for reference)</a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td valign = "top" class = "fieldcustom3">
                                                        <input type = "checkbox" value = "Yangon" name = "myanmar[]">
                                                        <a target = "_blank" href = "/myanmar/guide/yangon.html">Yangon</a>
                                                        <br>
                                                        <input type = "checkbox" value = "Bago" name = "myanmar[]">
                                                        <a target = "_blank" href = "/myanmar/guide/bago.html">Bago</a>
                                                        <br>
                                                        <input type = "checkbox" value = "Kyaikhtiyo/Golden Rock" name = "myanmar[]">
                                                        <a target = "_blank" href = "/myanmar/guide/mon/kyaikhto.html">Kyaikhtiyo</a>
                                                        /
                                                        <a target = "_blank" href = "/myanmar/guide/mon/attraction/golden_rock.html">Golden Rock</a>
                                                        <br>
                                                        <input type = "checkbox" value = "Taunggo" name = "myanmar[]">
                                                        <a target = "_blank" href = "/myanmar/guide/shan/taunggyi.html">Taunggo</a>
                                                        <br>
                                                        <input type = "checkbox" value = "Pa An" name = "myanmar[]">
                                                        <a target = "_blank" href = "/myanmar/guide/kayin/pha_an.html">Pa An</a>
                                                        <br>
                                                        <input type = "checkbox" value = "Mawlamyine" name = "myanmar[]">
                                                        <a target = "_blank" href = "/myanmar/guide/mon/mawlamyine.html">Mawlamyine</a>
                                                    </td>

                                                    <td valign = "top" class = "fieldcustom3">
                                                        <input type = "checkbox" value = "Myeik" name = "myanmar[]">
                                                        <a target = "_blank" href = "/myanmar/guide/tanintharyi/myeik.html">Myeik</a>
                                                        <br>
                                                        <input type = "checkbox" value = "Ngwesaung Beach" name = "myanmar[]">
                                                        <a target = "_blank" href = "/myanmar/guide/ayeyarwady/ngwe_saung.html">Ngwesaung Beach</a>
                                                        <br>
                                                        <input type = "checkbox" value = "Chaungtha Beach" name = "myanmar[]">
                                                        <a target = "_blank" href = "/myanmar/guide/ayeyarwady/chaung_thar.html">Chuangtha Beach</a>
                                                        <br>
                                                        <input type = "checkbox" value = "Ngapali Beach" name = "myanmar[]">
                                                        <a target = "_blank" href = "/myanmar/guide/rakhine/ngapali.html">Ngapali Beach</a>
                                                        <br>
                                                        <input type = "checkbox" value = "Mrauk U/Sittwe" name = "myanmar[]">
                                                        <a target = "_blank" href = "/myanmar/guide/rakhine/mrauk_u.html">Mrauk U</a>
                                                        /
                                                        <a target = "_blank" href = "/myanmar/guide/rakhine/sittwe.html">Sittwe</a>
                                                        <br>
                                                        <input type = "checkbox" value = "Bagan" name = "myanmar[]">
                                                        <a target = "_blank" href = "/myanmar/guide/mandalay/bagan.html">Bagan</a>
                                                    </td>


                                                    <td valign = "top" class = "fieldcustom3">
                                                        <input type = "checkbox" value = "Mont Popa" name = "myanmar[]">
                                                        <a target = "_blank" href = "/myanmar/guide/mandalay/mount_popa.html">Mont Popa</a>
                                                        <br>
                                                        <input type = "checkbox" value = "Mandalay" name = "myanmar[]">
                                                        <a target = "_blank" href = "/myanmar/guide/mandalay/mandalay.html">Mandalay</a>
                                                        <br>
                                                        <input type = "checkbox" value = "Monywa" name = "myanmar[]">
                                                        <a target = "_blank" href = "/myanmar/guide/sagaing/monywa.html">Monywa</a>
                                                        <br>
                                                        <input type = "checkbox" value = "Pyin Oo Lwin Hillstation" name = "myanmar[]">
                                                        <a target = "_blank" href = "/myanmar/guide/mandalay/pyin_oo_lwin.html">Pyin Oo Lwin Hillstation</a>
                                                        <br>
                                                        <input type = "checkbox" value = "Myitkyina" name = "myanmar[]">
                                                        <a target = "_blank" href = "/myanmar/guide/kachin/myitkyina.html">Myitkyina</a>
                                                        <br>
                                                        <input type = "checkbox" value = "Kalaw Hillstation" name = "myanmar[]">
                                                        <a target = "_blank" href = "/myanmar/guide/shan/kalaw.html">Kalaw Hillstation</a>
                                                    </td>

                                                    <td valign = "top" class = "fieldcustom3">
                                                        <input type = "checkbox" value = "Pindaya Cave" name = "myanmar[]">
                                                        <a target = "_blank" href = "/myanmar/guide/shan/pindaya.html">Pindaya Cave</a>
                                                        <br>
                                                        <input type = "checkbox" value = "Inle Lake" name = "myanmar[]">
                                                        <a target = "_blank" href = "/myanmar/guide/shan/inle_lake.html">Inle Lake</a>
                                                        <br>
                                                        <input type = "checkbox" value = "Taunggyi" name = "myanmar[]">
                                                        <a target = "_blank" href = "/myanmar/guide/shan/taunggyi.html">Taunggyi</a>
                                                        <br>
                                                        <input type = "checkbox" value = "Kengtong" name = "myanmar[]">
                                                        <a target = "_blank" href = "/myanmar/guide/shan/kyaing_tong.html">Kengtong</a>
                                                        <br>
                                                        <input type = "checkbox" value = "Tachileik" name = "myanmar[]">
                                                        <a target = "_blank" href = "/myanmar/guide/shan/tachileik.html">Tachileik</a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class = "fieldcustom2">Other</td>
                                                    <td colspan = "3"><input type = "text" class = "field" size = "40" name = "other5"></td>
                                                </tr>
                                                <tr>
                                                    <td class = "fieldcustom2" colspan = "4">Where would you like to visit in Yunnan ?
                                                        <a href = "javascript:popImage('../images/common/yunnan_map_details.png','Yunnan Map');">(Click below sight for reference)</a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td valign = "top" class = "fieldcustom3">
                                                        <input type = "checkbox" value = "Kunming" name = "yunnan[]">
                                                        <a target = "_blank" href = "/yunnan/guide/kunming.html">Kunming</a>
                                                        <br>
                                                        <input type = "checkbox" value = "Stone Forest" name = "yunnan[]">
                                                        <a target = "_blank" href = "/yunnan/guide/kunming/attraction/stone_forest.html">Stone Forest</a>
                                                        <br>
                                                        <input type = "checkbox" value = "Jiangshui" name = "yunnan[]">
                                                        <a target = "_blank" href = "/yunnan/guide/honghe/jianshui.html">Jiangshui</a>
                                                        <br>
                                                        <input type = "checkbox" value = "Jingping" name = "yunnan[]">
                                                        <a target = "_blank" href = "#">Jingping</a>
                                                    </td>
                                                    <td valign = "top" class = "fieldcustom3">
                                                        <input type = "checkbox" value = "Jinghong" name = "yunnan[]">
                                                        <a target = "_blank" href = "/yunnan/guide/xishuangbanna/jinghong.html">Jinghong</a>
                                                        <br>
                                                        <input type = "checkbox" value = "Mengla" name = "yunnan[]">
                                                        <a target = "_blank" href = "/yunnan/guide/xishuangbanna/mengla.html">Mengla</a>
                                                        <br>
                                                        <input type = "checkbox" value = "Menghai" name = "yunnan[]">
                                                        <a target = "_blank" href = "#">Menghai</a>
                                                        <br>
                                                        <input type = "checkbox" value = "Rulli" name = "yunnan[]">
                                                        <a target = "_blank" href = "/yunnan/guide/dehong/ruili.html">Rulli</a>
                                                    </td>
                                                    <td valign = "top" class = "fieldcustom3">
                                                        <input type = "checkbox" value = "Tengchong" name = "yunnan[]">
                                                        <a target = "_blank" href = "/yunnan/guide/baoshan/tengchong.html">Tengchong</a>
                                                        <br>
                                                        <input type = "checkbox" value = "Baoshan" name = "yunnan[]">
                                                        <a target = "_blank" href = "/yunnan/guide/baoshan.html">Baoshan</a>
                                                        <br>
                                                        <input type = "checkbox" value = "Weixi" name = "yunnan[]">
                                                        <a target = "_blank" href = "#">Weixi</a>
                                                        <br>
                                                        <input type = "checkbox" value = "Dali" name = "yunnan[]">
                                                        <a target = "_blank" href = "/yunnan/guide/dali.html">Dali</a>
                                                    </td>
                                                    <td valign = "top" class = "fieldcustom3">
                                                        <input type = "checkbox" value = "Lijiang" name = "yunnan[]">
                                                        <a target = "_blank" href = "/yunnan/guide/lijiang.html">Lijiang</a>
                                                        <br>
                                                        <input type = "checkbox" value = "Zhongdian" name = "yunnan[]">
                                                        <a target = "_blank" href = "/yunnan/guide/deqen/zhongdian.html">Zhongdian</a>
                                                        <br>
                                                        <input type = "checkbox" value = "Depin" name = "yunnan[]">
                                                        <a target = "_blank" href = "/yunnan/guide/deqen/deqin.html">Depin</a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class = "fieldcustom2">Other</td>

                                                    <td colspan = "3"><input type = "text" class = "field" size = "40" name = "other6"></td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width = "40%" class = "textitle">Acitivities (*)</td>
                                        <td class = "textitle">Transport (*)</td>
                                        <td width = "30%" class = "textitle">Accommodation (*)</td>
                                    </tr>
                                    <tr>
                                        <td class = "textitle1">
                                            <input type = "checkbox" value = "Culture and history" name = "program[]">
                                            Culture and history<br>
                                            <input type = "checkbox" value = "Eco tourism" name = "program[]">
                                            Eco tourism<br>
                                            <input type = "checkbox" value = "Beach relaxaing" name = "program[]">
                                            Beach relaxing<br>
                                            <input type = "checkbox" value = "Bicycle/Motorbike" name = "program[]">Bicycle / Motorbike<br>
                                            <input type = "checkbox" value = "Walking/Trekking" name = "program[]">Walking / Trekking<br>
                                            <input type = "checkbox" value = "Health retreat/Spa" name = "program[]">
                                            Health retreat / Spa<br>
                                            <input type = "checkbox" value = "Golf touring" name = "program[]">
                                            Golf touring<br>
                                            <input type = "checkbox" value = "Kayaking/Rafting" name = "program[]">Kayaking / Rafting<br>
                                            <input type = "checkbox" value = "Photography/Videography" name = "program[]">Photography / Videography<br>
                                            <input type = "checkbox" value = "Snorkeling / Diving" name = "program[]">Snorkeling / Diving<br>
                                            <input type = "checkbox" value = "Cooking class" name = "program[]">
                                            Cooking class

                                        </td>
                                        <td class = "textitle1">
                                            <input type = "checkbox" value = "Private car" name = "transport[]">
                                            Private car<br>
                                            <input type = "checkbox" value = "Local bus" name = "transport[]">Local bus<br>
                                            <input type = "checkbox" value = "Airplane" name = "transport[]">Airplane<br>
                                            <input type = "checkbox" value = "Motorbike" name = "transport[]">Motorbike<br>
                                            <input type = "checkbox" value = "Train" name = "transport[]">Train<br>
                                            <input type = "checkbox" value = "Cruise" name = "transport[]">Cruise<br>
                                            <input type = "checkbox" value = "Bicyle" name = "transport[]">Bicyle<br>

                                            <div style = "padding-top:5px; padding-left:2px; text-align:left; font:Arial; font-weight:bold; font-size:9pt; text-transform:uppercase;color:#ff6e00;width:100px">MEALS (*)</div>
                                            <input type = "checkbox" value = "Breakfast" name = "meal[]">Breakfast<br>
                                            <input type = "checkbox" value = "Lunch" name = "meal[]">Lunch<br>
                                            <input type = "checkbox" value = "Dinner" name = "meal[]">Dinner
                                        </td>
                                        <td class = "textitle1"><input type = "checkbox" value = "Standard Class" name = "hotel[]">Standard class (2*)<br>
                                            <input type = "checkbox" value = "First class" name = "hotel[]">First class (3*)<br>
                                            <input type = "checkbox" value = "Superior class" name = "hotel[]">Superior class (4*)<br>
                                            <input type = "checkbox" value = "Deluxe class" name = "hotel[]">Deluxe class (5*) <br>
                                            <input type = "checkbox" value = "Homestay" name = "hotel[]">Homestay<br>
                                            <input type = "checkbox" value = "Boutique hotels " name = "hotel[]">Boutique hotels
                                            <div style = "padding-top:5px; padding-left:2px; text-align:left; font:Arial; font-weight:bold; font-size:9pt; text-transform:uppercase;color:#ff6e00; width:100px">TRAVEL TYPE (*)</div>
                                            <input type = "checkbox" value = "Private trip" name = "traveltype[]">
                                            Private trip<br>
                                            <input type = "checkbox" value = "Join group" name = "traveltype[]">
                                            Join group<br>
                                            <input type = "checkbox" value = "Both options(Private trip/Join group)" name = "traveltype[]">
                                            Both options
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>

                            </td>
                        </tr>
                        <tr>
                            <td class = "fieldcustom2" colspan = "4">
                                <table width = "540" border = "0" cellspacing = "0" cellpadding = "0" style = "background-color:#f6f3f3">
                                    <tbody>
                                    <tr>
                                        <td style = "padding-top:5px; text-align:center; font:Arial; font-weight:bold; font-size:10pt;color:#003366">ADDITIONAL INFORMATION</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>

                        <tr>
                            <td colspan = "4">
                                <table width = "100%" border = "0" cellspacing = "0" cellpadding = "0">
                                    <tbody>
                                    <tr>
                                        <td width = "45%" class = "textdescription">Date of anticipated travel ?*</td>
                                        <td rowspan = "14"><img width = "35" height = "10" src = "../images/common/blank.gif"></td>
                                        <td class = "textdescription">Name and surname*</td>
                                    </tr>
                                    <tr>
                                        <td><input type = "text" style = "width:250px" class = "field" name = "traveldate"></td>
                                        <td> <select style = "width:65px" name = "gender">
                                                <option>Gender</option>
                                                <option value = "Mr">Mr</option>
                                                <option value = "Mrs">Mrs</option>
                                                <option value = "Ms">Ms</option>
                                            </select>&nbsp;<input type = "text" class = "field" size = "26" name = "firstname">
                                            <input type = "hidden" value = "" name = "url"></td>
                                    </tr>
                                    <tr>
                                        <td class = "textdescription">How many days do you plan to travel ?*</td>
                                        <td class = "textdescription">Nationality*</td>
                                    </tr>
                                    <tr>
                                        <td><input type = "text" class = "field" style = "width:250px" name = "day"></td>
                                        <td><select style = "width:250px" id = "nationality" class = "box" name = "nationality">
                                                <option selected = "" value = "---">--------select--------</option>
                                                <option>United Kingdom</option>

                                            </select></td>
                                    </tr>
                                    <tr>
                                        <td class = "textdescription">How many people are traveling?*</td>
                                        <td class = "textdescription">Country of resident*</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <table width = "100%" border = "0" cellspacing = "0" cellpadding = "0">
                                                <tbody>
                                                <tr>
                                                    <td width = "120"><select style = "width:115px;font-size:14px" name = "number1">
                                                            <option value = "--">Adult</option>
                                                            <?php for ($i = 1; $i <= 10; $i++) { ?>

                                                                <option value = "<?php echo $i ?>"><?php echo $i ?></option>
                                                            <?php } ?>

                                                        </select></td>
                                                    <td><select style = "width:130px;font-size:14px" name = "number2">
                                                            <option value = "--">Child less 12 y</option>
                                                            <?php for ($i = 1; $i <= 10; $i++) { ?>

                                                                <option value = "<?php echo $i ?>"><?php echo $i ?></option>
                                                            <?php } ?>

                                                        </select></td>
                                                </tr>
                                                </tbody>
                                            </table>

                                        </td>
                                        <td><select style = "width:250px" id = "country" class = "box" name = "country">
                                                <option selected = "" value = "---">----------select----------</option>
                                                <option>United Kingdom</option>

                                            </select></td>
                                    </tr>
                                    <tr>
                                        <td class = "textdescription">Where would you like to start your trip ?*</td>
                                        <td class = "textdescription">Home address*</td>
                                    </tr>
                                    <tr>
                                        <td valign = "top">
                                            <table width = "100%" border = "0" cellspacing = "0" cellpadding = "0">
                                                <tbody>
                                                <tr>
                                                    <td width = "120"><select style = "width:115px;font-size:14px" onchange = "madeSelection(this);" id = "select01" name = "select01">
                                                            <option value = "--">Select country</option>
                                                            <option value = "vietnam">Vietnam</option>
                                                            <option value = "laos">Laos</option>
                                                            <option value = "cambodia">Cambodia</option>
                                                            <option value = "thailand">Thailand</option>
                                                            <option value = "myanmar">Myanmar</option>
                                                        </select> </td> <td id = "select02Container">
                                                        <select style = "width:130px;font-size:14px" onchange = "madeSelection(this);" id = "select02" name = "select02">
                                                            <option value = "--">Select city</option>
                                                        </select>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                        <td><span class = "textdescription">
      <input type = "text" class = "field" style = "width:250px" name = "address">
    </span></td>
                                    </tr>
                                    <tr>
                                        <td class = "textdescription">Where would you like to end your trip ?</td>
                                        <td class = "textdescription">Home or cell phone*&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Work phone*</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <table width = "100%" border = "0" cellspacing = "0" cellpadding = "0">
                                                <tbody>
                                                <tr>
                                                    <td width = "120"><select style = "width:115px;font-size:14px" onchange = "madeSelection1(this);" id = "select001" name = "select001">
                                                            <option value = "--">Select country</option>
                                                            <option value = "vietnam">Vietnam</option>
                                                            <option value = "laos">Laos</option>
                                                            <option value = "cambodia">Cambodia</option>
                                                            <option value = "thailand">Thailand</option>
                                                            <option value = "myanmar">Myanmar</option>
                                                        </select> </td> <td id = "select002Container">
                                                        <select style = "width:130px;font-size:14px" onchange = "madeSelection1(this);" id = "select002" name = "select002">
                                                            <option value = "--">Select city</option>
                                                        </select>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                        <td><input type = "text" class = "field" style = "width:120px" name = "homephone">
                                            <input type = "text" class = "field" style = "width:125px" name = "workphone"></td>
                                    </tr>
                                    <tr>
                                        <td class = "textdescription">What is your budget per person per day ?*</td>
                                        <td class = "textdescription">Email address*</td>
                                    </tr>
                                    <tr>
                                        <td width = "255"><select style = "width:115px;font-size:14px" name = "budget">
                                                <option>select budget</option>
                                                <option value = "US$65 per day">US$ 65 per day</option>
                                                <option value = "US$80 per day">US$ 80 per day</option>
                                                <option value = "US$100 per day">US$ 100 per day</option>
                                                <option value = "US$120 per day">US$ 120 per day</option>
                                                <option value = "US$140 per day">US$ 140 per day</option>
                                                <option value = "US$160 per day">US$ 160 per day</option>
                                                <option value = "US$180 per day">US$ 180 per day</option>
                                                <option value = "US$200 per day">US$ 200 per day</option>
                                                <option value = "US$250 per day">US$ 250 per day</option>
                                                <option value = "US$300 per day">US$ 300 per day</option>
                                                <option value = "US$350 per day">US$ 350 per day</option>
                                            </select>&nbsp;Other <input type = "text" class = "field" style = "width:95px" name = "other8"></td>
                                        <td><input type = "text" class = "field" style = "width:250px" name = "email"></td>
                                    </tr>
                                    <tr>
                                        <td class = "textdescription">How did you hear about us ?</td>
                                        <td class = "textdescription">Retype email address*</td>
                                    </tr>
                                    <tr>
                                        <td> <select style = "width:250px" name = "search">
                                                <option value = "">Please select one</option>
                                                <option value = "Yahoo">Search Engine: Yahoo!</option>
                                                <option value = "Infoseek">Search Engine: Infoseek</option>
                                                <option value = "Excite">Search Engine: Excite</option>
                                                <option value = "Lycos">Search Engine: Lycos</option>
                                                <option value = "Webcrawler">Search Engine: Webcrawler</option>
                                                <option value = "Hot Bot">Search Engine: Hot Bot</option>
                                                <option value = "Alta Vista">Search Engine: Alta Vista</option>
                                                <option value = "Voila">Search Engine: Voila</option>
                                                <option value = "Other Search Engine ">Search Engine: Other</option>
                                                <option value = "Web Site Link/Button">Web Site Link/Button</option>
                                                <option value = "Magazine">Magazine</option>
                                                <option value = "Word of Mouth">Word of Mouth</option>
                                                <option value = "Other">Other</option>
                                            </select></td>
                                        <td> <input type = "text" class = "field" style = "width:250px" name = "cemail"></td>
                                    </tr>
                                    <tr>
                                        <td colspan = "3">
                                            <table width = "100%" border = "0" cellspacing = "0" cellpadding = "0">
                                                <tbody>
                                                <tr>
                                                    <td>
                                                        <font style = "padding-top:5px; padding-bottom:2px;padding-left:3px; text-align:left; font:Arial; font-weight:bold; font-size:10pt;color:#000">OTHER REQUIREMENT</font><br>
                                                        <textarea class = "field" rows = "3" cols = "65" name = "comment"></textarea></td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <input type = "checkbox" onclick = "validate1()" value = "" name = "newsletter"> Subscribe to receive our newsletters on travel events, special offers,..
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>

                            </td>
                        </tr>

                        <tr>
                            <td class = "description" colspan = "4"></td>
                        </tr>

                        </tbody>
                    </table>

                    <table width = "100%" border = "0" cellspacing = "0" cellpadding = "0" class = "tableform">
                        <tbody>
                        <tr>
                            <td colspan = "4"></td>
                        </tr>
                        <tr>
                            <td width = "100%" style = "text-align:right;padding-right:20" colspan = "4">
                                <input type = "hidden" value = "www.asianventure.com" name = "fromurl">
                                <input type = "hidden" name = "submitted1">
                                <input type = "hidden" name = "submitted2">
                                <input type = "hidden" name = "submitted3">
                                <input type = "hidden" name = "submitted4">
                                <input type = "hidden" name = "submitted5">
                                <input type = "submit" value = "submit">
                                <input type = "reset" value = "Reset"></td>
                        </tr>
                        </tbody>
                    </table>
                </form>
            </td>
        </tr>
        <tr>
            <td width = "15" valign = "bottom" align = "left"><img width = "15" height = "15" border = "0" src = "http://asianventure.com/images/common/corner32_left.gif"></td>
            <td>&nbsp;</td>
            <td width = "15" valign = "bottom" align = "right"><img width = "15" height = "15" border = "0" src = "http://asianventure.com/images/common/corner34_right.gif"></td>
        </tr>
        <tr>
            <td height = "20" bgcolor = "#336699" class = "close" colspan = "5"><img width = "10" height = "10" border = "0" src = "http://asianventure.com/images/common/blank.gif">
                <a href = "javascript:window.close();">Close Window</a>
            </td>
        </tr>
        </tbody>
    </table>


    <!--send hidden value -->
    <input type = "hidden" name = "option" value = "com_bookpro">
    <input type = "hidden" name = "controller" value = "tourbook">
    <input type = "hidden" name = "tourtype" value = "customtrip">
    <input type = "hidden" name = "task" value = "bookingtourpackage">
    <input type = "hidden" name = "Itemid" value = "<?php echo JRequest::getVar(Itemid); ?>" id = "Itemid"/>

    <div style = "float: right;">
        <input type = "submit" value = "Submit"/>
        <input type = "reset" value = "Reset"/>
    </div>
    <?php echo JHtml::_('form.token'); ?>
</form>
<!-- Modal  -->


<style>
    a {
        color: #000;
    }

    table.tableform td.textdescription {
        color: #000;
        font-size: 9pt;
        font-weight: bold;
        line-height: 15px;
        padding-bottom: 0;
        padding-left: 2px;
        padding-top: 0;
        text-align: left;
    }

    table.tableform td.textdestination {
        color: #406f9f;
        font-size: 10pt;
        font-weight: bold;
        padding-right: 5px;
        text-align: right;
    }

    table.tableform td.textitle {
        color: #ff6e00;
        font-size: 9pt;
        font-weight: bold;
        padding-left: 2px;
        padding-top: 5px;
        text-align: left;
        text-transform: uppercase;
    }

    table.tableform td.textitle1 {
        font-size: 9pt;
        font-weight: bold;
        padding-left: 2px;
        text-align: left;
        vertical-align: top;
    }

</style>
