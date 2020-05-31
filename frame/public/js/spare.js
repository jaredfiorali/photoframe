// function getLights() {
//
// 	// Get state of Lights
// 	$.ajax({
// 		url: "http://aurora.fiora.li/inbound.php",
// 		type: "POST",
// 		data: 'command=lightsState',
// 		accept: 'application/json',
// 		contentType: 'application/x-www-form-urlencoded',
// 		success: function (result) {
// 			lightOn = result;
//
// 			if (result == 1)
// 				switchImage = onImage;
// 			else
// 				switchImage = offImage;
//
// 			$('#lightSwitch').attr("src", switchImage);
// 		},
// 		error: function (err) {
// 			displayErrorAlert("Error retreiving state of lights - Details in console", err);
// 		}
// 	});
// }
//
// function addToPocket(pocketAddress) {
//
// 	// Add URL to Pocket
// 	$.ajax({
// 		url: "http://aurora.fiora.li/inbound.php",
// 		type: "POST",
// 		data: 'command=sendToPocket&pocketAddress=' + pocketAddress,
// 		contentType: 'application/x-www-form-urlencoded',
// 		success: function (result) {
//
// 			// Inform the user that Pocket has saved the article
// 			$("#alert_success_placeholder").fadeTo(2000, 500).slideUp(500, function () {
// 				$("#alert_success_placeholder").slideUp(500);
// 			});
// 		},
// 		error: function (err) {
// 			displayErrorAlert("Error adding to Pocket - Details in console", err);
// 		}
// 	});
// }
//
// function displayErrorAlert(message, err) {
// 	// document.getElementById('bootstrapError').innerHTML = message;
// 	// $("#alert_failed_placeholder").fadeTo(2000, 500).slideUp(500, function () {
// 	// 	$("#alert_failed_placeholder").slideUp(5000);
// 	// });
// 	//
// 	// console.log("AJAX error in request: " + JSON.stringify(err, null, 2));
// }
