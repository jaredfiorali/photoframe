var errorsLogged = [];
var weekday = [
	"Sunday",
	"Monday",
	"Tuesday",
	"Wednesday",
	"Thursday",
	"Friday",
	"Saturday"
];

function callServer(endpoint, callback, initial, errorType, errorStatement) {

	// Retrieve
	$.ajax({
		url: endpoint,
		type: "POST",
		accept: 'application/json',
		cache: false,
		dataType: "json",
		contentType: 'application/x-www-form-urlencoded',
		success: function(result) {

			// Confirm we received an error type
			if (errorType) {

				// Check to see if this error is currently pending resolution
				if (errorsLogged[errorType] === true) {

					// Remove the error that we might have had before
					elem = document.getElementById(errorType+"Error").remove();
				}

				// Mark this error as resolved
				errorsLogged[errorType] = false;

				// Check to see if we can completely remove the error alert button
				checkHideErrorAlert();
			}

			// Run the function we want to use to process this data
			callback(result, initial);
		},
		error: function(err) {
			displayErrorAlert("Error retrieving " + endpoint + " - Details in console", errorType, errorStatement);
		}
	});
}

function checkHideErrorAlert() {

	// Assume everything is awesome
	var isHide = true;

	// Loop through our array of errors
	for (var i in errorsLogged) {

		// Check to see if this error is still pending
		if (errorsLogged[i] === true) {

			// Darn, let's mark as false
			isHide = false;
		}
	}

	// We survived!!! Set the alert to hidden so the user is not bothered by this error
	if (isHide) {
		document.getElementById('alert').style.visibility='hidden';
	}
}

function displayErrorAlert(error, errorType, errorStatement) {

	// Start with an empty error
	var errorID = '';

	// Log it in the console, in case we are debugging
	console.log(error);

	// Set the Alert icon to visible so we can alert users that there was an error
	document.getElementById('alert').style.visibility='visible';

	// Check to see if this error is currently pending action
	if (errorsLogged[errorType] === 'undefined' || errorsLogged[errorType] === false) {

		// Create a new div ID for this error
		errorID = errorType+"Error";

		// Generate the new troubleshooting error instructions div
		generateElement("div", errorID, "errorTroubleshooting", "errors");
		document.getElementById(errorID).innerHTML=errorStatement;

		// Make this error as pending resolution
		errorsLogged[errorType] = true;
	}
}

/**
 * Generates a random integer, with 0 as the minimum returned
 * @param max The maximum number that the random integer can be
 * @returns {number}
 */
function getRandomInt(max) {

	return Math.floor(Math.random() * Math.floor(max));
}

/**
 * Returns the day of the week that a date object lands on
 * @param {string} date A date in string format that we want to find the day of week from
 */
function getDayOfWeekFriendly(date) {

	// Construct a date object from our incoming date
	var d = new Date(date);

	return weekday[d.getDay()+1];
}
