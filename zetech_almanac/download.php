<?php
session_start();
include("db.php");

// Set headers for CSV download
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=events.csv');

// Open output stream
$output = fopen('php://output', 'w');

// Write the column headers (excluding "Actions")
fputcsv($output, ['Week', 'Date', 'January-April 2025 Semester', 'Committee/Meeting']);

// Fetch data from database
$stmt = $conn->prepare("SELECT week, date, semester_event, committee FROM events");
$stmt->execute();
$result = $stmt->get_result();

// Write rows to CSV
while ($row = $result->fetch_assoc()) {
    fputcsv($output, $row);
}

// Close file pointer
fclose($output);
exit;
?>
