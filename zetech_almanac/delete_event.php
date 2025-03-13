<?php
session_start();

// Ensure session variables are set
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit;
}

include("db.php"); // Database connection

// Check if the event_id is provided
if (isset($_GET['event_id']) && isset($_GET['year'])) {
    $event_id = intval($_GET['event_id']); // Ensure event_id is an integer
    $year = intval($_GET['year']); // Ensure year is an integer


    // Prepare a query to delete the event
    $stmt = $conn->prepare("DELETE FROM events WHERE id = ?");
    $stmt->bind_param("i", $event_id);

    // Execute the deletion
    if ($stmt->execute()) {
        $stmt->close();
        $conn->close();
        // Redirect to dashboard or the same page after deletion
        header("Location: admin.php?year=$year");
    } else {
        echo "Error: " . $stmt->error;
    }
} else {
    echo "Event ID is missing!";
}
?>
