import React, { useState, useEffect } from 'react';
import ReactDOM from 'react-dom';

import { styled, createTheme, ThemeProvider } from '@mui/material/styles';
import { Typography, Box, Grid, Slide, Container } from '@mui/material';

import BigClock from './components/bigClock';
import Photo from './components/photo';
import Spacer from './components/spacer';
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

const GridWeatherIcon = styled(Grid)(({ }) => ({
	transformOrigin: 'top left',
	transition: 'all 1s',
}));

function App() {
	const [slideIn, setSlideIn] = useState(false);
	const [displayOverlay, setDisplayOverlay] = useState(false);

	useEffect(() => {
		setSlideIn(true);
	});

	function toggleOverlay() {
		if (displayOverlay) {
			setDisplayOverlay(false);
		} else {
			setDisplayOverlay(true);
		}
	}

	return (
		<ThemeProvider theme={darkTheme}>
			<Box sx={{
				flexGrow: 1,
				color: 'text.primary',
			}}>
				<OverlayContainer onClick={toggleOverlay}>
					<Photo displayOverlayEnabled={displayOverlay} />
					<GridContainer>
						<GridTop />
						<GridBottom container style={{ transform: displayOverlay ? 'translateY(-250%)' : 'translateY(0%)' }}>
							<BigClock displayOverlayEnabled={displayOverlay} />

							<Spacer space={5} />

							<GridWeatherIcon style={{ paddingTop: displayOverlay ? '0px' : '60px', transform: displayOverlay ? 'scale(2)' : 'scale(1)' }} container item xs={2}>
								<Slide direction="left" in={slideIn}>
									<Grid item xs={11}>
										<WeatherIcon />
									</Grid>
								</Slide>
								<Grid item style={{ transition: 'all 1s', paddingTop: '10%', opacity: displayOverlay ? '0' : '1' }} xs={1}>
									<Slide direction="left" in={slideIn}>
										<Typography variant="h2">23°C</Typography>
									</Slide>
								</Grid>
							</GridWeatherIcon>
						</GridBottom>
					</GridContainer>
				</OverlayContainer>
			</Box>
		</ThemeProvider>
	);
}

ReactDOM.render(<App />, document.querySelector('#app'));
