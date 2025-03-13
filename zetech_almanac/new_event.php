<?php 
session_start();

// Ensure session variables are set
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit;
}

// Database connection
include("db.php");

// Variables for the event form
$week = $date = $semester_event = $committee = "";
$event_id = null;

// If editing an event (event_id in URL), fetch event data
if (isset($_GET['event_id'])) {
    $event_id = $_GET['event_id'];

    // Fetch event data from database for the given event_id
    $sql = "SELECT * FROM events WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $event_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $event = $result->fetch_assoc();

    // Populate form fields with existing event data
    if ($event) {
        $week = $event['week_no'];
        $date = $event['event_date'];
        $semester_event = $event['semester_event'];
        $committee = $event['committee'];
    }
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['week'], $_POST['date'], $_POST['semester_event'], $_POST['committee'])) {
        $week = $_POST['week'];
        $date = $_POST['date'];
        $semester_event = $_POST['semester_event'];
        $committee = $_POST['committee'];

        if (isset($_POST['event_id']) && !empty($_POST['event_id'])) {
            // Update existing event
            $event_id = $_POST['event_id'];
            $sql = "UPDATE events SET week_no=?, event_date=?, semester_event=?, committee=? WHERE id=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssi", $week, $date, $semester_event, $committee, $event_id);
        } else {
            // Insert new event
            $sql = "INSERT INTO events (week_no, event_date, semester_event, committee) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssss", $week, $date, $semester_event, $committee);
        }

        if ($stmt->execute()) {
            echo "<script>
            alert('Event saved successfully.');
            window.location.href = 'new_event.php';
            </script>";
        } else {
            echo "Error: " . $stmt->error;
        }
    } else {
        echo "All fields are required.";
    }
}

// Fetch all events for display
$eventsQuery = "SELECT * FROM events";
$eventsResult = $conn->query($eventsQuery);

// Format date range
function formatDateRange($date) {
    $startDate = new DateTime($date);
    $endDate = clone $startDate;
    $endDate->modify('+6 days');

    $startDay = $startDate->format('j');
    $endDay = $endDate->format('j');

    $startOrdinal = getOrdinalSuffix($startDay);
    $endOrdinal = getOrdinalSuffix($endDay);

    return $startDay . $startOrdinal . ' ' . $startDate->format('M') . ' - ' . $endDay . $endOrdinal . ' ' . $endDate->format('M Y');
}

function getOrdinalSuffix($day) {
    if ($day >= 11 && $day <= 13) {
        return 'th';
    }
    switch ($day % 10) {
        case 1: return 'st';
        case 2: return 'nd';
        case 3: return 'rd';
        default: return 'th';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit or Add Event</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>

<a href="./admin.php">
    <i id="home_icon" class="fa fa-home"></i>
</a>

<div class="container mt-5">
    <h2>Edit or Add Event</h2>
    <form id="eventForm" action="new_event.php" method="POST">
        <input type="hidden" name="event_id" value="<?php echo $event_id ?? ''; ?>">

        <div class="form-group">
            <label for="week">Week:</label>
            <input type="text" class="form-control" id="week" name="week" value="<?php echo htmlspecialchars($week); ?>" required>
        </div>
        <div class="form-group">
            <label for="date">Date:</label>
            <input type="date" class="form-control" id="date" name="date" value="<?php echo htmlspecialchars($date); ?>" required>
        </div>
        <div class="form-group">
            <label for="semester_event">Semester Event:</label>
            <textarea class="form-control" id="semester_event" name="semester_event" required><?php echo htmlspecialchars($semester_event); ?></textarea>
        </div>
        <div class="form-group">
            <label for="committee">Committee:</label>
            <textarea class="form-control" id="committee" name="committee" required><?php echo htmlspecialchars($committee); ?></textarea> 
        </div>
        <button type="submit" class="btn btn-primary">Save Event</button>
    </form>

    <h3 class="mt-5">Existing Events</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Week</th>
                <th>Date</th>
                <th>Semester Event</th>
                <th>Committee</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $eventsResult->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['week_no']; ?></td>
                    <td><?php echo formatDateRange($row['event_date']); ?></td>
                    <td><?php echo $row['semester_event']; ?></td>
                    <td><?php echo $row['committee']; ?></td>
                    <td>
                        <a href="new_event.php?event_id=<?php echo $row['id']; ?>" class="btn btn-success btn-sm">Edit</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

</body>
</html>
