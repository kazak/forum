
var map;
function initMap(lat, lng, zoom) {
    var Options = {
        center: new google.maps.LatLng(lat, lng),
        zoom: zoom,
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        scrollwheel: false,
        streetViewControl: false,
        panControl: false,
        mapTypeControl: false,
        zoomControlOptions: {
            style: google.maps.ZoomControlStyle.SMALL
        }
    };
    map = new google.maps.Map(document.getElementById("map"), Options);
}
