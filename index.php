<!DOCTYPE html>
<html>  
    <head>
    		<!-- jQuery -->
   		 <script  src="https://code.jquery.com/jquery-2.2.4.min.js"  integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44="  crossorigin="anonymous"></script>
        <!-- Include Google Maps JS API -->
        <script type="text/javascript"
          src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDtm5595-6LfvCZfMx_j-AK_59Ri_PU4NQ">
        </script>
        <style type="text/css">
              #mapDiv { width: 100%; height: 300px; }
        </style>
        <script type="text/javascript">
        			// Перетворює гугл координати в числовий масив
           //          function ltnlngToNumber(coordinates) {
           //          		var newCoords = [], newLatLng = [];
           //          		coordinates.forEach(function (item, i, coordinates){
	          //           		latlng = item.toString().replace(/\(|\)/g, '').split(', ');
	          //           		latlng.forEach(function(item, i, latlng) {
	          //           			newLatLng[i] = Number(item);
	          //           		});
							    // newCoords.push(newLatLng);
							    // newLatLng = [];
	          //           	});
	          //           	return newCoords;
	          //           };
	                 // Перетворює числовий масив в гугл координати
	     //             function numberToLtnLng(arrayNumber) {
	     //               var pointCount = arrayNumber.length; 
	     //               var linePath = [];
						// for (var i=0; i < pointCount; i++) {   
						// var tempLatLng = new google.maps.LatLng(arrayNumber[i][0] , arrayNumber[i][1]);
						// 	linePath.push(tempLatLng); 
						// }
						// return linePath;
	     //            }

					function sortPoints2Polygon() {
					      if (bermudaTriangle) bermudaTriangle.setMap(null);
					      points = [];
					      var bounds = new google.maps.LatLngBounds(); 
					      for (var i=0; i < markers.length; i++) {
					        points.push(markers[i].getPosition());
					    bounds.extend(markers[i].getPosition());
					      }
					      center = bounds.getCenter();
					      var bearing = [];
					      for (var i=0; i < points.length; i++) {
					        points[i].bearing = google.maps.geometry.spherical.computeHeading(center,points[i]);
					      }
					      points.sort(bearingsort);
					      bermudaTriangle.setPath(points);
					      //  = new google.maps.Polygon({
					      //   map: map,
					      //   paths:points, 
					      //   fillColor:"#FF0000",
					      //   strokeWidth:2, 
					      //   fillOpacity:0.5, 
					      //   strokeColor:"#0000FF",
					      //   strokeOpacity:0.5
					      // });
					}
					function bearingsort(a,b) {
					  return (a.bearing - b.bearing);
					}


					function getPoligonArea(polygon) {     
						var path = polygon.getPath();
					    var area = google.maps.geometry.spherical.computeArea(path); 
					    return area;
					}

	                // function getPoligonArea(poligon) {
	                // 	return google.maps.geometry.spherical.computeArea(poligon.getPath());
	                // }


              //Defining map as a global variable to access from other functions
              var map;
              var lastCoordinate, allCoordinate = [], markerId = 1, bermudaTriangle = [], poligons = [], allCoordinateNTL = [], poligonArea, markers = [], center, newbermudaTriangle;

              // Initializing the map
              function initMap() {



                    //Setting starting options of map
                    var mapOptions = {
                          center: new google.maps.LatLng(39.9078, 32.8252),
                          zoom: 10
                    };


                    //Getting map DOM element
                    var mapElement = document.getElementById('mapDiv');

                    //Creating a map with DOM element which is just obtained
                    map = new google.maps.Map(mapElement, mapOptions);

                    //Listening the map object for mouse right click.
                    // При кліку добавляється маркер, його координати записуються в масив (allCoordinate)
                    google.maps.event.addListener(map, 'rightclick', function(e) {
                        lastCoordinate = e.latLng;

	                    var marker = new google.maps.Marker({
	                      position: lastCoordinate,
	                      map: map,
	                      title: 'Marker'
	                  	});
	                  	markerId++;
	                  	markers.push(marker);
	                  	allCoordinate.push(marker.getPosition());
	                  	$('.options').append('<li>'+lastCoordinate+'</li>');

	                  	// Перетворюю масив з гугл координати в масив з числами
	                  	// allCoordinateNumber = ltnlngToNumber(allCoordinate);
	                  	// console.log(allCoordinateNumber);

	                  	// Перетворюю відфільтрований масив з числами в гугл координати
	                  	// allCoordinateNTL = numberToLtnLng(allCoordinateNumber);
	                  	// console.log(allCoordinateNTL);

	                  		// Налаштування лінії
	                  		var flightPath = new google.maps.Polyline({
							    path: allCoordinate,
							    geodesic: true,
							    strokeColor: '#FF0000',
							    strokeOpacity: 1.0,
							    strokeWeight: 2
							  });

	                  		// Налаштування полігону
	                  		bermudaTriangle = new google.maps.Polygon({
								paths: allCoordinate,
								strokeColor: '#FF0000',
								strokeOpacity: 0.8,
								strokeWeight: 2,
								fillColor: '#FF0000',
								fillOpacity: 0.35
							});

	                  		// console.log(bermudaTriangle);
	                  		// Активування лінії
	                  		bermudaTriangle.setMap(null);
	                  	// if (allCoordinate.length > 1 or allCoordinate.length < 3) {
	                  	// 	flightPath.setMap(map);
	                  	// }
	                  	if (allCoordinate.length == 3) {
	                  		bermudaTriangle.setMap(map);
							poligonArea = getPoligonArea(bermudaTriangle);
              				var infowindow = new google.maps.InfoWindow({ content: 'Poligon area:'+poligonArea, center: center });
	                  		infowindow.open(map, bermudaTriangle)
	                  		newbermudaTriangle = bermudaTriangle
	                  	}
	                  		// Активування полігону
	                  	if (allCoordinate.length > 3) {
	                  		// bermudaTriangle.setPath();	
							sortPoints2Polygon();
	                  		newbermudaTriangle.setMap(null);
	                  		bermudaTriangle.setMap(map);
	                  		newbermudaTriangle = bermudaTriangle
	                  	}
							
                    });



              }
              google.maps.event.addDomListener(window, 'load', initMap);
        </script>
    </head>
    <body>
        <div id="mapDiv"></div>
		<ol class="options"></ol>
    </body>
</html>