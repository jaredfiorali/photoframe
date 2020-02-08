function modifyPhoto(e, type) {

	// Don't go anywhere!
	e.preventDefault();

	var data = {};
	var form = e.target.form;

	data['id'] = form[0].value;
	data['location'] = form[1].value;
	data['date_taken'] = form[2].value;

	var payload = JSON.stringify(data);

	callServer("admin/photomanagement/update", payload);
}

function findPhoto(e, type) {

	var value = e.target.value,
		callback;

	if (type) {

		if (type == 'modify') {

			callback = processPhotoModify;
		} else if (type == 'delete') {

			callback = processPhotoDelete;
		}
	}

	callServer("admin/get/photo/"+value, callback);
}

function processPhotoAddPreview(result) {

	var element = document.getElementById("image_preview_add");

	element.style.backgroundImage = 'url(data:image/jpeg;base64,' + result.photo + ')';

	document.getElementById("submit_upload").disabled = false;
}

function processPhotoModify(result) {

	var element = document.getElementById("image_preview_modify");

	element.style.backgroundImage = 'url(data:image/jpeg;base64,' + result.photo + ')';

	document.getElementById("location_modify").value = result.location;
	document.getElementById("date_taken_modify").value = result.date_taken;

	document.getElementById("submit_modify").disabled = false;
}

function processPhotoDelete(result) {

	var element = document.getElementById("image_preview_delete");

	element.style.backgroundImage = 'url(data:image/jpeg;base64,' + result.photo + ')';

	document.getElementById("submit_delete").disabled = false;
}

function uploadPreview(e) {

	// The Javascript
	var fileInput = document.getElementById('image_add'),
		file = fileInput.files[0],
		root = window.location.hostname,
		formData = new FormData();

	formData.append('file', file);

	$.ajax({
	url: 'http://' + root + '/admin/photomanagement/upload_preview',
	type: "POST",
		data: formData,
		contentType: false,
		processData:false,
		accept: 'application/json',
		cache: false,
		dataType: "json",
	success: function (result) {
		processPhotoAddPreview(result)
	},
	});
}

function uploadFinal(e) {

	// The Javascript
	var fileInput = document.getElementById('image_add'),
		file = fileInput.files[0],
		root = window.location.hostname,
		formData = {
	        file: fileInput.files[0],
            location: document.getElementById("location_upload").value,
        },
        base64 = getBase64(file);

	debugger;

	$.ajax({
		url: 'http://' + root + '/admin/photomanagement/upload_final',
		type: "POST",
		data: JSON.stringify(formData),
		contentType: false,
		processData:false,
		accept: 'application/json',
		cache: false,
		dataType: "json",
		success: function (result) {

			debugger;
		},
	});
}

function getBaseUrl()  {
    var file = document.querySelector('input[type=file]')['files'][0];
    var reader = new FileReader();
    var baseString;

    reader.onloadend = function () {
        baseString = reader.result;
        console.log(baseString);
    };

    reader.readAsDataURL(file);
}

function getBase64(file) {
    var reader = new FileReader();
    reader.readAsDataURL(file);
    reader.onload = function () {
        console.log(reader.result);
    };
    reader.onerror = function (error) {
        console.log('Error: ', error);
    };
}

/**
 * The method that runs on initialization
 */
$(document).ready(function() {

	document.getElementById("submit_upload").disabled = true;
	document.getElementById("submit_modify").disabled = true;
	document.getElementById("submit_delete").disabled = true;
});
