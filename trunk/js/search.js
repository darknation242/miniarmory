var page=0;
var lastpage=0;
var per_page=5;
var section;
var sort_by;
var sort_by_nr = Array();
var sort_by_st = Array();
var sort_asc=1;
var guildid = 0;

var char_list = 0;
var guild_list = 0;

var RealmID = 0;

var dots=0;


function wait() {
	try {
		var dt='';
		dots++;
		for(i=0;i<dots;i++) dt+='.';
		
		if(dots>3) dots=0;
		document.getElementsByTagName('body')[0].style.cursor='wait';
		document.getElementById('loading-box').innerHTML = LG['loading'];
		document.getElementById('loading-box').style.display = "inline";
	}catch(e) {};	
}
function wait_end() {
	try {
		dots=0;
		document.getElementsByTagName('body')[0].style.cursor='auto';
		document.getElementById('loading-box').innerHTML = '';
		document.getElementById('loading-box').style.display = "none";
	}catch(e) {};	
}

function preproces() {
	var table = document.getElementById('res_table');
	tds = table.getElementsByTagName('td');
	for(i=0;i<tds.length;i++) {
		txt = tds[i].innerHTML;
		try {
			sort_by_nr[txt.toLowerCase()] = i;
			sort_by_st[i] = txt;
		}catch(e) {};
	}	
}

function view() {
	if(section=='char') view_char_list();else if(section=='guild')view_guild_list();
}

function enableNextButtons() {try{next = document.getElementById('next_button');next.className = "page_next csearch-page-button";next.onclick = next_page;}catch(e){};try{next = document.getElementById('fastnext_button');next.className = "page_fastnext csearch-page-button";next.onclick = fastnext_page;}catch(e){};}

function enablePrevButtons() {try{prev = document.getElementById('prev_button');prev.className = "page_prev csearch-page-button";prev.onclick = prev_page;}catch(e){};try{prev = document.getElementById('fastprev_button');prev.className = "page_fastprev csearch-page-button";prev.onclick = fastprev_page;}catch(e){};}

function disableNextButtons() {try{next = document.getElementById('next_button');next.className = "page_next_disabled csearch-page-button";next.onclick = null;}catch(e){};try{next = document.getElementById('fastnext_button');next.className = "page_fastnext_disabled csearch-page-button";next.onclick = null;}catch(e){};}

function disablePrevButtons() {try{prev = document.getElementById('prev_button');prev.className = "page_prev_disabled csearch-page-button";prev.onclick = null;}catch(e){};try{prev = document.getElementById('fastprev_button');prev.className = "page_fastprev_disabled csearch-page-button";prev.onclick = null;}catch(e){};}

JTP_time = 0;

function jumpToPage(pg) {
	clearTimeout(JTP_time);
	field = document.getElementById('page-input');
	value = field.value;
	
	if((value<1 && !isNaN(value)) && !pg) {
		JTP_time=setTimeout("jumpToPage(true)",2000);
		return;
	}else if(pg || isNaN(value)) {
		value = 1;
	}
	if(value>lastpage+1) value=lastpage+1;
	field.value = value;
	goToPage(value-1);
	document.getElementById('page-input').focus();
}

function setPerPage(fg) {
	sel = document.getElementById('per_page');
	if(!sel) return;
	if(fg) {
		if(sel.value<=0) return;
		per_page = sel.value;
		view();
	}
	sel = document.getElementById('per_page');
	sel = sel.getElementsByTagName('option');
	for(i=0;i<sel.length;i++) {
		if(per_page==sel[i].value) sel[i].selected = 1;	
	}
}



function next_page() {page++;view();}
function prev_page() {page--;view();}
function fastnext_page() {page=Number(lastpage);view();}
function fastprev_page() {page=0;view();}
function goToPage(nr) {page=Number(nr);view();}

function sort_cmp(a,b) {
	if(sort_asc) {
		tmp=a;a=b;b=tmp;	
	}
	v1 = a.getElementsByTagName(sort_by)[0].childNodes[0].nodeValue;
	v2 = b.getElementsByTagName(sort_by)[0].childNodes[0].nodeValue
	if(!isNaN(Number(v1))) {
		v1 = Number(v1);
		v2 = Number(v2);
	}
	if(v1>v2) return 1;
	else return -1;
}

function set_sort(sort_) {
	sort_by = sort_;sort_asc = (sort_asc+1)%2;
	if(section=='char') {
		char_list.sort(sort_cmp);
		view_char_list();
	}else if(section=='guild') {
		guild_list.sort(sort_cmp);
		view_guild_list();
	}
}

function search_character_start(type) {
	if(type!=2 && type!=1) page=0; 
	try {
		form = document.getElementById('search_character');
	}catch(e) {alert('Cannot access to search data :(');return;}
	if(form) name = form.name.value;
	else name='';
	section='char';
	try {
		wait();
		search_character(name,1,80,guildid,0);
	}catch(e) {
	  	return;
	}
}
function search_guild_start(type) {
	if(type!=2 && type!=1) page=0; 
	
	try {
		form = document.getElementById('search_guild');
	}catch(e) {alert('Cannot access to search data :(');return;}
	section='guild';
	try {
		wait();
		search_guild(form.name.value);
	}catch(e) {
	  	
	}
}

function view_char_list() {
	 			if(typeof(char_list)=='number') {
					// Uzytkownik klika szybciej niz mysli :(
					setTimeout("view_char_list()",150);
					return;
				}
				
				delRows('char_table');
				disablePrevButtons();
				disableNextButtons();
				var chars=char_list;
				var count = chars.length;
				wait_end();
				if(chars.length==0) {
				   tds = new Array();
				   classes = new Array();
				   classes[0] = classes[1] = classes[2] = classes[3]= classes[4]= classes[5] = classes[6] = 'csearch-results-table-item';;
				   tds[1]=tds[2]=tds[3]=tds[4]=tds[5]=tds[6]='';
				   tds[0]=LG['noresults'];
				   addRow(tds,'char_table','no-results',classes);	
				}
				//if(overcount>0) overcount=0;
				for(var i=page*per_page;i<chars.length && i<(page+1)*per_page;i++) {
				   tds = new Array();
				   classes = new Array();
				   id = chars[i].getElementsByTagName('guid')[0].childNodes[0].nodeValue;
				   tds[0] = '<img src="'+_DOMAIN+'images/icons/'+chars[i].getElementsByTagName('alliance')[0].childNodes[0].nodeValue+'.png" alt=""> <a href="'+_DOMAIN+'index.php?character='+id+'&Realm='+chars[i].getElementsByTagName('realm')[0].childNodes[0].nodeValue+'">'+chars[i].getElementsByTagName('name')[0].childNodes[0].nodeValue+'</a>';
				   
				   tds[1]=chars[i].getElementsByTagName('level')[0].childNodes[0].nodeValue;
				   
				   tds[2] = '<img onMouseOut="tooltip_hide()" onMouseOver="tooltip(\''+LG[chars[i].getElementsByTagName('race_string')[0].childNodes[0].nodeValue]+'\')" src="'+_DOMAIN+'images/icons/race/'+
					   chars[i].getElementsByTagName('race')[0].childNodes[0].nodeValue+'-'+
					   chars[i].getElementsByTagName('gender')[0].childNodes[0].nodeValue+'.gif" alt="">';
				   tds[3] = '<img onMouseOut="tooltip_hide()" onMouseOver="tooltip(\''+LG[chars[i].getElementsByTagName('class_string')[0].childNodes[0].nodeValue]+'\')" src="'+_DOMAIN+'images/icons/class/'+
					   chars[i].getElementsByTagName('class')[0].childNodes[0].nodeValue+'.gif" alt="">';
				   if(guildid>0) {
					   tds[4]=chars[i].getElementsByTagName('rname')[0].childNodes[0].nodeValue;
				   }else {
					   if(chars[i].getElementsByTagName('guildid')[0].childNodes[0].nodeValue>0) {
					   	tds[4]='<a href="'+_DOMAIN+'index.php?guild='+chars[i].getElementsByTagName('guildid')[0].childNodes[0].nodeValue+'&Realm='+chars[i].getElementsByTagName('realm')[0].childNodes[0].nodeValue+'">'+chars[i].getElementsByTagName('guild')[0].childNodes[0].nodeValue+'</a>';
					   }else{
						   tds[4]=chars[i].getElementsByTagName('guild')[0].childNodes[0].nodeValue;
					   }
				   }
				   tds[5]=chars[i].getElementsByTagName('honor')[0].childNodes[0].nodeValue;
				   
				   
				   if(guildid) {
					   tds[6]=chars[i].getElementsByTagName('hk')[0].childNodes[0].nodeValue;
				   }else{
					   tds[6]=chars[i].getElementsByTagName('realm')[0].childNodes[0].nodeValue;   
				   }
				   classes[1] = classes[4] = classes[5] = classes[6] = '';
				   classes[2] = 'rightalign nopadding';
				   classes[3] = 'leftalign nopadding';

				   classes[sort_by_nr[sort_by]] = classes[sort_by_nr[sort_by]]+' csearch-results-table-item-ordered';
				   addRow(tds,'char_table',id,classes,'csearch-results-table-item');
				   
				}
				table_bottom(count,'char');
}


function view_guild_list() {
	 			if(typeof(guild_list)=='number') {
					// Uzytkownik klika szybciej niz mysli :(
					setTimeout("view_guild_list()",150);
					return;
				}

				delRows('guild_table');
				disablePrevButtons();
				disableNextButtons();
				
				var guild=guild_list;
				var count = guild_list.length;
				wait_end();
				if(guild.length==0) {
				   tds = new Array();
				   classes = new Array();
				   classes[0] = classes[1] = classes[2] = classes[3]= classes[4] = 'csearch-results-table-item';;
				   tds[1]=tds[2]=tds[3]=tds[4]='';
				   tds[0]=LG['noresults'];
				   addRow(tds,'guild_table','no-results',classes);	
				}
				//if(overcount>0) overcount=0;
				for(var i=page*per_page;i<guild.length && i<(page+1)*per_page;i++) {
				   tds = new Array();
				   classes = new Array();
				   tds[1] = '<img src="'+_DOMAIN+'images/icons/'+guild[i].getElementsByTagName('faction')[0].childNodes[0].nodeValue+'.png" alt="">';
				   
				   tds[0]='<a href="'+_DOMAIN+'index.php?guild='+guild[i].getElementsByTagName('id')[0].childNodes[0].nodeValue+'&Realm='+guild[i].getElementsByTagName('realm')[0].childNodes[0].nodeValue+'">'+guild[i].getElementsByTagName('name')[0].childNodes[0].nodeValue+'</a>';
				   
				   tds[2] = '<a href="'+_DOMAIN+'index.php?character='+guild[i].getElementsByTagName('leader_guid')[0].childNodes[0].nodeValue+'&Realm='+guild[i].getElementsByTagName('realm')[0].childNodes[0].nodeValue+'">'+guild[i].getElementsByTagName('leader')[0].childNodes[0].nodeValue+'</a>';
				   tds[3] = guild[i].getElementsByTagName('members')[0].childNodes[0].nodeValue;
				   tds[4]=guild[i].getElementsByTagName('realm')[0].childNodes[0].nodeValue;
				   id = guild[i].getElementsByTagName('id')[0].childNodes[0].nodeValue;
				   classes[0] = classes[4] = classes[2] = '';
				   classes[1] = 'centeralign';
				   classes[3] = 'centeralign';
				   
				   classes[sort_by_nr[sort_by]] = classes[sort_by_nr[sort_by]]+' csearch-results-table-item-ordered';
				   addRow(tds,'guild_table',id,classes,'csearch-results-table-item');
				}
				table_bottom(count,'guild');
}



function search_character(name,lvl_down,lvl_up,guild,class_f) {
	lvl_down = lvl_down<1 ? 1 : lvl_down;
	lvl_down = lvl_down>80 ? 80 : lvl_down;
	
	lvl_up = lvl_up<1 ? 1 : lvl_up;
	lvl_up = lvl_up>80 ? 80 : lvl_up;
	
	if(AJAX = getAjaxObject()) {
	   	AJAX.abort(); 
		var _GET = '?name='+name+'&lvl_down='+lvl_down+'&lvl_up='+lvl_up+'&guildid='+guild+'&class='+class_f+'&sort_asc='+sort_asc+'&sort_by='+sort_by+'&RealmID='+RealmID;
		
	    AJAX.open('GET', _DOMAIN+'ajax/search_character.php'+_GET, true);
		
		AJAX.onreadystatechange=function() {
			if((AJAX.readyState == 4) && (AJAX.status == 200)) {
				wait_end();
				tmp=AJAX.responseXML;
				s_count = tmp.getElementsByTagName('count')[0].childNodes[0].nodeValue;
				tmp=tmp.getElementsByTagName('character');
				char_list = new Array;
				for(i=0;i<tmp.length;i++) {
					char_list[i] = tmp[i];
				}
				view_char_list();
			}else if((AJAX.readyState == 4) &&  AJAX.status > 400) {
			   error();	
			   
			}
		}
		
		AJAX.send(null);
	}
	return 0;
	
}
function search_guild(name) {
	if(AJAX = getAjaxObject()) {
	   	AJAX.abort(); 
		var _GET = '?name='+name+'&sort_asc='+sort_asc+'&sort_by='+sort_by;
	    AJAX.open('GET', _DOMAIN+'ajax/search_guild.php'+_GET, true);
		
		AJAX.onreadystatechange=function() {
			if((AJAX.readyState == 4) && (AJAX.status == 200) ) {
				wait_end();
				tmp=AJAX.responseXML;
				s_count = tmp.getElementsByTagName('count')[0].childNodes[0].nodeValue;
				tmp=tmp.getElementsByTagName('guild');
				guild_list = new Array;
				for(i=0;i<tmp.length;i++) {
					guild_list[i] = tmp[i];
				}
				view_guild_list();
			}else if((AJAX.readyState == 4) &&  AJAX.status > 400) {
			   error();			
			}
		}
		AJAX.send(null);
	}
	return 0;
	
}


function table_bottom(count,type) {
				tds = new Array();
				per_pages = new Array(5,10,20,40);
				pages = parseInt(count/per_page);
				
				if(count%per_page>0) pages++;
				if(pages==0) pages=1;
				lastpage=pages-1;
				pp='';
				for(i=0;i<per_pages.length;i++) {
					pp+='<option value="'+per_pages[i]+'" '+(per_pages[i]==per_page?'selected':'')+'>'+per_pages[i]+'</option>';	
				}
				
				tds[0] = '<div style="float:left;">'+LG['page']+': <input type="text" value="'+Number(Number(page)+1)+'" style="width:25px;" id="page-input" onKeyUp="jumpToPage(false)"> / '+pages+'</div><div style="float:left;margin-left:24px;">'+LG['showing']+' <strong>'+count+'</strong> '+LG['of']+' <strong>'+s_count+'</strong> '+LG['results']+'</div>';
				tds[0] += '<div class="result-navi"><span class="page_fastprev_disabled csearch-page-button" id="fastprev_button"><span class="hidden2">.</span></span><span class="page_prev_disabled csearch-page-button" id="prev_button"><span class="hidden2">.</span></span>';
				k=page-2<0?0:page-2-(page-lastpage+1<0?0:page-lastpage+1);
				for(k=k<0?0:k;k<pages && k<Number(page)+2- (page-2<0?page-2:0);k++) {if(k==page) selected='-selected';else selected='';tds[0] += '<span onclick="goToPage(\''+k+'\')" class="csearch-page-number'+selected+' csearch-page-button">'+Number(k+1)+'</span>';}
				tds[0] +='<span class="page_next_disabled csearch-page-button" id="next_button"><span class="hidden2">.</span></span><span class="page_fastnext_disabled csearch-page-button" id="fastnext_button"><span class="hidden2">.</span></span></div><div style="float:right;margin-right:25px;">'+LG['rpp']+' <select id="per_page" onChange="setPerPage(true)">'+pp+'</select></div>';
				//addRow(tds,type+'_table','csearch-results-bottom',classes);
				document.getElementById('res-top').innerHTML = tds[0];
				
				if(count>(page+1)*per_page) enableNextButtons();
				if(page>0) enablePrevButtons();
}

function error() {
	document.getElementById('loading-box').innerHTML = 'ERROR';
}