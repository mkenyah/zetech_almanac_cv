<?php
include("db.php");

$message = "";

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate inputs to avoid undefined index errors
    $semester_id = isset($_POST['semester_id']) ? $_POST['semester_id'] : '';
    $start_date = isset($_POST['start_date']) ? $_POST['start_date'] : '';
    $end_date = isset($_POST['end_date']) ? $_POST['end_date'] : '';
    $year = isset($_POST['year']) ? $_POST['year'] : '';

    if (!empty($semester_id) && !empty($start_date) && !empty($end_date) && !empty($year)) {
        // Ensure semester does not overlap with existing ones
        $checkQuery = "SELECT * FROM dates WHERE (start_date <= ? AND end_date >= ?) AND year = ?";
        $stmt = $conn->prepare($checkQuery);
        $stmt->bind_param("ssi", $end_date, $start_date, $year);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 0) {
            // Insert semester into the database
            $sql = "INSERT INTO dates (semester_id, start_date, end_date, year) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("issi", $semester_id, $start_date, $end_date, $year);

            if ($stmt->execute()) {
                $message = '<div class="alert alert-success">✔ Semester added successfully.</div>';
            } else {
                $message = '<div class="alert alert-danger">❌ Error adding semester: ' . $conn->error . '</div>';
            }
        } else {
            $message = '<div class="alert alert-warning">⚠ Overlapping semester exists!</div>';
        }

        $stmt->close();
    } else {
        $message = '<div class="alert alert-danger">❌ All fields are required!</div>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Semester</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        body {
            font-family: 'Times New Roman', serif;
            background-color: #f4f4f4;
            padding: 20px;
        }
        .container {
            max-width: 500px;
            margin: auto;
            padding: 20px;
            background: #fff;
            border: 1px solid #ddd;
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }
        .btn-custom {
            background-color: #343a40;
            color: #fff;
            border-radius: 5px;
        }
        .btn-custom:hover {
            background-color: #23272b;
        }
    </style>
</head>
<body>

<a href="index.php" class="btn-home"><i class="fa fa-home"></i></a>  
    <div class="container mt-5">
   
</a>
        <h2>Add Semester</h2>
        <?php echo $message; ?>
        <form method="POST">
            <div class="mb-3">
                <label for="semester_id" class="form-label">Semester</label>
                <select name="semester_id" id="semester_id" class="form-control" required>
                    <option value="">Select Semester</option>
                    <?php
                    $query = "SELECT * FROM semester";
                    $result = $conn->query($query);
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='{$row['semester_id']}'>{$row['semester_name']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="start_date" class="form-label">Start Date</label>
                <input type="date" name="start_date" id="start_date" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="end_date" class="form-label">End Date</label>
                <input type="date" name="end_date" id="end_date" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="year" class="form-label">Year</label>
                <input type="number" name="year" id="year" class="form-control" required min="2000" max="2100">
            </div>
            <button type="submit" class="btn btn-primary">Add Semester</button>
        </form>
    </div>
</body>
</html>
