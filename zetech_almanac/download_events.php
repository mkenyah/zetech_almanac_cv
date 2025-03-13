<?php
include("db.php");

$selectedYear = intval($_GET['year']);
header('Content-Type: text/csv');
header("Content-Disposition: attachment; filename=events_$selectedYear.csv");

// Open output stream
$output = fopen('php://output', 'w');

// Set the CSV header
fputcsv($output, ['Week', 'Date', 'Semester Event', 'Committee']);

// Fetch events for the selected year
