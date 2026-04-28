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
    <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <style>
        html, body { height: 100%; margin: 0; padding: 0; }
        body { font-family: 'Inter', sans-serif; background: #f0f2f5; }
        #map { width: 100%; height: 100vh; }
        
        .info-panel {
            position: absolute;
            bottom: 30px;
            left: 50%;
            transform: translateX(-50%);
            background: white;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
            width: 90%;
            max-width: 400px;
            z-index: 1000;
            border: 1px solid rgba(0,0,0,0.05);
        }
        
        .info-panel h5 { 
            margin: 0 0 15px 0; 
            color: #1a1a1a; 
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .info-panel h5 i { color: #0066ff; }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-bottom: 15px;
        }

        .info-box {
            background: #f8f9fa;
            padding: 12px;
            border-radius: 10px;
            text-align: center;
        }

        .info-box span {
            display: block;
            font-size: 12px;
            color: #6c757d;
            margin-bottom: 4px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .info-box strong {
            font-size: 18px;
            color: #212529;
            font-weight: 700;
        }

        .address-box {
            background: #eef2ff;
            padding: 12px;
            border-radius: 10px;
            margin-bottom: 15px;
            font-size: 14px;
            color: #4338ca;
            display: flex;
            align-items: flex-start;
            gap: 10px;
        }

        .address-box i { margin-top: 3px; }

        .btn-back-map {
            display: block;
            width: 100%;
            padding: 12px;
            background: #1a1a1a;
            color: white;
            text-align: center;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.2s;
        }
        
        .btn-back-map:hover {
            background: #333;
            color: white;
            text-decoration: none;
            transform: translateY(-2px);
        }
        
        /* Custom Routing UI */
        .leaflet-routing-container { display: none; }
        
        /* Pulse Animation for Shipper */
        .shipper-icon-container {
            position: relative;
        }
        .shipper-pulse {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 40px;
            height: 40px;
            background: rgba(0, 102, 255, 0.3);
            border-radius: 50%;
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0% { transform: translate(-50%, -50%) scale(0.5); opacity: 1; }
            100% { transform: translate(-50%, -50%) scale(2.5); opacity: 0; }
        }
    </style>
</head>
<body>
    <div id="map"></div>
    <div class="info-panel">
        <h5><i class="fas fa-box"></i> Đơn hàng #<?= $order['ma_dh'] ?></h5>
        
        <div class="address-box">
            <i class="fas fa-map-marker-alt"></i>
            <span><?= $fullAddress ?></span>
        </div>

        <div class="info-grid">
            <div class="info-box">
                <span>Khoảng cách</span>
                <strong><span id="distance">--</span> km</strong>
            </div>
            <div class="info-box">
                <span>Thời gian</span>
                <strong><span id="duration">--</span> phút</strong>
            </div>
        </div>

        <div class="info-row mb-3" style="font-size: 14px; color: #666; text-align: center;">
            <i class="fas fa-user"></i> Khách hàng: <strong><?= htmlspecialchars($order['ten_nguoinhan']) ?></strong>
        </div>

        <a href="index.php?action=shipper" class="btn-back-map"> Quay lại danh sách</a>
    </div>

    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.js"></script>
    <script>
        const map = L.map('map', {
            zoomControl: false
        }).setView([<?= $deliveryLat ?>, <?= $deliveryLng ?>], 13);

        L.control.zoom({ position: 'topright' }).addTo(map);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap'
        }).addTo(map);

        // Icons
        const shipperIcon = L.divIcon({
            className: 'shipper-icon-container',
            html: '<div class="shipper-pulse"></div><img src="https://cdn-icons-png.flaticon.com/512/2830/2830305.png" style="width: 40px; height: 40px; position: relative; z-index: 2;">',
            iconSize: [40, 40],
            iconAnchor: [20, 20]
        });

        const customerIcon = L.icon({
            iconUrl: 'https://cdn-icons-png.flaticon.com/512/149/149059.png',
            iconSize: [45, 45],
            iconAnchor: [22, 45],
            popupAnchor: [0, -45]
        });

        // Delivery location from PHP
        let delLat = <?= $deliveryLat ?>;
        let delLng = <?= $deliveryLng ?>;
        const fullAddress = "<?= addslashes($fullAddress) ?>";
        
        // Shipper location
        let shipLat = null;
        let shipLng = null;

        let shipMarker, delMarker, routingControl;
        let firstLoad = true;

        // Try to geocode if using default coordinates
        async function geocodeWithFallback(address) {
            let parts = address.split(',').map(p => p.trim());
            while (parts.length > 0) {
                let query = parts.join(', ');
                try {
                    let res = await fetch('https://nominatim.openstreetmap.org/search?format=json&limit=1&q=' + encodeURIComponent(query));
                    let data = await res.json();
                    if (data && data.length > 0) return data[0];
                } catch (e) {
                    console.error("Geocoding API error:", e);
                }
                parts.shift();
            }
            return null;
        }

        if (delLat === 10.7769 && delLng === 106.6964 && fullAddress) {
            geocodeWithFallback(fullAddress).then(result => {
                if (result) {
                    delLat = parseFloat(result.lat);
                    delLng = parseFloat(result.lon);
                    console.log('Geocoded address:', delLat, delLng);
                    map.setView([delLat, delLng], 13);
                    drawMap();
                }
            });
        }

        function drawMap() {
            if (shipLat === null || shipLng === null) return;

            const shipLatLng = L.latLng(shipLat, shipLng);
            const delLatLng = L.latLng(delLat, delLng);

            // Update or create shipper marker
            if (shipMarker) {
                shipMarker.setLatLng(shipLatLng);
            } else {
                shipMarker = L.marker(shipLatLng, { icon: shipperIcon }).addTo(map).bindPopup('📍 Vị trí của bạn');
            }

            // Update or create delivery marker
            if (!delMarker) {
                delMarker = L.marker(delLatLng, { icon: customerIcon }).addTo(map).bindPopup('🏠 Điểm giao hàng');
            }

            // Routing
            if (routingControl) {
                routingControl.setWaypoints([shipLatLng, delLatLng]);
            } else {
                routingControl = L.Routing.control({
                    waypoints: [shipLatLng, delLatLng],
                    routeWhileDragging: false,
                    addWaypoints: false,
                    draggableWaypoints: false,
                    fitSelectedRoutes: false, // We handle fitting manually
                    showAlternatives: false,
                    lineOptions: {
                        styles: [{ color: '#0066ff', opacity: 0.7, weight: 6 }]
                    },
                    createMarker: function() { return null; }
                }).addTo(map);

                routingControl.on('routesfound', function(e) {
                    const routes = e.routes;
                    const summary = routes[0].summary;
                    
                    const km = (summary.totalDistance / 1000).toFixed(2);
                    const min = Math.round(summary.totalTime / 60);

                    document.getElementById('distance').textContent = km;
                    document.getElementById('duration').textContent = min;
                });
            }

            // Auto-fit map to show both markers on first load
            if (firstLoad) {
                const group = new L.featureGroup([shipMarker, delMarker]);
                map.fitBounds(group.getBounds().pad(0.2));
                firstLoad = false;
            }
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
