(function($){
	$.tree_object= function(item,object_list,key_path){
		if(typeof object_list=='undefined'){
			var object_list={};
		}

		if(!$.isEmptyObject(item))
		{

			$.each(item,function(key,value){
				if(typeof key_path!=='undefined'){
					var key_path1=key_path+'.'+key;
				}else{
					var key_path1=key;
				}
				if(typeof value!=='object')
				{
					object_list[key_path1]=value
				}else if(!$.isEmptyObject(value))
				{
					$.tree_object(value,object_list,key_path1);
				}
			});
		}
		return object_list;
	};
    $.random = function(min,max)
    {
        min = parseInt(min);
        max = parseInt(max);
        return Math.floor( Math.random() * (max - min + 1) ) + min;
    }

	$.array_chunk=function(array,groupsize){
		var sets = [], chunks, i = 0;
		chunks = array.length / groupsize;
		while(i < chunks){
			sets[i] = array.splice(0,groupsize);
			i++;
		}
		return sets;
	};
	$.str_repeat=function (input, multiplier) {
		//  discuss at: http://phpjs.org/functions/str_repeat/
		// original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
		// improved by: Jonas Raoni Soares Silva (http://www.jsfromhell.com)
		// improved by: Ian Carter (http://euona.com/)
		//   example 1: str_repeat('-=', 10);
		//   returns 1: '-=-=-=-=-=-=-=-=-=-='

		var y = '';
		while (true) {
			if (multiplier & 1) {
				y += input;
			}
			multiplier >>= 1;
			if (multiplier) {
				input += input;
			} else {
				break;
			}
		}
		return y;
	};
    $.isScrolledIntoView=function(elem)
    {
        var $elem = $(elem);
        var $window = $(window);

        var docViewTop = $window.scrollTop();
        var docViewBottom = docViewTop + $window.height();

        var elemTop = $elem.offset().top;
        var elemBottom = elemTop + $elem.height();

        return ((elemBottom <= docViewBottom) && (elemTop >= docViewTop));
    };
    $.makeid=function(length)
    {
        if(typeof length=='undefined')
        {
            length=5;
        }
        var text = "";
        var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

        for( var i=0; i < length; i++ )
            text += possible.charAt(Math.floor(Math.random() * possible.length));

        return text;
    }
    $.fn.getOuterHTML = function() {
        var wrapper = $('<div class="getOuterHTML"></div>');
        $(this).wrap(wrapper);
        var html=$(this).parent().html();
        $(this).unwrap();
        return html;
    };
    $.fn.add_event_element = function(event,call_back_function,event_class) {
        $(this).each(function(){
            if(typeof event_class=='undefined' || event_class.trim()=='')
            {
                throw 'there are no event class';
                return;
            }
            if(!$(this).hasClass(event_class))
            {
                $(this).on(event,call_back_function).addClass(event_class);
            }
        });

    };
    $.get_year_old_by_date = function(dateString) {
        if(dateString=='')
        {
            return '';
        }
        var today = new Date();
        var birthDate = new Date(dateString);
        var age = today.getFullYear() - birthDate.getFullYear();
        var m = today.getMonth() - birthDate.getMonth();
        if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
            age--;
        }
        return age;
    };
    $.randomDate=function(start, end) {
        return new Date(start.getTime() + Math.random() * (end.getTime() - start.getTime()));
    }

})(jQuery);