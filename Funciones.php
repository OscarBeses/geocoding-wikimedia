<?php

class Funciones
{

    /**
     * GOOGLE GEOCODING API
     *
     * Se hace una petición a la API geocoding de google y se analiza el json recuperado
     * para conseguir las coordenadas del lugar escrito.
     */
    public function getLatLongConDireccion($direccion, $apiKey)
    {
        $urlApiGoogle = "https://maps.googleapis.com/maps/api/geocode/json";
        $urlApiGoogle .="?address=" . urlencode($direccion) . "&key=$apiKey";
        
        $jsonConCoordenadas = file_get_contents($urlApiGoogle);
        $arrayConCoordenadas = json_decode($jsonConCoordenadas, true);

        $resultados = $arrayConCoordenadas['results'];

        if (sizeof($resultados) > 0) {
            $location = $resultados[0]['geometry']['location'];
            $lat = $location["lat"];
            $long = $location["lng"];
            return array(
                'lat' => $lat,
                'lng' => $long
            );
        } else 
            return null;
    }

    /**
     * WIKIMEDIA API
     *
     * Se hace una petición a la API de wikimedia y se analiza el json recuperado
     * para conseguir unicamente la url que es lo que interesa.
     * 
     * No necesita de API KEY para esta solicitud.
     */
    public function getUrlConCoords($coords)
    {
        $coordsConBarra = $coords['lat'] . '|' . $coords['lng'];

        $wikimediaUrl = "https://commons.wikimedia.org/w/api.php?";
        $wikimediaUrl .= "format=json&action=query&generator=geosearch&ggsprimary=all&ggsnamespace=6&ggsradius=500";
        $wikimediaUrl .= "&ggscoord=$coordsConBarra&ggslimit=1&prop=imageinfo&iilimit=1&iiprop=url&iiurlwidth=200&iiurlheight=200";

        $stringWikimedia = file_get_contents($wikimediaUrl);
        $jsonWikimedia = json_decode($stringWikimedia, true);

        if (array_key_exists('query', $jsonWikimedia)) {
            $jsonConUrl = $jsonWikimedia['query']['pages'];
            // reset establece el puntero interno del array en su primer elemento
            $pagina = reset($jsonConUrl);
            echo '<br/><br/>';
            echo 'Y se ha conseguida esta foto de WIKIMEDIA:<br/>';
            return $pagina['imageinfo'][0]['thumburl'];
        } else {
            echo '<p>No se ha encontrado imagen en wikimedia</p>';
            return null;
        }
    }

    /**
     * GOOGLE STATIC MAP API
     *
     * Únicamente se monta la URL y se devuelve sin hacer petición a la API,
     * la petición a la API se hace dentro del tag img pero devuelve directamente una foto en lugar de un JSON
     * 
     * Se necesita usar la API KEY facilitada por google
     */
    public function getUrlUbicacionMaps($coords, $apiKey)
    {
        $coordsConComa = $coords['lat'] . ',' . $coords['lng'];
        $ubicacionMapsURL = "https://maps.googleapis.com/maps/api/staticmap?center=$coordsConComa&zoom=12&size=300x200&key=$apiKey";
        return $ubicacionMapsURL;
    }

}
?>