<?php

namespace App\Controllers;

use App\Controllers\Base\BaseController;
use App\Entities\Curl;
use App\Entities\News;
use App\Services\ConfigService;

class NewsController extends BaseController {

	const IMAGE_TYPES = [
		'image/jpg' => [
			'image_create_function' => 'imagecreatefromjpeg',
			'image_save_function' => 'imagejpeg',
			'new_image_extension' => 'jpeg',
		],
		'image/jpeg' => [
			'image_create_function' => 'imagecreatefromjpeg',
			'image_save_function' => 'imagejpeg',
			'new_image_extension' => 'jpeg',
		],
		'image/png' => [
			'image_create_function' => 'imagecreatefrompng',
			'image_save_function' => 'imagepng',
			'new_image_extension' => 'png',
		],
		'image/gif' => [
			'image_create_function' => 'imagecreatefromgif',
			'image_save_function' => 'imagegif',
			'new_image_extension' => 'gif',
		],
	];

	/** @inheritdoc	 */
	public function getAction($param = null) {

		// Prepare and execute the MySQL statement
		$db_results = $this->db->fetchOne("CALL getNews()");

		// Create a new news object from our DB data
		$news = new News($db_results);

		// Return our data
		return $news->data;
	}

	/** @inheritdoc	 */
	public function updateAction() {

		// Create our cURL object for communicating with our third party service
		$ch = new Curl();

		// Execute the cURL request by setting the cURL options: url, # of POST vars, POST data
		$result = $ch->execute(array (
			CURLOPT_URL => "https://newsapi.org/v2/top-headlines?pageSize=8&language=en"."&sources=". ConfigService::get_value('news_sources')."&apiKey=". ConfigService::get_value('news_api_key'),
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_HEADEROPT => "Accept-Encoding: gzip"
		));

		// Confirm that the cURL was successful
		if ($result) {

			// Convert our received JSON to and array
			$news = json_decode($result);

			foreach ($news->articles as $article) {

				// Download our image from the original URL
				$image_from_url = file_get_contents($article->urlToImage);

				// Create an image resource to temporarily manipulate the image
				$image_original = getimagesizefromstring($image_from_url);

				// Assign the important properties to variables for access later
				$width = $image_original[0] ?? null;
				$height = $image_original[1] ?? null;
				$mime = $image_original['mime'] ?? null;

				// Confirm we were able to extract all required image properties
				if ($width and $height and $mime) {

					// Get a reference to our array which describes how to handle variable media types
					$image_type = $this::IMAGE_TYPES[$mime];

					// Shrink our width to match the current aspect ratio
					$new_height = 120;
					$new_width = $width * ($new_height/$height);

					// Create our new resized image
					$image_original_binary = imagecreatefromstring($image_from_url);
					$image_canvas = imagecreatetruecolor($new_width, $new_height);
					imagecopyresampled($image_canvas, $image_original_binary, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

					// Halt output buffering while we get the image contents for base64 conversion
					ob_start();
						$image_resize = $image_type['image_save_function']($image_canvas);
						$contents = ob_get_contents();
					ob_end_clean();

					// Base64 encode for storing in DB
					$image_base64 = base64_encode($contents);

					// Replace our image with a base64 encoded string
					$article->urlToImage = $image_base64;
				}
				else {

					// TODO: Some sort of error here
				}
			}

			// Encode the data for DB insert
			$data = addslashes(json_encode($news));

			// Prepare and execute the MySQL statement
			$this->db->execute("CALL setNews('$data')");

			// Let the FE know that it worked
			echo "Success";
		}
		else {

			// Let the FE know that it failed...
			echo "Failed";
		}
	}
}
