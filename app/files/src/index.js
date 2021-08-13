import React, { useState, useEffect } from 'react';
import ReactDOM from 'react-dom';

import { styled, createTheme, ThemeProvider } from '@material-ui/core/styles';
import { makeStyles } from '@material-ui/styles';
import { Box, Grid, Slide, Container } from '@material-ui/core';

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
	}
});

const OverlayContainer = styled(Container)(({ }) => ({
	zIndex: '1',
	height: '100%',
	position: 'absolute',
	backgroundColor: 'black',
	opacity: 0.5,
}));

const GridContainer = styled(Grid)(({}) => ({
	height: '100%',
	width: '100%'
}));

const GridTop = styled(Grid)(({}) => ({
	height: '85%'
}));

const GridBottom = styled(Grid)(({}) => ({
	height: '15%',
	overflow: 'hidden',
	marginLeft: '10px'
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
				<OverlayContainer style={{display: displayOverlay ? 'block' : 'none'}}/>
				<Container onClick={toggleOverlay}>
					<GridContainer>
						<GridTop/>
						<GridBottom container>
							<Grid item xs={10}>
								<BigClock></BigClock>
							</Grid>
							<Grid item xs={1}>
								<Slide direction="left" in={slideIn}>
									<img src="icons/weather/overcast-day.svg" className={classes.weatherIcon} />
								</Slide>
							</Grid>
						</GridBottom>
					</GridContainer>
				</Container>
			</Box>
		</ThemeProvider>
	);
}

ReactDOM.render(<App />, document.querySelector('#app'));
