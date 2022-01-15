import React from 'react';
import { styled } from '@mui/material/styles';

const WeatherIconContainer = styled('img')({
	width: '150px',
	marginTop: '-20px',
});

class WeatherIcon extends React.Component {
	render() {
		return <WeatherIconContainer src="icons/weather/overcast-day.svg" />;
	}
}

export default WeatherIcon;
