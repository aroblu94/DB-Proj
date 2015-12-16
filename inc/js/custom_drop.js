$(document).ready(function() {
	$("#cstmdrop1")
		.mouseover(function() {
			$("#cstmdrop1").addClass("is-open open");
		})
		.mouseout(function() {
			$("#cstmdrop1").removeClass("open");
		});
});