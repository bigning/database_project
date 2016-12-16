$.getScript("./include/js/rating.js");


function transformUnit(pre_unit, curr_unit, pre_value){
	var temp = 0;
	var rst = 0;

	// convert to ml/g first
	switch(pre_unit){
		case "l":
			temp = pre_value * 1000.00;
			break;
		case "tbsp":
			temp = pre_value * 15.15;
			break;
		case "tsp":
			temp = pre_value * 5.00;
			break;
		case "oz":
			temp = pre_value * 29.41;
			break;
		case "lb":
			temp = pre_value * 454.54;
			break;
		default:
			temp = pre_value;
	}

	console.log("the intermediate ml is " + temp);

	switch(curr_unit){
		case "l":
			rst = temp * 0.001;
			break;
		case "tbsp":
			rst = temp * 0.066;
			break;
		case "tsp":
			rst = temp * 0.200;
			break;
		case "oz":
			rst = temp * 0.034;
			break;
		case "lb":
			rst = temp * 0.0022;
			break;
		default:
			rst = temp;
	}

	return rst;
}





$("#count").on("DOMSubtreeModified", function(){
	$("#star_value").attr("value", $(this).text());
});

$("select").data("previous-value", $("select").val());

$("select").on("change", function(){
	var unit = $(this).val();
	var value = $(this).closest("div").find(".quantity");
	var quantity = parseInt(value.html());
	var pre_unit = $(this).data("previous-value");

	curr_value = transformUnit(pre_unit, unit, quantity);
	value.html(curr_value.toFixed(1));
	
	//updata pre unit 
	$(this).data("previous-value", $(this).val());
});