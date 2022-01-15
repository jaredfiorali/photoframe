import React from 'react';
import { styled } from '@mui/material/styles';
import { Container } from '@mui/material';

const PhotoContainer = styled(Container)(({ }) => ({
	background: 'url(img/background.jpg)',
	backgroundSize: 'cover',
	backgroundColor: 'black',
	height: '100%',
	position: 'absolute',
	transition: 'all 1s',
}));

class Photo extends React.Component {
	render() {
		return <PhotoContainer style={{ opacity: this.props.displayOverlayEnabled ? '0.5' : '1' }} />;
	}
}

export default Photo;
