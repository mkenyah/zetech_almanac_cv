<?php
// Database connection
include("db.php");

// Ensure the ID is provided
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch event data from the database
    $stmt = $conn->prepare("SELECT * FROM events WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        // Fetch the data and return it as JSON
        $event = $result->fetch_assoc();
        echo json_encode($event);
    } else {
        echo json_encode(["error" => "Event not found."]);
    }
}
?>
