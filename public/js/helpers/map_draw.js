// import '/js/helpers/ol/ol.css';
import Map from '/js/helpers/ol/Map.js';
import View from '/js/helpers/ol/View.js';
import Draw from '/js/helpers/ol/interaction/Draw.js';
import { Tile as TileLayer, Vector as VectorLayer } from '/js/helpers/ol/layer.js';
import { OSM, Vector as VectorSource } from '/js/helpers/ol/source.js';

// import '/js/helpers/ol/ol.css';
// import Map from '/js/app.js';
// import View from '/js/app.js';
// import Draw from '/js/app.js';
// import { Tile as TileLayer, Vector as VectorLayer } from '/js/app.js';
// import { OSM, Vector as VectorSource } from '/js/app.js';

var raster = new TileLayer({
  source: new OSM()
});

var source = new VectorSource({wrapX: false});

var vector = new VectorLayer({
  source: source
});

var map = new Map({
  layers: [raster, vector],
  target: 'map',
  view: new View({
    center: [-11000000, 4600000],
    zoom: 4
  })
});

var draw; // global so we can remove it later
function addInteraction() {
    console.log("adding interaction");
    draw = new Draw({
      source: source,
      type: "Polygon"
    });
    map.addInteraction(draw);
}

addInteraction();
