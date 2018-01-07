function validateInput() {
		var title = document.getElementById('title').value;
		var answer1 = document.getElementById('answer1').value;
		var answer2 = document.getElementById('answer2').value;
		var answer3 = document.getElementById('answer3').value;
		var answer4 = document.getElementById('answer4').value;
		
		
		if (title.length < 10) {
	        //alert("יש למלא לפחות 10 תווים בשדה שאלה");
	        return false;
	    }
	    if (answer1.length < 2) {
	        //alert("יש למלא לפחות תו אחד בשדה תשובה");
	        return false;
	    }
	    if (answer2.length < 2) {
	        //alert("יש למלא לפחות תו אחד בשדה תשובה");
	        return false;
	    }
	    if (answer3.length < 2) {
	        //alert("יש למלא לפחות תו אחד בשדה תשובה");
	        return false;
	    }
	    if (answer4.length < 2) {
	        //alert("יש למלא לפחות תו אחד בשדה תשובה");
	        return false;
	    }
		
		
	return true;	
}

