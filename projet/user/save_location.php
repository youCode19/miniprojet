<?php
// /projet/user/save_location.php
define('ROOT_PATH', dirname(__DIR__, 2)); // Adjust path as needed to reach ROOT_PATH
require_once ROOT_PATH . '/../includes/session_init.php'; // Start session
require_once ROOT_PATH . '/../includes/config.php'; // For database connection if needed

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);

    if (isset($input['lat']) && isset($input['lon'])) {
        $lat = $input['lat'];
        $lon = $input['lon'];

        // Store in session
        $_SESSION['last_known_location'] = ['latitude' => $lat, 'longitude' => $lon];

        echo json_encode(['status' => 'success', 'message' => 'Location saved to session.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid data.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}
?>