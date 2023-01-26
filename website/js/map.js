function osmSearch() {
  const url = new URL("https://nominatim.openstreetmap.org/search?");

  const osm_params = {
    "q": document.querySelector(".middle input#location-search").value,
    "format": "json",
    "limit": 1,
  }

  for (const [key, value] of Object.entries(osm_params)) {
    url.searchParams.append(key, value);
  }

  fetch(url)
    .then(res => res.json())
    .then(res => getNearestLocations(res[0]["lon"], res[0]["lat"])
      .then(locations => generateMap(res[0]["lon"], res[0]["lat"], locations))
      .catch(err => console.log(err))
    ).catch(err => console.log(err));
}

function generateMap(lon, lat, locations) {
  disableButton();

  if (document.querySelector(".middle div>div.ol-viewport") != null) {
    insertPopupElement();
    document.querySelector(".middle div>div.ol-viewport").remove();
  }

  const map = new ol.Map({
    target: 'mapdiv',
    layers: [
      new ol.layer.Tile({
        source: new ol.source.OSM()
      })
    ]
  });

  handlePopup(map);

  let zoom = 11;

  const lonLat = ol.proj.transform([Number.parseFloat(lon), Number.parseFloat(lat)], 'EPSG:4326', map.getView().getProjection());

  const currentPositionMarker = getMarker(lon, lat, map, "my_location.png");

  const markers = [currentPositionMarker]

  locations.forEach(l => markers.push(getMarker(l['lon'], l['lat'], map)));


  let vectorSource = new ol.source.Vector({
    // foreach marker that are being created, add them i this list
    features: markers
  });
  let vectorLayer = new ol.layer.Vector({
    source: vectorSource
  });

  map.getView().setCenter(lonLat);
  map.getView().setZoom(zoom);
  drawCenteredCircle(map);
  map.addLayer(vectorLayer);

  vectorLayer.getSource().getFeatures().forEach(feature => {
    feature.on('click', function() {
      console.log('Feature clicked');
    });
  });
}

function getNearestLocations(lon, lat) {
  const center = {
    "lon": lon,
    "lat": lat,
  };

  const radius = 10; // 10 km

  const formData = new FormData();
  formData.append("center", JSON.stringify(center));
  formData.append("radius", radius);

  const locations = axios.post("api/api-locations.php?action=1", formData)
    .then(res => res.data)
    .catch(() => []);
  return locations;
}

function getMarker(lon, lat, map, icon = "marker.png") {
  const lonLat = ol.proj.transform([Number.parseFloat(lon), Number.parseFloat(lat)], 'EPSG:4326', map.getView().getProjection());

  const marker = new ol.Feature(new ol.geom.Point(lonLat));
  marker.setStyle(new ol.style.Style({
    image: new ol.style.Icon(/** @type {olx.style.IconOptions} */({
      anchor: [0.5, 1],
      anchorXUnits: 'fraction',
      anchorYUnits: 'fraction',
      src: 'img/' + icon,
    }))
  }));

  return marker;
}

function insertPopupElement() {
  const map = document.getElementById("mapdiv");

  const popup = `<div id="popup" class="ol-popup">
                <a href="#" id="popup-closer" class="ol-popup-closer" title="popup-closer"></a>
                <div id="popup-content"></div>
            </div>`;

  map.insertAdjacentHTML('afterend', popup);
}

function handlePopup(map) {
  const container = document.getElementById('popup');
  const content = document.getElementById('popup-content');
  const closer = document.getElementById('popup-closer');

  const overlay = new ol.Overlay({
    element: container,
    autoPan: true,
    autoPanAnimation: {
      duration: 250
    }
  });
  map.addOverlay(overlay);

  closer.onclick = () => {
    overlay.setPosition(undefined);
    closer.blur();
    return false;
  }

  map.on('singleclick', (event) => {
    const features = map.getFeaturesAtPixel(event.pixel);

    if (features === null) {
      overlay.setPosition(undefined);
      container.classList.remove("show");
      content.innerHTML = "";
      closer.blur();
      return;
    }

    let foundMarker = false;
    let lonlat = [0,0];
    features.forEach(feature => {
      // Check if the feature is a marker
      if (feature instanceof ol.Feature && feature.getStyle() instanceof ol.style.Style) {
        foundMarker = true;
        lonlat = ol.proj.transform(feature.getGeometry().getCoordinates(),'EPSG:3857', 'EPSG:4326');
      }
    });
    if (foundMarker) {
      content.innerHTML = "";
      fillPopupEvent(content, lonlat)
        .then(() => {
          const coordinate = event.coordinate;
          container.classList.add("show");
          overlay.setPosition(coordinate);
        }).catch(err =>console.log(err))
    } else {
      overlay.setPosition(undefined);
      container.classList.remove("show");
      closer.blur();
    }
  });
}

function fillPopupEvent(popoupContainer, lonlat) {
  // make lat rounded to the 6th decimal
  const approx = 1000000;
  // Using EPSILON due to float precision arithmetic
  lonlat[1] = Math.round((lonlat[1] + Number.EPSILON) * approx) / approx;
  // make a db request for events in give lonlat
  const center = {
    "lon": lonlat[0],
    "lat": lonlat[1],
  };

  const radius = 1 / approx;

  const formData = new FormData();
  formData.append("center", JSON.stringify(center));
  formData.append('radius', radius);

  return axios.post("api/api-locations.php?action=1", formData)
    .then(res => {
      if (res.data.length > 0) {
        const postId = res.data[0].post;
        fetchEvent(postId)
          .then(res => insertPopupContent(res, popoupContainer))
          .catch(() => []);
      }
    }).catch(() => []);
}

function fetchEvent(postId) {
  const formData = new FormData();
  formData.append("postId", postId);

  return axios.post("api/api-post.php?action=fetch", formData)
    .then(res => res.data)
    .catch(() => []);
}
  
function insertPopupContent(post, popoupContainer) {
  if (post != []) {
    const reference = `post.php?usrId=${post['usrId']}&postId=${post['postId']}`
    popoupContainer.innerHTML = `<a href="${reference}"><b>${post['title']}</b></a>`
    return popoupContainer;
  } else {
    return [];
  }
}

function drawCenteredCircle(map) {
  const center = map.getView().getCenter();
  const radius = 20000;

  // Create a list of points for the circular LinearRing
  const points = [];
  for (let i = 0; i < 360; i++) {
    let angle = i * Math.PI / 180;
    let x = center[0] + radius * Math.cos(angle);
    let y = center[1] + radius * Math.sin(angle);
    points.push([x, y]);
  }

  const linearRing = new ol.geom.LinearRing(points);

  const polygon = new ol.geom.Polygon([linearRing.getCoordinates()]);

  const feature = new ol.Feature(polygon);


  // Create a vector layer to hold the circle
  const vectorLayer = new ol.layer.Vector({
    source: new ol.source.Vector({
      features: [feature]
    }),
    name: 'Circle Layer'
  });

  map.addLayer(vectorLayer);

  const circleExtent = feature.getGeometry().getExtent();
  map.getView().fit(circleExtent, map.getSize());
}

function disableButton() {
  document.querySelector(".middle #search-button").disabled = true;
  setTimeout(() => document.querySelector(".middle #search-button").disabled = false, 6000);

}

