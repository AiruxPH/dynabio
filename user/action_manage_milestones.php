<?php
session_start();
require_once __DIR__ . '/../includes/db.php';

// Set JSON response headers
header('Content-Type: application/json');

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
if (!$data || !isset($data['action'])) {
    echo json_encode(['success' => false, 'message' => 'No action specified.']);
    exit;
}

$user_id = $_SESSION['user_id'];
$action = $data['action'];

try {
    if ($action === 'add') {
        $date = trim($data['date']);
        $title = trim($data['title']);
        $desc = trim($data['desc']);
        $icon = trim($data['icon']);

        if (empty($date) || empty($title)) {
            echo json_encode(['success' => false, 'message' => 'Date and Title are required.']);
            exit;
        }

        $stmt = $conn->prepare("INSERT INTO milestones (user_id, milestone_date, title, description, icon) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$user_id, $date, $title, $desc, $icon]);

        echo json_encode(['success' => true, 'message' => 'Milestone appended to journey.']);

    } elseif ($action === 'delete') {
        $ms_id = (int) $data['id'];

        // Ensure user owns milestone
        $stmt = $conn->prepare("DELETE FROM milestones WHERE milestone_id = ? AND user_id = ?");
        $stmt->execute([$ms_id, $user_id]);

        if ($stmt->rowCount() > 0) {
            echo json_encode(['success' => true, 'message' => 'Milestone deleted.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Milestone not found or permission denied.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Unknown action.']);
    }

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
