<?php
    $pageTitle = "Bản Đồ Giao Hàng";
?>
<!DOCTYPE html>
<html>
<head>
    <title><?= $pageTitle ?></title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <style>
        html, body { height: 100%; margin: 0; padding: 0; }
        body { font-family: Arial, sans-serif; }
        #map { width: 100%; height: 100vh; }
        .info-panel {
            position: absolute;
            top: 20px;
            right: 20px;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
            width: 300px;
            z-index: 1000;
        }
        .info-panel h5 { margin: 0 0 15px 0; color: #333; }
        .info-row { display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #eee; }
        .info-row:last-child { border-bottom: none; }
        .info-row span:first-child { font-weight: bold; }
    </style>
</head>
<body>
    <div id="map"></div>
    <div class="info-panel">
        <h5>📦 Đơn hàng: <?= $order['ma_dh'] ?></h5>
        <div class="info-row">
            <span>Người nhận:</span>
            <strong><?= substr($order['ten_nguoinhan'], 0, 15) ?></strong>
        </div>
        <div class="info-row">
            <span>Khoảng cách:</span>
            <strong><span id="distance">--</span> km</strong>
        </div>
        <div class="info-row">
            <span>Thời gian:</span>
            <strong><span id="duration">--</span> phút</strong>
        </div>
        <a href="index.php?action=shipper" style="display: block; margin-top: 15px; padding: 10px; background: #007bff; color: white; text-align: center; border-radius: 5px; text-decoration: none;">← Quay lại</a>
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        const map = L.map('map').setView([21.0285, 105.8542], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap'
        }).addTo(map);

        // Delivery location
        const delLat = 21.0408;
        const delLng = 105.7931;
        
        // Shipper location
        let shipLat = null;
        let shipLng = null;

        let shipMarker, delMarker, line;

        function drawMap() {
            if (shipLat === null || shipLng === null) return;

            if (shipMarker) map.removeLayer(shipMarker);
            if (delMarker) map.removeLayer(delMarker);
            if (line) map.removeLayer(line);

            // Shipper - Blue
            shipMarker = L.circleMarker([shipLat, shipLng], {
                radius: 12,
                fillColor: '#0066ff',
                color: '#fff',
                weight: 2,
                opacity: 1,
                fillOpacity: 0.9
            }).addTo(map).bindPopup('📍 Shipper<br/>Lat: ' + shipLat.toFixed(4) + '<br/>Lng: ' + shipLng.toFixed(4));

            // Delivery - Red
            delMarker = L.circleMarker([delLat, delLng], {
                radius: 12,
                fillColor: '#ff0000',
                color: '#fff',
                weight: 2,
                opacity: 1,
                fillOpacity: 0.9
            }).addTo(map).bindPopup('🏠 Delivery<br/>Lat: ' + delLat.toFixed(4) + '<br/>Lng: ' + delLng.toFixed(4));

            // Line
            line = L.polyline([
                [shipLat, shipLng],
                [delLat, delLng]
            ], {
                color: '#0066ff',
                weight: 3,
                opacity: 0.8,
                dashArray: '5, 5'
            }).addTo(map);

            // Distance
            const R = 6371;
            const dLat = (delLat - shipLat) * Math.PI / 180;
            const dLng = (delLng - shipLng) * Math.PI / 180;
            const a = Math.sin(dLat/2) * Math.sin(dLat/2) +
                      Math.cos(shipLat * Math.PI / 180) * Math.cos(delLat * Math.PI / 180) *
                      Math.sin(dLng/2) * Math.sin(dLng/2);
            const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
            const km = (R * c).toFixed(2);
            const min = Math.round(km * 2.5);

            document.getElementById('distance').textContent = km;
            document.getElementById('duration').textContent = min;

            map.fitBounds(L.latLngBounds([[shipLat, shipLng]], [[delLat, delLng]]), {padding: [80, 80]});
        }

        // Function to save GPS to database
        function saveGPS(lat, lng) {
            fetch('index.php?action=shipper_update_location', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({lat: lat, lng: lng})
            }).catch(err => console.log('Save GPS error:', err));
        }

        // Get GPS immediately
        if ("geolocation" in navigator) {
            navigator.geolocation.getCurrentPosition(
                pos => {
                    shipLat = pos.coords.latitude;
                    shipLng = pos.coords.longitude;
                    console.log('Got GPS:', shipLat, shipLng);
                    saveGPS(shipLat, shipLng);  // Save immediately
                    drawMap();
                },
                err => console.log('GPS denied')
            );
        }

        // Update every 5 sec
        setInterval(() => {
            if ("geolocation" in navigator) {
                navigator.geolocation.getCurrentPosition(
                    pos => {
                        const newLat = pos.coords.latitude;
                        const newLng = pos.coords.longitude;
                        if (Math.abs(newLat - shipLat) > 0.0001 || Math.abs(newLng - shipLng) > 0.0001) {
                            shipLat = newLat;
                            shipLng = newLng;
                            saveGPS(shipLat, shipLng);  // Save when changed
                            drawMap();
                        }
                    },
                    err => {}
                );
            }
        }, 5000);
    </script>
</body>
</html>
