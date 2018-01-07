describe("Testing validateInput() for question addition", function() {



it("Expects to return false if first name length is too low.", function() {
	$('#title').val("");
	expect(validateInput()).toBeFalsy();
});

it("Expects to return false if last name length is too low.", function() {
	$('#answer1').val("");
	expect(validateInput()).toBeFalsy();
});

it("Expects to return false if username length is too low.", function() {
	$('#answer2').val("");
	expect(validateInput()).toBeFalsy();
});

it("Expects to return false if answer3 length is too low.", function() {
	$('#answer3').val("");
	expect(validateInput()).toBeFalsy();
});

it("Expects to return false if answer4 is empty.", function() {
	$('#answer4').val("");
	expect(validateInput()).toBeFalsy();
});

it("Expects to return true if whole form is valid.", function() {
	$('#title').val("dsfsdfdddgdgdggg");
	$('#answer1').val("dsfsfgdf");
	$('#answer2').val("dsfsfgfgdf");
	$('#answer3').val("dsfsddf");
	$('#answer4').val("dsfbsdf");
	
	expect(validateInput()).toBeTruthy();
});
});