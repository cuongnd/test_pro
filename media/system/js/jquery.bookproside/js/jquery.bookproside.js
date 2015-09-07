/**
 * Created by Son on 3/13/2015.
 */
(function ($) {

    $.fn.bookproside = function (options) {
        Util={
            showthisimage:function(self){

            },
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
            bookproside: function (self,sortype){
                th_sorting = self.closest('th');
                position = th_sorting.find('.icon-sorting').attr('position');

                var table_sorting = self.closest('.bookproside');
                var rows = table_sorting.find('tr:gt(0)').toArray().sort(Util.comparer(position));
                if (sortype=='desc'){
                    rows = rows.reverse();
                }
                for (var i = 0; i < rows.length; i++){
                    table_sorting.append(rows[i])
                }
            }

        };

        self = $(this);

        $(document).on('click',self.find('.small-image .image-item'),function(){
            Util.showthisimage($(this));
        });


    };

}(jQuery));

