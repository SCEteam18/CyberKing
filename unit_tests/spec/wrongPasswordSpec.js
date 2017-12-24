describe("Testing login function", function() {

it("Expects wrongPassword() to print the right message on the screen.", function() {
	expect(wrongPassword()).toEqual(undefined);
	expect($('#error_msg').text()).toEqual("שם המשתמש או הסיסמא אינם נכונים");
});

});