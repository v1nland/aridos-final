<?php
require_once('campo.php');

use Illuminate\Support\Facades\Log;
use App\Helpers\Doctrine;

class CampoMapsOL extends Campo
{
    public $requiere_datos = false;
    public $datos_mapa = true;

    protected function display($modo, $dato, $etapa_id = false)
    {

        if ($etapa_id) {
            $etapa = Doctrine::getTable('Etapa')->find($etapa_id);
            $regla = new Regla($this->valor_default);
            $valor_default = $regla->getExpresionParaOutput($etapa->id);
        } else {
            $valor_default = $this->valor_default;
	}
	if(!isset($etapa->tramite_id)){
		$etapa=new stdClass();
	$etapa->tramite_id = 0;
	}
        $columns = $this->extra;

        Log::debug('columns: ' . json_encode($columns));
	Log::debug('dato: ' . json_encode($dato));
	if(isset($dato->valor) && strlen(json_encode($dato->valor)) > 10){
		$geom = json_encode($dato->valor);
	}else{
		$geom = "";
	}
        $display = '
   <style>
      .map {
        width: 100%;
        height:400px;
        -webkit-filter: drop-shadow(0 1px 4px rgba(0,0,0,0.2));
        filter: drop-shadow(0 1px 4px rgba(0,0,0,0.2));
        padding: 15px;
        border-radius: 10px;
        border: 1px solid #cccccc;
        min-width: 280px;


}
      #poly {
        min-heigth:400px;
        background-color:#FFFFFF;
}
      .add {
        top: 5px;
        left: 95%;
        background-color:rgba(0,0,255,0.6);;

}
.custom-mouse-position{
color:white;
font-weight: bold;
background-color:black;
text-align: center;
}
.coords {
        background-color: white;
        -webkit-filter: drop-shadow(0 1px 4px rgba(0,0,0,0.2));
        filter: drop-shadow(0 1px 4px rgba(0,0,0,0.2));
        padding: 15px;
        border-radius: 10px;
        border: 1px solid #cccccc;
	min-width: 280px;
	margin-top:10px;

}
.ol-popup {
        background-color: white;
        -webkit-filter: drop-shadow(0 1px 4px rgba(0,0,0,0.2));
        filter: drop-shadow(0 1px 4px rgba(0,0,0,0.2));
        padding: 15px;
        border-radius: 10px;
        border: 1px solid #cccccc;
        min-width: 280px;

}
      .ol-popup:after, .ol-popup:before {
        top: 100%;
        border: solid transparent;
        content: " ";
        height: 0;
        width: 0;
        position: absolute;
        pointer-events: none;

}
      .ol-popup:after {
        border-top-color: white;
        border-width: 10px;
        left: 48px;
        margin-left: -10px;

}
      .ol-popup:before {
        border-top-color: #cccccc;
        border-width: 11px;
        left: 48px;
        margin-left: -11px;

}
      .ol-popup-closer {
        text-decoration: none;
        position: absolute;
        top: 2px;
        right: 8px;

}
      .ol-popup-closer:after {
	content:"x"
}
.sexagesimal, .sexagesimalsec {
    display:inline-block;
    padding:6px;
}

.sexagesimal {
    width: 40px;
}

.sexagesimalsec {
    width: 70px;
}

    </style>
		    
<script type="text/javascript">
var map;
var geom = \''.$geom.'\';
var polygon = [];
var polys=0;
var selectedFeatureID;
var draw,modify,selectClick;

function updatePoly(){
$("#poly").html("<ul style=\' width:100%; \'>");
	for(var i in polygon){
                pol = ol.proj.transform(polygon[i],"EPSG:3857", "EPSG:4326")
		$("#poly").append("<li>"+coordFormat(pol)+"</li>");
        }
        $("#poly").append("</ul>");
        $("#popup3").append("<br/>");
        
}
function remove(id){
source.removeFeature(source.getFeatureByUid(id));
changePoly();
}
function changePoly(){
let p = source.getFeatures();
$("#popup3").html("");
	var count = 1;
	for(var j in p){
	console.log(p[j]);
	if(p[j].getGeometry().getCoordinates()[0].length >=3){
if(geomenable)
$("#popup3").append("<p>Polígono #"+count+" <a  class=\' btn \'onclick=\'remove("+p[j].ol_uid+")\'> - <b style=\'color:red; \'>Eliminar</b></a></p>");
else
$("#popup3").append("<p>Polígono #"+count+"</p>");
count++;
$("#popup3").append("<ul style=\' width:100%; \'>");
		let coords = p[j].getGeometry().getCoordinates();
		for(var i in coords[0]){
		if(coords[0].length <= 3){
			pol = ol.proj.transform(coords[0][i],"EPSG:3857", "EPSG:4326")
			$("#popup3").append("<li>"+coordFormat(pol)+"</li>");
		}else	
		if(i<coords[0].length ){
			pol = ol.proj.transform(coords[0][i],"EPSG:3857", "EPSG:4326")
			$("#popup3").append("<li>"+coordFormat(pol)+"</li>");
		}

		}
 $("#popup3").append("</ul>");
 $("#popup3").append("<br/>");

}
	}
}
$(function(){    
	$("#'.$this->id.'").hide();
	if(!geomenable)
		$("#popup").hide();
});

var centrarMapa; 


var osm = new ol.source.OSM();
var base = new ol.layer.Tile({
title: "rutas",
        source: osm 
}); 
var source = new ol.source.Vector();
var styles = [
  new ol.style.Style({
    stroke: new ol.style.Stroke({
      color: "blue",
      width: 3
    }),
    fill: new ol.style.Fill({
      color: "rgba(255, 0, 0, 0.5)"
    })
  })
];



var vector2 = new ol.layer.Vector({source:new ol.source.Vector()});
if(geom != ""){
  source = new ol.source.Vector({
  features: (new ol.format.GeoJSON()).readFeatures(\''.$geom.'\',  {
	  dataProjection : "EPSG:4326", 
  	featureProjection: "EPSG:3857"
	})
  });
  	setInterval(changePoly,1000);

 vector2 = new ol.layer.Vector({
    source: new ol.source.Vector({
        format: new ol.format.GeoJSON(),
        url: "https://aridos.eit.technology/backend/api/geo_all/1/'.$etapa->tramite_id.'?token=EyMRb8Uhx"
    }),
	style: styles
});
}

  var vector = new ol.layer.Vector({
                 source: source
   });



var RotateNorthControl = /*@__PURE__*/(function (Control) {
  function RotateNorthControl(opt_options) {
    var options = opt_options || {};

    var add = document.createElement("img");
    add.id="add";
    add.src="'.url('/').'/../js/helpers/v6.1.1-dist/edit.png";
    add.style="width:30px;";
    var minus = document.createElement("img");
    minus.src="'.url('/').'/../js/helpers/v6.1.1-dist/minus.png";
    minus.style="width:30px;";
    minus.id="minus";
    var element = document.createElement("div");
    element.className = "add olControlPanPanel ol-unselectable ol-control";

     element.appendChild(add);
    element.appendChild(document.createElement("br"));
    element.appendChild(minus);

    Control.call(this, {
      element: element,
      target: options.target

});

    add.addEventListener("click", this.handleadd, false);
    minus.addEventListener("click", this.handleminus, false);

}

  if ( Control  ) RotateNorthControl.__proto__ = Control;
  RotateNorthControl.prototype = Object.create( Control && Control.prototype  );
  RotateNorthControl.prototype.constructor = RotateNorthControl;
  RotateNorthControl.prototype.handleadd = function handleadd () {
	var loop = false;
	map.getInteractions().forEach((interaction) => {
  		if (interaction instanceof ol.interaction.Draw) {
		    loop=true;
		  }
	});
	if(!loop){
	    map.addInteraction(draw);
		$("#add").attr("src","'.url('/').'/../js/helpers/v6.1.1-dist/edit.png");
		$("#minus").fadeOut();	
	}else{
   		 map.removeInteraction(draw);
		$("#add").attr("src","'.url('/').'/../js/helpers/v6.1.1-dist/add_blue.png");	
		$("#minus").fadeIn();	

	}
    };


  RotateNorthControl.prototype.handleminus = function handleminus () {
polygon =[]; 
function removeSelectedFeature() {
   var features = source.getFeatures();
     if (features != null && features.length > 0) {
         for (x in features) {
            if (features[x] == selectedFeatureID) {
              source.removeFeature(features[x]);
               break;
            }
          }
        }
      }

polys--;
removeSelectedFeature();
/*
	source.clear();
	$("#poly").html("");
	$("#popup3").html($("#poly").html());
	polygon = "";
	polygon = [];
	poly = [];
	polys=0;
	map.removeLayer(vector);
		source.clear();
		source = new ol.source.Vector();
		 vector = new ol.layer.Vector({
                          source: source
                    });
		 draw = new ol.interaction.Draw({
	      source: source,
	      type: "Polygon"
	});

*/

updatePoly();
changePoly();
};
  return RotateNorthControl;

}(ol.control.Control));

function coordFormat(coord){
var point = new GeoPoint(coord[0], coord[1]);
return point.getLatDeg()+" , "+point.getLonDeg();
}


var mousePositionControl = new ol.control.MousePosition({
  coordinateFormat: coordFormat,
  //coordinateFormat: ol.coordinate.createStringXY(4),
  projection: "EPSG:4326",
  className: "custom-mouse-position",
  target: document.getElementById("mouse-position"),
  undefinedHTML: "&nbsp;"
});
var sate = new ol.layer.Tile({
title: "Satelital",
  source: new ol.source.XYZ({
    attributionsCollapsible: false,
    url: "https://services.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}",
    maxZoom: 23
  })
});


// --------------------------------------
// --------------------------------------
// --------------------------------------
// --------------------------------------
// --------------------------------------
// --------------------------------------
$(function(){
			 map = new ol.Map({
                            target: "map",
			    layers: [base,sate,vector2,vector],
                            view: new ol.View({
                                center: ol.proj.fromLonLat([-70.73576,-33.47004]),
                                zoom: 15
			    }),
				controls : ol.control.defaults({
      					  attribution : false,
				        zoom : false,
				    })
			});
var zoomslider = new ol.control.ZoomSlider();
  map.addControl(zoomslider);
// --------------------------------------
// --------------------------------------
// --------------------------------------
// --------------------------------------
base.setVisible(false);
$("#sate").click(function(){
if(base.getVisible()){
$("#sate").html("Vista Rutas");
base.setVisible(false);
 sate.setVisible(true);
}else{
$("#sate").html("Vista Satelital");
 base.setVisible(true);
sate.setVisible(false);
}
});
if(!geomenable){
 var extent = vector.getSource().getExtent();
 map.getView().fit(extent, map.getSize());
}
if(geomenable)
  map.addControl(new RotateNorthControl());
  map.addControl(mousePositionControl);
  centrarMapa = function(com){
    map.getView().setCenter(ol.proj.transform([com.lng, com.lat], "EPSG:4326", "EPSG:3857"));
    map.getView().setZoom(9);
  };
   modify = new ol.interaction.Modify({source: source});
if(geomenable)
  map.addInteraction(modify);

 draw = new ol.interaction.Draw({
      source: source,
      type: "Polygon"
});

if(geomenable){
    map.addInteraction(draw);
 selectClick = new ol.interaction.Select({
  condition: ol.events.condition.click
});
selectClick.getFeatures().on("add", function (event) {
     var properties = event.element.getProperties();
     selectedFeatureID = event.element;
    });
    map.addInteraction(selectClick);
}
    source.on("addfeature",function(poly){
		map.removeInteraction(draw);
$("#add").attr("src","'.url('/').'/../js/helpers/v6.1.1-dist/add_blue.png");	
$("#minus").fadeIn();
	poly.type = "polygon";	
            showPoly(poly.feature.clone().getGeometry().transform("EPSG:3857","EPSG:4326").getCoordinates());
	       changePoly();
	    poly.feature.on("change",function(poly){
	       changePoly();
             });

     });

    $("#'.$this->id.'").hide();
$("#minus").hide();	


});
function showPoly(poly){
var writer = new ol.format.GeoJSON();
    var geojsonStr = writer.writeFeatures(source.getFeatures(),{
                    dataProjection: "EPSG:4326",
      featureProjection: "EPSG:3857"

                
});
$("#'.$this->id.'").val( geojsonStr);


}
$(function(){
$("#add").click(function(e){
$("#popup").css({top:e.pageY-200,left:e.pageX-400,heigth:300})
$("#popup").fadeIn();
});
$("#popup-closer2").click(function(e){
$("#popup2").fadeOut();
});


$("#nextpoly").click(function(e){
	$("#poly").html("");
        //source.clear();
                var feature = new ol.Feature({
                    geometry: new ol.geom.Polygon([polygon])
                });
polys++;
        source.addFeature(feature);
         var extent = vector.getSource().getExtent();
         map.getView().fit(extent, map.getSize());
polygon=[]; 
});


$("#agregar").click(function(e){
	var lat = $("#latitude_degres").val()+"° "+$("#latitude_minutes").val()+"\' "+$("#latitude_secondes").val()+"\"";
	var lon = $("#longitude_degres").val()+"° "+$("#longitude_minutes").val()+"\' "+$("#longitude_secondes").val()+"\"";


	var point = new GeoPoint(lon, lat);
	if(isNaN(point.getLonDec()) || isNaN(point.getLatDec()))
		alert("Coordenadas no validas");
	else{
	polygon.push(ol.proj.transform([-point.getLonDec(),-point.getLatDec()],"EPSG:4326", "EPSG:3857"));

                var features = source.getFeatures();
		var lastFeature = features[features.length - 1];
		if(lastFeature && lastFeature.getGeometry() instanceof ol.geom.Point)
		source.removeFeature(lastFeature);
	updatePoly();
	}
	});


});
$(function(){

var
    container = document.getElementById("popup2"),
    content_element = document.getElementById("popup-content2"),
    closer = document.getElementById("popup-closer2");
closer.onclick = function() {
    //overlay.setPosition(undefined);
    closer.blur();
    return false;
};
var overlay = new ol.Overlay({
    element: container,
    autoPan: true,
    offset: [-100, 0]
});
if(!geomenable){
map.addOverlay(overlay);
map.on("click", function(evt){
$("#popup2").fadeIn();
    var feature = map.forEachFeatureAtPixel(evt.pixel,
      function(feature, layer) {
	if(!feature.get("tramite"))return null;
        return feature;
      });
    if (feature) {
        var geometry = feature.getGeometry();
        var coord = geometry.getCoordinates();
        var content = "<h3>" + feature.get("estado") + "</h3>";
         content += "<h4>F.inicio: " + feature.get("inicio_extraccion") + "</h4>";
         content += "<h4>F.Fin: " + feature.get("fin_extraccion") + "</h4>";
         content += "<h4>Etapa: " + feature.get("etapa") + "</h4>";
         content += "<h4>id: " + feature.get("tramite") + "</h4>";
        
        content_element.innerHTML = content;
        overlay.setPosition(coord);
        
    }
});
}

//$(".mapalt2").hide();
    map.removeInteraction(draw);
    map.removeInteraction(modify);
    map.removeInteraction(selectClick);
$("input:radio[name=optmap]").change(function(){
if(this.value=="mapalt1"){
    map.removeInteraction(draw);
    map.removeInteraction(modify);
    map.removeInteraction(selectClick);
console.log("mapalt1");
//$(".mapalt2").fadeOut(function(){
//$(".mapalt1").fadeIn();
//});
}else{
console.log("mapalt2");
    map.addInteraction(draw);
    map.addInteraction(modify);
    map.addInteraction(selectClick);
//$(".mapalt1").fadeOut(function(){
//$(".mapalt2").fadeIn();
//});

}
});
});

                    </script>
  <div id="popup2" class="ol-popup">
            <a href="#" id="popup-closer2" class="ol-popup-closer"></a>
            <div id="popup-content2"></div>
        </div>
 <div id="popup" class="ol-popup">
		    <h4>WGS84 (grados, minutos, segundos)*</h4>
<div>
<center>
<label><input type="radio" name="optmap" value="mapalt2" >Ingreso en el mapa</label>
<label><input type="radio" name="optmap" value="mapalt1" checked>Ingreso por coordenadas</label>
</center>
</div>
<script>

</script>
<div class="row mapalt1">

<div class="col" style="border-style: groove; margin:10px; min-height:250px">
 <div id="poly" style="    
    background: whitesmoke;
">
</div>
</div>
<div class="col">

  <a id="popup-closer" class="ol-popup-closer"></a>
      <div id="popup-content" class="">
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="latitude">Latitud (S)</label>
                        <div class="col-md-9">
                            -<input class="form-control sexagesimal" id="latitude_degres" type="textbox" onkeyup="">
                            <label for="latitude_degres">°</label>
                            <input class="form-control sexagesimal" id="latitude_minutes" type="textbox" onkeyup="">
                            <label for="latitude_minutes">\'</label>
                            <input class="form-control sexagesimalsec" id="latitude_secondes" type="textbox" onkeyup="">
                            <label for="latitude_secondes">\'\'</label>
                        </div>
                    </div>
                
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="longitude">Longitud (O)</label>
                        <div class="col-md-9">
                            -<input class="form-control sexagesimal" id="longitude_degres" type="textbox" onkeyup="">
                            <label for="longitude_degres">°</label>
                            <input class="form-control sexagesimal" id="longitude_minutes" type="textbox" onkeyup="">
                            <label for="longitude_minutes">\'</label>
                            <input class="form-control sexagesimalsec" id="longitude_secondes" type="textbox" onkeyup="">
                            <label for="longitude_secondes">\'\'</label>
                        </div>
                    </div>
                    


  <div class="form-group">
    <a class="btn btn-success" id="agregar">Agregar</a>
    <a class="btn btn-warning" id="nextpoly">Siguente poligono</a>
        </div>
<a href="https://www.coordenadas-gps.com/convertidor-de-coordenadas-gps" target="_blank">Calculadora de coordenadas</a>
        </div>
	</div>    
</div>
</div>
</div>
                    <div id="map" class="map mapalt2"></div>
<p class="mapalt2">* Nota: Puede eliminar vértices presionando -Alt- y haciendo Clic sobre el vértice.</p>
<center > <a id="sate" class=" mapalt2 btn btn-info btn-small">Vista Rutas</a><center>
  <div id="popup3" class="coords">
</div>

';
         $display .= '<input id="' . $this->id . '"  type="text" class="form-control has-error" name="' . $this->nombre . '" value="'.$geom.'" />';
        return $display;
    }

    public function backendExtraFields()
    {
        $columns = array();
        if (isset($this->extra))
            $columns = $this->extra;
        $output = '
            <div class="columnas" ' . ($this->readonly == 0 ? 'style="display: none;"' : '') . '>
                 <script type="text/javascript">
                    $(document).ready(function() {
                        $("#formEditarCampo .columnas .nuevo").click(function() {
                            var pos=$("#formEditarCampo .columnas table tbody tr").length;
                            var html="<tr>";
                            html+="<td><input type=\'text\' class=\'form-control\' name=\'extra[" + pos + "][latitude]\' style=\'width:100px;\' /></td>";
                            html+="<td><input type=\'text\' class=\'form-control\' name=\'extra[" + pos + "][longitude]\' style=\'width:100px;\' /></td>";
                            html+="<td><input type=\'text\' class=\'form-control\' name=\'extra[" + pos + "][address]\' style=\'width:140px;\' /></td>";
                            html+="<td><button type=\'button\' class=\'btn btn-light eliminar\'><i class=\'material-icons\'>close</i> Eliminar</button></td>";
                            html+="</tr>";

                            $("#formEditarCampo .columnas table tbody").append(html);
                        });

                        $("#formEditarCampo .columnas").on("click", ".eliminar", function() {
                            $(this).closest("tr").remove();
                        });
                    });
                </script>
                <h4 class="mt-3">Columnas</h4>
                <button class="btn btn-light nuevo" type="button"><i class="material-icons">add</i> Nuevo</button>
                <table class="table mt-3">
                    <thead>
                        <tr>
                            <th>Latitud</th>
                            <th>Longitud</th>
                            <th>Dirección</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                    ';

        if ($columns) {
            $i = 0;
            foreach ($columns as $key => $c) {
                $output .= '
                <tr>
                    <td><input type="text" class="form-control" name="extra[' . $i . '][latitude]" style="width:100px;" value="' . $c->latitude . '" /></td>
                    <td><input type="text" class="form-control" name="extra[' . $i . '][longitude]" style="width:100px;" value="' . $c->longitude . '" /></td>
                    <td><input type="text" class="form-control" name="extra[' . $i . '][address]" style="width:140px;" value="' . $c->address . '" /></td>
                    <td><button type="button" class="btn btn-light eliminar"><i class="material-icons">close</i>Eliminar</button></td>
                </tr>
                ';
                $i++;
            }
        }

        $output .= '
        </tbody>
        </table>
        </div>
        ';

        return $output;
    }

}
