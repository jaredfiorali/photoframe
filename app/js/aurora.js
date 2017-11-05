var photoToggle = true;
var getW = 0;
var getP = 0;
var getN = 0;
var getT = 0;
var getL = 0;
var getC = 0;
var lastUpdate = new Date();
var lightOn = 0;
var switchImage;
var onImage = "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAFAAAAAuCAYAAAC/OZ4cAAAABHNCSVQICAgIfAhkiAAAAAFzUkdCAK7OHOkAAAAEZ0FNQQAAsY8L/GEFAAAACXBIWXMAAA7EAAAOxAGVKw4bAAAAGXRFWHRTb2Z0d2FyZQB3d3cuaW5rc2NhcGUub3Jnm+48GgAACiZJREFUaEPtmwmM1Fcdx7/zn3tndmZvZIWFZQ8OqSDQ0LRpihwKSrBHlDRqCm3FC0ua1CMmxphoRK1tWilqY7s2ahAbGrRY2nCIaQCx0BRbFtijwAKL7Ow99/Uff9+3Oxyzs7sz7OwytnyTyfznP8d77/N+7/d7v/feGPDnrQlkq4R8xagBJg1Wmx2Tu/1wt3ng7OqHubsfrkAE/f4AgqGQ+rjBYFDPE60E6ymy22xwOQrQX2BBtMQFX6kLfVXluFTiQDgUBGI6EJfHDdQzO4BJcAVW1HeHMO34GViOt6Db70PCZEJCkwpoGnSpiMZr0c2Cl1QSoq4noPFa12GQa0MshhKHE5F5tTg3rxpNJTYgEM4aZHYAnXbMvtCL2gP/QeeZ89BtFgFnFFgC9f9QOmHG4tBCEZRVT0XLko/j5JQiwCdWmaFGB8hesxhRo9kxZ+dBdJ56H/ECGzQB90GSLiCNgRDKZs1A4713oVUXiJH4qNY4MkDCczmwvLkTie174LUaYZCherOH5XiJwz0hQ7swHIdh7QrsrSuDOPMRIY4w9hKwlxThvteOw7vtdfgcVmhm86jwWIl4PI6oVCQajSImz3yd9EX5LLaNbWRb2Wa2nQzIYjgNY4EJmJwOfGbbm7h09gI0u3VYcAqY+JJwOIxIJKqKqigrRbHbBavVqiJxT28vPF09En80WCxmWC0W5Tfz2ZLZLj0YxuTpU/Dag3cj5hNLFFypGgpQvugqLcayl/bjQtsFGG3WwTeGKiIW5pMfnlRRjjUrl+OORZ/A7Jm1sEgv0kGTJhkZBFYoFMZ7J0/j4L+PYdcb+9Db1w+ndJJZXEI+Ky71nlI1BfseWop+MQLVoGt0PUAOM7cTn9v5Ftobm2G0S2hPI8Lp6e3D3Nkz8dhX12HxgvnwBQKIigVyyF7fIwOitZkk8FjMFjgK7Nj/5mE8+3wDzp2/iCKx1ny2xngwhMo5dfjrvbcDfb7rIF4FSHhWE1a39KLjlT3QCh3qdqrCkYgM1Qh+/P0n8Kmld4sleZWvy1Ycxi5XIV7euQs/eXorXIXOvLZG3etHxf0rsKtWfGI4dgXi1SAiE985cTO8O/bC4CwYvHm9/IEgyktLsOeVP+LOxQvh6ey+IXgUO8LT2YXVn16G3dsb1LAPiR/NV5EJ2ZARWSU1AJDWV1iA2pf/iaCkO+mGE+HV10zHjpd+LVFVR1DMOhfyScrHVGvXthdRUlyUtxDJhGzIiKwUM9EAQPFN85o60NHWLhPkocOI1lI5qRwNW55ET0+fmpbkUpzyBIJBbP/dFtgkctOP5qPIhozIiszUPUVSnHrV/negS46bKgYMTk9eFHh9/V7og+RzLXYKo3rDr36BPq/k1uNUzlhFRmRFZmSncXFgQXsfPB0eaMah6VmPTDd+9L3HxcEbc255qaI/rSgvw6YN69AvEPNRZERWZEZ2GsxGVBxrRsJmGfzIVbFBs+pmYNXyJTLEcuPzRpPX58P6L34BxUXuce8wSiUCoThiAcmW9MysnqzIjOw0t9WOyHstV8Z0Uvxhr9+PxzasR59Y4USKEL/xyJclcAUG74yP2MZofwyVKytQ+2iVpHGGzCAKKzIjO23SpV4EdUmeUyIvfV9JURHuvH2B8k0TqbDM/j+74pNSJ20goxkHKXh9MdSsm4qKe0pRWOfApGXl0COjl6cisjAjO8193qPW9FLFyLtqmQzdwVXliRRtQNqHJXfdMS6ddwXeI1Vwz3Uh5hcDMhrQdbgHBnNma5tkRnaao1ciXpoF0Wg0hsWL5qv07GaI2c7ihVJ+jgEm4dVumAb3bKekaXGYHCaceqoVoc6IBImhc+B0IjOy0+zd3oGl+BSx4vW1MxCbAEeeTiy3vqZa5oQjl08gelTPyHcl4dV9bRpc9Q4JHjpMToH3dCuC/w3DaM3M+igyIzvNEJSZf4r/Y0FciiqVzGC8fNBoYgSe8tHJaqlsOLGehED/RencHBpGCp4EjPpvVqOwZhBegREnn2xByCPwbFmusAszstP6JeKlBhAW5nCkz4cnSqyDU+rA5+Gkh3VMve8jqP3KNNz2g3oY7ca0QYDWSXgzN1bDMd2OuHyP8Bp/2YpwV1QsL0t4IjIju8xtNk8ldqCsKS7gPvbdWpgKTeo6KcKL+WKYtWkGCqrsCrDRrqHx5y2IdBPe2BBoLqdzSC+Trl+S/Jsp1oELDamj41pp0vi2He3wtwWgWTRlWXO+UwuLSyDK9QC8OGZuqoG90jYAT6xNwROLHAs8MiM7LWGX/DcNQC7Rd/XIPCdNhJ4IGSVlunDxktoGGE6sJ4ft6WfOINAWhJEQJaoqiMVmFTBmPT4D9slWFWj4/onNzYh54+p6TBJmZKcFSwrVRnOqzGYzmlrel0l39v4hF2K5Ta1n1Cr2SDJINDS7TTi95Qx8ZwPKqpiW0d/N3FQNW4UFCYGnyfzuxOYWxOU9WutYRWZkp/mLnPJiqOM1m004cvQdmC3mwTsTK4vFgiPHpHzpyNFESzTLsG167iy8rX6JqBoS8QTsk6zq2WAy4MRPm1XOmwt4FJmRndY3tVztzqeKS+679x1AgS39vsh4il6Pru/AwX+plepMpCCKJTb/5hz6T/tlcsw9bPkRsVDCo/+jFeZKZEZ22uXJRbBrxiGBhL6vu7cXh956O+NG5EpWmxV/3/MPqZM0OgsfnITY8sI5eA53I3AxhMaftYj/S+QUHlmRGdlpfeEgLHNrOfUffHtArEyhw4Fnf9sAt9s1eHdiVCjRbesLf4CjIPu5qIIoU5mLr15W1piI5RaekrAiM7LTEI2jY2EdDKHI4LtXRf9zSgLJ7r0ylIfZ4sy1CK/hT39R26aMxDciQmRmwckyFwlyLbIiM7KTuK/j7Uo3yivKoafJe3nC4Iebn0JUqN9ogzIVO6zD04lnnv+92ubMR5ERWZEZ2fF8BRAIom3pfGg8H5ci+iBGxIc3PgG3S8I2Pz8OYufQ167/1rfhFni0onwUGZEVmZHdgHMQ6zpeX4GKqkpJyIfuiDEit1/2YL1ALC5259wSuaFeYLdj7aMb1bamKU832MmGjMgqGTMGALK3vQG0fP4e2AORIRGZ4nGMptazeOChrwtADfYc+UQuGPAA0uoHH0a3ZD7c1sxHqcgrbMiIrBQz0dXwJDPrRmMUhQ8sR8KXPg8mRE9XN1bc/yUcOnIM5WUlGU1004lWXV5Wilff2IdVa9erled8hUeRCdmQEVkldetwUQbK7HBRUgIx++NtZVizcsXA8bZ6SeQl/UsuxLKo5PG2d0+exqEP9PG2KxrrAcsSsSq3GpL0b8xoOj80ByyvSJxmcRFW/u0o2o++qw7UZNJgVbAAVUdA5MHv8JHvwK6VCqISKCoX3YbX1yxCUIJbOnjUCABF/KFbh8yF0vDtHRkgRYi3/uYwrEYHeK1u/dFmiLIDSGu89Vev65QdwKSSID/0fzYE/ge2C4NxXNptPgAAAABJRU5ErkJggg==";
var offImage = "data:image/png;base64,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";

function resetInterval(initial) {
    if (initial) {
		startTime(initial);
		getT = setInterval(startTime, 1000, false);
	}
    getWeather();
    getPhoto(initial);
	getNews();
    getLights();
    getCalendar();
    clearInterval(getW);
    clearInterval(getP);
    clearInterval(getN);
    clearInterval(getL);
    clearInterval(getC);
    getW = setInterval(getWeather, 500000);
    getP = setInterval(getPhoto, 30000);
    getN = setInterval(getNews, 500000);
    getL = setInterval(getLights, 500000);
    getL = setInterval(getCalendar, 500000);
}

function displayErrorAlert(message, err) {
    document.getElementById('bootstrapError').innerHTML = message;
    $("#alert_failed_placeholder").fadeTo(2000, 500).slideUp(500, function () {
        $("#alert_failed_placeholder").slideUp(5000);
    });

    console.log("AJAX error in request: " + JSON.stringify(err, null, 2));
}

function startTime(initial) {
    //Get time
    var today = new Date();
    var h = today.getHours();
    var m = today.getMinutes();
    var s = today.getSeconds();

    if (today.getTime() > lastUpdate.getTime() + 240000)
        resetInterval(false);

    if (m < 10) {
		// Add leading 0 in front of minutes if required
        m = "0" + m
    }
    if (h > 12) {
		// Convert to 12 hour time
        h = h - 12
    }

	if (today.getMinutes() > lastUpdate.getMinutes() || today.getHours() > lastUpdate.getHours() || initial) {
		document.getElementById('bigClock').innerHTML = h + ':' + m
	}

	lastUpdate = today;
}

function getPhoto(initial) {
    //Get the next photo
    $.ajax({
        url: "http://aurora.fiora.li/inbound.php",
        type: "POST",
        data: 'command=getPhoto',
        accept: 'application/json',
		cache: false,
        dataType: "json",
        contentType: 'application/x-www-form-urlencoded',
        success: function (result) {
            document.getElementById('location').innerHTML = '<i class="fa fa-location-arrow" aria-hidden="true"></i> ' + result[0];

            if (initial) {
                $('#backgroundImage').attr("src", result[1]);
                $('#backgroundImage1').attr("src", result[1]);
            } else {
                if (photoToggle) {
                    $('#backgroundImage').attr("src", result[1]);
                    $('#backgroundImage1').css('z-index', 2);
                    $('#backgroundImage').css('z-index', 1);

                    $('#backgroundImage1').fadeOut(2500);
                    $('#backgroundImage').fadeIn(0);
                    photoToggle = false;
                } else {
                    $('#backgroundImage1').attr("src", result[1]);
                    $('#backgroundImage1').css('z-index', 1);
                    $('#backgroundImage').css('z-index', 2);

                    $('#backgroundImage').fadeOut(2500);
                    $('#backgroundImage1').fadeIn(0);
                    photoToggle = true;
                }
            }
        },
        error: function (err) {
            displayErrorAlert("Error retreiving Photo - Details in console", err);
        }
    });
}

function getWeather() {
    //Get the next weather report
    $.ajax({
        url: "http://aurora.fiora.li/inbound.php",
        type: "POST",
        data: 'command=getWeather',
        accept: 'application/json',
        cache: false,
        dataType: "json",
        contentType: 'application/x-www-form-urlencoded',
        success: function (result) {

            //Update icons
            document.getElementById('topWeatherIcon').className = "";
            document.getElementById('topWeatherIcon').className = "wi topWeatherIcon";
            document.getElementById('topWeatherIcon').classList.add(result.weatherIcon);
            document.getElementById('todayIcon').className = "";
            document.getElementById('todayIcon').className = "wi topWeatherIcon";
            document.getElementById('todayIcon').classList.add(result.todayIcon);
            document.getElementById('tomorrowIcon').className = "";
            document.getElementById('tomorrowIcon').className = "wi topWeatherIcon";
            document.getElementById('tomorrowIcon').classList.add(result.tomorrowIcon);

            //Update Current information
            document.getElementById('apparentTemperature').innerHTML = result.apparentTemperature;
            document.getElementById('clothing').innerHTML = "Long story short..." + result.clothing;
            document.getElementById('bigDate').innerHTML = result.bigDate;
            document.getElementById('todaySunrise').innerHTML = '<i class="wi wi-sunrise sunriseSunsetIcon" aria-hidden="true"></i>' + result.todaySunrise;
            document.getElementById('todaySunset').innerHTML = '<i class="wi wi-sunset sunriseSunsetIcon" aria-hidden="true"></i>' + result.todaySunset;

            //Update Today's information
            document.getElementById('todaySummary').innerHTML = "Today it will be " + result.todaySummary;
            document.getElementById('todayPrecipProbability').innerHTML = '<i class="wi wi-umbrella smallWeatherIcon" aria-hidden="true"></i>' + result.todayPrecipProbability + '%';
            document.getElementById('todayTemperature').innerHTML = result.todayTemperatureMin + '/' + result.todayTemperatureMax + '<i class="wi wi-celsius celsiusIcon" aria-hidden="true"></i>';
            document.getElementById('todayWindSpeed').innerHTML = '<i class="wi wi-strong-wind smallWeatherIcon" aria-hidden="true"></i>' + result.todayWindSpeed + 'km/h';

            //Update Tomorrow's information
            document.getElementById('tomorrowSummary').innerHTML = "Tomorrow it's looking like " + result.tomorrowSummary;
            document.getElementById('tomorrowPrecipProbability').innerHTML = '<i class="wi wi-umbrella smallWeatherIcon" aria-hidden="true"></i>' + result.tomorrowPrecipProbability + '%';
            document.getElementById('tomorrowTemperature').innerHTML = result.tomorrowTemperatureMin + '/' + result.tomorrowTemperatureMax + '<i class="wi wi-celsius celsiusIcon" aria-hidden="true"></i>';
            document.getElementById('tomorrowWindSpeed').innerHTML = '<i class="wi wi-strong-wind smallWeatherIcon" aria-hidden="true"></i>' + result.tomorrowWindSpeed + 'km/h';
        },
        error: function (err) {
            displayErrorAlert("Error retreiving Weather - Details in console", err);
        }
    });
}

function addToPocket(pocketAddress) {
    //Add URL to Pocket
    $.ajax({
        url: "http://aurora.fiora.li/inbound.php",
        type: "POST",
        data: 'command=sendToPocket&pocketAddress=' + pocketAddress,
        contentType: 'application/x-www-form-urlencoded',
        success: function (result) {
            $("#alert_success_placeholder").fadeTo(2000, 500).slideUp(500, function () {
                $("#alert_success_placeholder").slideUp(500);
            });
        },
        error: function (err) {
            displayErrorAlert("Error adding to Pocket - Details in console", err);
        }
    });
}

function getNews() {
    //Receive News from AP
    $.ajax({
        url: "http://aurora.fiora.li/inbound.php",
        type: "POST",
        data: 'command=getNews',
        accept: 'application/json',
        dataType: "json",
        contentType: 'application/x-www-form-urlencoded',
        success: function (result) {
            $("#headlineList tbody tr").remove();
            var table = document.getElementById("headlineList");

            for (var i = 5; i > 0; i--) {
                var row = table.insertRow(0);
                var cell1 = row.insertCell(0);
                cell1.innerHTML = '<a class="summaryList" href="javascript:;" onclick=addToPocket("' + result[1][i][0] + '")>' + result[0][i][0] + '</a>';
            }
        },
        error: function (err) {
            displayErrorAlert("Error retreiving News - Details in console", err);
        }
    });
}

function getLights() {
    //Philips Token - sOoZ7udF4PHgrQukaDM3LpnF5BW9v9DBs5frKQyG

    //Get state of Lights
    $.ajax({
        url: "http://aurora.fiora.li/inbound.php",
        type: "POST",
        data: 'command=lightsState',
        accept: 'application/json',
        contentType: 'application/x-www-form-urlencoded',
        success: function (result) {
            lightOn = result;

            if (result == 1)
                switchImage = onImage;
            else
                switchImage = offImage;

            $('#lightSwitch').attr("src", switchImage);
        },
        error: function (err) {
            displayErrorAlert("Error retreiving state of lights - Details in console", err);
        }
    });
}

function getCalendar() {
    //Get calendar list
    $.ajax({
        url: "http://aurora.fiora.li/inbound.php",
        type: "POST",
        data: 'command=calendar',
        accept: 'application/json',
        contentType: 'application/x-www-form-urlencoded',
        success: function (result) {
            /*var resultJSON = $.parseJSON(result);
            var table = document.getElementById("calendarList");*/

            /*for (var i = 0; i < resultJSON.length; i++) {
                var row = table.insertRow(0);
                var cell1 = row.insertCell(0);
                cell1.innerHTML = '<a class="summaryList" href="javascript:;" onclick="clearTodo(\'' + resultJSON[i].id + '\', this);">' + resultJSON[i].item + "</a>";
            }*/
        },
        error: function (err) {
            displayErrorAlert("Error retreiving calendar - Details in console", err);
        }
    });
}

$(document).ready(function () {
    $(window).on('onunload', function () {
        $(window).scrollTop(0);
    });

    $('#backgroundTopSummary').css('z-index', 30);
    $('#backgroundTopRefresh').css('z-index', 30);

    //Hide the Pocket Alert
    $("#alert_success_placeholder").hide();
    $("#alert_failed_placeholder").hide();

    //Initalize all of the functions on timers
    resetInterval(true);

    $('div.backgroundRefresh').click(function () {
        setTimeout(function () {
            document.getElementById('backgroundRefresh').innerHTML = '<i class="fa fa-refresh fa-1 weatherIcon" aria-hidden="true"></i>';
        }, 2000);

        document.getElementById('backgroundRefresh').innerHTML = '<i class="fa fa-refresh fa-1 fa-pulse fa-3x fa-fw weatherIcon"></i>';
        resetInterval(false);
    });

    $('#lightSwitch').click(function () {
        var message = 'command=toggleLights&on=';

        if (lightOn == 1)
            message = message + "false";
        else
            message = message + "true";

        $.ajax({
            url: "http://aurora.fiora.li/inbound.php",
            type: "POST",
            data: message,
            accept: 'application/json',
            contentType: 'application/x-www-form-urlencoded',
            success: function (result) {
                lightOn = result;

                if (result == 1)
                    switchImage = onImage;
                else
                    switchImage = offImage;

                $('#lightSwitch').attr("src", switchImage);
            },
            error: function (err) {
                displayErrorAlert("Error changing lights - Details in console", err);
            }
        });
    });
});
