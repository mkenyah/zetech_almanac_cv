<?php
include 'db.php';

// Function to format date ranges
function formatDateRange($startDate, $endDate) {
    return date("F j, Y", strtotime($startDate)) . " - " . date("F j, Y", strtotime($endDate));
}

// Initialize variables to avoid undefined array key warnings
$week_no = $_POST['week_no'] ?? '';
$event_date = $_POST['event_date'] ?? '';
$semester_event = $_POST['semester_event'] ?? '';
$committee = $_POST['committee'] ?? '';

if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($event_date)) {
    // Extract the month and year from the event date
    $event_month = date('n', strtotime($event_date)); // 1-12
    $event_year = date('Y', strtotime($event_date)); // 2025, 2026, etc.

    // Assign semester based on month
    if ($event_month >= 1 && $event_month <= 4) {
        $semester_id = 1; // Semester 1 (Jan - Apr)
    } elseif ($event_month >= 5 && $event_month <= 8) {
        $semester_id = 2; // Semester 2 (May - Aug)
    } else {
        $semester_id = 3; // Semester 3 (Sep - Dec)
    }

    // Fetch date_id based on semester_id and event year
    $query = "SELECT id FROM dates WHERE semester_id = ? AND YEAR(start_date) = ? LIMIT 1";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $semester_id, $event_year);
    $stmt->execute();
    $stmt->bind_result($date_id);
    $stmt->fetch();
    $stmt->close();

    if ($date_id) {
        // Check if the week_no already exists in the same semester AND year
        $check_query = "SELECT COUNT(*) FROM events WHERE week_no = ? AND date_id = ? AND YEAR(event_date) = ?";
        $stmt = $conn->prepare($check_query);
        $stmt->bind_param("iii", $week_no, $date_id, $event_year);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();

        if ($count > 0) {
            echo "<script>alert('Error: This week number already exists in the selected semester and year.');</script>";
        } else {
            // Insert event with the correct date_id
            $insert_query = "INSERT INTO events (date_id, week_no, event_date, semester_event, committee) 
                             VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($insert_query);
            $stmt->bind_param("iisss", $date_id, $week_no, $event_date, $semester_event, $committee);
            
            if ($stmt->execute()) {
                echo "<script>
                alert('Event added successfully!');
                document.getElementById('eventForm').reset();
            </script>";
            
            } else {
                echo "Error: " . $stmt->error;
            }
            $stmt->close();
        }
    } else {
        echo "<script>alert('No matching semester found for this event date.');</script>";
    }
}

// Fetch all events for display
$eventsResult = $conn->query("SELECT * FROM events");

// Handle year-based filtering for events
if (isset($_GET['year'])) {
    include 'db.php';
    
    $year = intval($_GET['year']); // Get the selected year safely

    // Fetch events where the event_date falls in the given year
    $query = "SELECT e.* FROM events e 
              JOIN dates d ON e.date_id = d.id 
              WHERE YEAR(e.event_date) = ?";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $year);
    $stmt->execute();
    $result = $stmt->get_result();

    $events = [];
    while ($row = $result->fetch_assoc()) {
        $events[] = $row;
    }

    echo json_encode($events); // Return JSON response
    exit;
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
    <script src="https://cdn.jsdelivr.net/npm/tinymce@6/tinymce.min.js"></script>
    <style>
        #home_icon {
            color: blue;
            font-size: 40px;
            position: absolute;
            top: 10px;
            left: 10px;
        }




        body {
            font-family: 'Arial', sans-serif;
            background-color: #f8f9fa;
        }
        .container {
            max-width: 900px;
        }
        .card {
            border-radius: 12px;
            box-shadow: 2px 2px 15px rgba(0, 0, 0, 0.1);
        }
        .btn-custom {
            background-color: #007bff;
            color: white;
            transition: 0.3s;
        }
        .btn-custom:hover {
            background-color: #0056b3;
        }
        table {
            background-color: white;
            border-radius: 8px;
            overflow: hidden;
        }
        #home_icon {
            color: blue;
            font-size: 40px;
            position: absolute;
            top: 10px;
            left: 10px;
        }
    
    </style>
</head>
<body>
    <div class="container mt-5">
        <a href="./admin.php">
            <i id="home_icon" class="fa fa-home"></i>
        </a>

   
        <!-- <button type="button" class="btn btn-primary" onclick="window.location.href='add_semester.php'">
    <i class="fa fa-plus"></i> Add Semester
</button> -->
      

        <form action="add_event.php" id="eventForm" method="POST">
    <h1>Add Event</h1>
    


    <div class="form-group">
        <label for="week_no">Week:</label>
        <input type="text" class="form-control" id="week_no" name="week_no" value="<?php echo htmlspecialchars($week_no); ?>" required>
    </div>

    <div class="form-group">
        <label for="event_date">Event Date:</label>
        <input type="date" class="form-control" id="event_date" name="event_date" value="<?php echo htmlspecialchars($event_date); ?>" required>
    </div>

    <div class="form-group">
        <label for="semester_event">Semester Event:</label>
        <textarea class="form-control myeditor" id="semester_event" name="semester_event"><?php echo htmlspecialchars($semester_event); ?></textarea>

    </div>

    <div class="form-group">
        <label for="committee">Committee:</label>
        
        <textarea class="form-control myeditor" id="semester_event" name="committee"><?php echo htmlspecialchars($semester_event); ?></textarea>

    </div>

    <button type="submit" class="btn btn-primary" name="add_event">Save Event</button>
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
                        <td><?php echo ($row['week_no']); ?></td>
                        <td><?php echo formatDateRange($row['event_date'], $row['event_date']); ?></td>
                        <td><?php echo $row['semester_event']; ?></td>
                        <td><?php echo $row['committee']; ?></td>
                        <td>
                            <a href="add_event.php?event_id=<?php echo $row['id']; ?>" class="btn btn-success btn-sm">Edit</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <script>
    tinymce.init({
        selector: '.myeditor', // Fix the selector by adding a dot (.) for class selection
        height: 200, // Increase height for better editing space
        menubar: false,
        plugins: 'lists link',
        toolbar: 'undo redo | bold italic | bullist numlist | link',
        content_style: 'body { font-family: Arial, sans-serif; font-size:14px }'
    });
</script>

</body>
</html>