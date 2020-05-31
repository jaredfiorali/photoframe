/* WEATHER */
var arrselectorBar = [
		/* [type, id, class, parent, id append, class append, parent append] */
		['div', 'selector', 'selector', 'selectorBar', true, false, false],
		['div', 'selectorContents', 'selectorContents', 'selector', true, false, true],
		['span', 'selectorIcon', 'selectorIcon', 'selectorContents', true, false, true],
		['span', 'selectorLabel', 'selectorLabel', 'selectorContents', true, false, true],
	],
	arrWeatherDashboard = [
		/* [type, id, class, parent, id append, class append, parent append] */
		['div', 'currentPane', 'currentPane', 'backgroundBottom', false, false, false],
		['div', 'hourlyPane', 'hourlyPane', 'backgroundBottom', false, false, false],
		['div', 'footer', 'footer', 'backgroundBottom', false, false, false],
	],
	arrCurrentPane = [
		/* [type, id, class, parent, id append, class append, parent append] */
		['div', 'leftWeatherSummary', 'leftWeatherSummary', 'currentPane', false, false, false],
		['div', 'rightWeatherSummary', 'rightWeatherSummary', 'currentPane', false, false, false],
		['div', 'rightWeatherDetails', 'rightWeatherDetails', 'rightWeatherSummary', false, false, false],
		['div', 'lastUpdateWeather', 'lastUpdateWeather', 'rightWeatherSummary', false, false, false],
		['span', 'bigTimeCaption', 'bigTimeCaption', 'lastUpdateWeather', false, false, false],
		['span', 'bigTime', 'bigTime', 'lastUpdateWeather', false, false, false],
	],
	arrLeftWeatherSummary = [
		/* [type, id, class, parent, id append, class append, parent append] */
		['span', 'bigWeatherIcon', 'bigWeatherIcon', 'leftWeatherSummary', false, false, false],
		['p', 'bigSummary', 'bigWeatherSummary', 'leftWeatherSummary', false, false, false],
		['div', 'bigTemperature', 'bigTemperature', 'leftWeatherSummary', false, false, false],
		['i', '', 'fas fa-thermometer-empty', 'bigTemperature', false, false, false],
		['span', 'bigTemperatureLow', 'bigTemperatureLow', 'bigTemperature', false, false, false],
		['span', 'spacer', 'spacer', 'bigTemperature', false, false, false],
		['span', 'bigTemperatureHigh', 'bigTemperatureHigh', 'bigTemperature', false, false, false],
	],
	arrRightWeatherSummary = [
		/* [type, id, class, parent, id append, class append, parent append] */
		['div', 'bigDetailsIcons', 'bigDetailsIcons', 'rightWeatherDetails', true, false, false],
		['div', 'bigDetailsTableIconLeft', 'bigDetailsTableIcon', 'bigDetailsIcons', true, false, true],
		['span', 'bigDetailsIconLeft', 'bigDetailsIconLeft', 'bigDetailsTableIconLeft', true, false, true],
		['div', 'bigDetailsTableIconRight', 'bigDetailsTableIcon', 'bigDetailsIcons', true, false, true],
		['span', 'bigDetailsIconRight', 'bigDetailsIconRight', 'bigDetailsTableIconRight', true, false, true],

		['div', 'bigDetailsValues', 'bigDetailsValues', 'rightWeatherDetails', true, false, false],
		['div', 'bigDetailsTableValueLeft', 'bigDetailsTableValue', 'bigDetailsValues', true, false, true],
		['span', 'bigDetailsValueLeft', 'bigDetailsValueLeft', 'bigDetailsTableValueLeft', true, false, true],
		['div', 'bigDetailsTableValueRight', 'bigDetailsTableValue', 'bigDetailsValues', true, false, true],
		['span', 'bigDetailsValueRight', 'bigDetailsValueRight', 'bigDetailsTableValueRight', true, false, true],
	],
	arrHourly = [
		/* [type, id, class, parent, id append, class append, parent append] */
		['div', 'hourly', 'hourly', 'hourlyPane', true, false, false],
		['span', 'hourlyClock', 'hourlyClock', 'hourly', true, false, true],
		['div', 'hourlyWeather', 'hourlyWeather', 'hourly', true, false, true],
		['div', 'hourlyTempIcon', 'hourlyTempIcon', 'hourlyWeather', true, false, true],
		['span', 'hourlyTemp', 'hourlyTemp', 'hourlyTempIcon', true, false, true],
		['span', 'hourlyIcon', 'hourlyIcon', 'hourlyTempIcon', true, false, true],
		['div', 'hourlySummary', 'hourlySummary', 'hourlyWeather', true, false, true],
	],
	arrFooter = [
		/* [type, id, class, parent, id append, class append, parent append] */
		['div', 'day', 'day', 'footer', true, false, false],
		['div', 'dayIconDate', 'dayIconDate', 'day', true, false, true],
		['span', 'dayIcon', 'dailyIcon', 'dayIconDate', true, false, true],
		['span', 'dayDate', 'dailyDate', 'dayIconDate', true, false, true],
		['div', 'dailyWeather', 'dailyWeather', 'day', true, false, true],
		['i', '', 'fas fa-thermometer-empty', 'dailyWeather', false, false, true],
		['span', 'dayLow', 'dailyWeatherLow', 'dailyWeather', true, false, true],
		['span', 'spacer', 'spacer', 'dailyWeather', false, false, true],
		['span', 'dayHigh', 'dailyWeatherHigh', 'dailyWeather', true, false, true],
		['div', 'dailyPrecepitation', 'dailyPrecepitation', 'day', true, false, true],
		['div', 'dayPrecepitationIcon', 'dayPrecepitationIcon', 'dailyPrecepitation', true, false, true],
		['span', 'dayPrecipitation', 'dayPrecipitation', 'dailyPrecepitation', true, false, true]
	],
	arrRightWeatherSummaryIcons = [
		['bigDetailsIconLeft0', 'fas fa-thermometer-empty'],
		['bigDetailsIconLeft1', 'wi wi-sunrise'],
		['bigDetailsIconRight1', 'wi wi-sunset'],
		['bigDetailsIconLeft2', 'fas fa-sun'],
		['bigDetailsIconLeft3', 'wi wi-raindrops'],
	];

	/* NEWS */
var arrNewsContainer = [
		/* [type, id, class, parent, id append, class append, parent append] */
		['div', 'newsRightContainer', 'newsRightLeft', 'backgroundBottom', false, false, false],
		['div', 'newsLeftContainer', 'newsRightLeft', 'backgroundBottom', false, false, false],
	],
	arrNewsItem = [
		/* [type, id, class, parent, id append, class append, parent append] */
		['div', 'newsRight', 'newsItem', 'newsRightContainer', true, false, false],
		['div', 'newsRightThumbContainer', 'newsThumbContainer', 'newsRight', true, false, true],
		['div', 'newsRightThumb', 'newsItemThumb', 'newsRightThumbContainer', true, false, true],
		['div', 'newsRightText', 'newsText', 'newsRight', true, false, true],
		['span', 'newsRightTitle', 'newsItemTitle', 'newsRightText', true, false, true],
		['span', 'newsRightDescription', 'newsItemDescription', 'newsRightText', true, false, true],
		['div', 'newsLeft', 'newsItem', 'newsLeftContainer', true, false, false],
		['div', 'newsLeftThumbContainer', 'newsThumbContainer', 'newsLeft', true, false, true],
		['div', 'newsLeftThumb', 'newsItemThumb', 'newsLeftThumbContainer', true, false, true],
		['div', 'newsLeftText', 'newsText', 'newsLeft', true, false, true],
		['span', 'newsLeftTitle', 'newsItemTitle', 'newsLeftText', true, false, true],
		['span', 'newsLeftDescription', 'newsItemDescription', 'newsLeftText', true, false, true],
	];

	/* REMINDERS */
var arrReminderContainer = [
	/* [type, id, class, parent, id append, class append, parent append] */
	['div', 'remindersRightContainer', 'remindersRight', 'backgroundBottom', false, false, false],
	['div', 'remindersLeftContainer', 'remindersLeft', 'backgroundBottom', false, false, false],
	['div', 'remindersWasteContainer', 'remindersWaste', 'remindersRightContainer', false, false, false],
	['span', 'remindersWasteType', 'remindersWasteType', 'remindersWasteContainer', false, false, false],
	['span', 'remindersWasteDate', 'remindersWasteDate', 'remindersWasteContainer', false, false, false],
	['div', 'remindersBirthdaysContainer', 'remindersBirthdays', 'remindersRightContainer', false, false, false],
];

	/* Options */
var arrOptionsContainer = [
	/* [type, id, class, parent, id append, class append, parent append] */
	['div', 'remindersRightContainer', 'remindersRight', 'backgroundBottom', false, false, false],
	['div', 'remindersLeftContainer', 'remindersLeft', 'backgroundBottom', false, false, false],
];

	/* GENERAL */
var arrSelectorIcons = [
	['selectorIcon0', 'fas fa-sun'],
	['selectorIcon1', 'fas fa-newspaper'],
	['selectorIcon2', 'fas fa-list-alt'],
	['selectorIcon3', 'fas fa-cog'],
];

function generateElement(elType, elID, elClass, elParent) {

	// Create our element
	var el = document.createElement(elType);

	// Assign the ID and class to the element
	el.id = elID;
	el.className = elClass;

	// Add the element to the parent
	document.getElementById(elParent).appendChild(el);

	return el;
}

function generateInitialFrame() {

	generateSection(arrselectorBar, 4, changePanel);
	addIcons(arrSelectorIcons);

	// Add label to our Updated timestamp
	document.getElementById('selectorLabel0').innerHTML = 'Weather';
	document.getElementById('selectorLabel1').innerHTML = 'News';
	document.getElementById('selectorLabel2').innerHTML = 'Reminders';
	document.getElementById('selectorLabel3').innerHTML = 'Options';

	// Assign the active class to the weather panel
	document.getElementById('selectorContents0').classList.add("active");

	// Create the elements for the weather dashboard
	generateWeatherDashboard();
}

function generateSpacers() {

	// Find all spacers (elements which are used to just as a slash)
	var spacers = document.getElementsByClassName('spacer');

	// Loop through all discovered spacers
	for (var i = 0; i < spacers.length; i++) {

		// Assign a slash as a placeholder for this spacer
		spacers[i].innerHTML = '/';
	}
}

function generateWeatherDashboard() {

	// Generate applicable div's
	generateSection(arrWeatherDashboard, 1);
	generateSection(arrCurrentPane, 1);
	generateSection(arrLeftWeatherSummary, 1);
	generateSection(arrRightWeatherSummary, 4);
	generateSection(arrHourly, 3);
	generateSection(arrFooter, 7, setBigWeather);

	// Add appropriate icons to the generated div's above
	addIcons(arrRightWeatherSummaryIcons);

	// Add label to our Updated timestamp
	document.getElementById('bigTimeCaption').innerHTML = 'Updated: ';

	// Finally, add spacers where the 'spacers' class exists
	generateSpacers();
}

function generateNewsDashboard() {

	generateSection(arrNewsContainer, 1);
	generateSection(arrNewsItem, 4);
}

function generateRemindersDashboard() {
	generateSection(arrReminderContainer, 1);
}

function generateOptionsDashboard() {
	generateSection(arrOptionsContainer, 1);
}

function generateSection(array, numLoops, callback) {

	// Loop through each day of the week
	for (var i = 0; i < numLoops; i++) {

		// Loop through each element in our array
		for (var j = 0; j < array.length; j++) {

			// Determine the type, id, classname, and parent
			var el, elType = array[j][0], elID = (array[j][4] ? array[j][1] + i : array[j][1]), elClass = (array[j][5] ? array[j][2] + i : array[j][2]), elParent = (array[j][6] ? array[j][3] + i : array[j][3]);

			// Create the element based off the properties from our array
			el = generateElement(elType, elID, elClass, elParent);

			// For the special case that we have to add an event listener
			if (callback) {

				// Append our onClick function call on the first div element
				j === 0 ? addEventListener(el, i, callback) : j;
			}
		}
	}
}

function addIcons(icons) {

	// Loop through icon
	for (var i = 0; i < icons.length; i++) {

		// Set the icon via classname
		document.getElementById(icons[i][0]).className = icons[i][1];
	}
}

function addEventListener(el, index, callback) {

	// Add the onclick function call using index
	el.onclick = function () {
		callback(index);
	};
}
