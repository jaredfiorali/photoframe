import React from 'react';
import { Slide, Typography } from '@material-ui/core';

function constructTime() {
	let date = new Date();

	return date.getHours() + ":" + leadingZero(date.getMinutes());
}

function leadingZero(timeValue) {
	if (timeValue < 10) {
		timeValue = "0" + timeValue;
	}

	return timeValue;
}

class BigClock extends React.Component {
	constructor(props) {
		super(props);
		this.state = {
			time: constructTime(),
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
			<div style={{textAlign: 'center'}}>
				<Slide direction="right" in={this.state.slideIn} mountOnEnter unmountOnExit>
					<Typography variant="h1">
						{this.state.time}
					</Typography>
				</Slide>
			</div>
		);
	}
}

export default BigClock;
