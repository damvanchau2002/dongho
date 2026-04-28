<!DOCTYPE html>
<html>
<head>
    <title>Test Bản Đồ</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.css" />
    <style>
        body { margin: 0; padding: 20px; font-family: Arial, sans-serif; }
        #map { width: 100%; height: 600px; border: 2px solid #ccc; }
        .info { margin-top: 20px; padding: 20px; background: #f0f0f0; border-radius: 10px; }
        .leaflet-routing-container { display: none; }
    </style>
</head>
<body>
    <h1>Test Bản Đồ Shipper</h1>
    <div id="map"></div>
    <div class="info">
        <p><strong>Vị trí Shipper:</strong> <span id="shipperPos">--</span></p>
        <p><strong>Vị trí Giao Hàng:</strong> <span id="deliveryPos">--</span></p>
        <p><strong>Khoảng cách:</strong> <span id="distance">--</span> km</p>
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.js"></script>
    <script>
        // Hardcode coordinates for testing
        const shipperLat = 21.0285;
        const shipperLng = 105.8542;
        const deliveryLat = 21.0408;
        const deliveryLng = 105.7931;

        console.log('Starting map...');
        
        // Create map
        const map = L.map('map').setView([21.02, 105.81], 13);
        console.log('Map created');

        // Add tiles
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);
        console.log('Tiles loaded');

        // Add shipper marker
        const shipperMarker = L.circleMarker([shipperLat, shipperLng], {
            radius: 10,
            fillColor: '#0066ff',
            color: '#fff',
            weight: 3,
            opacity: 1,
            fillOpacity: 0.9
        }).addTo(map);
        shipperMarker.bindPopup('<b>Shipper</b>').openPopup();
        console.log('Shipper marker added at', [shipperLat, shipperLng]);

        // Add delivery marker
        const deliveryMarker = L.circleMarker([deliveryLat, deliveryLng], {
            radius: 10,
            fillColor: '#ff0000',
            color: '#fff',
            weight: 3,
            opacity: 1,
            fillOpacity: 0.9
        }).addTo(map);
        deliveryMarker.bindPopup('<b>Delivery</b>');
        console.log('Delivery marker added at', [deliveryLat, deliveryLng]);

        // Routing instead of straight line
        const routingControl = L.Routing.control({
            waypoints: [
                L.latLng(shipperLat, shipperLng),
                L.latLng(deliveryLat, deliveryLng)
            ],
            routeWhileDragging: false,
            addWaypoints: false,
            draggableWaypoints: false,
            createMarker: function() { return null; },
            lineOptions: {
                styles: [{ color: '#0066ff', opacity: 0.7, weight: 5 }]
            }
        }).addTo(map);

        routingControl.on('routesfound', function(e) {
            const routes = e.routes;
            const summary = routes[0].summary;
            const km = (summary.totalDistance / 1000).toFixed(2);
            document.getElementById('distance').textContent = km;
            console.log('Route distance:', km, 'km');
        });

        // Update info
        document.getElementById('shipperPos').textContent = shipperLat.toFixed(4) + ', ' + shipperLng.toFixed(4);
        document.getElementById('deliveryPos').textContent = deliveryLat.toFixed(4) + ', ' + deliveryLng.toFixed(4);

        console.log('Map ready!');
    </script>
</body>
</html>
