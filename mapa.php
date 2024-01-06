<!DOCTYPE html>

  <?php
    session_start();

    $ses = 0; 

    if (isset($_SESSION['validar']) && $_SESSION['validar'] == 1) {
        $ses = 1;
    }
  ?>

<html>

<head>
  <link rel="stylesheet" href="style.css">
  <title>Geolocalización</title>
  <script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
  <script>
    let map, infoWindow, searchBox, marker, directionsService, directionsRenderer;

    function initMap() {
      map = new google.maps.Map(document.getElementById("map"), {
        zoom: 14
      });

      infoWindow = new google.maps.InfoWindow();
      directionsService = new google.maps.DirectionsService();
      directionsRenderer = new google.maps.DirectionsRenderer();

      

      if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
          position => {
            const pos = {
              lat: position.coords.latitude,
              lng: position.coords.longitude
            };

            infoWindow.setPosition(pos);
            infoWindow.setContent("Ubicacion actual.");
            infoWindow.open(map);
            map.setCenter(pos);
            // Mostrar información de posición actual
            document.getElementById("position-info").innerText = `Posicion Actual: ${pos.lat}, ${pos.lng}`;
          },
          () => {
            handleLocationError(true, infoWindow, map.getCenter());
          }
        );
      } else {
        handleLocationError(false, infoWindow, map.getCenter());
      }

      const input = document.createElement("input");
      input.type = "text";
      input.placeholder = "Search places...";
      input.classList.add("custom-map-control-button");

      map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

      searchBox = new google.maps.places.SearchBox(input);

      searchBox.addListener("places_changed", () => {
        const places = searchBox.getPlaces();

        if (places.length === 0) {
          return;
        }

        if (marker) {
          marker.setMap(null);
        }
        directionsRenderer.setMap(null);

        const place = places[0];
        marker = new google.maps.Marker({
          map,
          position: place.geometry.location
        });

        marker.addListener("click", () => {
          infoWindow.setContent(place.name);
          infoWindow.open(map, marker);
        });

        map.setCenter(place.geometry.location);

        calculateAndDisplayRoute(place.geometry.location);

        // Mostrar información del destino
        document.getElementById("destination-info").innerText = `Destino: ${place.geometry.location.lat()}, ${place.geometry.location.lng()}`;
      });

      map.addListener("dblclick", event => {
        if (marker) {
          marker.setMap(null);
        }
        directionsRenderer.setMap(null);

        addMarker(event.latLng);

        calculateAndDisplayRoute(event.latLng);

        // Mostrar información del destino al hacer doble clic en el mapa
        document.getElementById("destination-info").innerText = `Destino: ${event.latLng.lat()}, ${event.latLng.lng()}`;
      });
    }

    function handleLocationError(browserHasGeolocation, infoWindow, pos) {
      infoWindow.setPosition(pos);
      infoWindow.setContent(
        browserHasGeolocation ?
        "Error: The Geolocation service failed." :
        "Error: Your browser doesn't support geolocation."
      );
      infoWindow.open(map);
    }

    function addMarker(location) {
      marker = new google.maps.Marker({
        position: location,
        map
      });

      marker.addListener("click", () => {
        infoWindow.setContent("Posicion del Marcador: " + location.toUrlValue(6));
        infoWindow.open(map, marker);
      });
    }

    function calculateAndDisplayRoute(destination) {
      directionsService.route({
        origin: infoWindow.getPosition(),
        destination: destination,
        travelMode: google.maps.TravelMode.DRIVING
      }, (response, status) => {
        if (status === "OK") {
          directionsRenderer.setDirections(response);
          directionsRenderer.setMap(map);

          const route = response.routes[0];
          const routeInfo = `Distancia: ${route.legs[0].distance.text}, Duracion: ${route.legs[0].duration.text}`;
          document.getElementById("route-info").innerText = routeInfo;
        } else {
          window.alert("Directions request failed due to " + status);
        }
      });
    }

    function calculateRoute(origin, destination) {
      directionsService.route({
        origin: origin,
        destination: destination,
        travelMode: google.maps.TravelMode.DRIVING
      }, (response, status) => {
        if (status === "OK") {
          directionsRenderer.setDirections(response);
          directionsRenderer.setMap(map);
          const route = response.routes[0];
          const routeInfo = `Distancia: ${route.legs[0].distance.text}, Duración: ${route.legs[0].duration.text}`;
          document.getElementById("route-info").innerText = routeInfo;
        } else {
          window.alert("Error al calcular la ruta debido a " + status);
        }
      });
    }

    function loadFavoriteMarkers() {
      const iconSize = 48;
      const favoriteLocations = [
        <?php  
          require "conect.php";

          $con = conecta();
          $id = $_SESSION['id_usuario'];
          $sql = "SELECT * FROM favoritos WHERE id_usuario=$id";
          $res        =$con->query($sql);
          $filas      =$res->num_rows;
          
          while ($row = $res->fetch_array()) {
            $latitud        =$row["latitud"];
            $longitud       =$row["longitud"];
            $descripcion     =$row["descripcion"];
            echo "{ lat: $latitud,  lng: $longitud, description: '$descripcion' },";
          }
          
          ?>
      ];

      favoriteLocations.forEach(location => {
        const favoriteMarker = new google.maps.Marker({
          position: { lat: location.lat, lng: location.lng },
          map: map,
          title: location.description,
          icon: {
            url: 'https://cdn.icon-icons.com/icons2/2444/PNG/512/favorite_location_favorite_place_location_map_icon_148668.png',
            scaledSize: new google.maps.Size(iconSize, iconSize),
          },
        });

        favoriteMarker.addListener('click', () => {
          infoWindow.setContent(location.description);
          infoWindow.open(map, favoriteMarker);
          calculateRoute(infoWindow.getPosition(), location);
        });
      });
    }

    

    function saveLocationInfo(position, place) {
      const latitud = position.lat();
      const longitud = position.lng();
      const iconSize = 48;

      const description = document.querySelector('input[name="description"]').value;

      if (!description) {
        alert('Por favor, ingrese una descripcion antes de guardar.');
        return;
      }
      
      const favoriteMarker = new google.maps.Marker({
        position: position,
        map: map,
        title: place.name,
        icon: {
          url: 'https://cdn.icon-icons.com/icons2/2444/PNG/512/favorite_location_favorite_place_location_map_icon_148668.png',
          scaledSize: new google.maps.Size(iconSize, iconSize),
        }, 
      });

      fetch('save_location.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `latitud=${latitud}&longitud=${longitud}&description=${description}`,
    })
      .then(response => response.text())  // Cambiado a response.text()
      .then(data => {
          console.log(data); // Muestra el contenido completo de la respuesta en la consola

          // Mostrar una ventana emergente si la respuesta del servidor indica éxito
          if (data.trim() === 'success') {
              alert('Ubicacion guardada exitosamente.');
              
          } else {
              alert('Error al guardar la ubicacion.');
          }
      })
      .catch(error => {
          console.error('Error:', error);
      });
      }
  </script>
</head>

<body>
  <nav>
    <a href="index.php">Inicio</a>
    <a href="mapa.php">Mapa</a>
    <?php
        if(!$ses){
            echo "<a href='sesion.php'>Iniciar Sesión</a>";
            echo "<a href='register.php'>Registrarse</a>";
        }
        else{
            echo "<a href='index.php'>Perfil</a>";
            echo "<a href='close_sesion.php'>Cerrar sesión</a>";
        }
    ?>
    <div class="dropdown">
      <button class="dropbtn">Contacto</button>
      <div class="dropdown-content">
        <a href="#email">Correo Electrónico</a>
        <a href="#phone">Teléfono</a>
      </div>
    </div>
  </nav>
  <div id="map"></div>
  <div id="position-info"></div>
  <div id="destination-info"></div>
  <div id="route-info"></div>
  <div id="description-info"><div name="description-text">Descripcion:</div><br><input type="text" name="description" placeholder="Ingrese la descripcion" style="width: 400px; word-wrap: break-word;"></div>
  <div id="button-container"><button class="save-button">Guardar marcador como Favorito</button></div>
  <div id="button-container"><button class="charge-button">Cargar favoritos</button></div>
  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC40vUgddWkU1g8MU6truYa0eCtXbETxFo&callback=initMap&libraries=places&v=weekly" defer></script>
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      document.querySelector('.save-button').addEventListener('click', function () {
        saveLocationInfo(marker.getPosition(), infoWindow.getContent());
      });
    });
    document.addEventListener('DOMContentLoaded', function () {
      loadFavoriteMarkers();
    });
  </script>
</body>

</html>

