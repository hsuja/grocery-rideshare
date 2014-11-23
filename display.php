<!DOCTYPE html>
<html>
<head>
	<title>Display</title>
	<meta charset="utf-8"/>
	<script src="jquery-1.11.0.min.js"></script>
	<script>
		$(document).ready(

				function(){

				
				}
		);

		function getDistanceFromLatLonInKm(lat1,lon1,lat2,lon2) {
			  var R = 6371; // Radius of the earth in km
			  var dLat = deg2rad(lat2-lat1);  // deg2rad below
			  var dLon = deg2rad(lon2-lon1); 
			  var a = 
			    Math.sin(dLat/2) * Math.sin(dLat/2) +
			    Math.cos(deg2rad(lat1)) * Math.cos(deg2rad(lat2)) * 
			    Math.sin(dLon/2) * Math.sin(dLon/2)
			    ; 
			  var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a)); 
			  var d = R * c; // Distance in km
			  return d;
		}

		function deg2rad(deg) {
		  return deg * (Math.PI/180)
		}

	</script>

</head>
<body>

<div class="wrapper">

<?php

ini_set('display_errors', 1);

$user_id = 1;
$user_lat = 10.22222222;
$user_long = 10.33333333;


function getDistance($lat1,$lon1,$lat2,$lon2){
  $R = 6371;
  $dLat = deg2rad($lat2 - $lat1);
  $dLon = deg2rad($lon2 - $lon1);
  $a =
  sin($dLat/2) * sin($dLat/2) +
  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
  sin($dLon/2) * sin($dLon/2);
  $c = 2 * atan2(sqrt($a),sqrt(1-$a));
  $d = $R * $c;
  return $d;
}


// Access rideshares
$rides_sqli = new mysqli("oniddb.cws.oregonstate.edu","hsuja-db","pgt3mQj3D6ck873q","hsuja-db");
	if(!$rides_sqli || $rides_sqli->connect_errno){

		echo "Connection error".$rides_sqli->connect_errno." ".$rides_sqli->connect_error;

	}else{


		if(!($stmt = $rides_sqli->prepare("SELECT l.id, l.GPS_long, l.GPS_lat FROM rideshares r INNER JOIN locations l ON r.pickup_id = l.id "))){
			echo "Prepare failed: (".$rides_sqli->errno.") ".$mysqli->error;
		}

		if(!($stmt->execute())){
				echo "Execute failed: (".$stmt->errno.") ".$stmt->error;
		}

		$stmt->store_result();

		$stmt->bind_result($loc_id_res, $GPS_long_res, $GPS_lat_res);

		echo "<h3>Available Rideshares</h3>";

	

			


			while($stmt->fetch()){

				//$distance = getDistanceFromLatLonInKm($user_lat,$user_long, $GPS_lat_res, $GPS_long_res);

				$distance = getDistance($user_lat,$user_long, $GPS_lat_res, $GPS_long_res);


					if(!($stmt2 = $rides_sqli->prepare("INSERT INTO distances(user_id, location_id, distance) VALUES (?, ?, ?)"))){
							echo "Prepare failed: (".$rides_sqli->errno.") ".$mysqli->error;
					}	
					

					if(!($stmt2->bind_param("iid", $user_id, $loc_id_res, $distance))){
						echo "Binding parameters failed: (" . $stmt2->errno . ") " . $stmt2->error;
					}
		
					if(!($stmt2->execute())){
							echo "Execute failed: (".$stmt2->errno.") ".$stmt2->error;
					}

					
					$stmt2->store_result();

					$stmt2->close();
/*
				echo "<p>$loc_id_res GPS long = $GPS_long_res</p>";
				echo "<p>$loc_id_res GPS lat = $GPS_lat_res</p>";
				echo "<p>$loc_id_res distance = $distance</p>";
			
				/*
				echo "<tr><td>$modelYear_res</td><td>$make_res</td><td>$model_res</td><td>$trans_res</td><td>$engine_res</td><td>$mileage_res</td><td class='noborder'><form action='car_page.php' method='POST'><input type='hidden' name='year' value='$modelYear_res'><input type='hidden' name='make' value='$make_res'><input type='hidden' name='model' value='$model_res'><button type='submit' name='carID' value='$carID_res'>View/Edit</button></form></td></tr>";
*/
			}

			
			//echo "</table>";

		
			
		$stmt->close();


		if(!($stmt3 = $rides_sqli->prepare("SELECT DISTINCT r.id, pickup_l.name, dest_l.name, r.owner_id, r.capacity, d.distance FROM rideshares r INNER JOIN locations pickup_l ON r.pickup_id = pickup_l.id INNER JOIN locations dest_l ON r.destination_id = dest_l.id INNER JOIN distances d ON pickup_l.id = d.location_id ORDER BY d.distance"))){
			echo "Prepare failed: (".$rides_sqli->errno.") ".$mysqli->error;
		}

		if(!($stmt3->execute())){
				echo "Execute failed: (".$stmt3->errno.") ".$stmt3->error;
		}

		$stmt3->store_result();

		$stmt3->bind_result($rid_res, $pickup_name_res, $dest_name_res, $owner_res, $capacity_res, $distance_res);
	
		echo "<p>User Latitude: $user_lat</p>";
		echo "<p>User Longitude: $user_long</p>";


		// Diplay table of rideshares	
		echo "<table><tr id='tablehead'><td>Pickup</td><td>Destination</td><td>Owner</td><td>Capacity</td><td>Distance (km)</td><td class='noborder'></td></tr>";

		while($stmt3->fetch()){

			echo "<tr><td>$pickup_name_res</td><td>$dest_name_res</td><td>$owner_res</td><td>$capacity_res</td><td>$distance_res</td></tr>";

		}

		echo "</table>";

		$stmt3->close();

	}

?>

</div>
</body></html>