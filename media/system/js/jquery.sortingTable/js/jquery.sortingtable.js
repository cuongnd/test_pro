/**
 * Created by Son on 3/13/2015.
 */
(function ($) {

    $.fn.sortingtable = function (options) {
        Util={

            getCellValue:function getCellValue(row, index){
                console.log(index);
                return $(row).children('td').eq(index).text();

            },
            comparer: function comparer(index) {
                return function(a, b) {
                    var valA = Util.getCellValue(a, index), valB = Util.getCellValue(b, index)
                    return $.isNumeric(valA) && $.isNumeric(valB) ? valA - valB : valA.localeCompare(valB)
                }
            },
            sortingTable: function (self,sortype){
                th_sorting = self.closest('th');
                position = th_sorting.find('.icon-sorting').attr('position');

                var table_sorting = self.closest('.sortingtable');
                var rows = table_sorting.find('tr:gt(0)').toArray().sort(Util.comparer(position));
                if (sortype=='desc'){
                    rows = rows.reverse();
                }
                for (var i = 0; i < rows.length; i++){
                    table_sorting.append(rows[i])
                }
            }

        }



        self = $(this);
        var callbacks = $.Callbacks();
        columnsOption = "<form class='form-inline'>";
        columnsOption += "<ul>";
        $("tr:first").children().each(function (index) {
            index = index + 1;

            columnsOption += '<li>' + '<input type="checkbox" name="' + index + '" ><label class="labelText">' + $(this).text() + '</label></li>'

        });
        columnsOption += "</ul>";

        columnsOption += "</form>";

        htmlwarper = '' +
        '<ul class="infoSofting">'
        + '<li class="option asc"><i class="i-option-asc"></i>Sort Ascending</li>'
        + '<li class="option desc"><i class="i-option-desc"></i>Sort Descending</li>'
        + '<li class="option columns"><i class="i-option-columns"></i>Columns</li>'
        + '<li class="option filter"><i class="i-option-filter"></i>Filter</li>'
        + '</ul>';

        var settings = $.extend({
            // These are the defaults.
            setpinbottom: ".setpinbottom"
        }, options);

        html = '<div class="icon-sorting"></div>';

        self.find("tr:first th").append(html);
        $('th').each(function (index) {
            $(this).find('.icon-sorting').attr('position', index)

        })

        self.find('.icon-sorting').bind({
            click: function () {
                position = $(this).attr('position');

                $(htmlwarper).appendTo("th:eq(" + position + ")").hide().show(200);

                self.find(".columns").bind("hover", function (index) {
                    if ($(this).children().hasClass("form-inline")) {
                        return false;
                    }

                    $(".columns").append(columnsOption);
                    $('.labelText').each(function () {
                        if (($(this).text()).trim() == "") {
                            $(this).text("Check All");
                        }
                    });
                    $(":checkbox").on("click", function () {
                        // $('table').find('tr td:eq(1)').hide();
                        //$( "#log" ).html( $( "input:checked" ).val() + " is checked!" );
                        name = $(this).attr('name');
                        $("tr td:nth-child(" + name + ")").toggle();
                        $("th:nth-child(" + name + ")").toggle();
                    });
                });
                self.find(".asc").bind("click", function (index) {
                    Util.sortingTable($(this),'asc');
                });
                self.find(".desc").bind("click", function (index) {
                    Util.sortingTable($(this),'desc');



                });


                $('.labelText').each(function () {
                    if (($(this).text()).trim() == "") {
                        $(this).text("Check All");
                    }
                });

            }
        });
        $(document).click(function (e) {
            infoSofting = $(e.target).closest('.infoSofting');
            has_class_icon_sorting = $(e.target).hasClass("icon-sorting");
            if (has_class_icon_sorting) {
                return true;
            } else if (infoSofting.length > 0) {
                return true;
            } else {
                $('.infoSofting').hide();
            }

        });


    };

}(jQuery));

