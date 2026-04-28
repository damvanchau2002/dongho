<!DOCTYPE html>
<html>
<head>
    <title>Test Bản Đồ</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        body { margin: 0; padding: 20px; font-family: Arial, sans-serif; }
        #map { width: 100%; height: 600px; border: 2px solid #ccc; }
        .info { margin-top: 20px; padding: 20px; background: #f0f0f0; border-radius: 10px; }
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

        // Draw line
        const line = L.polyline([
            [shipperLat, shipperLng],
            [deliveryLat, deliveryLng]
        ], {
            color: '#0066ff',
            weight: 4,
            opacity: 0.8,
            dashArray: '5, 5'
        }).addTo(map);
        console.log('Line drawn');

        // Calculate distance
        function calculateDistance(lat1, lon1, lat2, lon2) {
            const R = 6371;
            const dLat = (lat2 - lat1) * Math.PI / 180;
            const dLon = (lon2 - lon1) * Math.PI / 180;
            const a = 
                Math.sin(dLat/2) * Math.sin(dLat/2) +
                Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
                Math.sin(dLon/2) * Math.sin(dLon/2);
            const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
            return R * c;
        }

        const distance = calculateDistance(shipperLat, shipperLng, deliveryLat, deliveryLng);
        console.log('Distance:', distance);

        // Update info
        document.getElementById('shipperPos').textContent = shipperLat.toFixed(4) + ', ' + shipperLng.toFixed(4);
        document.getElementById('deliveryPos').textContent = deliveryLat.toFixed(4) + ', ' + deliveryLng.toFixed(4);
        document.getElementById('distance').textContent = distance.toFixed(2);

        // Fit bounds
        const bounds = L.latLngBounds([[shipperLat, shipperLng]], [[deliveryLat, deliveryLng]]);
        map.fitBounds(bounds, {padding: [100, 100]});
        console.log('Map ready!');
    </script>
</body>
</html>
