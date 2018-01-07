describe("Testing askDelete() to delete a user. ", function() {

it("should always return false if there is no ajax error", function() {
	expect(askDelete()).toBeFalsy();
});

});