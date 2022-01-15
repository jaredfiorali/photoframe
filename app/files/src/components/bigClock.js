import React from 'react';
import { Slide, Grid, Typography } from '@mui/material';
import { styled } from '@mui/material/styles';

const GridClock = styled(Grid)(({ }) => ({
	transformOrigin: 'top left',
	transition: 'all 1s',
	textAlign: 'center'
}));

const CurrentDate = styled(Typography)(({ }) => ({
	textAlign: 'center',
	transition: 'all 1s',
	paddingTop: '10%',
	paddingLeft: '5%',
}));

/**
 * Helper function to return a date with the correct ordinal (th, rd, etc)
 *
 * @param {integer} n Day of the month that we want to display nicely
 * @returns string
 */
function getOrdinalNum(n) {
	return n + (n > 0 ? ['th', 'st', 'nd', 'rd'][(n > 3 && n < 21) || n % 10 > 3 ? 0 : n % 10] : '');
}

/**
 * Returns a two digit integer, with a leading 0 if the incoming integer was a single digit
 *
 * @param {integer} timeValue An integer that we might want to display a leading 0 for
 * @returns integer
 */
function leadingZero(timeValue) {
	if (timeValue < 10) {
		timeValue = "0" + timeValue;
	}

	return timeValue;
}

/**
 * Takes in a Date object, and returns the time 24 hour formatted
 *
 * @returns string
 */
function constructTime() {
	let currentDate = new Date();

	return currentDate.getHours() + ":" + leadingZero(currentDate.getMinutes());
}

/**
 * Takes in a Date object, and returns the current date...all nicely formatted
 *
 * @returns string
 */
function constructDate() {
	let currentDate = new Date();

	const days = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
	const months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
	let ordinal = getOrdinalNum(currentDate.getDate());

	return days[currentDate.getDay()] + ", " + months[currentDate.getMonth()] + " " + ordinal;
}

class BigClock extends React.Component {
	constructor(props) {
		super(props);

		this.state = {
			time: constructTime(),
			date: constructDate(),
			slideIn: false
		};
	}

	componentDidMount() {
		this.intervalID = setInterval(
			() => this.tick(),
			1000
		);

		this.state.slideIn = true;
	}

	componentWillUnmount() {
		clearInterval(this.intervalID);
	}

	tick() {
		this.setState({
			time: constructTime()
		});
	}

	render() {
		return (
			<GridClock style={{ transform: this.props.displayOverlayEnabled ? 'scale(1.2)' : 'scale(1)' }} item xs={3}>
				<CurrentDate variant="h5" style={{ opacity: this.props.displayOverlayEnabled ? '1' : '0' }}>
					{this.state.date}
				</CurrentDate>
				<Slide direction="right" in={this.state.slideIn} mountOnEnter unmountOnExit>
					<Typography variant="h1">
						{this.state.time}
					</Typography>
				</Slide>
			</GridClock>
		);
	}
}

export default BigClock;
