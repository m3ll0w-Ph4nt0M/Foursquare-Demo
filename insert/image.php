<?php

include_once('config/dbh.php');
include_once('include/client_cred.php');
include_once('vendor/hownowstephen/php-foursquare/src/FoursquareApi.php');
ini_set('max_execution_time', 100);
$foursquare = new FoursquareApi($client_id, $client_secret);

#fetch venue ll coordinates from google maps
$sql_all_loc = "SELECT `loc_name` 
                FROM `location`
               ";

$fetch_loc = $dbh->query($sql_all_loc);

$venues = $cur_img_id = $cur_user_id = array();

while($row = $fetch_loc->fetch()){
	#fecth venue id from foursquare
	$place = $row['loc_name'];
	$position = $foursquare->GeoLocate($place);
	$endpoint = 'venues/search?';
	$params = 'll='.$position[0].','.$position[1].'&limit=1';

	$request_date = date('Ymd');
	$base_url = 'https://api.foursquare.com/v2/';
	$endpoint = 'venues/search?';
	$auth = "&client_id=".$client_id."&client_secret=".$client_secret."&v=".$request_date;
	$url = $base_url.$endpoint.$params.$auth;

	$results = file_get_contents($url);
	$json_results = json_decode($results,true);

	$venues = $json_results['response']['venues'];

	#fetch images from instagram with venue id
	foreach($venues as $venue){

		$request_date = date('Ymd');
		$base_url = 'https://api.foursquare.com/v2/';
		$endpoint = 'venues/'.$venue['id'].'/photos?';
		$auth = "&client_id=".$client_id."&client_secret=".$client_secret."&v=".$request_date;
		$url = $base_url.$endpoint.$auth;

		$results = file_get_contents($url);
		$json_results = json_decode($results,true);
		$photos = $json_results['response']['photos']['items'];

		foreach($photos as $photo){			

			#Insert img info
			$img = $loc_id = $source = $url = $user_fname = $user_lname = "";

			if($photo['source']['name']=="Instagram"){

				/**fecth existing photo id's*/
				$query_img_id = "SELECT `loc_img_id`
				          		 FROM `loc_image`
				           		";

				$fetch = $dbh->query($query_img_id);

				while ($row = $fetch ->fetch()) {
				    $cur_img_id[] = $row['loc_img_id'];
				}

				/**fecth existing user id's*/
				$query_user_id = "SELECT `loc_img_user_id`
				          		  FROM `loc_img_user`
				         		 ";

				$fetch = $dbh->query($query_user_id);

				while ($row = $fetch ->fetch()) {
				    $cur_user_id[] = $row['loc_img_user_id'];
				}

				$new_img_array = array($photo['id']);
				$counter_img   = array_diff($new_img_array,$cur_img_id);

				$new_user_array = array($photo['user']['id']);
				$counter_user   = array_diff($new_user_array,$cur_user_id);

				if(count($counter_img) === 1){#compare for new id

					$img        = $photo['id'];
					$loc_name   = $venue['name'];
					$source     = $photo['source']['name'];
					$url        = $photo['prefix'].'original'.$photo['suffix'];
					$user_id    = $photo['user']['id'];
					$user_fname = $photo['user']['firstName'];
					if (array_key_exists('lastName', $photo['user'])){
						$user_lname = $photo['user']['lastName'];
					}else{
						$user_lname = "-";
					}

					$img_insert_query = "INSERT INTO 
										 	`loc_image` (
										 		`loc_img_id`,
										 		`l_name`,
										 		`loc_img_src_name`,
										 		`loc_img_src_url`,
										 		`l_img_user_id`
										 	)
										 VALUES(
										 		'{$img}',
										 		'{$loc_name}',
										 		'{$source}',
										 		'{$url}',
										 		'{$user_id}'
										 	)
										";

					$dbh->exec($img_insert_query);

					if(count($counter_user) === 1){#compare for new id
						$img_insert_query = "INSERT INTO 
										 		`loc_img_user` (
										 		`loc_img_user_id`,
										 		`loc_img_user_fname`,
										 		`loc_img_user_lname`
										 		)
										 	 VALUES(
										 		'{$user_id}',
										 		'{$user_fname}',
										 		'{$user_lname}'
										 		)
										   ";

						$dbh->exec($img_insert_query);
					}else{
						#Do nothing
					}
				}

			}else{
				#Do nothing
			}
		}
	}
}

?>