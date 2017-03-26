function initMap() {
	var map = new google.maps.Map(document.getElementById('map'), {
		center: {lat: -27.3801, lng: 153.0387},
		zoom: 12});
}

function showDetailedInfo()
{
	document.getElementById('detailedInfo').style.display = 'block';
	document.getElementById('contentList').style.display = 'none';
}
