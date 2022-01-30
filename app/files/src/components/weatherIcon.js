import React from 'react';
import { styled } from '@mui/material/styles';

const WeatherIconContainer = styled('img')({
	width: '150px',
	marginTop: '-12%',
});

// This array is to keep track of the wording that Environment Canada uses to display icons
const weatherIconText = [
	'Sunny',
	'Mainly Sunny',
	'A mix of sun and cloud',
	'Mostly Cloudy',
	'',
	'',
	'',
	'',
	'Chance of flurries',
	'',
	'Cloudy',
	'',
	'',
	'',
	'',
	'Periods of rain or snow',
	'Light snow',
	'Snow',
	'Heavy snow',
	'',
	'',
	'',
	'',
	'',
	'',
	'',
	'',
	'',
	'',
	'',
	'Clear',
	'Mainly Clear',
	'Cloudy periods',
	'',
	'',
	'',
	'',
	'',
	'Chance of flurries',
	'',
	'Snow and blowing snow',
];

// This array maps the Environment Canada icon filenames with our internal filenames
const weatherIcon = [
	'clear-day',
	'partly-cloudy-day',
	'overcast-day',
	'cloudy',
	'',
	'',
	'',
	'',
	'',
	'',
	'cloudy',
	'',
	'',
	'',
	'',
	'thunderstorms-day-snow',
	'snow',
	'snow',
	'snowflake',
	'',
	'',
	'',
	'',
	'',
	'',
	'',
	'',
	'',
	'',
	'',
	'clear-night',
	'partly-cloudy-night',
	'overcast-night',
	'',
	'',
	'',
	'',
	'',
	'partly-cloudy-night-snow',
];

class WeatherIcon extends React.Component {
	constructor(props) {
		super(props);
	}

	render() {
		let resultIcon = weatherIcon[parseInt(this.props.iconCode)];

		if (resultIcon === 'undefined') {
			resultIcon = 'not-available';
		}
		return <WeatherIconContainer src={"icons/weather/" + resultIcon + ".svg"} />;
	}
}

export default WeatherIcon;
