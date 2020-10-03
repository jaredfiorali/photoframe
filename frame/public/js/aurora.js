var weatherJSON = "",
	newsJSON = "",
	remindersJSON = "",
	getW = 0,
	getN = 0,
	getR = 0,
	getT = 0,
	weatherDayActive = 0,
	selectorActive = 0,
	photoClass = 0,
	version = 0,
	hourlyOffset = 4,
	lastUpdate = new Date(),
	currentTime = new Date(),
	arrMoonPhase = [
		'wi-moon-new',
		'wi-moon-waxing-crescent-1',
		'wi-moon-waxing-crescent-2',
		'wi-moon-waxing-crescent-3',
		'wi-moon-waxing-crescent-4',
		'wi-moon-waxing-crescent-5',
		'wi-moon-waxing-crescent-6',
		'wi-moon-first-quarter',
		'wi-moon-waxing-gibbous-1',
		'wi-moon-waxing-gibbous-2',
		'wi-moon-waxing-gibbous-3',
		'wi-moon-waxing-gibbous-4',
		'wi-moon-waxing-gibbous-5',
		'wi-moon-waxing-gibbous-6',
		'wi-moon-full',
		'wi-moon-waning-gibbous-1',
		'wi-moon-waning-gibbous-2',
		'wi-moon-waning-gibbous-3',
		'wi-moon-waning-gibbous-4',
		'wi-moon-waning-gibbous-5',
		'wi-moon-waning-gibbous-6',
		'wi-moon-third-quarter',
		'wi-moon-waning-crescent-1',
		'wi-moon-waning-crescent-2',
		'wi-moon-waning-crescent-3',
		'wi-moon-waning-crescent-4',
		'wi-moon-waning-crescent-5',
		'wi-moon-waning-crescent-6',
		'wi-moon-new'
	],
	arrPhotoClass = [
		'#backgroundImage1',
		'#backgroundImage2'
	],
	arrPhotoLocation = [],
	// errorConfig = '<div><a href="javascript:getConfig(false)">Manually refresh configuration</a></div>',
	errorWeather = '<div><a href="javascript:getWeather(false)">Manually refresh weather</a></div>',
	errorPhoto = '<div><a href="javascript:getPhoto(false)">Manually refresh photo</a></div>',
	errorNews = '<div><a href="javascript:getNews(false)">Manually refresh news</a></div>',
	errorReminders = '<div><a href="javascript:getReminders(false)">Manually refresh reminders</a></div>'
	;

/**
 * This process will reset the timers for retrieving API data
 * It runs when the application starts, and when it's viewed again after the client device has "woken" up from sleep
 * @param  {boolean} initial - Whether or not this is the initial load of the site
 */
function resetInterval(initial) {

	// If this it the first time starting up, let's initialize our timer...timer
	if (initial) {

		// Initialize the timer
		startTime(initial);

		// Set our refresh timer
		getT = setInterval(startTime, 1000, false);
	}
	// This means initial is false on purpose.
	// Run logic here where we want to run something when the clock detects a reboot
	else {

		// Reset our active weather forecast date to today
		weatherDayActive = 0;
	}

	// Get our initial information from the BE endpoints
	getWeather(initial);
	getNews(initial);
	getReminders(initial);

	//  Disabling for now, as the docker container doesn't have access to git
	// getConfig(initial);

	// Clear any timers we had for our endpoints
	clearInterval(getW);
	clearInterval(getN);
	clearInterval(getR);

	// Initialize our endpoint timers
	getW = setInterval(getWeather, 60000);
	getN = setInterval(getNews, 500000);
	getR = setInterval(getReminders, 500000);
}

/**
 * This method manages the clock
 * It also triggers a reset if the clock is too out of sync (meaning the client device was offline)
 * @param  {boolean} initial - Whether or not this is the initial load of the site
 */
function startTime(initial) {

	// Get time
	var	currentTime = new Date(),
		h = currentTime.getHours(),
		m = currentTime.getMinutes();

	// Check if the last time the clock updated was more than 4 minutes ago
	if (currentTime.getTime() > lastUpdate.getTime() + 3000) {

		// Life is just a series of reboots
		resetInterval(false);
	}

	// If the time is less than 10, we need leading 0's
	if (m < 10) {

		// Add leading 0 in front of minutes if required
		m = "0" + m
	}

	// If the time is greater than noon, subtract 12 in order to maintain 12 hour time
	if (h > 12) {

		// Convert to 12 hour time
		h = h - 12
	}
	// In one other case, we care if the hour is 0. That's midnight
	else if (h === 0) {

		// Convert to 12 hour time
		h = 12
	}

	// Update the clock only if the time actually changed
	if (currentTime.getTime() > (lastUpdate.getTime() + 60) || initial) {

		document.getElementById('topClock').innerHTML = h + ':' + m;
	}

	// Save the last update time for later
	lastUpdate = currentTime;
}

/**
 * A helper to translate the API icon to an icon in our font library
 * This is strictly for precipitation
 * @param  {string} icon_text - The text that was received from our API to represent the weather icon
 */
function setPrecipitationIcon(icon_text) {

	// Initialize the return variable
	var icon_returned = 'fas fa-tint';

	if (icon_text === 'rain') {
		icon_returned = 'fas fa-tint';
	} else if (icon_text === 'snow') {
		icon_returned = 'fas fa-snowflake';
	} else if (icon_text === 'sleet') {
		icon_returned = 'wi wi-sleet';
	}

	return icon_returned;
}

/**
 * A helper to translate the API icon to an icon in our font library
 * This is strictly for daily weather icons
 * @param {?} icon_text - The string (or int) that was received from our API to represent the weather icon
 * @param {boolean} is_day - If true, will return a "day" icon (if available)
 * @param {string} className - The name of the CSS class that we intend to append to our return icon
 */
function setIcon(icon_text, is_day, className) {

	// Initialize the return variable
	var icon_returned = 'wi-na';

	// Check that the incoming icon is a number. If so, we are looking for a clock
	if (!isNaN(icon_text)) {

		// Subtract 12 so that we are working with 12-hour time
		if (icon_text > 12) {
			icon_text -= 12;
		} else if (icon_text === 0) {
			icon_text = 12;
		}

		// Return the clock icon appropriate to the time
		icon_returned = 'wi-time-' + icon_text;
	} else {

		// There are specific day/night classifications from our weather data. Try those first
		if (icon_text === 'clear-day') {
			icon_returned = 'wi-day-sunny';
		} else if (icon_text === 'clear-night') {
			icon_returned = 'wi-night-clear';
		} else if (icon_text === 'partly-cloudy-day') {
			icon_returned = 'wi-day-cloudy';
		} else if (icon_text === 'partly-cloudy-night') {
			icon_returned = 'wi-night-cloudy';
		} else if (icon_text === 'cloudy') {
			icon_returned = 'wi-cloudy';
		} else {
			// Next, let's send daytime icons back if it's not night
			if (is_day === true) {
				if (icon_text === 'rain') {
					icon_returned = 'wi-day-rain';
				} else if (icon_text === 'snow') {
					icon_returned = 'wi-day-snow';
				} else if (icon_text === 'sleet') {
					icon_returned = 'wi-day-sleet';
				} else if (icon_text === 'wind') {
					icon_returned = 'wi-day-windy';
				} else if (icon_text === 'fog') {
					icon_returned = 'wi-day-fog';
				} else if (icon_text === 'hail') {
					icon_returned = 'wi-day-hail';
				} else if (icon_text === 'thunderstorm') {
					icon_returned = 'wi-day-thunderstorm';
				}
			}
			// It's nighttime. Send the appropriate icon back
			else if (is_day === false) {
				if (icon_text === 'rain') {
					icon_returned = 'wi-night-rain';
				} else if (icon_text === 'snow') {
					icon_returned = 'wi-night-snow';
				} else if (icon_text === 'sleet') {
					icon_returned = 'wi-night-sleet';
				} else if (icon_text === 'wind') {
					icon_returned = 'wi-cloudy-windy';
				} else if (icon_text === 'fog') {
					icon_returned = 'wi-night-fog';
				} else if (icon_text === 'hail') {
					icon_returned = 'wi-night-hail';
				} else if (icon_text === 'thunderstorm') {
					icon_returned = 'wi-night-thunderstorm';
				}
			}
		}
	}

	return "wi " + className + ' ' + icon_returned;
}

/**
 * Constructs an array of single letter representations of each day of the week, shifted by the offset
 * @param  {int} offset - The current day of the week (today)
 */
function getShortDay(offset) {

	// Initialize our array for the date
	var arrShortDOW = ['S', 'M', 'T', 'W', 'T', 'F', 'S'],
		date = new Date(),
		dow = date.getDay(),
		shortDOW;

	// Add the offset plus today's day of the week
	shortDOW = dow + offset;

	// If the sum of the offset and today is more than the # of days in a week, let's loop
	if (shortDOW > 6) {
		shortDOW = shortDOW - 7;
	}

	return arrShortDOW[shortDOW];
}

/**
 * Helper method to take a incoming piece of weather data, and convert it to a human friendly format
 * @param  {?} input - The incoming weather data
 * @param  {string} format - The format that the method will return it in
 */
function formatWeather(input, format) {

	// Start assuming we are going to return the input right back
	var output = input;

	// First confirm that we have a value to work with
	if (input == null) {

		output = "N/A"
	}
	else {

		if (format === 'number') {

			// Round the input, and then convert it to string
			output = (Math.round(input * 100) / 100).toString();
		} else if (format === 'roundNumber') {

			// Round the input, and then convert it to string
			output = Math.round(input).toString();
		} else if (format === 'icon') {

			// TODO: Do I need to do something?
		} else if (format === 'time') {

			// Convert to date object. *1000 in order to use milliseconds
			var date = new Date(input * 1000),
				meridian = " AM",
				hours = date.getHours(),
				minutes = "0" + date.getMinutes(),
				seconds = "0" + date.getSeconds();

			// If the time is greater than noon, subtract 12 in order to maintain 12 hour time
			if (hours > 12) {

				// Convert to 12 hour time
				hours = hours - 12;

				// Update to PM
				meridian = " PM";
			}
			// In one other case, we care if the hour is 0. That's midnight
			else if (hours === 0) {

				// Convert to 12 hour time
				hours = 12;
			} else if (hours === 12) {

				// Update to PM
				meridian = " PM";
			}

			if (minutes === "00" && seconds === "00") {

				output = hours + meridian;
			} else {

				output = hours + ':' + minutes.substr(-2) + meridian;
			}
		} else if (format === 'percent') {

			// Round the input, multiply by 100, then send back a string
			output = Math.round((input) * 100).toString() + "%";
		} else if (format === 'distance') {

			// Just convert the input to string, and append the unit
			output = Math.round(input).toString() + " KM";
		}
		else if (format === 'moonPhase') {

			// Direction swaps hands after each 11.25 degrees
			output = "wi " + arrMoonPhase[(Math.round(input * 28))];
		} else if (format === 'direction') {

			// Direction swaps hands after each 11.25 degrees
			// Create a fancier array which will contain compass directions
			var arrIconDirection = [
				"wi-from-n",
				"wi-from-nne",
				"wi-from-ne",
				"wi-from-ene",
				"wi-from-e",
				"wi-from-ese",
				"wi-from-se",
				"wi-from-sse",
				"wi-from-s",
				"wi-from-ssw",
				"wi-from-sw",
				"wi-from-wsw",
				"wi-from-w",
				"wi-from-wnw",
				"wi-from-nw",
				"wi-from-nnw"
			];

			// Math :D
			output = "wi wi-wind " + arrIconDirection[((Math.round((input / 22.5) + .5)) % 16)];
		} else if (format === 'speed') {

			// Just convert the input to string, and append the unit
			output = Math.round(input).toString() + " KM/h";
		} else if (format === 'precipRate') {

			// Just convert the input to string, and append the unit
			output = (Math.round(input * 100) / 100).toString() + ' mm/h';
		}
	}

	return output;
}

/**
 * Updates our main weather dashboard with the weather information of the day the user has selected
 * Will display the today's weather by default
 * @param  {int} index - The day of the week that the user has selected to see
 */
function setBigWeather(index) {

	var i = 0,
		className = '';

	// Reset the 7-day forecast classes in order to disable our active marker
	for (i = 0; i < 7; i++) {
		document.getElementById('day' + i).className = 'day';
	}

	// Assign the active class to this specfic forecast element
	document.getElementById('day' + index).classList.add("active");

	// Update our big weather high and low. This needs to be done manually, as we duplicate this data in the details section
	document.getElementById('bigTemperatureLow').innerHTML = formatWeather(weatherJSON.daily.data[index].apparentTemperatureLow, 'roundNumber');
	document.getElementById('bigTemperatureHigh').innerHTML = formatWeather(weatherJSON.daily.data[index].apparentTemperatureHigh, 'roundNumber');

	// Set the global variable so we can track which day is active
	weatherDayActive = index;

	// Create our array for processing
	var arrWeatherJson = [
		/** JSON Key, format, div ID, current capable **/
		// ['apparentTemperatureHigh', 'roundNumber'],
		['apparentTemperatureHighTime', 'time', 'bigDetailsValueLeft0', false],
		// ['apparentTemperatureLow', 'roundNumber'],
		// ['apparentTemperatureLowTime', 'time'],
		// ['apparentTemperatureMax', 'number'],
		// ['apparentTemperatureMaxTime', 'time'],
		// ['apparentTemperatureMin', 'number'],
		// ['apparentTemperatureMinTime', 'time'],
		// ['cloudCover', 'percent'],
		// ['dewPoint', 'roundNumber'],
		// ['humidity', 'percent', 'bigDetailsValueLeft3', true],
		['icon', 'icon', 'bigWeatherIcon', false],
		['moonPhase', 'moonPhase', 'bigDetailsIconRight3', false],
		// ['ozone', 'roundNumber'],
		// ['precipAccumulation', 'number'],
		['precipIntensity', 'precipRate', 'bigDetailsValueLeft3', true],
		// ['precipIntensityMax', 'number'],
		['precipIntensityMaxTime', 'time', 'bigDetailsValueRight0', false],
		// ['precipProbability', 'percent'],
		['precipType', 'icon', 'bigDetailsIconRight0', false],
		// ['pressure', 'roundNumber'],
		['summary', 'text', 'bigSummary', false],
		['sunriseTime', 'time', 'bigDetailsValueLeft1', false],
		['sunsetTime', 'time', 'bigDetailsValueRight1', false],
		// ['temperatureHigh', 'roundNumber'],
		// ['temperatureHighTime', 'time'],
		// ['temperatureLow', 'roundNumber'],
		// ['temperatureLowTime', 'time'],
		// ['temperatureMax', 'roundNumber'],
		// ['temperatureMaxTime', 'time'],
		// ['temperatureMin', 'roundNumber'],
		// ['temperatureMinTime', 'time'],
		['uvIndex', 'roundNumber', 'bigDetailsValueLeft2', true],
		// ['uvIndexTime', 'time'],
		// ['visibility', 'distance'],
		['windBearing', 'direction', 'bigDetailsIconRight2', true],
		// ['windGust', 'speed'],
		// ['windGustTime', 'time'],
		['windSpeed', 'speed', 'bigDetailsValueRight2', true],
	];

	// Loop through each weather data point and update their associated span
	for (i = 0; i < arrWeatherJson.length; i++) {

		var weatherData,
			propertyName = arrWeatherJson[i][0],
			format = arrWeatherJson[i][1],
			elementID = arrWeatherJson[i][2],
			current = arrWeatherJson[i][3];

		// If this property has a "currently" value, and the user is looking at today, let's assign the current value
		weatherData = (current && index === 0) ? weatherJSON.currently[propertyName] : weatherJSON.daily.data[index][propertyName];

		// Check to see if we are working with one of the special icons
		if (propertyName === 'icon' || format === 'direction' || format === 'moonPhase' || propertyName === 'precipType') {

			// Grab the class that we intend to replace this element with
			className = propertyName.charAt(0).toUpperCase() + propertyName.slice(1);

			if (propertyName === 'icon') {

				// Update the weather icon class
				document.getElementById(elementID).className = setIcon(formatWeather(weatherData, format), true, 'bigWeatherIcon');
			} else if (format === 'direction' || format === 'moonPhase') {

				// Update the weather direction (or moon phase) class
				document.getElementById(elementID).className = formatWeather(weatherData, format);
			} else if (propertyName === 'precipType') {

				// Update the weather precipitation class
				document.getElementById(elementID).className = setPrecipitationIcon(weatherData);
			}
		}	else {

			// Just update like a normal person
			document.getElementById(elementID).innerHTML = formatWeather(weatherData, format);
		}
	}
}

/**
 * Retrieves the server configuration
 * @param  {boolean} initial - Whether or not this is the initial load of the site
 */
function getConfig(initial) {

	callServer("config/get", processConfig, initial, "config", errorConfig);
}

/**
 * Adjusts the FE depending on the config received from the server
 * Forces a reload of the website if there is a version mismatch
 * @param  {string} result - The response from our API call
 * @param  {boolean} initial - Whether or not this is the initial load of the site
 */
function processConfig(result, initial) {

	// Confirm we got something from our BE query
	if (result) {

		// If this is the first boot up for the app, we need to do something different
		if (initial) {

			// Store the version we received
			version = result['version'];
		}
		// It's not the first time we are loading this app, so process as usual
		else {

			// Check if there is a version mismatch against what we remember the version to be
			if (version !== result['version']) {

				// Version mismatch! Let's reload!
				location.reload();
			}
		}
	}
}

/**
 * Retrieves the weather information from our API
 * @param  {boolean} initial - Whether or not this is the initial load of the site
 */
function getWeather(initial) {

	try {

		// Check to see if we can use SSE
		if (!!window.EventSource && initial) {
			var source = new EventSource('weather/sse');
	
			source.addEventListener('message', function (e) {
				let jsonResult = JSON.parse(e.data);
				processWeather(jsonResult.weather, initial);
				processPhoto(jsonResult.photo, initial);
			}, false);
	
			source.addEventListener('open', function (e) {
				// Connection was opened.
			}, false);
	
			source.addEventListener('error', function (e) {
				if (e.readyState == EventSource.CLOSED) {
					// TODO: jfiorali - Configure this to show an alert on the FE
				}
			}, false);
		}
		// SSE not allowed on this client...
		else {
	
			// TODO: jfiorali - This will be deprecated in the future
			callServer("config/get", processConfig, initial, "config", errorConfig);
		}
	}
	catch (err) {

		alert(err);
	}
}

/**
 * Takes the incoming API result and assigns it to various div's for display
 * @param  {string} result - The response from our API call
 * @param  {boolean} initial - Whether or not this is the initial load of the site
 */
function processWeather(result, initial) {

	if (result) {

		// Update the global weather variable
		weatherJSON = result;
	}

	// Confirm the user is looking at the weather dashboard. No need to update if they aren't viewing it
	if (selectorActive === 0) {

		// Get the current UNIX timestamp in seconds
		var currentUnixTime = (currentTime.getTime()) / 1000,
			sunrise = weatherJSON.daily.data[0].sunriseTime,
			sunset = weatherJSON.daily.data[0].sunsetTime,
			is_day = false;

		// If we are currently in between sunrise and sunset...it's daytime
		if (currentUnixTime > sunrise && currentUnixTime < sunset) {
			is_day = true;
		}

		// Populate the Top forecast
		document.getElementById('topWeatherIcon').className = setIcon(weatherJSON.currently.icon, is_day, 'topWeatherIcon');
		document.getElementById('apparentTemperature').innerHTML = formatWeather(weatherJSON.currently.apparentTemperature, 'roundNumber');
		document.getElementById('bigTime').innerHTML = formatWeather(weatherJSON.currently.time, 'time');

		// Populate the big weather section with the active forecast
		setBigWeather(weatherDayActive);

		// Populate the 7 day forecast
		for (var i = 0; i < 7; i++) {
			document.getElementById('dayIcon' + i).className = setIcon(weatherJSON.daily.data[i].icon, false, 'dailyIcon');
			document.getElementById('dayDate' + i).innerHTML = getShortDay(i);
			document.getElementById('dayLow' + i).innerHTML = formatWeather(weatherJSON.daily.data[i].apparentTemperatureLow, 'roundNumber');
			document.getElementById('dayHigh' + i).innerHTML = formatWeather(weatherJSON.daily.data[i].apparentTemperatureHigh, 'roundNumber');
			document.getElementById('dayPrecepitationIcon' + i).className = setPrecipitationIcon(weatherJSON.daily.data[i].precipType);
			document.getElementById('dayPrecipitation' + i).innerHTML = formatWeather(weatherJSON.daily.data[i].precipProbability, 'percent');
		}

		// Populate the hourly forecast
		for (var j = hourlyOffset; j <= (hourlyOffset * 3); j += hourlyOffset) {

			// Get our class name calculated from the for loop index
			var classname = (j / hourlyOffset) - 1;

			// Get the date object (and therefore the hour) from the hourly timestamp
			var date = new Date(weatherJSON.hourly.data[j].time * 1000),
				hour = date.getHours();

			// Get the appropriate clock icon for the time we are working with
			document.getElementById('hourlyClock' + classname).className = setIcon(hour, true, 'hourlyClock');

			document.getElementById('hourlySummary' + classname).innerHTML = '<p>' + weatherJSON.hourly.data[j].summary + '</p>';

			document.getElementById('hourlyIcon' + classname).className = setIcon(weatherJSON.hourly.data[j].icon, is_day, 'hourlyIcon');
			document.getElementById('hourlyTemp' + classname).innerHTML = formatWeather(weatherJSON.hourly.data[j].apparentTemperature, 'roundNumber');
		}
	}
}

/**
 * Calls our API to retrieve the next photo
 * On initial, it will download a second image in order to cache the next photo
 * @param  {boolean} initial - Whether or not this is the initial load of the site
 */
function getPhoto(initial) {

	// Since we are starting for the first time, get a photo
	if (initial) {

		// Grab and set our initial photo
		callServer("photo/get", processPhoto, initial, "photo", errorPhoto);
	} else {

		// Remember which photo we are on
		photoClass = 1 - photoClass;

		// Change photos!
		changePhoto(arrPhotoClass[(1 - photoClass)], arrPhotoClass[photoClass]);
	}
}

/**
 * This method will swap the secondaryPhoto with the primaryPhoto
 * @param  {string} primaryClass - The class name of the background photo that we intend to display
 * @param  {string} secondaryClass - The class name of the background photo that we intend to hide
 */
function changePhoto(primaryClass, secondaryClass) {

	// Assign primaryClass to the front, and secondaryClass to the back
	$(primaryClass).css('z-index', 1);
	$(secondaryClass).css('z-index', 2);

	// Fade primaryClass front, and fade secondaryClass out
	$(primaryClass).fadeIn(0);
	$(secondaryClass).fadeOut(2500);

	// Update the location of the photo
	document.getElementById('topLocation').innerHTML = arrPhotoLocation[(1 - photoClass)];
}

/**
 * Takes the incoming API result and assigns it to various div's for display
 * @param  {string} result - The response from an API call
 * @param  {boolean} initial - Whether or not this is the initial load of the site
 */
function processPhoto(result, initial) {

	// Nothing to run if there's no result!
	if (result) {

		// Just load the photos if this is the first launch
		if (initial) {

			// Assign the same photo and location to both background divs
			for (var i = 0; i < 2; i++) {

				$(arrPhotoClass[i]).css('background-image', 'url(data:image/jpeg;base64,' + result.photo + ')');
				arrPhotoLocation[i] = result.location;
			}

			// Update the location on the frame
			document.getElementById('topLocation').innerHTML = result.location;
		}
		// This is normal operation. Let's swap photos
		else {

			// Grab the latest photo
			$(arrPhotoClass[photoClass]).css('background-image', 'url(data:image/jpeg;base64,' + result.photo + ')');

			// Update the photo location
			arrPhotoLocation[photoClass] = result.location;
		}
	}
}

/**
 * Calls our API to retrieve news information
 * @param  {boolean} initial - Whether or not this is the initial load of the site
 */
function getNews(initial) {

	callServer("news/get", processNews, initial, "news", errorNews);
}

/**
 * Takes the incoming API result and assigns it to various div's for display
 * @param  {string} result - The response from an API call
 * @param  {boolean} initial - Whether or not this is the initial load of the site
 */
function processNews(result, initial) {

	if (result) {

		// Update the global news variable
		newsJSON = result;
	}

	// Confirm we received some news before working with it, and that this isn't the initial load, and that we are looking at the news panel
	if (newsJSON && !initial && selectorActive === 1) {

		// Populate the news
		for (var i = 0; i < 8; i++) {

			// Assume we are populating the right side
			var side = 'Right',
				j = i;

			// If we are working on the 5th or higher article...it's the left side
			if (i > 3) {
				side = 'Left';
				j = i-4;
			}

			// Populate the news title and description
			$('#news' + side + 'Thumb' + j).css('background-image', 'url(data:image/jpeg;base64,' + newsJSON.articles[i].urlToImage + ')');

			// Populate the news title and description
			document.getElementById('news' + side + 'Title' + j).innerHTML = newsJSON.articles[i].title + "\n";
		}
	}
}

/**
 * Calls our API to retrieve a list of reminders
 * @param  {boolean} initial - Whether or not this is the initial load of the site
 * TODO: jfiorali - This isn't just for reminders anymore...probably needs a rename. "Events" perhaps?
 */
function getReminders(initial) {

	callServer("waste/get", processReminders, initial, "reminders", errorReminders);
}

/**
 * Takes the incoming API result and assigns it to various div's for display
 * @param  {string} result - The response from an API call
 * @param  {boolean} initial - Whether or not this is the initial load of the site
 */
function processReminders(result, initial) {

	// Ensure that we received a result
	if (result) {

		// Update the global reminders object
		remindersJSON = result;
	}

	// Confirm that we are on the reminders page. No need to do any work if no one is looking...
	if (selectorActive === 2) {

		// Confirm that we have a reminders object
		if (remindersJSON && !initial) {

			var wasteDate = remindersJSON.date,
				wasteType = remindersJSON.type,
				wasteClass;

			// Determine the icon that we want to display
			wasteClass = (wasteType === 'Garbage') ? " fas fa-trash-alt" : " fas fa-recycle";

			// Set the type of waste being picked up
			document.getElementById('remindersWasteType').className += wasteClass;

			// Set the day of the week
			document.getElementById('remindersWasteDate').innerText = getDayOfWeekFriendly(wasteDate);
		}
	}
}

/**
 * TODO: ...something goes here
 */
function processOptions() {
	// TBD
}

/**
 * Will change the dashboard based off of the user's selection of the top bar
 * @param  {int} index - The panel number that the user selected
 */
function changePanel(index) {

	selectorActive = index;

	// Reset the selector bar active class in order to disable our active marker
	for (var i = 0; i < 4; i++) {
		document.getElementById('selectorContents' + i).className = 'selectorContents';
	}

	// Assign the active class to this specific selection
	document.getElementById('selectorContents' + index).classList.add("active");

	// Set a reference to the panel div
	var elem = document.getElementById('backgroundBottom');

	// Loop through all element children div's
	while (elem.firstChild) {

		// Remove each element that is found
		elem.removeChild(elem.firstChild);
	}

	// Selection: News
	if (index === 1) {

		generateNewsDashboard();
		processNews('', false);
	}
	// Selection: Reminders
	else if (index === 2) {

		generateRemindersDashboard();
		processReminders();
	}
	// Selection: Options
	else if (index === 3) {

		generateOptionsDashboard();
		processOptions();
	}
	// Selection: Weather
	else {

		generateWeatherDashboard();
		processWeather('', false);
	}
}

/**
 * The method that runs on initialization
 */
$(document).ready(function() {

	// Set background image z-index
	$('#backgroundTopSummary').css('z-index', 30);
	$('#backgroundTopRefresh').css('z-index', 30);

	// Hide the Pocket Alert
	$("#alert_success_placeholder").hide();
	$("#alert_failed_placeholder").hide();

	// Create the elements for the weather dashboard
	generateInitialFrame();

	// Initialize all of the timer function
	resetInterval(true);
});
