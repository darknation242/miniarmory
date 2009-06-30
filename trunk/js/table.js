function test() {
	var row = document.createElement('TR');
	var td1 = document.createElement('TD');
	td1.appendChild(document.createTextNode('dsadsada'));
	row.appendChild(td1);
	document.getElementById('test_table').appendChild(row);
}

function delRows(table) {
	while(document.getElementById(table).rows.length>1) {
	    	delLastRow(table);
	}
}

function addRow(tds,table,id,classes,trclass) {
         
	table = document.getElementById(table);
   	var row = document.createElement('TR');
	row.className = trclass;
        if(id!='undefined') row.id = id;
	for(var i=0;i<tds.length;i++) {
	    var td = document.createElement('TD');
	    td.className=classes[i];
            
	    td.innerHTML=tds[i];
		row.appendChild(td);
	}
	table.appendChild(row);
}

function delLastRow(table) {
	table = document.getElementById(table);
	table.removeChild(table.lastChild);
}