$(function() {
    //sizes
    $.each([1, 2, 3, 5], function() {
        $('#container .tools').append("<a href='#sketch_area' data-size='" + this + "' style='background: #fff'>" + this + "</a> ");
    });
    
    $('#sketch_area').sketch();
});

//saves the sketch canvas as a png
function saveSketch(){
	var imageStr = $('#sketch_area')[0].toDataURL();

    //creates a download link and "clicks" the link prompting a download in the browser
    var a = $("<a>").attr("href", imageStr).attr("download", "sketch.png").appendTo("body");
    a[0].click();
   	a.remove();
}

function erase(){
	location.reload();
}

//jQuery on document load
$(document).ready(function () {
    $('#saveButton').on('click', saveSketch);
    $('#eraseButton').on('click', erase);
});