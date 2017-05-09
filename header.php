<?php require_once 'functions.php'; ?>

<div id="header">
	<div id="logo">
    	<span id="text_brisbane">Brisbane</span>
        <span id="text_park">Park</span>
        <span id="text_finder">Finder</span>
    </div>

    <div id="searchBox">
        <form action="resultPage.php" method="get" onsubmit="return searchSubmit(this)">
            <select id="typeSelectorHeader" class="typeSelector_name" name="typeSelector" onchange="typeSelectorChanged()">
                <option value="name">Name</option>
                <option value="suburb">Suburb</option>
                <option value="rating">Rating</option>
                <option value="location">Location</option>
            </select>
            <div id="ratingSelectorHeader" class="ratingSelector_name">
                <img src="imgs/unfilledStar.svg" alt="stars" onclick="ratingStarClicked(this, 1)">
                <img src="imgs/unfilledStar.svg" alt="stars" onclick="ratingStarClicked(this, 2)">
                <img src="imgs/unfilledStar.svg" alt="stars" onclick="ratingStarClicked(this, 3)">
                <img src="imgs/unfilledStar.svg" alt="stars" onclick="ratingStarClicked(this, 4)">
                <img src="imgs/unfilledStar.svg" alt="stars" onclick="ratingStarClicked(this, 5)">
            </div>
            <select id="suburbSelectorHeader" class="suburbSelector_name">
				<?php showSuburbOptions($db); ?>
            </select>
            <input type="text" id="searchBarHeader" class="searchBar_name" name="value" placeholder="Search parks">
            <input type="submit" id="submitButtonHeader" value="Search">
        </form>
    </div>

    <div id="loginLink">
    	<a href="login.php">Login</a>	
	</div>

    <div id="navigationBar">
        <ul>
            <li><a href="index.html">Home</a></li>
            <li><a href="resultPage.html">All Parks</a></li>
            <li><a href="itemPage.html">Random Park</a></li>
        </ul>
    </div>
</div>
