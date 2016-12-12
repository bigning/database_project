var ingredient= 1;
var step = 1;
var file = "";
var list = "";


function handleFileSelect(evt) {
    var files = evt.target.files; // FileList object

    // Loop through the FileList and render image files as thumbnails.
    for (var i = 0, f; f = files[i]; i++) {

      // Only process image files.
      if (!f.type.match('image.*')) {
        continue;
      }

      var reader = new FileReader();

      // Closure to capture the file information.
      reader.onload = (function(theFile) {
        return function(e) {
          // Render thumbnail.
          var span = document.createElement('span');
          span.innerHTML = ['<img class="thumb" src="', e.target.result, '" title="', escape(theFile.name), '"/>'].join('');
          document.getElementById(list).insertBefore(span, null);
        };
      })(f);

      // Read in the image file as a data URL.
      reader.readAsDataURL(f);
    }
}









$("#add_ingredient_button").on("click", function(){
	// add a new div
	$("<div></div>").attr("id", "ingredient_tuple_" + ingredient.toString()).appendTo(".ingredient-div");

	// add inputs into this new div
	$("<input type='text' placeholder='Ingredient Name' required class = 'ingredient-inputs'>").attr("name", "ingredient_name_" + ingredient.toString()).appendTo("#ingredient_tuple_" + ingredient.toString());

	$(" <input type='number' placeholder='Quantity' required class = 'ingredient-inputs'>").attr("name", "ingredient_quantity_" + ingredient.toString()).appendTo("#ingredient_tuple_" + ingredient.toString());

	$(" <select  required class = 'ingredient-inputs'> <option value = 'lb'>lb</option> <option value = 'oz'>oz</option> <option value = 'l'>l</option> <option value = 'ml'>ml</option> <option value = 'tbsp'>tbsp</option> <option value = 'tsp'>tsp</option> </select>").attr("name", "ingredient_unit_" + ingredient.toString()).appendTo("#ingredient_tuple_" + ingredient.toString());

	// update variable
	ingredient++;
});


$("#add_step_button").on("click", function(){
	// add a new div
	$("<div></div>").attr("id", "step_tuple_" + step.toString()).appendTo(".step-div");

	// add inputs into this new div
	$("<textarea class = 'form-control' rows='2' required placeholder = 'Describe how you cook it! You could upload multiple images for better explanation'></textarea>").attr("name", "step_" + step.toString()).appendTo("#step_tuple_" + step.toString());

	$(" <input type='file'>").attr("id", "file_" + step.toString()).attr("name", "step_file_" + step.toString()).attr("multiple", "").appendTo("#step_tuple_" + step.toString());

	$(" <output></output>").attr("id", "list_" + step.toString()).appendTo("#step_tuple_" + step.toString());

	// update variable
	file = "#file_" + step.toString();
	list = "list_" + step.toString();
	step++;
});



$(".step-div").on("change", file, handleFileSelect);
