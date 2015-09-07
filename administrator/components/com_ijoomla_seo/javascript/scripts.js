function jSelectArticle(id, title, object){
	document.getElementById('id_name').value = title;
	document.getElementById('id_id').value = id;
	window.parent.SqueezeBox.close();
}

function changeColor(id, spanId, color){
	document.getElementById(spanId+"_"+id).style.color = color;
}

function unColor(textareaName){
	var obj = document.getElementsByName(textareaName)[0];
	if(window.parentNode !== undefined){
		var par = obj.parentNode.parentNode.parentNode.parentNode;
		par.style.backgroundColor = 'transparent';
	}
}

function countKey(obj, no, i, type, maxNum){
	if(type == "Words"){
		var myArray = no.split(' ');
		
		for(var k = myArray.length - 1; k >= 0; k--) {
			if(myArray[k] === "") {
				myArray.splice(k, 1);
			}
		}
		
		var numar = maxNum - myArray.length;
	}
	else{
		var numar = maxNum - no.length;
	}
	document.getElementById("no_"+i).innerHTML = numar;
	if(numar < 0)
	changeColor(i, 'no', 'red')
	else changeColor(i, 'no', '#666666')
	var par = obj.parentNode.parentNode.parentNode.parentNode;
	if(numar != maxNum){
		par.style.backgroundColor='transparent';
	}
	else par.style.backgroundColor='#ffffcc';
}

function countDesc(obj, dtext, id, type, maxNum){
	maxNum = parseInt(maxNum);
	if(type == "Words"){
		var words = dtext.split(/[^\w\d-]+/g);
		var len = words.length;
		for(var i=0; i<len; i++){
			if(!words[i]){
				words.splice(i,1);
				len--; break;
			}
		}
		
		for(var k = words.length - 1; k >= 0; k--) {
			if(words[k] === "") {
				words.splice(k, 1);
			}
		}
		
		var numar = maxNum - words.length;
	}
	else{
		var numar = maxNum - dtext.length;
	}
	
	document.getElementById("do_"+id).innerHTML = numar;
	if(numar < 0)
		changeColor(id, 'do', 'red')
	else changeColor(id, 'do', '#666666')
	var par = obj.parentNode.parentNode.parentNode.parentNode;
	
	if(dtext.length >0){
		par.style.backgroundColor='transparent';
	}
	else{
		par.style.backgroundColor='#ffffcc';
	}
}

function countTitle(obj, no, i, type, maxNum){
	if(type == "Words"){
		var myArray = no.split(' ');
		
		for(var k = myArray.length - 1; k >= 0; k--) {
			if(myArray[k] === "") {
				myArray.splice(k, 1);
			}
		}
		
		var numar = maxNum - myArray.length;
	}
	else{
		var numar = maxNum - no.length;
	}
	
	document.getElementById("go_"+i).innerHTML = numar;
	if(numar < 0)
	changeColor(i, 'go', 'red')
	else changeColor(i, 'go', '#666666')
	var par = obj.parentNode.parentNode.parentNode.parentNode;
	if(numar != maxNum) {
		par.style.backgroundColor='transparent';
	}
	else par.style.backgroundColor='#ffffcc';
}

function showSpecificMenu(what_to_show, controller) {
	$('list_menus').setStyle('display', 'none');
	$('list_mtree').setStyle('display', 'none');
	$('list_zoo').setStyle('display', 'none');
	$('list_ktwo').setStyle('display', 'none');
	$('list_kunena').setStyle('display', 'none');
	$('list_easyblog').setStyle('display', 'none');
	
	if (what_to_show) {
		$(what_to_show).setStyle('display', 'block');
	}
	
	document.adminForm.task.value = controller;
	document.adminForm.controller.value = controller;
	document.adminForm.submit();
}

function showMenu(selected) {
	var controller = document.adminForm.controller.value;

	if (controller == "articles" || controller == "menus" || controller == "mtree" 
	    || controller == "zoo" || controller == "ktwo" || controller == "kunena" || controller == "easyblog") {
		switch(selected) {
			case '':
			case 'articles':
				showSpecificMenu(0, 'articles');
				break;		
			case 'menus':
				showSpecificMenu('list_menus', 'menus');
				break;
			case 'mtree':
				showSpecificMenu('list_mtree', 'mtree');
				break;
			case 'zoo':
				showSpecificMenu('list_zoo', 'zoo');
				break;	
			case 'ktwo':
				showSpecificMenu('list_ktwo', 'ktwo');
				break;
			case 'kunena':
				showSpecificMenu('list_kunena', 'kunena');
				break;
			case 'easyblog':
				showSpecificMenu('list_easyblog', 'easyblog');
				break;
		}
	} else if(controller == "keysarticles" || controller == "keysmenus" || controller == "keysmtree" 
				|| controller == "keyszoo" || controller == "keysktwo" || controller == "keyskunena"
				|| controller == "keyseasyblog") {
		switch(selected){
			case '':
			case 'articles':
				$('list_menus').setStyle('display', 'none');
				$('list_mtree').setStyle('display', 'none');
				$('list_zoo').setStyle('display', 'none');
				$('list_ktwo').setStyle('display', 'none');
				$('list_kunena').setStyle('display', 'none');
				$('list_easyblog').setStyle('display', 'none');
				document.adminForm.task.value="keysarticles";
				document.adminForm.controller.value="keysarticles";
				document.adminForm.submit();
				break;
			case 'menus':
				$('list_menus').setStyle('display', 'block');
				$('list_mtree').setStyle('display', 'none');
				$('list_zoo').setStyle('display', 'none');
				$('list_ktwo').setStyle('display', 'none');
				$('list_kunena').setStyle('display', 'none');
				$('list_easyblog').setStyle('display', 'none');
				document.adminForm.task.value="keysmenus";
				document.adminForm.controller.value="keysmenus";
				break;
			case 'mtree':
				$('list_mtree').setStyle('display', 'block');
				$('list_menus').setStyle('display', 'none');				
				$('list_zoo').setStyle('display', 'none');
				$('list_ktwo').setStyle('display', 'none');
				$('list_easyblog').setStyle('display', 'none');
				$('list_kunena').setStyle('display', 'none');
				document.adminForm.task.value="keysmtree";
				document.adminForm.controller.value="keysmtree";				
				break;
			case 'zoo':
				$('list_mtree').setStyle('display', 'none');
				$('list_menus').setStyle('display', 'none');				
				$('list_zoo').setStyle('display', 'block');
				$('list_ktwo').setStyle('display', 'none');
				$('list_kunena').setStyle('display', 'none');
				$('list_easyblog').setStyle('display', 'none');
				document.adminForm.task.value="keyszoo";
				document.adminForm.controller.value="keyszoo";				
				break;		
			case 'ktwo':
				$('list_mtree').setStyle('display', 'none');
				$('list_menus').setStyle('display', 'none');				
				$('list_zoo').setStyle('display', 'none');
				$('list_ktwo').setStyle('display', 'block');
				$('list_easyblog').setStyle('display', 'none');
				$('list_kunena').setStyle('display', 'none');
				document.adminForm.task.value="keysktwo";
				document.adminForm.controller.value="keysktwo";
				break;
			case 'kunena':
				$('list_mtree').setStyle('display', 'none');
				$('list_menus').setStyle('display', 'none');				
				$('list_zoo').setStyle('display', 'none');
				$('list_ktwo').setStyle('display', 'none');
				$('list_easyblog').setStyle('display', 'none');
				$('list_kunena').setStyle('display', 'block');
				document.adminForm.task.value="keyskunena";
				document.adminForm.controller.value="keyskunena";
				break;
			case 'easyblog':
				$('list_mtree').setStyle('display', 'none');
				$('list_menus').setStyle('display', 'none');				
				$('list_zoo').setStyle('display', 'none');
				$('list_ktwo').setStyle('display', 'none');
				$('list_easyblog').setStyle('display', 'block');
				$('list_kunena').setStyle('display', 'none');
				document.adminForm.task.value="keyseasyblog";
				document.adminForm.controller.value="keyseasyblog";
				break;
		}
	}
}

function f_refresh(){
	var mtitle = document.metatags.mtitle.value;
	var metakey = document.metatags.metakey.value;
	var metadesc = document.metatags.metadesc.value;
	var id = document.metatags.id.value;
	window.parent.document.location = 'index.php?option=com_ijoomla_seo&controller=pages&task=savepage&mtitle='+mtitle+'&metakey='+metakey+'&metadesc='+metadesc+'&id='+id;
	window.parent.SqueezeBox.close();	
}

function f_refresh2(){
	var mtitle = document.metatags.mtitle.value;
	var metakey = document.metatags.metakey.value;
	var metadesc = document.metatags.metadesc.value;
	var id = document.metatags.id.value;
	window.parent.document.location = 'index.php?option=com_ijoomla_seo&controller=keysarticles&task=savepage&mtitle='+mtitle+'&metakey='+metakey+'&metadesc='+metadesc+'&id='+id;
	window.parent.SqueezeBox.close();
}

function changeMenu() {
	var type = document.getElementById('type').value;
	if(type == "1"){
		document.getElementById('t_article').style.display = "block";
		document.getElementById('t_menu').style.display = "none";
		document.getElementById('t_menu_2').style.display = "none";
		document.getElementById('t_url').style.display = "none";	
	}
	else if(type == "2"){
		document.getElementById('t_article').style.display = "none";
		document.getElementById('t_menu').style.display = "block";
		document.getElementById('t_menu_2').style.display = "block";
		document.getElementById('t_url').style.display = "none";
	}
	else if(type == "3"){
		document.getElementById('t_article').style.display = "none";
		document.getElementById('t_menu').style.display = "none";
		document.getElementById('t_menu_2').style.display = "none";
		document.getElementById('t_url').style.display = "block";	
	}
    else if(type == "4") {
		document.getElementById('t_article').style.display = "none";
		document.getElementById('t_menu').style.display = "none";
		document.getElementById('t_menu_2').style.display = "none";
		document.getElementById('t_url').style.display = "none";
    }    
 }
 
function fieldSort(field, sort, num){
	var form =  document.adminForm;
	form.col.value = field; 
	form.colnum.value = num;
	updown = sort.value;
	if(updown == '' || updown == 'desc'){ 
		sort.value = 'asc'; 
	}	
	else{ 
		sort.value = 'desc';
	}	
}

function getMenuItems(value){
	var req = new Request.HTML({
		method: 'get',
		url: 'components/com_ijoomla_seo/javascript/ajax.php?tasks=changeMenuItems&menu_type='+value,
		data: { 'do' : '1' },
		update: $('t_menu_2'),
		onComplete: function(response){
		}
	}).send();
}

function changeSticky(image, path){
	alt = image.alt;
	var num = (image.id).substring(6);
	var id = parseInt($("cb"+num).value);
	var onoff = null;
					
	if(alt == 'sticky_off') {		
		onoff = 1;
	}
	else {
		onoff = 0;
	}

	var req = new Request.HTML({
		method: 'get',
		url: 'components/com_ijoomla_seo/javascript/ajax.php?tasks=change_sticky&sid='+id+'&onoff='+onoff,
		data: { 'do' : '1' },
		onComplete: function(response){
			if(alt == 'sticky_off') {
				image.alt = 'sticky_on';
				image.src = path+'components/com_ijoomla_seo/images/sticky_on.gif';
			}
			else {
				image.alt = 'sticky_off';
				image.src = path+'components/com_ijoomla_seo/images/sticky_off.gif';
			}		
		}
	}).send();
				
}

function changeStickyDB(id, onoff, path){
	var req = new Request.HTML({
		method: 'get',
		url: 'components/com_ijoomla_seo/javascript/ajax.php?tasks=change_sticky&sid='+id+'&onoff='+onoff,
		data: { 'do' : '1' },
		onComplete: function(response){		
		}
	}).send();
}

function getRank(key, i, path, search_count){
	key = key.replace("&", "*and*");
	var grank = 'rank'+i;
	var gchange = 'change'+i;
	$(gchange).innerHTML = '';
	oldrank = ($(grank).innerHTML == "-")? 0: parseInt($(grank).innerHTML);
	
	var req = new Request.HTML({
		method: 'get',
		url: 'components/com_ijoomla_seo/javascript/ajax.php?key='+key+'&tasks=get_Grank&oldrank='+oldrank,
		data: { 'do' : '1' },
		update: $(gchange),
		onComplete: function(response){
			if($(gchange).innerHTML == "0"){
				alert("Your keyword doesn't appear in the first "+search_count+" search results of Google!");
			}
			if($(gchange).innerHTML != 0 && $(grank).innerHTML == "-"){										
				$(grank).innerHTML = $(gchange).innerHTML;										
			}
			changeImg(i, key, path);
		}
	}).send();	
}

// function called after the ajax responses to the request
function changeImg(i, title, path){
	grank = 'rank'+i;
	gchange = 'change'+i;
	
	// old G rank
	if($(grank).innerHTML == '-')	{				
		vrank = 0; 						
	}									
	else{
		vrank = parseInt($(grank).innerHTML);					
	}
	
	// new G rank
	if($(gchange).innerHTML == '-'){
		vchange = 0;	
		out = '-';					
	}
	else{					
		vchange = parseInt($(gchange).innerHTML);
		if(vrank){ 
			out = vchange;
		}
		else{
			out = '-';
		}
	}
	
	$(grank).innerHTML = out;
	
	var change = 0;	
	if(vchange && vchange != vrank){
		change = Math.abs(vchange - vrank);	
	}
		
	if(change){
		val = change;
	}
	else{
		val = '-';
	}
	
	var mode = -1;		
	//alert(vchange+" "+change+" "+vrank+" ");				
	if((vchange > vrank || vchange == 0) && vrank > 0 && change>0){						
		$(gchange).innerHTML = '<span style="color:red">'+val+'</span>'+'&nbsp;&nbsp;<img src="'+path+'images/down.gif" border="0" alt="down" align="absmiddle"/>';
			mode = 0;
	}
	
	else if((vchange < vrank || vrank == 0) && vchange > 0 && change>0){
		$(gchange).innerHTML = '<span style="color:green">'+val+'</span>'+'&nbsp;&nbsp;<img src="'+path+'images/up.gif" border="0" alt="up" align="absmiddle"/>';
			mode = 1;
	}
	else{
		$(gchange).innerHTML = '-';
	}
	
	var req = new Request.HTML({
		method: 'get',
		url: 'components/com_ijoomla_seo/javascript/ajax.php?tasks=change&val='+change+'&key='+title+'&mode='+mode,
		data: { 'do' : '1' },
		onComplete: function(resp){ 
		}
	}).send();
}