<?php
#get search terms and formatted coordinates
$search = $_POST['search'];
$lat = $_POST['lat'];
$lng = $_POST['lng'];
$locationRadius = $_POST['locationRadius'];

/**
 * Library Requirements
 *
 * 1. Install composer (https://getcomposer.org)
 * 2. On the command line, change to this directory (api-samples/php)
 * 3. Require the google/apiclient library
 *    $ composer require google/apiclient:~2.0
 */
if (!file_exists(__DIR__ . '/vendor/autoload.php')) {
  throw new \Exception('please run "composer require google/apiclient:~2.0" in "' . __DIR__ .'"');
}

require_once __DIR__ . '/vendor/autoload.php';

// This code will execute if the user entered a search query in the form
// and submitted the form. Otherwise, the page displays the form above.
if (isset($search) && isset($lat) && isset($lng) && isset($locationRadius)
  && $search != '' && $lat != '' && $lng != '' && $locationRadius != '') {
	/*
	 * Set $DEVELOPER_KEY to the "API key" value from the "Access" tab of the
	 * {{ Google Cloud Console }} <{{ https://cloud.google.com/console }}>
	 * Please ensure that you have enabled the YouTube Data API for your project.
	 */
  $DEVELOPER_KEY = 'AIzaSyBDHMNRqXAzSFbBaccaklyI2gI0RCCvIfM';

  $client = new Google_Client();
  $client->setDeveloperKey($DEVELOPER_KEY);

  // Define an object that will be used to make all API requests.
  $youtube = new Google_Service_YouTube($client);

  try {

    // Call the search.list method to retrieve results matching the specified
    // query term.
    $searchResponse = $youtube->search->listSearch('id,snippet', array(
      'q' => $search,
      'maxResults' => "10",
      'type' => "video",
      'order' => "viewCount",
      'location' => $lat . "," . $lng,
      'locationRadius' => $locationRadius
    ));

		$videoResults = array();
    # Merge video ids
    foreach ($searchResponse['items'] as $searchResult) {
      array_push($videoResults, $searchResult['id']['videoId']);
    }
    $videoIds = join(',', $videoResults);

    # Call the videos.list method to retrieve location details for each video.
    $videosResponse = $youtube->videos->listVideos('id,snippet, recordingDetails', array(
    'id' => $videoIds,
    ));

    $videos = array();
		$i = 0;
    // create array of matching videos including title, id, lat, long
		foreach ($videosResponse['items'] as $videoResult) {
      $videos[$i][0] = $videoResult['snippet']['title'];
			$videos[$i][1] = $videoResult['id'];
			$videos[$i][2] = $videoResult['recordingDetails']['location']['latitude'];
			$videos[$i][3] = $videoResult['recordingDetails']['location']['longitude'];
			$i++;
    }

		//echo videos array
		echo json_encode($videos);

  } catch (Google_Service_Exception $e) {
    $htmlBody = sprintf('<p>A service error occurred: <code>%s</code></p>',
    $htmlBody .= htmlspecialchars($e->getMessage()));
		echo $htmlBody;
  } catch (Google_Exception $e) {
    $htmlBody = sprintf('<p>An client error occurred: <code>%s</code></p>',
    $htmlBody .= htmlspecialchars($e->getMessage()));
		echo $htmlBody;
  }
}
?>
