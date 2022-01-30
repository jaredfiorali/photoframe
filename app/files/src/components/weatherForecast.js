import React from 'react';
import { Grid, Typography } from '@mui/material';
import { styled } from '@mui/material/styles';

import WeatherIcon from './weatherIcon';

const Icon = styled('img')({
	width: '50px',
	marginTop: '-5px'
});

const GridForecastContainer = styled(Grid)(({ }) => ({
	// backgroundColor: 'blue',
	height: '60%',
	transition: 'all 1s'
}));

const GridForecast = styled(Grid)(({ }) => ({
	transition: 'all 1s',
	textAlign: 'center'
}));

const GridForecastDetailsContainer = styled(Grid)(({ }) => ({
	// backgroundColor: 'green',
	height: '100%'
}));

function renderForecast(weatherData) {
	let forecast = [];

	for (let i = 0; i < 5; i++) {
		forecast.push(
			<GridForecast item xs key={i}>
				<GridForecastDetailsContainer container rowSpacing={2} direction="column" justifyContent="center" alignItems="center">
					<Grid item xs={3} />
					<Grid item xs>
						<div style={{display: 'flex'}}>
							<Icon src={"icons/weather/thermometer-celsius.svg"} />
							<Typography variant="h4">
								{weatherData.forecast[i].temperature}°C
							</Typography>
						</div>
						<div style={{ display: 'flex' }}>
							<Icon src={"icons/weather/umbrella.svg"} />
							<Typography variant="h4">
								{weatherData.forecast[i].precipitationChance}%
							</Typography>
						</div>
						<div style={{ display: 'flex' }}>
							<Icon src={"icons/weather/uv-index-" + weatherData.forecast[i].uv + ".svg"} />
						</div>
					</Grid>
					<Grid item xs>
						<WeatherIcon iconCode={weatherData.forecast[i].iconCode} />
					</Grid>
					<Typography variant="h4">
						{weatherData.forecast[i].period}
					</Typography>
				</GridForecastDetailsContainer>
			</GridForecast>
		);
	}

	return forecast;
}

class WeatherForecast extends React.Component {
	render() {
		let forecast = renderForecast(this.props.weatherData);

		return (
			<GridForecastContainer container spacing={2} style={{
						transform: this.props.displayOverlayEnabled ? 'translateY(-120%)' : 'translateY(0%)'
					}}>
				{forecast}
			</GridForecastContainer>
		);
	}
}

export default WeatherForecast;
