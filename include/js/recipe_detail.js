$.getScript("./include/js/rating.js");

$("#count").on("DOMSubtreeModified", function(){
	$("#star_value").attr("value", $(this).text());
})

