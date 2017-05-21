<?php
	function connectDB()
	{
		return new PDO('mysql:host=localhost;dbname=parkfinder', 'root', '123456');
	}

	function disconnectDB($db)
	{
		$db = null;
	}

	function showStars($path, $num)
	{
		while ($num > 0) {
			echo "<img src=\"$path\" alt=\"starts\">";
			$num--;
		}	
	}

	function showSuburbOptions()
	{
		$db = connectDB();
		$sql = 'select distinct suburb from parks order by suburb';

		$results = $db->query($sql);	
		foreach ($results as $row) {
			echo "<option value=\"{$row['suburb']}\">{$row['suburb']}</option>";
		}

		disconnectDB($db);
	}

	function showContent($pid, $name, $rating, $street, $suburb, $latitude, $longitude)
	{
		echo '<div class="contentCell" itemscope itemtype="http://schema.org/Place">';
			echo "<span class=\"contentTitle\" itemprop=\"name\">$name</span>";
			
			echo '<div class="contentRating">';
				if ($rating > 0) {
					showStars('imgs/filledStar.svg', $rating);

				} else {
					echo 'No rating';
				}
			echo '</div>';

			echo "<span class=\"contentDescription\" itemprop=\"address\">$street, $suburb</span>";
			echo "<data value=\"$latitude,$longitude,$pid\" itemprop=\"geo\"></data>";
		echo '</div>';
	}

	function searchParks($type, $value)
	{
		if ($type == 'name') {
			$sql = 'select * from parks where name like :value';
			$value = "%$value%";

		} else if ($type == 'suburb') {
			$sql = 'select * from parks where suburb = :value';

		} else if ($type == 'location') {
			$position = explode(',', $value); 
			$lat = $position[0];
			$lon = $position[1];
			$range = 10; // KM

			$pidList = searchByLocation($lat, $lon, $range);
			$pidList = implode(',', $pidList);
			$sql = "select * from parks where pid in ($pidList)";
		
		} else if ($type == 'rating') {
			$sql = 'select * from parks where pid in (
						select pid from (
							select pid, sum(rating)/count(pid) as avgRating from reviews group by pid
						) as t where avgRating >= :value
					)';

		} else if ($type == 'pid') {
			$sql = 'select * from parks where pid = :value';

		} else {
			$sql = 'select * from parks';
		}

		$db = connectDB();
		$stmt = $db->prepare($sql);	
		$stmt->bindValue(':value', $value);
		$stmt->execute();

		$results = $stmt->fetchAll();
        
        if (count($results) > 0) {
            
            foreach ($results as $row) {
                $pid = $row['pid'];
                $name = $row['name'];
                $street = $row['street'];
                $suburb = $row['suburb'];
                $latitude = $row['latitude'];
                $longitude = $row['longitude'];
                $rating = floor(calculateAverageRating($db, $pid));
                
                if ($type != 'pid') {
                    echo "<a href=\"itemPage.php?pid=$pid\">";
                    showContent($pid, $name, $rating, $street, $suburb, $latitude, $longitude);
                    echo '</a>';
                } else {
                    showContent($pid, $name, $rating, $street, $suburb, $latitude, $longitude);
                }
            }
        } else {
            echo "<div id=\"noResults\"><br><span class=\"noResultsText\">No results found.</span></div>";
        }

		disconnectDB($db);
	}
    
    function getParkName($value)
	{
        $sql = 'select name from parks where pid = :value';
        $db = connectDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':value', $value);
        $stmt->execute();
        
        $name = $stmt->fetchColumn();
		echo "<title>$name - Brisbane Park Finder</title>";

        disconnectDB($db);
    }

	function calculateAverageRating($db, $pid)
	{
		$sql = 'select sum(rating)/count(rating) from reviews where pid = :pid';
		$stmt = $db->prepare($sql);
		$stmt->bindValue(':pid', $pid);
		$stmt->execute();

		$averageRating = $stmt->fetchColumn();	
		
		return $averageRating;
	}
	
	function showComments($name, $rating, $comment, $date)
	{
		echo '<div class="commentCell" itemscope itemtype="http://schema.org/Review">';
			echo "<span class=\"commentTitle\" itemprop=\"author\">$name</span>";

			echo '<div class="commentRating" itemprop="reviewRating">';
				showStars('imgs/filledStar.svg', $rating);
			echo '</div>';

			echo '<div class="commentDate">';
				echo "<span>$date</span>";
			echo '</div>';
			
			echo "<span class=\"comment\" itemprop=\"reviewBody\">$comment</span>";
		echo '</div>';
	}

	function searchComments($pid)
	{
		$sql = 'select first_name, last_name, rating, date, comment
				from reviews, members
				where reviews.uid = members.uid and pid = :pid';

		$db = connectDB();
		$stmt = $db->prepare($sql);
		$stmt->bindValue(':pid', $pid);
		$stmt->execute();

		$results = $stmt->fetchAll();	

		echo '<div id="commentList">';

		foreach ($results as $row) {
			$name = "{$row['first_name']} {$row['last_name']}";
			$rating = $row['rating'];
			$date = $row['date'];
			$comment = $row['comment'];

			showComments($name, $rating, $comment, $date);
		}

		echo '</div>';
		disconnectDB($db);

	}

	function uploadComment($uid, $pid, $comment, $rating, $date)
	{
		$sql = 'insert into reviews
				values(null, :uid, :pid, :comment, :date, :rating)';

		$db = connectDB();
		$stmt = $db->prepare($sql);

		$stmt->bindValue(':uid', $uid);
		$stmt->bindValue(':pid', $pid);
		$stmt->bindValue(':comment', $comment);
		$stmt->bindValue(':date', $date);
		$stmt->bindValue(':rating', $rating);

		$stmt->execute();
		disconnectDB($db);
	}

	/*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
	/*::                                                                         :*/
	/*::  This routine calculates the distance between two points (given the     :*/
	/*::  latitude/longitude of those points). It is being used to calculate     :*/
	/*::  the distance between two locations using GeoDataSource(TM) Products    :*/
	/*::                                                                         :*/
	/*::  Definitions:                                                           :*/
	/*::    South latitudes are negative, east longitudes are positive           :*/
	/*::                                                                         :*/
	/*::  Passed to function:                                                    :*/
	/*::    lat1, lon1 = Latitude and Longitude of point 1 (in decimal degrees)  :*/
	/*::    lat2, lon2 = Latitude and Longitude of point 2 (in decimal degrees)  :*/
	/*::    range = the range in KM you desire for results                       :*/
	/*::                  					                                     :*/
	/*::  Worldwide cities and other features databases with latitude longitude  :*/
	/*::  are available at http://www.geodatasource.com                          :*/
	/*::                                                                         :*/
	/*::         GeoDataSource.com (C) All Rights Reserved 2015		   		     :*/
	/*::                                                                         :*/
	/*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
	function calculateDistance($lat1, $lon1, $lat2, $lon2, $range) {

  		$theta = $lon1 - $lon2;
  		$dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
  		$dist = acos($dist);
  		$dist = rad2deg($dist);
  		$miles = $dist * 60 * 1.1515;

		return ($miles * 1.609344) <= $range;
	}

	function searchByLocation($lat, $lon, $range)
	{
		$sql = 'select * from parks';
		$desiredPidList = array();

		$db = connectDB();
		$results = $db->query($sql);
		
		foreach ($results as $row) {
            $pid = $row['pid'];
            $latitude = $row['latitude'];
            $longitude = $row['longitude'];

			if (calculateDistance($lat, $lon, $latitude, $longitude, $range)) {
				$desiredPidList[] = $pid;		
			}
		}

		disconnectDB($db);
		return $desiredPidList;
	}
?>
