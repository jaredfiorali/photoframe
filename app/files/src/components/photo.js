import React from 'react';
import { styled } from '@material-ui/core/styles';
import { Container } from '@material-ui/core';

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
