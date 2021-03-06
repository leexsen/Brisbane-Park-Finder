<?php
    
    // Ensures the session is active on every page, as functions.php is included on all pages
    session_start();
    
    // Connects to the fastapps database
	function connectDB()
	{
		return new PDO('mysql:host=localhost;dbname=parkfinder', 'root', '123456');
	}
    
    // Disconnects from the fastapps database
	function disconnectDB($db)
	{
		$db = null;
	}

	// This method used to display a numbers of star based on the value of num
	function showStars($path, $num)
	{
		while ($num > 0) {
			echo "<img src=\"$path\" alt=\"starts\">";
			$num--;
		}	
	}

	// Fills the combo box from the suburbs in the database
	function showSuburbOptions()
	{
		$db = connectDB();
        
        // 'order by suburb' lists them in alphabetical order
		$sql = 'select distinct suburb from items order by suburb';

		$results = $db->query($sql);	
		foreach ($results as $row) {
			echo "<option value=\"{$row['suburb']}\">{$row['suburb']}</option>";
		}

		disconnectDB($db);
	}

	// It generates a list of parks in result page
	function showContent($pid, $name, $rating, $street, $suburb, $latitude, $longitude)
	{
        // Only add the Place meta/microdata if the user is on the results page
        if (basename($_SERVER['PHP_SELF']) == 'resultPage.php') {
            echo '<div class="contentCell" itemscope itemtype="http://schema.org/Place">';
            echo "<span class=\"contentTitle\" itemprop=\"name\">$name</span>";
        } else {
            echo '<div class="contentCell">';
            echo "<span class=\"contentTitle\" itemprop=\"itemReviewed\">$name</span>";
        }
        
        // Add the rating
		echo '<div class="contentRating">';
		if ($rating > 0) {
			showStars('imgs/filledStar.svg', $rating);

		} else {
			echo 'No rating';
		}
		echo '</div>';

        // Only add the address microdata if the user is on the results page
        if (basename($_SERVER['PHP_SELF']) == 'resultPage.php') {
            echo "<span class=\"contentDescription\" itemprop=\"address\">$street, $suburb</span>";
        } else {
            echo "<span class=\"contentDescription\">$street, $suburb</span>";
        }
			echo "<data value=\"$latitude,$longitude,$pid\"></data>";
		echo '</div>';
	}

	// The search function based on the name, suburb, location, rating and pid (park's id)
    function searchParks($type, $value)
    {
        // Sql query based on searching by name, with fuzzy searching enabled
        if ($type == 'name') {
            $sql = 'select * from items where name like :value';
            $value = "%$value%";
        
        } else if ($type == 'suburb') {
            // Sql query based on searching by suburb
            $sql = 'select * from items where suburb = :value';
            
        } else if ($type == 'location') {
            // Sql query based on searching by location
            $position = explode(',', $value);
            $lat = $position[0];
            $lon = $position[1];
            $range = 10; // KM
            
            $pidList = searchByLocation($lat, $lon, $range);

			// convert the array to string seperated by comma
            $pidList = implode(',', $pidList);
			$sql = "select * from items where pid in ($pidList)";
        
        } else if ($type == 'rating') {
            // Sql query based on searching by rating
            
			// Calculates the average rating for each park, which is greater or equal to the variable value
			// and get their pid 
            $sql = 'select * from items where pid in (
            			select pid from (
                             select pid, sum(rating)/count(pid) as avgRating from reviews group by pid
                        ) as t where avgRating >= :value
            		)';
            
        } else if ($type == 'pid') {
            // Sql query based on searching by the park id
            $sql = 'select * from items where pid = :value';
        
        } else {
            // Get all the items for the All Parks Page
            $sql = 'select * from items';
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
                
				// Show it in the result page, otherwise, in the item page
                if ($type != 'pid') {
                    echo "<a href=\"itemPage.php?pid=$pid\">";
                    showContent($pid, $name, $rating, $street, $suburb, $latitude, $longitude);
                    echo '</a>';

                } else {
                    showContent($pid, $name, $rating, $street, $suburb, $latitude, $longitude);
                }
            }
        } else {
            // Show a 'no results found' message if there are no matching results
            echo "<div id=\"noResults\"><br><span class=\"noResultsText\">No results found.</span></div>";
        }
        
        disconnectDB($db);
    }
    
    // Get the name of the park for the tab title
    function getParkName($value) {
        $sql = 'select name from items where pid = :value';
        $db = connectDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':value', $value);
        $stmt->execute();
        
        $name = $stmt->fetchColumn();
        echo "<title>$name - Brisbane Park Finder</title>";
        
        disconnectDB($db);
    }

	// Calcaulate average rating for the park whose pid is the value of the variable pid
	function calculateAverageRating($db, $pid)
	{
		$sql = 'select sum(rating)/count(rating) from reviews where pid = :pid';
		$stmt = $db->prepare($sql);
		$stmt->bindValue(':pid', $pid);
		$stmt->execute();

		$averageRating = $stmt->fetchColumn();	
		
		return $averageRating;
	}
	
    
	// Display the structure of comments
    function showComments($name, $rating, $comment, $date)
    {
        echo '<div class="commentCell">';
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
    
	// Query comments based on the value of the variable pid
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
        
        if (count($results) > 0) {

        	foreach ($results as $row) {
            	$name = "{$row['first_name']} {$row['last_name']}";
            	$rating = $row['rating'];
            	$date = $row['date'];
            	$comment = $row['comment'];
            
            	showComments($name, $rating, $comment, $date);
        	}
		} else {
            echo "<div id=\"noResults\"><br><span class=\"noResultsText\">No comments found.</span></div>";
		}
        
        echo '</div>';
        disconnectDB($db);
        
    }

	// Add the user's comment into the database
    function uploadComment($uid, $pid, $comment, $rating, $date)
    {
        $sql = 'insert into reviews
        		values(null, :uid, :pid, :comment, :date, :rating)';

        $db = connectDB();
        $stmt = $db->prepare($sql);
        
        $stmt->bindValue(':uid', $uid);
        $stmt->bindValue(':pid', $pid);
        $stmt->bindValue(':comment', strip_tags($comment));
        $stmt->bindValue(':date', $date);
        $stmt->bindValue(':rating', $rating);
        
        $stmt->execute();
        disconnectDB($db);
    }
    
    // Adds a new user ot the database
    function addUser($fName, $lName, $email, $password, $birthday) {
        $sql = 'insert into members
        		values(null, :fName, :lName, :email, :salt, SHA2(CONCAT(:password, :salt), 0), :birthday)';
        
        // Generate a random salt value for the password
        $salt = uniqid();
        
        $db = connectDB();
        $stmt = $db->prepare($sql);
        
        $stmt->bindValue(':fName', $fName);
        $stmt->bindValue(':lName', $lName);
        $stmt->bindValue(':email', $email);
        $stmt->bindValue(':salt', $salt);
        $stmt->bindValue(':password', $password);
        $stmt->bindValue(':birthday', $birthday);
        
        $stmt->execute();
        
        disconnectDB($db);
    }
    
    // Gets the user who has just registered or logged in so the session value can be set
    function getUserID($email) {
        $sql = 'select uid from members where email = :email';
        
        $db = connectDB();
        $stmt = $db->prepare($sql);
        
        $stmt->bindValue(':email', $email);
        
        $stmt->execute();
        
        return $stmt->fetchColumn();
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

	// Given a certain range, search parks based on user's location
    function searchByLocation($lat, $lon, $range)
    {
        $sql = 'select * from items';
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
