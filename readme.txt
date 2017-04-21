 YouTube Video Mapper

 index.html - main page providing search boxes for video search keywords, street, city, state, zip code, and location radius and displays results on map once search is made

 js/script.js - javascript making ajax calls to hit google geocoding api, YouTube data api, and then creating a map using the google maps api

 assets/style.css - css styling for index.html

 assets/submit-location.php - php hitting google geocoding api, uses user entered search terms to search api and echos results

 assets/submit-video.php - php hitting YouTube Data api, uses user entered search keywords and radius as well as lat/long from google geocoding api and echos back name of video, url-id, and coordinates

 composer.json, composer.lock, and everything in vendor - files autoloaded by Composer to use YouTube Data API

had to run this to use YouTube Data API
 /**
  * Library Requirements
  *
  * 1. Install composer (https://getcomposer.org)
  * 2. On the command line, change to this directory (api-samples/php)
  * 3. Require the google/apiclient library
  *    $ composer require google/apiclient:~2.0
  */
