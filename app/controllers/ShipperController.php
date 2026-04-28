<?php

require_once BASE_PATH . '/app/core/Controller.php';
require_once BASE_PATH . '/models/BaseModel.php';

class ShipperController extends Controller
{
    private function guardShipper()
    {
        if (!isset($_SESSION['id_nd']) || $_SESSION['quyennd'] != 3) {
            header('Location: index.php?action=dangnhap');
            exit;
        }
    }

    public function index()
    {
        $this->guardShipper();
        $store = new StoreModel();
        
        $id_shipper = $_SESSION['id_nd'];
        $myOrders = $store->getShipperOrders($id_shipper);
        $availableOrders = $store->getAvailableOrders();

        $this->renderLegacy('shipper', [
            'orders' => $myOrders,
            'availableOrders' => $availableOrders
        ]);
    }

    public function updateStatus()
    {
        $this->guardShipper();
        
        if (!SecurityHelper::verifyCsrf($_POST['csrf_token'] ?? '')) {
            die('CSRF token validation failed');
        }

        $id_dh = (int)($_POST['id_dh'] ?? 0);
        $status = (int)($_POST['trang_thai'] ?? 3); // Default to Hoàn thành (3)
        $id_shipper = $_SESSION['id_nd'];

        if ($id_dh > 0) {
            $store = new StoreModel();
            $order = $store->orderById($id_dh);
            // Verify order belongs to this shipper
            if ($order && $order['id_shipper'] == $id_shipper) {
                $store->updateOrderStatus($id_dh, $status);
            }
        }
        
        header('Location: index.php?action=shipper');
        exit;
    }

    public function acceptOrder()
    {
        $this->guardShipper();
        
        if (!SecurityHelper::verifyCsrf($_POST['csrf_token'] ?? '')) {
            die('CSRF token validation failed');
        }

        $id_dh = (int)($_POST['id_dh'] ?? 0);
        $id_shipper = $_SESSION['id_nd'];

        if ($id_dh > 0) {
            $store = new StoreModel();
            $order = $store->orderById($id_dh);
            // Verify order is waiting for shipper
            if ($order && $order['trang_thai'] == 2 && (empty($order['id_shipper']) || $order['id_shipper'] == 0)) {
                $store->assignShipper($id_dh, $id_shipper);
            }
        }
        
        header('Location: index.php?action=shipper');
        exit;
    }

    // Endpoint called via AJAX to broadcast location
    public function updateLocation()
    {
        $this->guardShipper();
        header('Content-Type: application/json');

        $input = json_decode(file_get_contents('php://input'), true);
        if ($input && isset($input['lat']) && isset($input['lng'])) {
            $store = new StoreModel();
            $success = $store->updateShipperLocation($_SESSION['id_nd'], $input['lat'], $input['lng']);
            echo json_encode(['success' => $success]);
            exit;
        }

        echo json_encode(['success' => false, 'message' => 'Invalid data']);
        exit;
    }

    // Endpoint called via AJAX by Admin or User to get Shipper location
    public function getLocation()
    {
        header('Content-Type: application/json');
        $id_shipper = (int)($_GET['id_shipper'] ?? 0);
        if ($id_shipper > 0) {
            $store = new StoreModel();
            $loc = $store->getShipperLocation($id_shipper);
            if ($loc) {
                echo json_encode(['lat' => $loc['lat'], 'lng' => $loc['lng']]);
                exit;
            }
        }
        echo json_encode(['error' => 'Not found']);
        exit;
    }

    // View map for delivery
    public function viewMap()
    {
        $this->guardShipper();
        
        $id_dh = (int)($_GET['id_dh'] ?? 0);
        if ($id_dh <= 0) {
            header('Location: index.php?action=shipper');
            exit;
        }

        $store = new StoreModel();
        $order = $store->orderById($id_dh);
        
        // Verify order belongs to this shipper
        if (!$order || $order['id_shipper'] != $_SESSION['id_nd']) {
            header('Location: index.php?action=shipper');
            exit;
        }

        // Get default delivery coordinates (for Vietnam, using center of target area)
        // In production, you'd geocode the address to get actual coordinates
        $deliveryLat = $order['delivery_lat'] ?? 10.7769;  // Default to Saigon
        $deliveryLng = $order['delivery_lng'] ?? 106.6964;

        // If coordinates not in DB, use simple geocoding based on district
        if (!$order['delivery_lat']) {
            // Simple mapping of common Vietnamese areas to coordinates
            $areaCoordinates = [
                'q1' => ['lat' => 10.7769, 'lng' => 106.6964],
                'q2' => ['lat' => 10.7594, 'lng' => 106.8058],
                'q3' => ['lat' => 10.7866, 'lng' => 106.6918],
                'q4' => ['lat' => 10.7752, 'lng' => 106.7017],
                'q5' => ['lat' => 10.7382, 'lng' => 106.6733],
                'q6' => ['lat' => 10.7491, 'lng' => 106.6286],
                'q7' => ['lat' => 10.7351, 'lng' => 106.7301],
                'q8' => ['lat' => 10.7564, 'lng' => 106.7305],
                'q9' => ['lat' => 10.7588, 'lng' => 106.8029],
                'q10' => ['lat' => 10.7833, 'lng' => 106.6754],
                'q11' => ['lat' => 10.8199, 'lng' => 106.6827],
                'q12' => ['lat' => 10.8767, 'lng' => 106.7743],
                'bn' => ['lat' => 10.8358, 'lng' => 106.7316],
                'gv' => ['lat' => 10.9022, 'lng' => 106.8439],
                'tq' => ['lat' => 10.9171, 'lng' => 106.5644],
                'tl' => ['lat' => 10.5964, 'lng' => 106.6318],
                'vt' => ['lat' => 10.4726, 'lng' => 106.8156],
            ];

            // Extract first few characters to match district
            $district = strtolower(substr($order['diachi_nguoinhan'], 0, 3));
            if (isset($areaCoordinates[$district])) {
                $deliveryLat = $areaCoordinates[$district]['lat'];
                $deliveryLng = $areaCoordinates[$district]['lng'];
            }
        }

        $this->renderLegacy('shipper_map', [
            'order' => $order,
            'deliveryLat' => $deliveryLat,
            'deliveryLng' => $deliveryLng
        ]);
    }
}
