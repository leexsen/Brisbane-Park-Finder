name_searchBar = 'searchBar_name';
name_typeSelector= 'typeSelector_name';
name_suburbSelector = 'suburbSelector_name';
name_ratingSelector = 'ratingSelector_name';

// Image file path
filledStar = 'imgs/filledStar.svg';
unfilledStar = 'imgs/unfilledStar.svg';

// Initialise the map
function initMap() {
    map = new google.maps.Map(document.getElementById('map'), {
                              center: {lat: -27.38006149, lng: 153.0387005},
                              zoom: 12});
    
    markers = showMapMarkers();
    // Set map center to the postion of the first result
    map.center = markers[0].position;
}

// Display the relevant markers on the map
function showMapMarkers()
{
    var markers = [];
    var positions = document.getElementsByTagName('data');
    
    for (var i = 0; i < positions.length; i++) {
        var position = positions[i].getAttribute('value').split(',');
        var data = {lat: parseFloat(position[0]),
            lng: parseFloat(position[1])};
        
        var marker = new google.maps.Marker({
                                            position: data,
                                            map: map
                                            });
        
        marker.addListener('click', function() {
								var pid = position[2];
								return function() {window.location.href = 'itemPage.php?pid=' + pid;};
                           }());
        
        markers[i] = marker;
    }
    
    return markers;
}

// Hide or show elements of search box in the header depending on the user's option.
function typeSelectorChanged()
{
    var type = document.getElementsByClassName(name_typeSelector)[0].value;
    
    
    // reset rating stars in the search box
    resetRatingStars(document.getElementsByClassName(name_ratingSelector)[0]);
    
    if (type == 'name') {
        showSearchBar();
        hideSuburbSelector();
        hideRatingSelector();
        
    } else if (type == 'suburb') {
        showSuburbSelector();
        hideRatingSelector();
        hideSearchBar();
        
    } else if (type == 'rating') {
        showRatingSelector();
        hideSuburbSelector();
        hideSearchBar();
        
    } else if (type == 'location') {
        showSearchBar();
        hideSuburbSelector();
        hideRatingSelector();
        getLocation();
    }
}

// Show the suburb selector field
function showSuburbSelector()
{
    document.getElementsByClassName(name_suburbSelector)[0].style.display = 'inline-block';
}

// Hide the suburb selector field
function hideSuburbSelector()
{
    document.getElementsByClassName(name_suburbSelector)[0].style.display = 'none';
}

// Show the rating selector field
function showRatingSelector()
{
    document.getElementsByClassName(name_ratingSelector)[0].style.display = 'inline-block';
}

// Hide the rating selector field
function hideRatingSelector()
{
    document.getElementsByClassName(name_ratingSelector)[0].style.display = 'none';
}

// SHow the search bar
function showSearchBar()
{
    document.getElementsByClassName(name_searchBar)[0].style.display = 'inline-block';
    document.getElementsByClassName(name_searchBar)[0].placeholder = ' Search parks';
}

// Hide the search bar
function hideSearchBar()
{
    document.getElementsByClassName(name_searchBar)[0].style.display = 'none';
}

// These functions get the user's current location and puts it in the search box automatically.
// If the user cannot do geolocation the appropriate message is shown.
function getLocation() {
    var searchBar = document.getElementsByClassName(name_searchBar)[0];
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(showPosition, showError);
    } else {
        searchBar.innerHTML = "Geolocation is not supported by your browser.";
    }
}

// Display the coordinates in the search bar
function showPosition(position) {
    var searchBar = document.getElementsByClassName(name_searchBar)[0];
    searchBar.value = position.coords.latitude + "," + position.coords.longitude;
}

// Show the appropriate error message
function showError(error) {
    var searchBar = document.getElementsByClassName(name_searchBar)[0];
    var msg = "";
    switch(error.code) {
        case error.PERMISSION_DENIED:
            msg = "Denied the request for Geolocation."
            break;
        case error.POSITION_UNAVAILABLE:
            msg = "Location information is unavailable."
            break;
        case error.TIMEOUT:
            msg = "The request to get your location timed out."
            break;
        case error.UNKNOWN_ERROR:
            msg = "An unknown error occurred."
            break;
    }
    searchBar.value = "";
    searchBar.placeholder = msg;
}

/* When a unfilled rating star with a index is clicked, all stars before
 * it will be replaced with filled rating stars (including itself).
 *
 * ratingImg: the rating star image clicked
 * index: the index of the rating star image clicked
 */
function ratingStarClicked(ratingImg, index)
{
    var parent = ratingImg.parentElement;
    var children = parent.children;
    
    resetRatingStars(parent);
    
    for (var i = 0; i < index; i++) {
        children[i].src = filledStar;
    }
    
    // restore the rating to its parent node, which will be sent to the server
    // when users click submit button.
    parent.value = index;
}

/* Reset stars to default
 *
 * parent: the parent of stars need to be reseted
 */
function resetRatingStars(parent)
{
    for (var i = 0; i < parent.children.length; i++) {
        parent.children[i].src = unfilledStar;
    }
    
    parent.value = null;
}

// Performing a validation and cleaning unecessary field before submitting
function searchSubmit(form)
{
    var ratingSelector = document.getElementsByClassName(name_ratingSelector)[0];
    var suburbSelector = document.getElementsByClassName(name_suburbSelector)[0];
    var searchBar = document.getElementsByClassName(name_searchBar)[0];
    var type = document.getElementsByClassName(name_typeSelector)[0].value;

    if (type == 'suburb') {
        searchBar.value = suburbSelector.value;
        
    } else if (type == 'rating') {
        searchBar.value = ratingSelector.value;
    }

	if (searchBar.value == null || searchBar.value == "") {
		alert('The value entered is invalid');
		return false;
	}
    
    // Remove these elements from form so that the data from them won't be sent
    form.removeChild(ratingSelector);
    form.removeChild(suburbSelector);
    
    return true;
}

// Performing a validation and cleaning unecessary field before submitting
function commentSubmit(form)
{
    var ratingValue = document.getElementById('commentBoxRating');
    var rating = document.getElementsByClassName('rating')[0];
	var comment = document.getElementById('commentArea');

	if (ratingValue.value == null || comment.value == "") {
		alert('Rating or comment cannot be empty');
		return false;
	}
    
    rating.value = ratingValue.value;
    
    // Remove these elements from form so that the data from them won't be sent
    form.removeChild(ratingValue);
    
    return true;
}

// Validates the login form
function loginSubmit(form) {
    var email = checkEmail(form);
    var password = checkPassword(form);
    
    return (email && password);
}

// Validates the register form
function registerSubmit(form) {
    var name = checkName(form);
    var email = checkEmail(form);
    var password = checkPassword(form);
    var confirm = checkConfirm(form);
    var date = checkDate(form);
    
    return (name && email && date && password && confirm);
}

// Checks if the name fields are empty and returns false if so
function checkName(form) {
    if (form.firstName.value == "" || form.lastName.value == "") {
        document.getElementById("noName").style.display = 'inline-block';
        return false;
    } else {
        return true;
    }
}

// Checks if the email field is empty or invalid and returns false if so.
function checkEmail(form) {
    // Pattern matches the style that is entered into the database
    var emailPattern =/^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if(!emailPattern.test(form.emailForm.value)) {
        document.getElementById("noEmail").style.display = 'inline-block';
        return false;
    } else {
        return true;
    }
}

// Checks if the password field is empty and returns false if so
function checkPassword(form) {
    if (form.passwordForm.value == "") {
        document.getElementById("noPassword").style.display = 'inline-block';
        return false;
    } else {
        return true;
    }
}

// Checks if the confirm password field is empty or if the password fields do not match and returns false if so
function checkConfirm(form) {
    if (form.confirmForm.value == "") {
        document.getElementById("noConfirm").style.display = 'inline-block';
        return false;
    } else if (form.passwordForm.value != form.confirmForm.value) {
        document.getElementById("noConfirm").innerHTML = "Please ensure the password fields match.";
        document.getElementById("noConfirm").style.display = 'inline-block';
        return false;
    } else {
        return true;
    }
}

// Checks if the date field is empty or invalid and returns true if so
function checkDate(form) {
    // Pattern ensures there is at least one character surrounding the '@' and '.'
    // e.g. 'g@g.c' is acceptable
    var datePattern =/^([0-9]{4})-([0-9]{1,2})-([0-9]{1,2})$/;
    if(!datePattern.test(form.dateForm.value)) {
        document.getElementById("noDate").style.display = 'inline-block';
        return false;
    } else {
        return true;
    }
}

// Clears the login and registration error messages
function clearError(id) {
    if (document.getElementById("incorrectLogin") != null) {
        document.getElementById("incorrectLogin").style.display = 'none';
    }
    
    document.getElementById(id).style.display = 'none';
    
}
