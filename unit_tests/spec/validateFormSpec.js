describe("Testing registration form", function() {



it("Expects to return false if first name length is too low.", function() {
	$('#firstname').val("");
	expect(validateForm()).toBeFalsy();
});

it("Expects to return false if last name length is too low.", function() {
	$('#lastname').val("");
	expect(validateForm()).toBeFalsy();
});

it("Expects to return false if username length is too low.", function() {
	$('#username').val("");
	expect(validateForm()).toBeFalsy();
});

it("Expects to return false if password length is too low.", function() {
	$('#password').val("");
	expect(validateForm()).toBeFalsy();
});

it("Expects to return false if captcha is empty.", function() {
	$('#captcha').val("");
	expect(validateForm()).toBeFalsy();
});

it("Expects to return false if email length is too low.", function() {
	$('#email').val("");
	expect(validateForm()).toBeFalsy();
});

it("Expects to return false if email is invalid.", function() {
	$('#email').val("good@email.com");
	expect(validateForm()).toBeFalsy();
});

it("Expects to return true if whole form is valid.", function() {
	$('#email').val("good@email.com");
	$('#firstname').val("firstname");
	$('#lastname').val("lastname");
	$('#username').val("username");
	$('#password').val("password");
	$('#captcha').val("sdsdf");
	expect(validateForm()).toBeTruthy();
    //expect(document.getElementsByName("firstname")[0].value).toEqual("test1");
	//spyOn(window, 'validateForm').and.returnValue(true);
    //expect($('#captcha').val()).not.toBe("");
});

});