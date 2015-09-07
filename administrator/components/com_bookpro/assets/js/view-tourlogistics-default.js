/**
 * Created by Administrator PC on 3/24/2015.
 */
jQuery(document).ready(function($){

    optionSorting={
        setpinbottom:'.setpinbottom'
    };
    //$('.sortingtable').sortingtable(optionSorting);

    //truyền vào các tham số
    optionedittable={
        class_delete_row:'logistic-delete-row',
        class_edit_row:'logistic-edit-row',
        class_enable_edit:'logistic-enable-row',
        class_update_row:'logistic-update-row',
        class_save_row:'logistic-save-row',
        link_delete:'index.php?option=com_bookpro&task=countries.delete_row',
        link_save:'index.php?option=com_bookpro&task=countries.save_row',
        link_update:'index.php?option=com_bookpro&task=countries.update_row',
        attr_column_name:'data-column-name',
        enable_multi_edit_row:false,
        class_delete_data_row: 'class_deleterow',
        class_new_row: 'countries-new-row',
        input_id_edit:'input[name="id"]',
        column_class_empty_when_editing:'empty_when_edit',
        class_show_hide_icon:'show-hide-icon',
        class_icon_country: 'icon-country-edit',
        attr_data_path:"data-path",//data path change image
        class_get_value_img:"get-value-img"
    };
    $('.bookpro-edit-table').bookproedittable(optionedittable);

});