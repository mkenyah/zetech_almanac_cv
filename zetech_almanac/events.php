<?php
include("db.php");

// Get selected year from AJAX request
$selectedYear = isset($_GET['year']) ? (int)$_GET['year'] : date("Y");

// Map semester_id to semester names
$semesterNames = [
    1 => "January - April",
    2 => "May - August",
    3 => "September - December"
];



function formatDateRange($date)
{
    $startDate = new DateTime($date);
    $endDate = clone $startDate;
    $endDate->modify('+6 days');

    $startDay = $startDate->format('j');
    $endDay = $endDate->format('j');

    $startOrdinal = getOrdinalSuffix($startDay);
    $endOrdinal = getOrdinalSuffix($endDay);

    return $startDay . $startOrdinal . ' ' . $startDate->format('M') . ' - ' . $endDay . $endOrdinal . ' ' . $endDate->format('M Y');
}


function getOrdinalSuffix($day)
{
    if ($day >= 11 && $day <= 13) {
        return 'th';
    }
    switch ($day % 10) {
        case 1:
            return 'st';
        case 2:
            return 'nd';
        case 3:
            return 'rd';
        default:
            return 'th';
    }
}


// Fetch semester dates from DB
$semesterQuery = "SELECT semester_id, start_date, end_date FROM dates";
$semesterResult = $conn->query($semesterQuery);
$semesters = [];

if ($semesterResult && $semesterResult->num_rows > 0) {
    while ($row = $semesterResult->fetch_assoc()) {
        $semesters[$row['semester_id']] = [
            'name' => $semesterNames[$row['semester_id']] ?? "Unknown Semester",
            'start' => $row['start_date'],
            'end' => $row['end_date']
        ];
    }
}

// Query events for the selected year
$query = "SELECT * FROM events WHERE YEAR(event_date) = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $selectedYear);
$stmt->execute();
$result = $stmt->get_result();

$eventsBySemester = [];

if ($result && $result->num_rows > 0) {
    while ($event = $result->fetch_assoc()) {
        $eventDate = $event['event_date'];
        $semesterName = "Unknown Semester";

        foreach ($semesters as $semester) {
            if ($eventDate >= $semester['start'] && $eventDate <= $semester['end']) {
                $semesterName = $semester['name'];
                break;
            }
        }

        if (!isset($eventsBySemester[$semesterName])) {
            $eventsBySemester[$semesterName] = [];
        }

        $eventsBySemester[$semesterName][] = $event;
    }
}

// Output JSON
header("Content-Type: application/json");
echo json_encode(["events" => $eventsBySemester]);
?>
