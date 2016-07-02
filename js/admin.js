// *************************************************************************************
// SET FOCUS ON FIRST FIELD - (id = "post") must exists
// *************************************************************************************
head_onload = function(env) {
	if (!document.getElementById || !document.getElementById("post")) {return;}
	var num = document.getElementById("post").elements.length;
	var myelement, el_id = '', name, type, found = false;
	for (var i=0; i < num; i++) {
		myelement = document.getElementById("post").elements[i];
		if ((myelement.type == 'text' || myelement.type == 'password' || myelement.type == 'email') && myelement.value === ""){
			myelement.focus();
			found = true;
			break;
		}
	}
};
// *************************************************************************************
// SET FOCUS - ADD EVENT
// *************************************************************************************
if (document.addEventListener) {
	document.addEventListener( "DOMContentLoaded", head_onload, false );
} else if (window.attachEvent) {
	window.onload = function (evt) {head_onload(evt);};
}
// *************************************************************************************
// DEPENDACY ONLOAD
// *************************************************************************************
function onload_dependacy() {
	var category = document.forms.post && document.forms.post.define_category ? document.forms.post.define_category : false;
	if (category) {
		var page = document.getElementById('def_page');
		var pextra = document.getElementById('def_subcat');
		if (page == null || pextra == null) {return;}
		page.style.display = category.options[category.selectedIndex].value == '-3' ? 'inline' : 'none';
		pextra.style.display = category.options[category.selectedIndex].value == '-1'  ||
			parseInt(category.options[category.selectedIndex].value) >= 1	? 'inline' : 'none';
	}
}
if (document.addEventListener) {
	document.addEventListener( "DOMContentLoaded", onload_dependacy, false );
} else if (window.attachEvent) {
	window.onload = function (evt) {onload_dependacy(evt);};
}


// *************************************************************************************
// OLD JAVASCRIPT snews 1.71 fixed and verified by lint
// *************************************************************************************
	var allowsef = /new|add|_ar|_ca/.test("new");
	var allowpreview = /new|_add|_ar|/.test("");

	// Detect browser and version
	var browser = navigator.appName;
	var get_info = navigator.appVersion;
	var version = parseFloat(get_info);

	if (allowpreview === true && browser != "Microsoft Internet Explorer" && browser!="Netscape" && version>=4) {
		window.onload = function() { 
			window.self.setInterval("updatePreview()", 1500);
		};
	}
	if (allowpreview === true && (browser == "Microsoft Internet Explorer" || browser=="Netscape") && version>=4) {
	  	window.onload = function() {
	  		window.self.setInterval("updatePreview()", 1500);
	  	};
	}

	// generate SEF url
   	function genSEF(from, to) {
    	if (allowsef === true) {
         	var str = from.value.toLowerCase();
         	str = str.replace(/[\xc0-\xc5\xe0-\xe5\u0100-\u0105\u0386\u0391\u03ac\u03b1\u0410\u0430\u05d0]/g,'a');
         	str = str.replace(/[\xc8-\xcb\xe8-\xeb\u0116-\u011b\u0112\u0113\u0388\u0395\u03ad\u03b5\u042d\u044d]/g,'e');
         	str = str.replace(/[\xa1\xcc-\xcf\xec-\xef\u0128-\u012b\u012e-\u0132\u013a\u0389\u038a\u0390\u0397\u0399\u03aa\u03ae\u03af\u03b7\u03b9\u03ca\u0418\u0438]/g,'i');
         	str = str.replace(/[\xd2-\xd6\xd8\xf0\xf2-\xf6\xf8\u014d\u014c\u0150\u0151\u038c\u038f\u039f\u03a9\u03bf\u03c9\u03cc\u03ce\u041e\u043e]/g,'o');
         	str = str.replace(/[\xb5\xd9-\xdc\xf9-\xfc\u0171\u0173\u0168-\u0170\u0423\u0443]/g,'u');
         	str = str.replace(/[\u0392\u03b2\u05d1]/g,'b');
         	str = str.replace(/[\xc7\xe7\u0106-\u010d\u0147\u0148\u05da\u05db]/g,'c');
         	str = str.replace(/[\xd0\u010e-\u0111\u0394\u03b4\u05d3]/g,'d');
         	str = str.replace(/[\u03a6\u03c6]/g,'f');
         	str = str.replace(/[\u011c-\u0123\u0393\u03b3\u05d2]/g,'g');
         	str = str.replace(/[\u0124-\u0127\u05d4]/g,'h');
         	str = str.replace(/[\u0134\u0135]/g,'j');
         	str = str.replace(/[\u0136\u0137\u039a\u03ba\u05d7\u05e7]/g,'k');
         	str = str.replace(/[\u0139-\u013e\u0141\u0142\u039b\u03bb\u05dc]/g,'l');
         	str = str.replace(/[\u039c\u03bc\u05dd\u05de]/g,'m');
         	str = str.replace(/[\xd1\xf1\u0143-\u0148\u039d\u03bd\u05df\u05e0]/g,'n');
         	str = str.replace(/[\u0154-\u0159\u03a1\u03c1\u05e8]/g,'r');
         	str = str.replace(/[\u03a0\u03c0\u05e3\u05e4]/g,'p');
         	str = str.replace(/[\x8a\x9a\xdf\u015a-\u0161\u03a3\u03c2\u03c3\u05e1]/g,'s');
         	str = str.replace(/[\u0162-\u0167\u021a\u021b\u03a4\u03c4\u05d8\u05ea]/g,'t');
         	str = str.replace(/[\u05d5]/g,'v');
         	str = str.replace(/[\u03be\u039e]/g,'x');
         	str = str.replace(/[\x9f\xdd\xfd\xff\u038e\u03a5\u03ab\u03b0\u03c5\u03cb\u03cd\u05d9]/g,'y');
         	str = str.replace(/[\x9e\u0179-\u017e\u0396\u03b6\u05d6]/g,'z');
         	str = str.replace(/[\u05e2]/g,'aa');
         	str = str.replace(/[\xc6\xe6]/g,'ae');
         	str = str.replace(/[\u03a7\u03c7]/g,'ch');
         	str = str.replace(/[\u039e\u03be\u0152\u0153]/g,'oe');
         	str = str.replace(/[\xde\xfe\u0398\u03b8]/g,'th');
         	str = str.replace(/[\u05e5\u05e6]/g,'ts');
         	str = str.replace(/[\u03c8\u03a8\u0398\u03b8]/g,'ps');
         	str = str.replace(/[\u05e9]/g,'sh');
         	str = str.replace(/[\xdf]/g,'sz');
         	str = str.replace(/[^a-z 0-9]+/g,'');
         	str = str.replace(/\s+/g, "-");
         	to.value = str;
      	}
   	}

	// generate preview
	function updatePreview() {
		if (document.getElementById('txt')) {
			var body = document.getElementById('txt').value;
			document.getElementById('preview').innerHTML = body;
		}
	}

	// settings home SEF restrict to alphanumeric
	function SEFrestrict(x) {
		var key = event.which || event.keyCode;
		var keychar = String.fromCharCode(key);
		keychar.toLowerCase();
		if (key == (null || 0 || 8 || 13 || 27) || ("abcdefghijklmnopqrstuvwxyz0123456789-_").indexOf(keychar) > -1) {
			return true;
		}
		if (key == ("9").indexOf(keychar) > -1) {
			return true;	
		} else {
			return false;
		}
	}
	
	// Basic promp message
	function pop(text) {
		var agree = confirm(text);
		if (agree) {return true;}
		else {return false;}
	}

	// basic html textarea editor
	function tag(tag, txt1, txt2) {
		var src = document.getElementById('txt');
		var start, end, url, alt, title='';
		switch(tag) {
			case 'underline': 
				start = '<span class="underline">'; 
				end = '<\/span>'; 
				break;
			case 'intro': 
				start = '[break]'; 
				end = '\n'; 
				break;
			// function use
			case 'func':// adding function insertion
				// function name no brackets
				url = prompt(txt1, '');
				//params, seperated by comma.
				title = prompt(txt2, "");
				if (url !== null) {
					start = '[func]'+url+':|:'; 
					end = '[\/func]';
				} else {
					start = ''; 
					end = ''; 
					title = '';
				}
				break;
			// function use end
			case 'include':
				url = prompt(txt1, '');
				start = url !== null ? '[include]'+url+'[\/include]' : '';
				end = '';
				break;
			case 'br': 
				start = ''; 
				end = '<br \/>\n'; 
				break;
			case 'img':
				url = prompt(txt1, '');
				alt = prompt(txt2, '');
				start = url !== null ? '<img src="'+url+'" alt="'+alt+'" \/>' : '';
				end = '';
				break;
			case 'link':
				url = prompt(txt1, '');
				title = prompt(txt2, '');
				if (url !== null) {
					start = '<a href="'+url+'" title="'+title+'">'; 
					end = '<\/a>';
				} else {
					start = '';
					end = ''; 
					title = '';
				}
				break;
			default: 
				start = '<'+tag+'>'; 
				end = '<\/'+tag+'>\n';
		} var codetext = '';
		if (!src.setSelectionRange) {
         	var selected = document.selection.createRange().text;
         	src.focus();
         	if (selected.length <= 0) {
         		codetext = start + title + end;
         	} else {
         		codetext = start + selected + end;
         	}
         	document.selection.createRange().text = codetext;
      	} else {
        	var h = src.scrollTop;
         	var pretext = src.value.substring(0, src.selectionStart);
         	codetext = start + src.value.substring(src.selectionStart, src.selectionEnd) + end;
         	var posttext = src.value.substring(src.selectionEnd, src.value.length);
         	if (codetext == start + end) {
         		codetext = start + title + end;
         	}
         	src.value = pretext + codetext + posttext;
         	document.getElementById("txt").scrollTop = h;
         	src.selectionStart = pretext.length;
         	src.selectionEnd = pretext.length+codetext.length;
         	src.focus();
		}
	}
	
	// toggle dynamic divs
	function toggle(div) {
		var elem = document.getElementById(div);
		if (elem.style.display === '') {
			elem.style.display = 'none'; 
			return;
		}
		elem.style.display = '';
	}
	// dependancy limiter
	function dependancy(extra) {
		var category = document.forms.post.define_category;
		var page = document.getElementById('def_page');
		var pextra = document.getElementById('def_subcat');
		// extra edit
		if (extra == 'extra') {
			page.style.display = category.options[category.selectedIndex].value == '-3' ? 'inline' : 'none';
			pextra.style.display = category.options[category.selectedIndex].value == '-1'  ||
				parseInt(category.options[category.selectedIndex].value) >= 1	? 'inline' : 'none';
		}
	}