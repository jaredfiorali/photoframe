import React, { useState, useEffect } from 'react';
import ReactDOM from 'react-dom';

import { styled, createTheme, ThemeProvider } from '@material-ui/core/styles';
import { Box, Grid, Slide } from '@material-ui/core';

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

const GridContainer = styled(Grid)(({}) => ({
	height: '100%'
}));

const GridTop = styled(Grid)(({}) => ({
	height: '85%'
}));

const GridBottom = styled(Grid)(({}) => ({
	height: '10%',
	marginLeft: '10px'
}));

function App() {
	const [slideIn, setSlideIn] = useState(false);

	useEffect(() => {
		setSlideIn(true);
	});

	return (
		<ThemeProvider theme={darkTheme}>
			<Box sx={{
				flexGrow: 1,
				color: 'text.primary',
			}}>
				<GridContainer>
					<GridTop></GridTop>
					<GridBottom container>
						<Grid item xs={10}>
							<BigClock></BigClock>
						</Grid>
						<Grid item xs={1}>
							<Slide direction="left" in={slideIn} mountOnEnter unmountOnExit>
								<img src="icons/weather/overcast-day.svg" style={{width: '150px'}} />
							</Slide>
						</Grid>
					</GridBottom>
				</GridContainer>
			</Box>
		</ThemeProvider>
	);
}

ReactDOM.render(<App />, document.querySelector('#app'));
