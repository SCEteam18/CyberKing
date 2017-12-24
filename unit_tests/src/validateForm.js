function validateForm() {
    var firstname = document.forms["registerForm"]["firstname"].value;
    var lastname = document.forms["registerForm"]["lastname"].value;
    var username = document.forms["registerForm"]["username"].value;
    var password = document.forms["registerForm"]["password"].value;
    var email = document.forms["registerForm"]["email"].value;
    var captcha = document.forms["registerForm"]["captcha"].value;

    if (firstname.length < 2) {
        errorMessage("נא למלא לפחות 2 תווים בשדה שם פרטי");
        return false;
    }
    if (lastname.length < 2) {
        errorMessage("נא למלא לפחות 2 תווים בשדה שם משפחה");
        return false;
    }
    if (username.length < 5) {
        errorMessage("נא למלא לפחות 5 תווים בשדה שם המשתמש");
        return false;
    }
    if (password.length < 5) {
        errorMessage("נא למלא לפחות 5 תווים בשדה של סיסמה");
        return false;
    }
    if (email.length < 5) {
        errorMessage("נא למלא לפחות 5 תווים בשדה המייל");
        return false;
    }
    else{
    	if(!validateEmail(email)){
    		errorMessage("נא לרשום כתובת מייל חוקית");
        	return false;
    	}
    }

    if (captcha.length < 1) {
        errorMessage("נא הכנס קוד אימות");
        return false;
    }
    return true;
}
function errorMessage(msg){
  
}

function validateEmail(email) {
    var regex = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return regex.test(email);
}