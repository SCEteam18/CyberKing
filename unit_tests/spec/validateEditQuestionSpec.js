describe("Testing editRow() function to send a request to edit questions. ", function() {

it("should always return false if there is no error", function() {
	expect(editRow(1)).toBeFalsy();
});

});