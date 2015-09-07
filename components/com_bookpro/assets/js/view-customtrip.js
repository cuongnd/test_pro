/**
 * 
 */
function loadSelectElement(selObjId, options) {
    var selObj = document.getElementById(selObjId);

    // clear the target select element apart from the "select your..." option
    selObj.options.length = 1;

    // copy options from array of [value, pair] arrays to select box
    // IE doesn't work if you use the DOM-standard method, however...
    if (typeof(window.clientInformation) != 'undefined') {
        // IE doesn't take the second "before" parameter...
        for (var loop=0; loop<options.length; loop++) selObj.add(new Option(options[loop][1], options[loop][0]));
    } else {
        for (var loop=0; loop<options.length; loop++) selObj.add(new Option(options[loop][1], options[loop][0]), null);
    }
}

function madeSelection(selObj) {
    var selectedValue = selObj.options[selObj.selectedIndex].value;
    var selectedText = selObj.options[selObj.selectedIndex].text;
    if (selectedValue == '--') return;
    if (selObj.name == 'select01') {
    	
        document.getElementById('select02').options[0].text = 'Select city';
     
        switch(selectedValue) {
            case 'vietnam':
                loadSelectElement('select02', [
                    ['hanoi', 'Hanoi'],
					['ho chi minh', 'Ho Chi Minh '],
                    ['an giang', 'An Giang'],
					['ba ria', 'Ba ria'],
					['binh thuan', 'Binh Thuan'],
					['cao bang', 'Cao Bang'],
					['da nang', 'Da Nang'],
					['daklak', 'Daklak'],
					['daknong', 'Daknong'],
					['dien bien', 'Dien Bien'],
					['hai phong', 'Hai Phong'],
					['hue', 'Hue'],
					['hoa binh', 'Hoa Binh'],
					['kien giang', 'Kien Giang'],
					['lam dong', 'Lam Dong'],
					['lang son', 'Lang Son'],
					['laocai', 'Lao Cai - Sapa'],
					['nha trang', 'Khanh Hoa - Nha Trang'],
					['nghe an', 'Nghe An'],
					['ninh binh', 'Ninh Binh'],
					['quang nam', 'Quang Nam - Hoi An'],
					['quang ninh', 'Quang Ninh - Halong'],
				    ['quang tri', 'Quang Tri'],
					['tay ninh', 'Tay Ninh'],
					['vinh long', 'Vinh Long']

                ]);
                return;

            case 'laos':
                loadSelectElement('select02', [
                    ['vientiane', 'Vientiane'],
                    ['luang prabang', 'Luang Prabang'],
					['attapeu', 'Attapeu'],
					['bokeo', 'Bokeo'],
					['bolikhamxay', 'Bolikhamxay'],
					['champasak', 'Champasak'],
					['houaphan', 'Houaphan'],
					['khammouane', 'Khammouane'],
					['luang namtha', 'Luang Namtha'],
					['oudomxay', 'Oudomxay'],
					['phong saly', 'Phong Saly'],
					['salavan', 'Salavan'],
					['savannakhet', 'Savannakhet'],
					['xieng khouang', 'Xieng Khouang']
                ]);
                return;

            case 'cambodia':
                loadSelectElement('select02', [
                    ['phnom penh', 'Phnom Penh'],
                    ['siem reap', 'Siem Reap'],
					['banteay meanchey', 'Banteay Meanchey'],
					['battambang', 'Battambang '],
					['kampong cham', 'Kampong Cham'],
					['kampong speu ', 'Kampong Speu '],
					['kampong chhnang ', 'Kampong Chhnang '],
					['kampong thom', 'Kampong Thom'],
					['kampot ', 'Kampot '],
					['kep', 'Kep'],
					['koh kong ', 'Koh Kong '],
					['mondulkiri ', 'Mondulkiri '],
					['oddor meanchey ', 'Oddor Meanchey '],
					['preah vihear', 'Preah Vihear'],
					['sihanoukville', 'Sihanoukville'],
					['Takeo', 'Takeo']
                ]);
                return;

            case 'thailand':
                loadSelectElement('select02', [
                    ['bangkok', 'Bangkok'],
					['ayutthaya', 'Ayutthaya'],
				    ['buriram', 'Buriram'],	
					['chanthaburi', 'Chanthaburi'],	
					['chiang mai', 'Chiang Mai'],
					['chiang rai', 'Chiang Rai'],
					['chon buri', 'Chon Buri'],
					['chumphon', 'Chumphon '],
					['kanchanaburi', 'Kanchanaburi'],
					['krabi', 'Krabi'],
					['lampang', 'Lampang'],
					['lamphun', 'Lamphun'],
					['mae hong son', 'Mae Hong Son'],
					['nakhon phanom', 'Nakhon Phanom'],
					['nan', 'Nan'],
					['narathiwat', 'Narathiwat'],
					['nong khai', 'Nong Khai'],
					['nonthaburi', 'Nonthaburi'],
					['pattani', 'Pattani'],
					['phang nga', 'Phang Nga'],
					['phatthalung', 'Phatthalung'],
					['phayao', 'Phayao'],
					['rayong', 'rayong'],
					['phayao', 'Phayao'],
					['Satun', 'Satun'],
					['sukhothai', 'Sukhothai'],
					['tak', 'Tak'],
					['trang', 'Trang'],
					['yala', 'Yala']

                ]);
                return;
		 
		    case 'myanmar':
                loadSelectElement('select02', [
                    ['yangon', 'Yangon'],
					['ayeyarwady', 'Ayeyarwady'],
					['bago', 'Bago'],
					['magway', 'Magway'],
					['mandalay', 'Mandalay'],
					['sagaing', 'Sagaing'],
					['tanintharyi', 'Tanintharyi'],
					['chin', 'Chin'],
					['kachin', 'Kachin'],
					['kayah', 'Kayah'],
					['mon', 'Mon'],
					['rakhine', 'Rakhine'],
					['shan', 'Shan']
                ]);
                return;
        }
    } // select01
}
/**
 * Select end trip
 * 
 */

function loadSelectElement1(selObjId, options) {
    var selObj = document.getElementById(selObjId);

    // clear the target select element apart from the "select your..." option
    selObj.options.length = 1;

    // copy options from array of [value, pair] arrays to select box
    // IE doesn't work if you use the DOM-standard method, however...
    if (typeof(window.clientInformation) != 'undefined') {
        // IE doesn't take the second "before" parameter...
        for (var loop=0; loop<options.length; loop++) selObj.add(new Option(options[loop][1], options[loop][0]));
    } else {
        for (var loop=0; loop<options.length; loop++) selObj.add(new Option(options[loop][1], options[loop][0]), null);
    }
}

function madeSelection1(selObj) {
    var selectedValue = selObj.options[selObj.selectedIndex].value;
    var selectedText = selObj.options[selObj.selectedIndex].text;
    if (selectedValue == '--') return;

    if (selObj.name == 'select001') {
        document.getElementById('select002').options[0].text = 'Select city';
        switch(selectedValue) {
            case 'vietnam':
                loadSelectElement1('select002', [
                    ['hanoi', 'Hanoi'],
					['ho chi minh', 'Ho Chi Minh '],
                    ['an giang', 'An Giang'],
					['ba ria', 'Ba ria'],
					['binh thuan', 'Binh Thuan'],
					['cao bang', 'Cao Bang'],
					['da nang', 'Da Nang'],
					['daklak', 'Daklak'],
					['daknong', 'Daknong'],
					['dien bien', 'Dien Bien'],
					['hai phong', 'Hai Phong'],
					['hue', 'Hue'],
					['hoa binh', 'Hoa Binh'],
					['kien giang', 'Kien Giang'],
					['lam dong', 'Lam Dong'],
					['lang son', 'Lang Son'],
					['laocai', 'Lao Cai - Sapa'],
					['nha trang', 'Khanh Hoa - Nha Trang'],
					['nghe an', 'Nghe An'],
					['ninh binh', 'Ninh Binh'],
					['quang nam', 'Quang Nam - Hoi An'],
					['quang ninh', 'Quang Ninh - Halong'],
				    ['quang tri', 'Quang Tri'],
					['tay ninh', 'Tay Ninh'],
					['vinh long', 'Vinh Long']

                ]);
                return;

            case 'laos':
                loadSelectElement1('select002', [
                    ['vientiane', 'Vientiane'],
                    ['luang prabang', 'Luang Prabang'],
					['attapeu', 'Attapeu'],
					['bokeo', 'Bokeo'],
					['bolikhamxay', 'Bolikhamxay'],
					['champasak', 'Champasak'],
					['houaphan', 'Houaphan'],
					['khammouane', 'Khammouane'],
					['luang namtha', 'Luang Namtha'],
					['oudomxay', 'Oudomxay'],
					['phong saly', 'Phong Saly'],
					['salavan', 'Salavan'],
					['savannakhet', 'Savannakhet'],
					['xieng khouang', 'Xieng Khouang']
                ]);
                return;

            case 'cambodia':
                loadSelectElement1('select002', [
                    ['phnom penh', 'Phnom Penh'],
                    ['siem reap', 'Siem Reap'],
					['banteay meanchey', 'Banteay Meanchey'],
					['battambang', 'Battambang '],
					['kampong cham', 'Kampong Cham'],
					['kampong chhnang ', 'Kampong Chhnang '],
					['kampong speu ', 'Kampong Speu '],
					['kampong thom', 'Kampong Thom'],
					['kampot ', 'Kampot '],
					['kep', 'Kep'],
					['koh kong ', 'Koh Kong '],
					['mondulkiri ', 'Mondulkiri '],
					['oddor meanchey ', 'Oddor Meanchey '],
					['preah vihear', 'Preah Vihear'],
					['sihanoukville', 'Sihanoukville'],
					['Takeo', 'Takeo']
                ]);
                return;

            case 'thailand':
                loadSelectElement1('select002', [
                    ['bangkok', 'Bangkok'],
					['ayutthaya', 'Ayutthaya'],
				    ['buriram', 'Buriram'],	
					['chanthaburi', 'Chanthaburi'],	
					['chiang mai', 'Chiang Mai'],
					['chiang rai', 'Chiang Rai'],
					['chon buri', 'Chon Buri'],
					['chumphon', 'Chumphon '],
					['kanchanaburi', 'Kanchanaburi'],
					['krabi', 'Krabi'],
					['lampang', 'Lampang'],
					['lamphun', 'Lamphun'],
					['mae hong son', 'Mae Hong Son'],
					['nakhon phanom', 'Nakhon Phanom'],
					['nan', 'Nan'],
					['narathiwat', 'Narathiwat'],
					['nong khai', 'Nong Khai'],
					['nonthaburi', 'Nonthaburi'],
					['pattani', 'Pattani'],
					['phang nga', 'Phang Nga'],
					['phatthalung', 'Phatthalung'],
					['phayao', 'Phayao'],
					['rayong', 'rayong'],
					['phayao', 'Phayao'],
					['Satun', 'Satun'],
					['sukhothai', 'Sukhothai'],
					['tak', 'Tak'],
					['trang', 'Trang'],
					['yala', 'Yala']

                ]);
                return;
		 
		    case 'myanmar':
                loadSelectElement1('select002', [
                    ['yangon', 'Yangon'],
					['ayeyarwady', 'Ayeyarwady'],
					['bago', 'Bago'],
					['magway', 'Magway'],
					['mandalay', 'Mandalay'],
					['sagaing', 'Sagaing'],
					['tanintharyi', 'Tanintharyi'],
					['chin', 'Chin'],
					['kachin', 'Kachin'],
					['kayah', 'Kayah'],
					['mon', 'Mon'],
					['rakhine', 'Rakhine'],
					['shan', 'Shan']
                ]);
                return;
        }
    } // select001

}

//-->
//Vadilator form;
function validate(formcustom) {

	var isChecked=false;
	var mode=document.formcustom;
	for(var i=0;i<document.forms["formcustom"].length;i++){
	if(document.forms["formcustom"][i].checked){
	isChecked=true;
	}} if(isChecked){}
	else{ 
	alert('Please select atleast one destination');
	 return false;
	}
	}
