id_searchBar = 'searchBar';
id_typeSelector= 'typeSelector';
id_suburbSelector = 'suburbSelector';
id_ratingSelector = 'ratingSelector';

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
	var type = document.getElementById(id_typeSelector).value;

	// reset rating stars in the search box
	resetRatingStars(document.getElementById(id_ratingSelector));

	if (type == 'suburb') {
		showSuburbSelector();
		hideRatingSelector();
		hideSearchBar();

	} else if (type == 'rating') {
		showRatingSelector();
		hideSuburbSelector();
		hideSearchBar();

	} else if (type == 'name') {
		showSearchBar();
		hideSuburbSelector();
		hideRatingSelector();
	}
}

function showSuburbSelector()
{
	document.getElementById(id_suburbSelector).style.visibility = 'visible';
}

function hideSuburbSelector()
{
	document.getElementById(id_suburbSelector).style.visibility = 'hidden';
}

function showRatingSelector()
{
	document.getElementById(id_ratingSelector).style.visibility = 'visible';
}

function hideRatingSelector()
{
	document.getElementById(id_ratingSelector).style.visibility = 'hidden';
}

function showSearchBar()
{
	document.getElementById(id_searchBar).style.visibility = 'visible';
}

function hideSearchBar()
{
	document.getElementById(id_searchBar).style.visibility = 'hidden';
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
	var ratingSelector = document.getElementById(id_ratingSelector);
	var suburbSelector = document.getElementById(id_suburbSelector);
	var searchBar = document.getElementById(id_searchBar);
	var type = document.getElementById(id_typeSelector).value;

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
