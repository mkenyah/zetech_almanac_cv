<?php
include("db.php");

if (isset($_POST['year'])) {
    $year = $_POST['year'];

    // Modify the query to filter based on year
    $eventQuery = "SELECT * FROM events WHERE YEAR(event_date) = ?";
    $stmt = $conn->prepare($eventQuery);
    $stmt->bind_param("i", $year);
    $stmt->execute();
    $result = $stmt->get_result();

    $events = [];
    while ($event = $result->fetch_assoc()) {
        $events[] = $event;
    }

    if (!empty($events)) {
        foreach ($events as $event) {
            // Format each event output as needed
            echo "<tr>";
            echo "<td>" . htmlspecialchars($event['week_no']) . "</td>";
            echo "<td>" . htmlspecialchars($event['event_date']) . "</td>";
            echo "<td>" . htmlspecialchars($event['semester_event']) . "</td>";
            echo "<td>" . htmlspecialchars($event['committee']) . "</td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='4' class='text-center'>No events found for this year.</td></tr>";
    }
}
?>
