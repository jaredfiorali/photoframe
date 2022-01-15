import React from 'react';
import { Grid } from '@mui/material';

class Spacer extends React.Component {
	render() {
		return <Grid item xs={this.props.space} />;
	}
}

export default Spacer;
