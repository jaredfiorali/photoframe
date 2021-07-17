import * as React from 'react';
import ReactDOM from 'react-dom';

import { styled } from '@material-ui/core/styles';
import Box from '@material-ui/core/Box';
import Grid from '@material-ui/core/Grid';
import { createTheme, ThemeProvider } from '@material-ui/core/styles';

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

const GridContainer = styled(Grid)(({ darkTheme }) => ({
	height: '100%'
}));

const GridTop = styled(Grid)(({ darkTheme }) => ({
	height: '85%'
}));

const GridBottom = styled(Grid)(({ darkTheme }) => ({
	height: '10%',
	marginLeft: '10px'
}));

function App() {
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
							<img src="icons/weather/overcast-day.svg" style={{width: '150px'}} />
						</Grid>
					</GridBottom>
				</GridContainer>
			</Box>
		</ThemeProvider>
	);
}

ReactDOM.render(<App />, document.querySelector('#app'));
