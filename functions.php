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

		} else if ($type == 'suburb') {
			$sql = 'select * from parks where suburb = :value';

		} else if ($type == 'location') {
		
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
                $rating = round(calculateAverageRating($db, $pid));
                
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
    
    function getParkName($value) {
        $sql = 'select * from parks where pid = :value';
        $db = connectDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':value', $value);
        $stmt->execute();
        
        $results = $stmt->fetchAll();
        
        foreach ($results as $row) {
            $name = $row['name'];
            echo "<title>$name - Brisbane Park Finder</title>";
        }

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
?>
