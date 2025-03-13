<?php
include 'db.php';

// Ensure data is received via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $event_id = $_POST['event_id'];
    $week = $_POST['week'];
    $date = $_POST['date'];
    $semester_event = $_POST['semester_event'];
    $committee = $_POST['committee'];

    $query = "UPDATE events SET week = ?, date = ?, semester_event = ?, committee = ? WHERE event_id = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$week, $date, $semester_event, $committee, $event_id]);

    echo json_encode(['status' => 'success']);
}
?>
