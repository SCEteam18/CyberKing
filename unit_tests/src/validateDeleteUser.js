function askDelete() {
	//if (confirm('אתה בטוח שאתה רוצה למחוק את עצמך?')) {
		var xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {
				
				if (this.responseText.includes('הרשומה נמחקה')) {
					
					//alert("רשומה  נמחקה הצלחה");
					return false;
				}
				else {
					return true;
					//alert('שגיאה במחיקת הרשומה \n' + this.responseText);
				}
			}
		};
		
		xmlhttp.open("GET", "disableUser.php", true);
		xmlhttp.send();
		return false;
}