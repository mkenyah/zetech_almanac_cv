<?php
include("db.php");

error_reporting(E_ALL);
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['id'], $_POST['week'], $_POST['date'], $_POST['semester_event'], $_POST['committee'])) {
        $id = $_POST['id'];  // Ensure id is received
        $week = $_POST['week'];
        $date = $_POST['date'];
        $semester_event = $_POST['semester_event'];
        $committee = $_POST['committee'];

        $sql = "UPDATE events SET week=?, date=?, semester_event=?, committee=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssi", $week, $date, $semester_event, $committee, $id);

        if ($stmt->execute()) {
            echo "Event updated successfully";
        } else {
            echo "Error updating event: " . $stmt->error;
        }
    } else {
        echo "Missing required form data.";
    }
}
?>
