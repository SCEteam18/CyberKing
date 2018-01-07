describe("Testing high score tables. ", function() {

it("Expects to show highscores of selected table.", function() {
	$('#category_list').val("1");
	expect(changeHighcores()).toBeTruthy();
	expect(document.getElementById("tbl_all_categories").style.display.toEqual("none");
    expect(document.getElementById("tbl1").style.display.toEqual("");
});

it("Expects to show highscores of all categories.", function() {
	$('#category_list').val("all");
	expect(changeHighcores()).toBeTruthy();
    expect(document.getElementById("tbl_all_categories").style.display.toEqual("");
    expect(document.getElementById("tbl1").style.display.toEqual("none");

});

});