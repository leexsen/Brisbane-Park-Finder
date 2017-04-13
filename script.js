name_searchBar = 'searchBar';
name_typeSelector= 'typeSelector';
name_suburbSelector = 'suburbSelector';
name_ratingSelector = 'ratingSelector';

// the file path
filledStar = 'imgs/filledStar.svg';
unfilledStar = 'imgs/unfilledStar.svg';

function initMap() {
	map = new google.maps.Map(document.getElementById('map'), {
		center: {lat: -27.38006149, lng: 153.0387005},
		zoom: 12});

	markers = showMapMarkers();
	// set map center to the postion of the first result
	map.center = markers[0].position;
}

function showMapMarkers()
{
	var markers = [];
	var positions = document.getElementsByName('position');

	for (var i = 0; i < positions.length; i++) {
		var position = positions[i].getAttribute('value').split(',');
		var data = {lat: parseFloat(position[0]), 
					lng: parseFloat(position[1])};
		
		var marker = new google.maps.Marker({
			position: data,
			map: map
		});
		
		marker.addListener('click', function() {
			window.location.href = 'itemPage.html';
		});

		markers[i] = marker;
	}

	return markers;
}

/* it will hide or show elements of search box
 * in the header deponds on users' option.
 */
function typeSelectorChanged()
{
    var type = document.getElementsByName(name_typeSelector)[0].value;
    
    
    // reset rating stars in the search box
    resetRatingStars(document.getElementsByName(name_ratingSelector)[0]);

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

function showSuburbSelector()
{
    document.getElementsByName(name_suburbSelector)[0].style.display = 'initial';
}

function hideSuburbSelector()
{
    document.getElementsByName(name_suburbSelector)[0].style.display = 'none';
}

function showRatingSelector()
{
    document.getElementsByName(name_ratingSelector)[0].style.display = 'initial';
}

function hideRatingSelector()
{
    document.getElementsByName(name_ratingSelector)[0].style.display = 'none';
}

function showSearchBar()
{
    document.getElementsByName(name_searchBar)[0].style.display = 'initial';
    document.getElementsByName(name_searchBar)[0].placeholder = ' Search parks';
}

function hideSearchBar()
{
    document.getElementsByName(name_searchBar)[0].style.display = 'none';
}

// These functions get the user's current location and puts it in the search box automatically.
// If the user cannot do geolocation the appropriate message is shown.
function getLocation() {
    var searchBox = document.getElementsByName(name_searchBar)[0];
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(showPosition, showError);
    } else {
        searchBox.innerHTML = " Geolocation is not supported by your browser.";
    }
}

function showPosition(position) {
    var searchBar = document.getElementsByName(name_searchBar)[0];
    searchBar.value = position.coords.latitude + ", " + position.coords.longitude;
}


function showError(error) {
    var searchBox = document.getElementsByName(name_searchBar)[0];
    var msg = "";
    switch(error.code) {
        case error.PERMISSION_DENIED:
            msg = " Denied the request for Geolocation."
            break;
        case error.POSITION_UNAVAILABLE:
            msg = " Location information is unavailable."
            break;
        case error.TIMEOUT:
            msg = " The request to get your location timed out."
            break;
        case error.UNKNOWN_ERROR:
            msg = " An unknown error occurred."
            break;
    }
    searchBar.value = "";
    searchBar.placeholder = msg;
}

/* when a unfilled rating star with a index is clicked, all stars before
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

/* reset stars to default
 *
 * parent: the parent of stars need to be reseted
 */
function resetRatingStars(parent)
{
    for (var i = 0; i < parent.children.length; i++) {
        parent.children[i].src = unfilledStar;
    }
    
    parent.value = 0;
}

/* simplify the interface between client side and server side
 * by reconstructing the data to [typeSelector=value?searchBar=value]
 * It will replace the value of searchBar depends on users' selection.
 *
 * form: the form need to be simplified.
 */
function searchSubmit(form)
{
    var ratingSelector = document.getElementsByName(name_ratingSelector)[0];
    var suburbSelector = document.getElementsByName(name_suburbSelector)[0];
    var searchBar = document.getElementsByName(name_searchBar)[0];
    var type = document.getElementsByName(name_typeSelector)[0].value;
    
    if (type == 'suburb') {
        searchBar.value = suburbSelector.value;
        
    } else if (type == 'rating') {
        searchBar.value = ratingSelector.value; 
    }
    
    // remove these elements from form so that the data from them
    // won't be sent
    form.removeChild(ratingSelector);
    form.removeChild(suburbSelector);
    
    return true;
}
