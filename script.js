id_searchBar = 'searchBar';
id_typeSelector= 'typeSelector';
id_suburbSelector = 'suburbSelector';
id_ratingSelector = 'ratingSelector';

// the file path
filledStar = 'imgs/filledStar.svg';
unfilledStar = 'imgs/unfilledStar.svg';

ratingScore = 0;


function initMap() {
	map = new google.maps.Map(document.getElementById('map'), {
		center: {lat: -27.3801, lng: 153.0387},
		zoom: 12});
}

/* it will hide or show elements of search box
 * in the header deponds on users' option.
 */
function typeSelectorChanged()
{
	var type = document.getElementById(id_typeSelector).value;

	// reset rating stars in the search box
	resetRatingStars(document.getElementById(id_ratingSelector).children);

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
	var children = ratingImg.parentElement.children;

	resetRatingStars(children);

	for (var i = 0; i < index; i++) {
		children[i].src = filledStar;	
	}

	// restore the rating which will be sent to the server
	// when users click submit button.
	ratingScore = index;
}

/* reset stars to default
 *
 * stars: the array of stars need to be reseted
 */
function resetRatingStars(stars)
{
	for (var i = 0; i < stars.length; i++) {
		stars[i].src = unfilledStar;	
	}

	ratingScore = 0;
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
		searchBar.value = ratingScore; 
	}

	// remove these elements from form so that the data from them
	// won't be sent
	form.removeChild(ratingSelector);
	form.removeChild(suburbSelector);

	return true;
}
