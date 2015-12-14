function initMap(lat, lng, zoom) {
    var map = new google.maps.Map(document.getElementById('map'), {
        zoom: zoom,
        center: {lat: lat, lng: lng}
    });

}

