<?php
	function connectDB()
	{
		return new PDO('mysql:host=localhost;dbname=parkfinder', 'root', '123456');
	}

	function disconnectDB($db)
	{
		$db = null;
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
		echo "<a href=\"itemPage.php?pid=$pid\">";

		echo '<div class="contentCell">';
			echo "<span class=\"contentTitle\">$name</span>";
			
			echo '<div class="contentRating">';
				if ($rating > 0) {
					while ($rating > 0) {
						echo '<img src="imgs/filledStar.svg" alt="starts">';
						$rating--;
					}	
				} else {
					echo 'No rating';
				}
			echo '</div>';

			echo "<span class=\"contentDescription\">$street, $suburb</span>";
			echo "<data value=\"$latitude,$longitude,$pid\"></data>";
		echo '</div>';

		echo '</a>';
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

		} else {
			$sql = "select * from parks";
		}

		$db = connectDB();
		$results = $db->query($sql);	

		echo '<div id="contentList">';

		while ($row = $results->fetch(PDO::FETCH_ASSOC)) {
			$pid = $row['pid'];
			$name = $row['name'];
			$street = $row['street'];
			$suburb = $row['suburb'];
			$latitude = $row['latitude'];
			$longitude = $row['longitude'];
			$rating = calculateAverageRating($db, $pid);

			showContent($pid, $name, $rating, $street, $suburb, $latitude, $longitude);
		}

		echo '</div>';
		disconnectDB($db);
	}

	function calculateAverageRating($db, $pid)
	{
		$sql = "select sum(rating)/count(rating) from reviews where pid=$pid";
		$averageRating = $db->query($sql)->fetchColumn();	
		
		return $averageRating;
	}
?>
