<html lang="en">

<head>
    <title>Fiorali Forecast</title>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/weather-icons.min.css">
    <link rel="stylesheet" type="text/css" href="css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="css/aurora.css">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300" rel="stylesheet">
    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
    <link rel="icon" href="/favicon.ico" type="image/x-icon">

    <!-- Base JavaScript Library -->
    <script src="js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>

    <!-- Custom JavaScript -->
    <script src="js/aurora.js"></script>

</head>

<body onload="startTime()">
    <div class="alert alert-success bootstrapAlerts" id="alert_success_placeholder">
        <button type="button" class="close" data-dismiss="alert"><i class="fa fa-times" aria-hidden="true"></i></button>
        <i class="fa fa-check-circle"></i><strong> Success! </strong>Added to Pocket!
    </div>
    <div class="alert alert-danger bootstrapAlerts" id="alert_failed_placeholder">
        <button type="button" class="close" data-dismiss="alert"><i class="fa fa-times" aria-hidden="true"></i></button>
        <i class="fa fa-exclamation-triangle" aria-hidden="true"></i><strong> Failed! </strong><span id="bootstrapError"></span>
    </div>
    <div class="backgroundTop">
        <img id="backgroundImage" class="img-responsive backgroundImage" aria-hidden="true">
        <img id="backgroundImage1" class="img-responsive backgroundImage" aria-hidden="true">
        <div class="backgroundTopSummary">
            <div class="topSummary">
                <p class="bigClock" id="bigClock"></p>
                <div class="bigWeather">
                    <i id="topWeatherIcon" class="wi topWeatherIcon" aria-hidden="true"></i>
                    <p id="apparentTemperature"></p>
                    <div class="sunriseSunset">
                        <p id="todaySunrise"></p>
                        <p id="todaySunset"></p>
                    </div>
                </div>
            </div>
            <p id="bigDate" class="bigDate"></p>
            <p id="location"></p>
        </div>
        <div id="backgroundRefresh" class="backgroundRefresh"><i class="fa fa-refresh fa-1 weatherIcon" aria-hidden="true"></i></div>
    </div>
    <div class="backgroundBottom" id="detailedContent">
        <div class="leftPane">
            <div class="today">
                <div class="info">
                    <p class="weatherDateHeader">Today</p>
                    <i id="todayIcon" class="wi weatherIcon" aria-hidden="true"></i>
                    <div class="temperature">
                        <p id="todayTemperature"></p>
                    </div>
                </div>
                <div class="weatherDetail">
                    <div class="summary">
                        <p id="todaySummary"></p>
                    </div>
                    <div class="rain">
                        <p id="todayPrecipProbability"></p>
                    </div>
                    <div class="wind">
                        <p id="todayWindSpeed"></p>
                    </div>
                </div>
            </div>
            <div class="tomorrow">
                <div class="info">
                    <p class="weatherDateHeader">Tomorrow</p>
                    <i id="tomorrowIcon" class="wi weatherIcon" aria-hidden="true"></i>
                    <div class="temperature">
                        <p id="tomorrowTemperature"></p>
                    </div>
                </div>
                <div class="weatherDetail">
                    <div class="summary">
                        <p id="tomorrowSummary"></p>
                    </div>
                    <div class="rain">
                        <p id="tomorrowPrecipProbability"></p>
                    </div>
                    <div class="wind">
                        <p id="tomorrowWindSpeed"></p>
                    </div>
                </div>
            </div>
        </div>
        <div class="mainPane">
            <div class="todoList">
                <p class="weatherDateHeader">Things to do:</p>
                <table id="todoList">
                    <tbody class="summary"></tbody>
                </table>
            </div>
            <div class="headlineList">
                <p class="weatherDateHeader">Headlines:</p>
                <table id="headlineList">
                    <tbody class="summary"></tbody>
                </table>
            </div>
            <div class="calendar">
                <p class="weatherDateHeader">Upcoming:</p>
                <table id="calendarList">
                    <tbody class="summary"></tbody>
                </table>
            </div>
            <div class="footer">
                <div class="friendlySummary">
                    <p id="clothing" class="friendlySummaryText"></p>
                </div>
                <div class="lights">
                    <img id="lightSwitch" class="lightSwitchImage" width="80px" src="" />
                </div>
            </div>
        </div>
    </div>
</body>

</html>
