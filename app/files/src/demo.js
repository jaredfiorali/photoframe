import React from "react";
import { makeStyles } from "@material-ui/core/styles";
import Switch from "@material-ui/core/Switch";
import Paper from "@material-ui/core/Paper";
import Fade from "@material-ui/core/Fade";
import Slide from "@material-ui/core/Slide";
import FormControlLabel from "@material-ui/core/FormControlLabel";

const useStyles = makeStyles(theme => ({
  root: {
    height: 180
  },
  container: {
    display: "flex"
  },
  paper: {
    margin: theme.spacing(1),
    backgroundColor: "lightblue"
  },
  svg: {
    width: 100,
    height: 100
  },
  polygon: {
    fill: theme.palette.primary.main,
    stroke: theme.palette.divider,
    strokeWidth: 1
  }
}));

export default function SlideAndFade() {
  const classes = useStyles();
  const [checked, setChecked] = React.useState(false);

  const handleChange = () => {
    setChecked(prev => !prev);
  };

  return (
    <div className={classes.root}>
      <FormControlLabel
        control={<Switch checked={checked} onChange={handleChange} />}
        label="Show"
      />
      <div className={classes.container}>
        <Slide in={checked} timeout={1000}>
          <div>
            <Fade in={checked} timeout={1000}>
              <Paper elevation={4} className={classes.paper}>
                <svg className={classes.svg}>
                  <polygon
                    points="0,100 50,00, 100,100"
                    className={classes.polygon}
                  />
                </svg>
              </Paper>
            </Fade>
          </div>
        </Slide>
      </div>
    </div>
  );
}
