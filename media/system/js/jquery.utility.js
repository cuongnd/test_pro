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
})(jQuery);