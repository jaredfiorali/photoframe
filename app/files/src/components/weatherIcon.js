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
	'',
	'',
	'',
	'',
	'',
	'',
	'',
	'Cloudy',
	'',
	'',
	'',
	'',
	'Periods of rain or snow',
	'',
	'Snow',
	'Snow at times heavy',
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
];

// This array maps the Environment Canada icon filenames with our internal filenames
const weatherIcon = [
	'clear-day',
	'partly-cloudy-day',
	'overcast-day',
	'',
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
	'',
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
		return <WeatherIconContainer src={"icons/weather/" + weatherIcon[parseInt(this.props.iconCode)] + ".svg"} />;
	}
}

export default WeatherIcon;
