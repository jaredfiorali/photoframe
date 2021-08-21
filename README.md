# Photo Frame

A small React/Rust project to display favorite photos and weather information

## External Resources

- Weather icons by <https://basmilius.github.io/weather-icons/index-line.html>
- Weather data provided by the Government of Canada: <https://eccc-msc.github.io/open-data/msc-datamart/amqp_en/>

## Weather Data

The following options are available via the Government of Canada's XML feed

### Current Conditions

The following parameters are provided for the current weather conditions

- Date last updated
- Short description explaining the condition
- Weather Icon # (maps to a specific icon that the Government uses)
- Temperature
- Dew Point
- Humidex
- Pressure
- Visibility
- Relative Humidity (in percent)
- Wind
  - Speed
  - Gust
  - Direction
  - Bearing

### Daily Forecast

The following parameters are provided for upcoming weather conditions. This covers the current day, as well as the next 6 days. These conditions are broken up in day/night for each day of the week. Beyond the typical differences, only the temperature high is listed in the day, and the low is listed in the night.

- Date the forecast was generated
- Regional Normals (Typical temperature range for the area)
- Weather Icon # (maps to a specific icon that the Government uses)
- Short description explaining the condition
- Cloud Precipitation
- Abbreviated description
  - Includes a precipitation unit (% for rain)
- Temperature (high for day, low for night)
- Wind
- Humidex
- Precipitation
  - Start time
  - End time
- UV Index
  - Category (Low, High, Very High)
  - Index
  - Short text description

### Hourly Forecast

The following parameters are provided for upcoming weather conditions. This covers the current day, as well as the next 6 days. These conditions are broken up in day/night for each day of the week. Beyond the typical differences, only the temperature high is listed in the day, and the low is listed in the night.

- Date the forecast was generated
- Abbreviated description
- Weather Icon # (maps to a specific icon that the Government uses)
- Temperature (Single metric, expected temp for that hour)
- Wind
  - Speed
  - Gust
  - Direction
- Humidex
- Windchill
- LOP (Perhaps it's the precipitation? Level of Precipitation?)
