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
		$sql = 'select distinct suburb from parks';

		$results = $db->query($sql);	
		while ($row = $results->fetch(PDO::FETCH_ASSOC)) {
			echo "<option value=\"${row['suburb']}\">${row['suburb']}</option>";
		}

		disconnectDB($db);
	}

	function showContent($pid, $name, $rating, $street, $suburb, $latitude, $longitude)
	{
		echo '<div class="contentCell">';
			echo "<span class=\"contentTitle\">$name</span>";
			
			echo '<div class="contentRating">';
				if ($rating > 0) {
					showStars('imgs/filledStar.svg', $rating);

				} else {
					echo 'No rating';
				}
			echo '</div>';

			echo "<span class=\"contentDescription\">$street, $suburb</span>";
			echo "<data value=\"$latitude,$longitude,$pid\"></data>";
		echo '</div>';
	}

	function searchParks($type, $value)
	{
		if ($type == 'name') {
			$sql = "select * from parks where name like '%$value%'";

		} else if ($type == 'suburb') {
			$sql = "select * from parks where suburb='$value'";

		} else if ($type == 'location') {
		
		} else if ($type == 'rating') {
			$sql = "select * from parks where pid in (
						select pid from (
							select pid, sum(rating)/count(pid) as avgRating from reviews group by pid
						) as t where avgRating>=$value
					)";

		} else if ($type == 'pid') {
			$sql = "select * from parks where pid=$value";

		} else {
			$sql = "select * from parks";
		}

		$db = connectDB();
		$results = $db->query($sql);	

		while ($row = $results->fetch(PDO::FETCH_ASSOC)) {
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

		disconnectDB($db);
	}

	function calculateAverageRating($db, $pid)
	{
		$sql = "select sum(rating)/count(rating) from reviews where pid=$pid";
		$averageRating = $db->query($sql)->fetchColumn();	
		
		return $averageRating;
	}
	
	function showComments($name, $rating, $comment, $date)
	{
		echo '<div class="commentCell">';
			echo "<span class=\"commentTitle\">$name</span>";

			echo '<div class="commentRating">';
				showStars('imgs/filledStar.svg', $rating);
			echo '</div>';
			
			echo "<span class=\"comment\">$comment</span>";
		echo '</div>';
	}

	function searchComments($pid)
	{
		$sql = "select first_name, last_name, rating, date, comment from reviews, members where reviews.uid=members.uid and pid=$pid";
		$db = connectDB();
		$results = $db->query($sql);	

		echo '<div id="commentList">';

		while ($row = $results->fetch(PDO::FETCH_ASSOC)) {
			$name = "${row['first_name']} ${row['last_name']}";
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
		$sql = "insert into reviews values(null,$uid,$pid,'$comment','$date',$rating)";	
		$db = connectDB();
		$db->exec($sql);
		disconnectDB($db);
	}
?>
