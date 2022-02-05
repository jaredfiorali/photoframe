import React, { useState, useEffect } from 'react';
import ReactDOM from 'react-dom';

import { styled, createTheme, ThemeProvider } from '@mui/material/styles';
import { Typography, Box, Grid, Slide, Container } from '@mui/material';

import BigClock from './components/bigClock';
import Photo from './components/photo';
import Spacer from './components/spacer';
import WeatherForecast from './components/weatherForecast';
import WeatherIcon from './components/weatherIcon';

import '@fontsource/roboto/300.css';
import '@fontsource/roboto/400.css';
import '@fontsource/roboto/500.css';
import '@fontsource/roboto/700.css';

const darkTheme = createTheme({
	palette: {
		mode: 'dark',
	},
});

const OverlayContainer = styled(Container)(({ }) => ({
	backgroundColor: 'black',
	transition: 'all 1s',
	padding: '0 !important',
}));

const GridContainer = styled(Grid)(({}) => ({
	height: '100%',
	width: '100%'
}));

const GridTop = styled(Grid)(({}) => ({
	height: '75%'
}));

const GridBottom = styled(Grid)(({}) => ({
	height: '30%',
	overflow: 'hidden',
	marginLeft: '1%',
	transition: 'all 1s',
}));

const GridCurrentConditionsContainer = styled(Grid)(({}) => ({
	backgroundColor: 'red',
	height: '10%',
	transition: 'all 1s'
}));

const GridCurrentConditions = styled(Grid)(({ }) => ({
	backgroundColor: 'yellow',
	transition: 'all 1s'
}));

const GridWeatherIcon = styled(Grid)(({ }) => ({
	transformOrigin: 'top left',
	transition: 'all 1s',
}));

function App() {
	const [slideIn, setSlideIn] = useState(false);
	const [weatherData, setWeatherData] = useState("");
	const [displayOverlay, setDisplayOverlay] = useState(false);
	const apiEndpoint = (!process.env.NODE_ENV || process.env.NODE_ENV === 'development') ? 'http://localhost:8080' : 'http://api.photoframe.fiora.li';

	useEffect(() => {
		setSlideIn(true);

		if (weatherData == "") {
			setWeatherData("fetching...");
			fetch(apiEndpoint+'/index.php?endpoint=getWeather')
				.then(res => res.json())
				.then((data) => {
					setWeatherData(data);
				})
				.catch((e) => {
					console.log(e);
				});
		}
	});

	function toggleOverlay() {
		displayOverlay ? setDisplayOverlay(false) : setDisplayOverlay(true);
	}

	return (
		<div>
		{
			weatherData !== "" && weatherData !== "fetching..." &&
			<ThemeProvider theme={darkTheme}>
				<Box sx={{
					flexGrow: 1,
					color: 'text.primary',
				}}>
					<OverlayContainer onClick={toggleOverlay}>
						<Photo displayOverlayEnabled={displayOverlay} />
						<GridContainer>
							<GridTop/>
							<GridBottom container style={{ transform: displayOverlay ? 'translateY(-250%)' : 'translateY(0%)' }}>
								<BigClock displayOverlayEnabled={displayOverlay} />

								<Spacer space={5} />

								<GridWeatherIcon style={{ paddingTop: displayOverlay ? '0px' : '60px', transform: displayOverlay ? 'scale(2)' : 'scale(1)' }} container item xs={2}>
									<Slide direction="left" in={slideIn}>
										<Grid item xs={11}>
											<WeatherIcon iconCode={weatherData.current.iconCode} />
										</Grid>
									</Slide>
									<Grid item style={{ transition: 'all 1s', paddingTop: '10%', opacity: displayOverlay ? '0' : '1' }} xs={1}>
										<Slide direction="left" in={slideIn}>
											<Typography variant="h2">{weatherData.current.feelsLike}°C</Typography>
										</Slide>
									</Grid>
								</GridWeatherIcon>
							</GridBottom>
							<WeatherForecast displayOverlayEnabled={displayOverlay} weatherData={weatherData}/>
						</GridContainer>
					</OverlayContainer>
				</Box>
			</ThemeProvider>
		}
		</div>
	);
}

ReactDOM.render(<App />, document.querySelector('#app'));
