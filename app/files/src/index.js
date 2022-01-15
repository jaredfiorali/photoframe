import React, { useState, useEffect } from 'react';
import ReactDOM from 'react-dom';

import { styled, createTheme, ThemeProvider } from '@material-ui/core/styles';
import { makeStyles } from '@material-ui/styles';
import { Typography, Box, Grid, Slide, Container } from '@material-ui/core';

import BigClock from './components/bigClock';

import '@fontsource/roboto/300.css';
import '@fontsource/roboto/400.css';
import '@fontsource/roboto/500.css';
import '@fontsource/roboto/700.css';

const darkTheme = createTheme({
	palette: {
		mode: 'dark',
	},
});

const useStyles = makeStyles ({
	weatherIcon: {
		width: '150px',
		marginTop: '-20px',
	},
	bigTemperature: {
		opacity: 1,
		transition: 'all 1s',
	}
});

const Photo = styled(Container)(({ }) => ({
	background: 'url(img/background.jpg)',
	backgroundSize: 'cover',
	backgroundColor: 'black',
	height: '100%',
	position: 'absolute',
	transition: 'all 1s',
}));

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
	marginLeft: '10px',
	transition: 'all 1s',
}));

const GridClock = styled(Grid)(({}) => ({
	transformOrigin: 'top left',
	transition: 'all 1s',
}));

const GridWeatherIcon = styled(Grid)(({ }) => ({
	transformOrigin: 'top left',
	transition: 'all 1s',
}));

function App() {
	const classes = useStyles();

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
					<Photo style={{ opacity: displayOverlay ? '0.5' : '1' }} />
					<GridContainer>
						<GridTop/>
						<GridBottom container style={{ transform: displayOverlay ? 'translateY(-250%)' : 'translateY(0%)' }}>
							<GridClock style={{ transform: displayOverlay ? 'scale(1.2)' : 'scale(1)' }} item xs={3}>
								<Typography variant="h5" style={{ textAlign: 'center', transition: 'all 1s', paddingTop: '30px', opacity: displayOverlay ? '1' : '0' }}>Friday, August 13th</Typography>
								<BigClock style={{ textAlign: 'center' }}/>
							</GridClock>
							<Grid item xs={5}/>
							<GridWeatherIcon style={{ paddingTop: displayOverlay ? '0px' : '60px', transform: displayOverlay ? 'scale(2)' : 'scale(1)' }} container xs={2}>
								<Grid item xs={11}>
									<Slide direction="left" in={slideIn}>
										<img src="icons/weather/overcast-day.svg" className={classes.weatherIcon} />
									</Slide>
								</Grid>
								<Grid item style={{ transition: 'all 1s', paddingTop: '30px', opacity: displayOverlay ? '0' : '1' }} xs={1}>
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
