import React from 'react';
import Typography from '@material-ui/core/Typography';

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
			time: constructTime()
		};
	}

	componentDidMount() {
		this.intervalID = setInterval(
			() => this.tick(),
			1000
		);
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
			<Typography variant="h1">
				{this.state.time}
			</Typography>
		);
	}
}

export default BigClock;
