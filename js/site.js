var markersArray = []; // global array to store the marker positions

function clearOverlays() {	// clearing the array
    while (markersArray.length) {
        markersArray.pop().setMap(null);
    }
    markersArray = [];
    document.getElementById("startingPointText").value = "";
    document.getElementById("startlat").value = "";
    document.getElementById("startlng").value = "";
    
    document.getElementById("endPointText").value = "";
    document.getElementById("endlat").value = "";
    document.getElementById("endlng").value = "";
}

function myMap() {
    var mapCanvas  = document.getElementById("map");
    var myCenter   = new google.maps.LatLng(51.4826, 0.0077);
    var mapOptions = {center: myCenter, zoom: 12};
    var map        = new google.maps.Map(mapCanvas, mapOptions);

    google.maps.event.addListener(map, 'click', function (event) {
        placeMarker(map, event.latLng);
    });

    google.maps.event.addDomListener(window, "resize", function() {
    var center = map.getCenter();
    google.maps.event.trigger(map, "resize");
    map.setCenter(center); 
    });   
}

function placeMarker(map, location) { // this function is called when the map is clicked
    var marker = new google.maps.Marker({ position: location, map: map});

    if(markersArray.length >=2){        
        clearOverlays();
    }else{        
        markersArray.push(marker);
        var geocoder  = new google.maps.Geocoder();   // create a geocoder object
        var loc  = new google.maps.LatLng(location.lat(), location.lng());    // turn coordinates into an object          

        geocoder.geocode({'latLng': loc}, function (results, status) {

            if(status == google.maps.GeocoderStatus.OK) {// if geocode success
                var add=results[0].formatted_address;   // if address found, pass to processing function

                if(markersArray.length === 1){ 
                    document.getElementById("startingPointText").value = add;
                    document.getElementById("startlat").value = location.lat();
                    document.getElementById("startlng").value = location.lng();
                }else if(markersArray.length === 2){
                    document.getElementById("endPointText").value = add;
                    document.getElementById("endlat").value = location.lat();
                    document.getElementById("endlng").value = location.lng();
                }
                var infowindow = new google.maps.InfoWindow({ content: 'Address: ' + add});
                infowindow.open(map,marker); 

            }
       });
    }
}