package main

import (
  "database/sql"
  "encoding/json"
  "encoding/base64"
  "fmt"
  "io/ioutil"
  "net/http"
  "time"
  "strings"
  "bytes"
  "image"
  _ "image/gif"
  "image/jpeg"
  _ "image/png"

  _ "github.com/go-sql-driver/mysql"
  "github.com/nfnt/resize"
)

const (
  hostDB           = "localhost"
  usernameDB       = "apache"
  passwordDB       = "fb8bda50b9f86b229be154a8e32edfc7"
  databaseDB       = "Dashboard"
  dbString         = usernameDB + ":" + passwordDB + "@/" + databaseDB
  darkskyAPIKey    = "ced23c715fea437145b3182bf1065f0c"
  weatherLatitude  = "43.595844"
  weatherLongitude = "-79.708389"
  // pocketAccessTokens = ["f1ee6595-6a11-198c-356c-0acf23", "6bdcd289-7c70-43bb-05c7-47252b"]
  // pocketConsumerKey = "69195-1cc1c210fb7e3db337ca2b78"
  newsSources     = "cbc-news,the-new-york-times,bbc-news"
  newsAPIKey      = "fa9bfe5bd5274c81a2dc4579d0e74620"
  philipsHueToken = "sOoZ7udF4PHgrQukaDM3LpnF5BW9v9DBs5frKQyG"
)

// Photo struct for JSON formatting
type Photo struct {
  Photo        string `json:"photo"`
  LocationDate string `json:"location"`
}

// Version struct for JSON formatting
type Version struct {
  Version string `json:"version"`
}

// Weather struct for JSON formatting
type Weather struct {
  Latitude  float64 `json:"latitude"`
  Longitude float64 `json:"longitude"`
  Timezone  string  `json:"timezone"`
  Currently struct {
    Time                 int     `json:"time"`
    Summary              string  `json:"summary"`
    Icon                 string  `json:"icon"`
    NearestStormDistance int     `json:"nearestStormDistance"`
    NearestStormBearing  int     `json:"nearestStormBearing"`
    PrecipIntensity      int     `json:"precipIntensity"`
    PrecipProbability    int     `json:"precipProbability"`
    Temperature          float64 `json:"temperature"`
    ApparentTemperature  float64 `json:"apparentTemperature"`
    DewPoint             float64 `json:"dewPoint"`
    Humidity             float64 `json:"humidity"`
    Pressure             float64 `json:"pressure"`
    WindSpeed            float64 `json:"windSpeed"`
    WindGust             float64 `json:"windGust"`
    WindBearing          int     `json:"windBearing"`
    CloudCover           float64 `json:"cloudCover"`
    UvIndex              int     `json:"uvIndex"`
    Visibility           float64 `json:"visibility"`
    Ozone                float64 `json:"ozone"`
  } `json:"currently"`
  Hourly struct {
    Summary string `json:"summary"`
    Icon    string `json:"icon"`
    Data    []struct {
      Time                int     `json:"time"`
      Summary             string  `json:"summary"`
      Icon                string  `json:"icon"`
      PrecipIntensity     float64 `json:"precipIntensity"`
      PrecipProbability   float64 `json:"precipProbability"`
      PrecipType          string  `json:"precipType,omitempty"`
      Temperature         float64 `json:"temperature"`
      ApparentTemperature float64 `json:"apparentTemperature"`
      DewPoint            float64 `json:"dewPoint"`
      Humidity            float64 `json:"humidity"`
      Pressure            float64 `json:"pressure"`
      WindSpeed           float64 `json:"windSpeed"`
      WindGust            float64 `json:"windGust"`
      WindBearing         int     `json:"windBearing"`
      CloudCover          float64 `json:"cloudCover"`
      UvIndex             int     `json:"uvIndex"`
      Visibility          float64 `json:"visibility"`
      Ozone               float64 `json:"ozone"`
    } `json:"data"`
  } `json:"hourly"`
  Daily struct {
    Summary string `json:"summary"`
    Icon    string `json:"icon"`
    Data    []struct {
      Time                        int     `json:"time"`
      Summary                     string  `json:"summary"`
      Icon                        string  `json:"icon"`
      SunriseTime                 int     `json:"sunriseTime"`
      SunsetTime                  int     `json:"sunsetTime"`
      MoonPhase                   float64 `json:"moonPhase"`
      PrecipIntensity             float64 `json:"precipIntensity"`
      PrecipIntensityMax          float64 `json:"precipIntensityMax"`
      PrecipIntensityMaxTime      int     `json:"precipIntensityMaxTime"`
      PrecipProbability           float64 `json:"precipProbability"`
      PrecipType                  string  `json:"precipType,omitempty"`
      TemperatureHigh             float64 `json:"temperatureHigh"`
      TemperatureHighTime         int     `json:"temperatureHighTime"`
      TemperatureLow              float64 `json:"temperatureLow"`
      TemperatureLowTime          int     `json:"temperatureLowTime"`
      ApparentTemperatureHigh     float64 `json:"apparentTemperatureHigh"`
      ApparentTemperatureHighTime int     `json:"apparentTemperatureHighTime"`
      ApparentTemperatureLow      float64 `json:"apparentTemperatureLow"`
      ApparentTemperatureLowTime  int     `json:"apparentTemperatureLowTime"`
      DewPoint                    float64 `json:"dewPoint"`
      Humidity                    float64 `json:"humidity"`
      Pressure                    float64 `json:"pressure"`
      WindSpeed                   float64 `json:"windSpeed"`
      WindGust                    float64 `json:"windGust"`
      WindGustTime                int     `json:"windGustTime"`
      WindBearing                 int     `json:"windBearing"`
      CloudCover                  float64 `json:"cloudCover"`
      UvIndex                     int     `json:"uvIndex"`
      UvIndexTime                 int     `json:"uvIndexTime"`
      Visibility                  float64 `json:"visibility,omitempty"`
      Ozone                       float64 `json:"ozone"`
      TemperatureMin              float64 `json:"temperatureMin"`
      TemperatureMinTime          int     `json:"temperatureMinTime"`
      TemperatureMax              float64 `json:"temperatureMax"`
      TemperatureMaxTime          int     `json:"temperatureMaxTime"`
      ApparentTemperatureMin      float64 `json:"apparentTemperatureMin"`
      ApparentTemperatureMinTime  int     `json:"apparentTemperatureMinTime"`
      ApparentTemperatureMax      float64 `json:"apparentTemperatureMax"`
      ApparentTemperatureMaxTime  int     `json:"apparentTemperatureMaxTime"`
    } `json:"data"`
  } `json:"daily"`
  Offset int `json:"offset"`
}

// News struct for JSON formatting
type News struct {
  Status       string `json:"status"`
  TotalResults int    `json:"totalResults"`
  Articles     []struct {
    Source struct {
      ID   string `json:"id"`
      Name string `json:"name"`
    } `json:"source"`
    Author      string    `json:"author"`
    Title       string    `json:"title"`
    Description string    `json:"description"`
    URL         string    `json:"url"`
    URLToImage  string    `json:"urlToImage"`
    PublishedAt time.Time `json:"publishedAt"`
  } `json:"articles"`
}

// Open a connection to the DB
var db, dbErr = sql.Open("mysql", dbString)

func main() {

  http.HandleFunc("/getPhoto", getPhoto)
  http.HandleFunc("/getWeather", getWeather)
	http.HandleFunc("/getNews", getNews)
	http.HandleFunc("/getReminders", getReminders)
  http.HandleFunc("/getVersion", getVersion)

  http.HandleFunc("/updateWeather", updateWeather)
  http.HandleFunc("/updateNews", updateNews)

  if err := http.ListenAndServe(":8080", nil); err != nil {
    panic(err)
  }
}

func getVersion(w http.ResponseWriter, r *http.Request) {

  // Get Git Version from git 'heads' file
  dat, err := ioutil.ReadFile("/var/www/photoframe/.git/refs/heads/master")
  checkErr(err)

  version := string(dat[:len(dat)-1])

  jData, err := json.Marshal(Version{version})
  checkErr(err)

  sendResponse(w, jData)
}

func getNews(w http.ResponseWriter, r *http.Request) {

  // Get News from database
  rows, err := db.Query("CALL getNews()")
  checkErr(err)

  for rows.Next() {
    var news string
    err = rows.Scan(&news)
    checkErr(err)

    sendResponse(w, []byte(news))
  }

  rows.Close()
}

func getReminders(w http.ResponseWriter, r *http.Request) {

  // // Get News from database
  // rows, err := db.Query("CALL getDates()")
  // checkErr(err)

  // for rows.Next() {
  //   var date string
  //   err = rows.Scan(&date)
  //   checkErr(err)
	// }

	// sendResponse(w, []byte(news))

  // rows.Close()
}

func getWeather(w http.ResponseWriter, r *http.Request) {

  // Get Weather from database
  rows, err := db.Query("CALL getWeather()")
  checkErr(err)

  for rows.Next() {
    var weather string
    err = rows.Scan(&weather)
    checkErr(err)

    sendResponse(w, []byte(weather))
  }

  rows.Close()
}

func getPhoto(w http.ResponseWriter, r *http.Request) {

  // Get Photo from database
  rows, err := db.Query("CALL randomPhoto()")
  checkErr(err)

  for rows.Next() {
    var photo string
    var location string
    var date string
    err = rows.Scan(&photo, &location, &date)
    checkErr(err)

    prettyDate, err := time.Parse("2006-01-02 15:04:05", date)
    checkErr(err)

    locationDate := fmt.Sprintf("%s - %s", location, prettyDate.Format("January 2006"))

    jData, err := json.Marshal(Photo{photo, locationDate})
    checkErr(err)

    sendResponse(w, jData)
  }

  rows.Close()
}

func updateWeather(w http.ResponseWriter, r *http.Request) {

  // Generate our URL
  url := "https://api.darksky.net/forecast/" + darkskyAPIKey + "/" + weatherLatitude + "," + weatherLongitude + "?units=ca&exclude=minutely,flags,alerts"

  // Create our http client which will initiate our http get
  var httpClient = &http.Client{
    Timeout: time.Second * 10,
  }

  // Execute and save the response
  resp, err := httpClient.Get(url)
  checkErr(err)

  //Convert the response body to bytes (and string eventually)
  weatherBytes, err := ioutil.ReadAll(resp.Body)
  checkErr(err)
  weatherString := string(weatherBytes)

  // Insert Weather into database
  _, err = db.Exec("CALL setWeather('" + weatherString + "')")
  checkErr(err)

  resp.Body.Close()
}

func updateNews(w http.ResponseWriter, r *http.Request) {

  // Generate our URL
  url := "https://newsapi.org/v2/top-headlines?language=en&pageSize=10&sources=" + newsSources + "&apiKey=" + newsAPIKey

  // Create our http client which will initiate our http get
  var httpClient = &http.Client{
    Timeout: time.Second * 10,
  }

  // Execute and save the response
  resp, err := httpClient.Get(url)
  checkErr(err)
  defer resp.Body.Close()

  // Convert the response body to bytes (and string eventually)
  newsBytes, err := ioutil.ReadAll(resp.Body)
  checkErr(err)

  // Create an object from our incoming news in order to easily manipulate
  newsObject := News{}
  json.Unmarshal([]byte(newsBytes), &newsObject)

  // Loop through each article in order to process the images
  for index, article := range newsObject.Articles {

    // Assume that it's going to be a jpg
    ext := "jpg"

    // Retreive the image
    resp, err := httpClient.Get(article.URLToImage)
    checkErr(err)

    // Check to see if the file has a trailing gif (extension)
    if len(strings.TrimSuffix(article.URLToImage, ".gif")) != len(article.URLToImage) {
      // This ain't no jpg
      ext = "gif"
    }

    // Create an image object to house the incoming image
    image, _, err := image.Decode(resp.Body)
    checkErr(err)

    // Resize the image to 80x80 thumbnail size
    thumbnail := resize.Resize(0, 100, image, resize.NearestNeighbor)

    // Convert our thumbnail to bytes buffer for base64 encoding
    buf := new(bytes.Buffer)
    err = jpeg.Encode(buf, thumbnail, nil)
    thumbnailBytes := buf.Bytes()

    // Now encode the article image
    image64 := base64.StdEncoding.EncodeToString(thumbnailBytes)

    // Add this base64 image to the cached JSON response
    newsObject.Articles[index].URLToImage = "url(data:image/" + ext + ";base64," + image64 + ")"
  }

  // Convert our news object to a JSON string for storage
  newsBytes, err = json.Marshal(newsObject)
  checkErr(err)
  newsString := string(newsBytes)

  // Double up on single quotes, because if we leave them alone it can escape the mysql query
  newsString = strings.Replace(newsString, `'`, `\'`, -1)
  newsString = strings.Replace(newsString, `"`, `\"`, -1)

  // Insert News Feed into database
  _, err = db.Exec("CALL setNews('" + newsString + "')")
  checkErr(err)
}

func sendResponse(w http.ResponseWriter, jData []byte) {

  w.Header().Set("Content-Type", "application/json")
  w.Header().Set("Access-Control-Allow-Origin", "*")
  w.WriteHeader(http.StatusOK)
  w.Write(jData)
}

func checkErr(err error) {
  if err != nil {
    panic(err)
  }
}
