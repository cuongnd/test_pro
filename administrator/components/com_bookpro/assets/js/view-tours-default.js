jQuery(document).ready(function ($) {

    /*
     optionSorting={
     setpinbottom:'.setpinbottom'
     };
     $('.sortingtable').sortingtable(optionSorting);
     */


    $("#data").kendoGrid({
        editable: "inline", //or "inline"
        columns: [
            { field: "field1", title: "Field 1" },
            { field: "field2", title: "Field 2" },
            { field: "field3", title: "Field 3" },
            { command:["edit", {text:"edit12",click: showDetails}], title: " ", width: "180px" }],
        dataSource:{
            /*transport: {
                type: "odata",

                update: {
                    url: function (data) {
                        return 'index.php'
                    },
                    type: "PUT"
                },
                destroy: {
                    url: function (data) {
                        return 'index.php';
                    },
                    type: "DELETE"
                },
                create: {
                    url: 'index.php',
                    type: "POST"

           },*/
            schema: {
                model: {
                    id: "ProductID",
                    fields: {
                        ProductID: { editable: false, nullable: true },
                        ProductName: { validation: { required: true } },
                        field1: { type: "image", validation: { required: true} },
                        Discontinued: { type: "boolean" },
                        UnitsInStock: { type: "number", validation: { min: 0, required: true } }
                    }
                }
            },
            height: 550,
            sortable: true,
            filterable: true,
            columnMenu: true,
            pageable: true
        }
    });
    function showDetails()
    {

    }
});