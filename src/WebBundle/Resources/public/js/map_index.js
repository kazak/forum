
var map;
function initMap(lat, lng, zoom) {
    console.log(lat);
    var Options = {
        center: new google.maps.LatLng(lat, lng),
        zoom: zoom,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    };
    map = new google.maps.Map(document.getElementById("map"), Options);
}
