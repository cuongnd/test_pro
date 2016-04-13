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

})(jQuery);