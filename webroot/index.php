<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8' />
    <title></title>
    <meta name='viewport' content='initial-scale=1,maximum-scale=1,user-scalable=no' />
    <script src='https://api.tiles.mapbox.com/mapbox-gl-js/v0.40.0/mapbox-gl.js'></script>
    <link href='https://api.tiles.mapbox.com/mapbox-gl-js/v0.40.0/mapbox-gl.css' rel='stylesheet' />
    <script src="http://code.jquery.com/jquery-1.11.0.min.js"></script>


    <style>
        body { margin:0; padding:0; }
        #map { position:absolute; top:0; bottom:0; width:100%; }
    </style>
</head>
<body>

<style type='text/css'>
    #info {
        display: block;
        position: relative;
        margin: 0px auto;
        width: 50%;
        padding: 10px;
        border: none;
        border-radius: 3px;
        font-size: 12px;
        text-align: center;
        color: #222;
        background: #fff;
    }
</style>


<script src='https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-directions/v3.1.1/mapbox-gl-directions.js'></script>
<link rel='stylesheet' href='https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-directions/v3.1.1/mapbox-gl-directions.css' type='text/css' />
<div id='map'></div>
<pre id='info'></pre>

<script>
puntos = []
var el;
var cln;
 geojson = {
    "type": "FeatureCollection",
    "features": [
        {
            "type": "Feature",
            "properties": {
                "message": "Foo",
                "iconSize": [60, 60]
            },
            "geometry": {
                "type": "Point",
                "coordinates": [
                    -66.324462890625,
                    -16.024695711685304
                ]
            }
        }
        
        
    ]
};

mapboxgl.accessToken = 'pk.eyJ1IjoiZ2xlbmNhbGliYSIsImEiOiJjaWs1ejdtaTkwMDA2d2prbzdzbDdkMWt2In0.mVdNlSVqVJjZaJbdRgTJxQ';
var map = new mapboxgl.Map({
    container: 'map',
    style: 'mapbox://styles/mapbox/streets-v9',
    center: [-79.4512, 43.6568],
    zoom: 13
});

/**
directions = new MapboxDirections({
            accessToken: 'pk.eyJ1IjoiZ2xlbmNhbGliYSIsImEiOiJjaWs1ejdtaTkwMDA2d2prbzdzbDdkMWt2In0.mVdNlSVqVJjZaJbdRgTJxQ',
            unit: 'metric',
            profile: 'driving',          
            interactive: false
});
*/
directions = new MapboxDirections({
            accessToken: mapboxgl.accessToken
        });
map.addControl( directions, 'top-left');


el = document.createElement('div');
el.className = 'marker';
el.style.backgroundImage = 'url(https://placekitten.com/g/' + '60'+'/'+ '60'+')';
el.style.width = 60 + 'px';
el.style.height = 60 + 'px';	





// add markers to map
/**
geojson.features.forEach(function(marker) {
    // create a DOM element for the marker
    el = document.createElement('div');
    el.className = 'marker';
    el.style.backgroundImage = 'url(https://placekitten.com/g/' + marker.properties.iconSize.join('/') + '/)';
    console.log(marker.properties.iconSize.join('/') + '/)');
    el.style.width = marker.properties.iconSize[0] + 'px';
    el.style.height = marker.properties.iconSize[1] + 'px';

    el.addEventListener('click', function() {
        window.alert(marker.properties.message);
    });

    // add marker to map
    new mapboxgl.Marker(el)
        .setLngLat(marker.geometry.coordinates)
        .addTo(map);
});
*/


map.on('click', function (e) {
	//guardar coordenadas
	//dibujar el marker
	//crear ruta si hay mas de dos markers
	puntos.push([e.lngLat.lng, e.lngLat.lat]);
	puntos.forEach(function(i){

		geojson.features.forEach(function(marker) {
			/**
			el = document.createElement('div');
    		el.className = 'marker';
    		el.style.backgroundImage = 'url(https://placekitten.com/g/' + marker.properties.iconSize.join('/') + '/)';
    		el.style.width = marker.properties.iconSize[0] + 'px';
    		el.style.height = marker.properties.iconSize[1] + 'px';	
			*/

    		// create DOM element for the marker
    		cln = el.cloneNode(true);
				

		});

		new mapboxgl.Marker(cln)
		.setLngLat(i)
  		.addTo(map); 
		if (puntos.length > 1){
			//console.log("linea")	

			//directions es un objeto que representa la ruta
			directions.removeRoutes();

			console.log(puntos);
			//console.log (directions.i);
          	$.each(puntos, function(i, item) {
          		//i es el indice e item es el array: Array [ -79.46446084137494, 43.66347510594886 ] 
          		//console.log (item);


            	
            	var lat = new mapboxgl.LngLat(item[0], item[1] ); //guardo la latitud
            	//console.log(lat); //--> Array [ -79.45424698944406, 43.66186072975927 ]

          
          		//el punto actual es el punto destino
            	if( i == puntos.length-1  && i != 0  ) {
                //console.log("destination "+i);
                	directions.setDestination([item[0], item[1]]);
                	

                //verifico si estoy en el punto inicial, entonces obtengo punto de origen
            	} else if( i  == 0 ) {
                	//console.log("origin "+i);
                	map.panTo(lat);
                	directions.setOrigin([item[0], item[1]]);
                	//console.log(directions);
                	if(puntos.length == 1) {
                  		//pin = 'map-pin.png';
                	}
            	}

            	//dado el punto actual en waypoint..hago la ruta de todos los puntos
            	else if(i >= 1) {
                	//console.log("waypoint "+i);
                	//pin = 'map-pin.png';  
                	var waypoint_coordinate = [item[0],item[1]];
                	directions.addWaypoint(i, waypoint_coordinate);
                	
            	}
          
            	/**
            	var lat = item.lat;
            	var lng = item.lng;
            	var title = item.title;
            	var descr = item.description;
            	var img = item.image;
            	var id = item.id;*/
              
            	//var coordinates = [lng,lat];                    
				symbol = 'star';
        	}); //fin each places function
        }//fin condicional
      }); //fin primer for


	 //fin function on.click
	
    	document.getElementById('info').innerHTML =
        // e.point is the x, y coordinates of the mousemove event relative
        // to the top-left corner of the map
	//L.marker(e.lngLat).addTo(map);
        JSON.stringify(e.lngLat);
	//console.log(e.lngLat);
	//console.log(e);
	//console.log(coord);

});
/**
map.addControl(new MapboxDirections({
    accessToken: mapboxgl.accessToken
}), 'top-left');*/

/**
var directions = new MapboxDirections({
    accessToken: mapboxgl.accessToken
  });
  // add to your mapboxgl map
  map.addControl(directions);

**/
</script>

</body>
</html>


