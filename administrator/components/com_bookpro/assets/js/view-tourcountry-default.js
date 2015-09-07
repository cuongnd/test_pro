/**
 * Created by Administrator PC on 3/24/2015.
 */
jQuery(document).ready(function($){
    var crudServiceBaseUrl = "index.php?option=com_bookpro&task=",
        dataSource = new kendo.data.DataSource({
            transport: {
                read:  {
                    url: crudServiceBaseUrl + "countries.getAjaxItems",
                    dataType: "json"
                },
                update: {
                    url: crudServiceBaseUrl + "country.updateItem",
                    dataType: "json"
                },
                destroy: {
                    url: crudServiceBaseUrl + "countries.DeleteItem",
                    dataType: "json"
                },
                create: {
                    url: crudServiceBaseUrl + "country.createItem",
                    dataType: "json"
                },
                parameterMap: function(options, operation) {
                    if (operation !== "read" && options.models) {
                        return {models: kendo.stringify(options.models)};
                    }
                }
            },
            batch: true,
            pageSize: 20,
            //serverPaging: true,
            serverFiltering: true,
            serverSorting: true,
            schema: {
                model: {
                    id: "id",
                    fields: {
                        id: { editable: false, nullable: true },
                        path: { type: "media" }
                        //ProductName: { validation: { required: true } },
                        //UnitPrice: { validation: { required: true } },
                        //Discontinued: { validation: { required: true } },
                        // UnitsInStock: {}
                    }
                }
            }
        });

    $("#grid").kendoGrid({
        dataSource: dataSource,
        height: 550,
        sortable: true,
        filterable: true,
        columnMenu: true,
        pageable: true,
        toolbar: ["create"],
        columns: [
            { field: "id", title:"Id"},
            { field: "path", title:"Icon"},
            { field: "country_name", title:"Country"},
            { field: "phone_code", title:"Phone Code"},
            { field: "state_number", title:"State Number"},
            { command: ["edit", "destroy"], title: "Action", width: "250px" }],

        editable: "inline"
    });
});