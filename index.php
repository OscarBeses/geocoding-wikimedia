<?php
include 'Funciones.php';
include 'utiles.php';

cabecera("3 peticiones API");
include 'formulario.php';

//La api key de google
$apiKey = 'REPLACE_WITH_YOUR_OWN_G_MAPS_GEOCODING_KEY';
$direccion = recoge('direccion');
if (!empty($direccion)) {
    
    echo "<br/>";
    echo "Se ha escrito esta dirección: <b>" . $direccion . "</b>";
    //-----------GOOGLE GEOCODING API-----------
    $coords = Funciones::getLatLongConDireccion($direccion, $apiKey);
    if ($coords) {
        
        echo "<br/><br/>";
        echo "Se han conseguido estas coordenadas de GOOGLE GEOCODING: ". $coords['lat'] .', '.$coords['lng'];
        //-----------WIKIMEDIA API-----------
        $urlFotoWikimedia = Funciones::getUrlConCoords($coords);
        echo "<img src='$urlFotoWikimedia'>";
        
        echo "<br/><br/>";
        echo "Y se ha encontrado la ubicación en GOOGLE STATIC MAP aquí: <br/>";
        //-----------GOOGLE STATIC MAP API-----------
        echo "<img src='".Funciones::getUrlUbicacionMaps($coords, $apiKey)."'>";
    } else{
        echo "<br/>";
        echo "No se encontraron coordenadas validad en Google Maps Geocoding
            y por tanto no se pueden buscar las imagenes de WIKIMEDIA ni en MAPS";
    }
}

pie();
?>