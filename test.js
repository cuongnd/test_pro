jQuery(function ($) {
    jQuery("#grid_3117").kendoGrid({
        "columns": [{
            "field": "id",
            "title": "<div class='checkbox'><label><input id='check_all_3117', type='checkbox', class='check_box_all' \/><span><\/span><\/label><\/div>",
            "width": 30,
            "template": "<div class='checkbox'><label><input class='check_box' value='#:id#' type=\"checkbox\" \/><span>#:id#<\/span><\/label><\/div>",
            "sortable": false,
            "filterable": false,
            "menu": false,
            "locked": true
        }, {
            "field": "id",
            "title": "id",
            "width": 30,
            "sortable": true,
            "filterable": true,
            "menu": true,
            "locked": true
        }, {
            "field": "firstname",
            "title": "First name",
            "width": 80,
            "sortable": false,
            "filterable": false,
            "menu": false,
            "locked": true
        }, {"title": "Middle name", "columns": {}}, {
            "field": "lastname",
            "title": "Last name",
            "width": 80,
            "sortable": false,
            "filterable": false,
            "menu": false,
            "locked": true
        }, {
            "field": "gender",
            "title": "gender",
            "width": 100,
            "sortable": false,
            "filterable": false,
            "menu": false
        }, {
            "field": "title_id",
            "title": "Title",
            "width": 100,
            "sortable": false,
            "filterable": false,
            "menu": false
        }, {
            "field": "birthday",
            "title": "birthday",
            "width": 100,
            "sortable": false,
            "filterable": false,
            "menu": false
        }, {
            "field": "passport",
            "title": "Passport no",
            "width": 100,
            "sortable": false,
            "filterable": false,
            "menu": false
        }, {
            "field": "passport",
            "title": "Place of Issue",
            "width": 100,
            "sortable": false,
            "filterable": false,
            "menu": false
        }, {
            "field": "passport_issue",
            "title": "Issue Date",
            "width": 100,
            "sortable": false,
            "filterable": false,
            "menu": false
        }, {
            "field": "passport_expiry",
            "title": "Expiry Date",
            "width": 100,
            "sortable": false,
            "filterable": false,
            "menu": false
        }, {
            "field": "country_id",
            "title": "Medical",
            "width": 100,
            "sortable": false,
            "filterable": false,
            "menu": false
        }, {
            "field": "country_id",
            "title": "Conditions",
            "width": 100,
            "sortable": false,
            "filterable": false,
            "menu": false
        }, {
            "field": "country_id",
            "title": "Meal",
            "width": 100,
            "sortable": false,
            "filterable": false,
            "menu": false
        }, {"title": "requirement", "columns": {}}, {
            "field": "country_id",
            "title": "Arrival flight",
            "width": 100,
            "sortable": false,
            "filterable": false,
            "menu": false
        }, {
            "field": "country_id",
            "title": "Departure flight",
            "width": 100,
            "sortable": false,
            "filterable": false,
            "menu": false
        }, {
            "field": "email",
            "title": "E-mail address",
            "width": 100,
            "sortable": false,
            "filterable": false,
            "menu": false
        }, {
            "field": "homephone",
            "title": "Work Phone number",
            "width": 100,
            "sortable": false,
            "filterable": false,
            "menu": false
        }, {
            "field": "homephone",
            "title": "Home Phone number",
            "width": 100,
            "sortable": false,
            "filterable": false,
            "menu": false
        }, {
            "field": "address",
            "title": "Home address",
            "width": 100,
            "sortable": false,
            "filterable": false,
            "menu": false
        }, {
            "field": "province",
            "title": "Town\/City",
            "width": 100,
            "sortable": false,
            "filterable": false,
            "menu": false
        }, {
            "field": "province",
            "title": "State\/Province",
            "width": 100,
            "sortable": false,
            "filterable": false,
            "menu": false
        }, {
            "field": "country_id",
            "title": "Country",
            "width": 100,
            "sortable": false,
            "filterable": false,
            "menu": false
        }, {
            "field": "country_id",
            "title": "special request",
            "width": 100,
            "sortable": false,
            "filterable": false,
            "menu": false
        }, {"title": "Contact name", "columns": {}}, {
            "field": "emergency_homephone",
            "title": "Home phone",
            "width": 100,
            "sortable": false,
            "filterable": false,
            "menu": false
        }, {
            "field": "emergency_homephone",
            "title": "Work phone",
            "width": 100,
            "sortable": false,
            "filterable": false,
            "menu": false
        }, {
            "field": "emergency_mobile",
            "title": "E-mail address",
            "width": 100,
            "sortable": false,
            "filterable": false,
            "menu": false
        }, {
            "field": "id",
            "command": ["edit", "destroy"],
            "title": "Action",
            "width": 300,
            "sortable": false,
            "filterable": false,
            "menu": false
        }],
        "toolbar": [{"name": "create"}],
        "dataSource": {
            "transport": {
                "create": {
                    "url": "http:\/\/admin.etravelservice.com:81\/index.php?option=com_phpmyadmin&task=datasource.ajax_update_Data&type=create&block_id=3117&order_id=1",
                    "contentType": "application\/json",
                    "type": "POST"
                },
                "read": {
                    "url": "http:\/\/admin.etravelservice.com:81\/index.php?option=com_phpmyadmin&task=datasource.readData&block_id=3117&order_id=1",
                    "contentType": "application\/json",
                    "type": "POST"
                },
                "update": {
                    "url": "http:\/\/admin.etravelservice.com:81\/index.php?option=com_phpmyadmin&task=datasource.ajax_update_Data&type=update&block_id=3117&order_id=1",
                    "contentType": "application\/json",
                    "type": "POST"
                },
                "destroy": {
                    "url": "http:\/\/admin.etravelservice.com:81\/index.php?option=com_phpmyadmin&task=datasource.ajax_update_Data&type=destroy&block_id=3117&order_id=1",
                    "contentType": "application\/json",
                    "type": "POST"
                },
                "parameterMap": function (data) {
                    return kendo.stringify(data);
                }
            },
            "batch": true,
            "pageSize": 10,
            "schema": {
                "data": "data",
                "model": {
                    "id": "id",
                    "fields": [{"field": "id", "validation": {"required": false}, "editable": false}, {
                        "field": "flag",
                        "validation": {"required": false},
                        "editable": true
                    }, {"field": "state", "validation": {"required": false}, "editable": true}, {
                        "field": "hasc",
                        "validation": {"required": false},
                        "editable": true
                    }, {
                        "field": "iso_code",
                        "validation": {"required": false},
                        "editable": true
                    }, {
                        "field": "phone_code",
                        "validation": {"required": false},
                        "editable": true
                    }, {
                        "field": "countries",
                        "validation": {"required": false},
                        "type": "object",
                        "editable": true,
                        "defaultValue": {}
                    }, {
                        "field": "longitude",
                        "validation": {"required": false},
                        "type": "number",
                        "editable": true,
                        "defaultValue": 0
                    }, {
                        "field": "latitude",
                        "validation": {"required": false},
                        "type": "number",
                        "editable": true,
                        "defaultValue": 0
                    }, {
                        "field": "total_city_area",
                        "validation": {"required": false},
                        "type": "number",
                        "editable": false,
                        "defaultValue": 0
                    }, {
                        "field": "ordering",
                        "validation": {"required": false},
                        "type": "number",
                        "editable": false,
                        "defaultValue": 0
                    }]
                },
                "total": "total"
            },
            "autoSync": false
        },
        "height": "500",
        "columnMenu": "true",
        "filterable": "true",
        "sortable": "true",
        "editable": {"mode": "popup", "confirmation": "do you want delete this item ?"},
        "autoBind": true,
        "scrollable": true,
        "pageable": {
            "input": true,
            "pageSizes": [10, 20, 30, 40, 50, 100, 200, 300, 400, 500, 1000],
            "info": true,
            "refresh": true,
            "buttonCount": 5
        },
        "groupable": null,
        "dataBound": onDataBound_3117,
        "dataBinding": onDataBinding_3117,
        "change": onChange_3117,
        "edit": edit_row_3117,
        "save": save_row_3117,
        "cancel": cancel_row_3117
    });
});