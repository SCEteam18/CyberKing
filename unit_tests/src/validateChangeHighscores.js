function changeHighcores() {
	var category_list = document.getElementById("category_list");
	var tables = document.getElementsByTagName("table");
	
	//hide all high scores
	for (var i = 0; i < tables.length; i++) {
		tables[i].style.display = 'none';
	}
	//show the selected highscore
	if (category_list.value == 'all') {
		document.getElementById('tbl_all_categories').style.display = '';
	}
	else {
		document.getElementById('tbl' + category_list.value).style.display = '';
	}
	return true;
}