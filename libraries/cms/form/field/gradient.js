/**
 * Created by cuongnd on 22/4/2015.
 */
(function() {

    var _initialised = false,
        listDiv = document.getElementById("list-diagram"),

        showConnectionInfo = function(s) {
            list.innerHTML = s;
            list.style.display = "block";
        },
        hideConnectionInfo = function() {
            list.style.display = "none";
        },
        connections = [],
        updateConnections = function(conn, remove) {
            if (!remove) connections.push(conn);
            else {
                var idx = -1;
                for (var i = 0; i < connections.length; i++) {
                    if (connections[i] == conn) {
                        idx = i; break;
                    }
                }
                if (idx != -1) connections.splice(idx, 1);
            }
            if (connections.length > 0) {
                var s = "<span><strong>Connections</strong></span><br/><br/><table><tr><th>Scope</th><th>Source</th><th>Target</th></tr>";
                for (var j = 0; j < connections.length; j++) {
                    s = s + "<tr><td>" + connections[j].scope + "</td>" + "<td>" + connections[j].sourceId + "</td><td>" + connections[j].targetId + "</td></tr>";
                }
                showConnectionInfo(s);
            } else
                hideConnectionInfo();
        };
    diagramJsPlumb='';
    diagramJsPlumbNodeColor='';
    jsPlumb.ready(function() {

        var instance = jsPlumb.getInstance({
            DragOptions : { cursor: 'pointer', zIndex:2000 },
            PaintStyle : { strokeStyle:'#666' },
            EndpointStyle : { width:20, height:16, strokeStyle:'#666' },
            Endpoint : "Rectangle",
            ConnectionOverlays: [
                ["Arrow", { location: -7, id: "arrow", length: 14, foldback: 0.8 }],
                ["Label", { location: 0.1, id: "label" }]
            ],
            Anchors : ["TopCenter", "TopCenter"],
            Container:"drag-drop-demo"
        });
        diagramJsPlumb=instance;

        // suspend drawing and initialise.
        instance.doWhileSuspended(function() {

            // bind to connection/connectionDetached events, and update the list of connections on screen.
            instance.bind("connection", function(info, originalEvent) {
                updateConnections(info.connection);
            });
            instance.bind("connectionDetached", function(info, originalEvent) {
                updateConnections(info.connection, true);
            });

            instance.bind("connectionMoved", function(info, originalEvent) {
                //  only remove here, because a 'connection' event is also fired.
                // in a future release of jsplumb this extra connection event will not
                // be fired.
                updateConnections(info.connection, true);
            });

            // configure some drop options for use by all endpoints.
            var exampleDropOptions = {
                tolerance:"touch",
                hoverClass:"dropHover",
                activeClass:"dragActive"
            };

            //
            // first example endpoint.  it's a 5x5 rectangle (the size is provided in the 'style' arg to the Endpoint),
            // and it's both a source and target.  the 'scope' of this Endpoint is 'exampleConnection', meaning any connection
            // starting from this Endpoint is of type 'exampleConnection' and can only be dropped on an Endpoint target
            // that declares 'exampleEndpoint' as its drop scope, and also that
            // only 'exampleConnection' types can be dropped here.
            //
            // the connection style for this endpoint is a Bezier curve (we didn't provide one, so we use the default), with a lineWidth of
            // 5 pixels, and a gradient.
            //
            // there is a 'beforeDrop' interceptor on this endpoint which is used to allow the user to decide whether
            // or not to allow a particular connection to be established.
            //
            var exampleColor = "#00f";
            var exampleEndpoint = {
                endpoint:"Rectangle",
                paintStyle:{ width:5, height:5, fillStyle:exampleColor },
                isSource:true,
                reattach:true,
                scope:"blue",
                connectorStyle : {
                    gradient:{stops:[[0, exampleColor], [0.5, "#09098e"], [1, exampleColor]]},
                    lineWidth:1,
                    strokeStyle:exampleColor,
                    dashstyle:"2 2"
                },
                isTarget:true,
                beforeDrop:function(params) {
                    return confirm("Connect " + params.sourceId + " to " + params.targetId + "?");
                },
                dropOptions : exampleDropOptions
            };

            //
            // the second example uses a Dot of radius 15 as the endpoint marker, is both a source and target,
            // and has scope 'exampleConnection2'.
            //
            var color2 = "#316b31";
            var exampleEndpoint2 = {
                endpoint:["Dot", { radius:5 }],
                paintStyle:{ fillStyle:color2 },
                isSource:true,
                scope:"green",
                connectorStyle:{ strokeStyle:color2, lineWidth:1 },
                connector: ["Flowchart", { curviness:63 } ],

                maxConnections:3,
                isTarget:true,
                dropOptions : exampleDropOptions
            };
            diagramJsPlumbNodeColor=exampleEndpoint2;
            //
            // the third example uses a Dot of radius 4 as the endpoint marker, is both a source and target, and has scope
            // 'exampleConnection3'.  it uses a Straight connector, and the Anchor is created here (bottom left corner) and never
            // overriden, so it appears in the same place on every element.
            //
            // this example also demonstrates the beforeDetach interceptor, which allows you to intercept
            // a connection detach and decide whether or not you wish to allow it to proceed.
            //
            var example3Color = "rgba(229,219,61,0.5)";
            var exampleEndpoint3 = {
                endpoint:["Dot", {radius:4} ],
                anchor:"BottomLeft",
                paintStyle:{ fillStyle:example3Color, opacity:0.5 },
                isSource:true,
                scope:'yellow',
                connectorStyle:{ strokeStyle:example3Color, lineWidth:1 },
                connector : "Straight",
                isTarget:true,
                dropOptions : exampleDropOptions,
                beforeDetach:function(conn) {
                    return confirm("Detach connection?");
                },
                onMaxConnections:function(info) {
                    alert("Cannot drop connection " + info.connection.id + " : maxConnections has been reached on Endpoint " + info.endpoint.id);
                }
            };


            var hideLinks = jsPlumb.getSelector(".drag-drop-demo .hide");
            instance.on(hideLinks, "click", function(e) {
                instance.toggleVisible(this.getAttribute("rel"));
                jsPlumbUtil.consume(e);
            });

            var dragLinks = jsPlumb.getSelector(".drag-drop-demo .drag");
            instance.on(dragLinks, "click", function(e) {
                var s = instance.toggleDraggable(this.getAttribute("rel"));
                this.innerHTML = (s ? 'disable dragging' : 'enable dragging');
                jsPlumbUtil.consume(e);
            });

            var detachLinks = jsPlumb.getSelector(".drag-drop-demo .detach");
            instance.on(detachLinks, "click", function(e) {
                instance.detachAllConnections(this.getAttribute("rel"));
                jsPlumbUtil.consume(e);
            });

            instance.on(document.getElementById("clear"), "click", function(e) {
                instance.detachEveryConnection();
                showConnectionInfo("");
                jsPlumbUtil.consume(e);
            });
        });

        jsPlumb.fire("jsPlumbDemoLoaded", instance);

    });
})();

jQuery(document).ready(function($){
    utilityDataSource={

        getListTable:function(){
            var listTable={};
            $('.panel-database-table').each(function(index){
                //listTable[index]=$(this).attr('data-table-name');
                table_name=$(this).attr('data-table-name');
                listTable[table_name]={};
                $(this).find('.list-field .item-field').each(function(index2){
                    listTable[table_name][index2]=$(this).attr('data-table-field');
                });
            });
            return listTable;
        },
        updateTableInSelectTableAndFunction:function()
        {
            listTable=utilityDataSource.getListTable();
            $('.select-tables').find('option[value!="0"]').remove();
            $('.table-and-function .list-field').empty();

            $.each(listTable, function( index, $fields ) {
                $('.select-tables').append('<option value="'+index+'">'+index+'</option>');
                $.each($fields, function( index, field ) {
                    $('.table-and-function .list-field').append('<li data-table-field="'+field+'"><a href="javascript:void(0)">'+field+'</a></li>');
                });
            });



        }
    };
    $( ".item-table" ).draggable({
        appendTo: 'body',
        helper: "clone"
    }).css({
        'z-index':'auto'
    });
    $('.diagrams').droppable({
        accept: ".item-table",
        greedy: true,
        drop: function(ev,ui){
            uiDraggable=$(ui.draggable);
            droppable=$(this);
            renderTable(uiDraggable,droppable);
        }
    });


    $('.show-select-table-and-function').focus(function(){
        $('.table-and-function').removeClass( "table-and-function-hide" );
    });
    $('.show-table-and-function').click(function(){
        $('.table-and-function').toggleClass( "table-and-function-hide", 1000 );
    });


/*
    html.draggable({
        handle: '.field-config-heading'
    });
*/

    $(document).on('.panel-database-table .panel-controls .panel-close','click',function(e){
        sprFlat=$('body').data('sprFlat');
        console.log('hello panel');
    });

    var ajaxRederTable;
    function renderTable(uiDraggable,droppable)
    {
        table=uiDraggable.attr('data-table');
        if(typeof ajaxRederTable !== 'undefined'){
            ajaxRederTable.abort();
        }
        ajaxRederTable=$.ajax({
            type: "GET",
            url: this_host+'/index.php',
            data: (function () {
                dataPost = {
                    option: 'com_phpmyadmin',
                    task: 'table.aJaxInsertTable',
                    table:table

                };
                return dataPost;
            })(),
            beforeSend: function () {
                // $('.loading').popup();
            },
            success: function (response) {
                response=$(response);
                droppable.append(response);
               /* $('.panel-database-table').draggable({
                    containment:"parent",
                    handle: '.panel-heading-database-table'
                });*/
                response.find('.list-field .item-field').each(function(){
                    diagramJsPlumb.addEndpoint($(this).attr('id'),  { anchor:["LeftMiddle", "RightMiddle"] }, diagramJsPlumbNodeColor);
                });
                sprFlat=$('body').data('sprFlat');
                sprFlat.panels();
                utilityDataSource.updateTableInSelectTableAndFunction();
                diagramJsPlumb.draggable(jsPlumb.getSelector(".drag-drop-demo .panel-database-table"),{
                    handle: response.find('.panel-heading-database-table')
                });
            }
        });
    }
});
