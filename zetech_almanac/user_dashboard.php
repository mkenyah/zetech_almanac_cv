<?php  
session_start();
// Database connection
include("db.php");

if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit;
}

// Map semester_id to semester names
$semesterNames = [
    1 => "January - April",
    2 => "May - August",
    3 => "September - December"
];

// Get selected year from URL parameter or default to the most recent year
// Get selected year from URL parameter or default to the most recent year
// Get all distinct years from the database (sorted from newest to oldest)
$yearQuery = "SELECT DISTINCT YEAR(start_date) AS year FROM dates ORDER BY year DESC";
$yearResult = $conn->query($yearQuery);
$years = [];

if ($yearResult && $yearResult->num_rows > 0) {
    while ($row = $yearResult->fetch_assoc()) {
        $years[] = $row['year'];
    }
}

// Get the current year
$currentYear = date("Y");

// If a user selects a year, use it. Otherwise, default to the current year.
if (isset($_GET['year']) && in_array((int)$_GET['year'], $years)) {
    $selectedYear = (int)$_GET['year']; // User-selected year
} else {
    $selectedYear = in_array($currentYear, $years) ? $currentYear : (!empty($years) ? max($years) : $currentYear);
}



// Fetch semesters only for the selected year
$semesterQuery = "SELECT semester_id, start_date, end_date FROM dates WHERE YEAR(start_date) = ?";
$stmt = $conn->prepare($semesterQuery);
$stmt->bind_param("i", $selectedYear);
$stmt->execute();
$semesterResult = $stmt->get_result();

$semesters = [];
if ($semesterResult && $semesterResult->num_rows > 0) {
    while ($row = $semesterResult->fetch_assoc()) {
        $semesters[$row['semester_id']] = [
            'name' => $semesterNames[$row['semester_id']] ?? "Unknown Semester",
            'start' => $row['start_date'] ?? null,
            'end' => $row['end_date'] ?? null
        ];
    }
}

// Function to format date range
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

// Fetch events for the selected year
$eventsBySemester = [];

if ($selectedYear) {
    $query = "SELECT * FROM events WHERE YEAR(event_date) = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $selectedYear);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($event = $result->fetch_assoc()) {
        if (!isset($event['event_date']) || empty($event['event_date'])) {
            continue; // Skip invalid data
        }

        $eventDate = strtotime($event['event_date']); // Convert event date to timestamp
        $semesterName = "Unknown Semester"; // Default semester name

        foreach ($semesters as $semesterId => $semester) {
            if (!empty($semester['start']) && !empty($semester['end'])) {
                $startTimestamp = strtotime($semester['start']);
                $endTimestamp = strtotime($semester['end']);

                if ($eventDate >= $startTimestamp && $eventDate <= $endTimestamp) {
                    $semesterName = $semester['name'];
                    break;
                }
            }
        }

        // Ensure semester_id exists before accessing it
        $semesterId = $event['semester_id'] ?? 0;

        if (isset($semesters[$semesterId])) {
            $startDate = $semesters[$semesterId]['start'];
            $endDate = $semesters[$semesterId]['end'];
        } else {
            $startDate = null;
            $endDate = null;
        }
        

        // Ensure `formatDateRange()` receives valid start and end dates
        $event['formatted_date_range'] = ($startDate && $endDate) ? formatDateRange($startDate, $endDate) : "N/A";

        // Group events by semester
        $eventsBySemester[$semesterName][] = $event;
    }
    
    // Close the prepared statement
    $stmt->close();
}

// Close database connection
$conn->close();
?>






<!DOCTYPE html>
<h lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .navbar {
            background-color: navy;
        }

        .navbar-brand,
        .nav-link {
            color: white !important;
        }

        .dashboard {
            margin-top: 20px;
        }

        .container {
            max-width: 95%;
            box-shadow: 0 4px 20px rgba(8, 8, 8, 0.9);
            background-color: white;
            padding: 20px;
            border-radius: 2px;
        }

        .btn-custom {
            background-color: navy;
            color: white;
        }

        footer {
            background-color: navy;
            color: white;
            position: fixed;
            bottom: 0;
            width: 100%;
        }

        table {
            border-collapse: collapse;
        }

        th,
        td {
            padding: 8px;
            /* Adjust padding as needed */
            text-align: center;

        }

        th {
            background-color: navy;
            color: whitesmoke;
        }

        .yearb {
            margin-top: 20px;
        }

        .yearb .btn {
            margin: 5px 30px 2px;
        }

       

        #btnd:hover {
            background-color: white;
            color: navy;
            border: 1px solid navy;
        }

        /* Modal Styling */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .modal-content {
            background-color: white;
            margin: 15% auto;
            padding: 20px;
            width: 30%;
            border-radius: 5px;
            text-align: center;
        }

        .modal input {
            width: 100%;
            margin-bottom: 10px;
            padding: 5px;
        }

        @media print {

            /* Hide elements you don't want to appear in the print version */
            body * {
                visibility: hidden;
            }

            /* Make sure only specific elements are visible when printed */
            .printable,
            .printable * {
                visibility: visible;
            }

            /* Hide the Actions column and other unwanted columns */
            .actions-column {
                display: none;
            }

            /* Hide the navigation and footer */
            nav,
            footer {
                display: none;
            }

            /* Position the printable section at the top of the page */
            .printable {
                position: absolute;
                top: 20px;
                left: 50%;
                transform: translateX(-50%);
                /* Center the table */
                width: 105%;
                /* Adjust table width */
            }

            /* Ensure the table takes up the full width of the page */
            table {
                width: 100%;
                border-collapse: collapse;
            }

            /* Make table headers bold and ensure a clear, clean look */
            th {
                font-weight: bold;
                color: black;
            }

            /* Remove any borders and padding that might cause layout issues */
            th,
            td {
                padding: 5px;
                border: 1px solid black;
                color: black;
                font-size: 23px;
            }

            /* Hide specific columns for print */
            td:nth-child(5),
            th:nth-child(5) {
                /* Actions column */
                display: none;
            }

            /* Ensure the "Week" column is visible */
            td:nth-child(1),
            th:nth-child(1) {
                /* Week column */
                visibility: visible;
            }

            /* Ensure there is no extra space or unwanted margins */
            body {
                margin: 0;
                padding: 0;
            }

            .print-footer {
                visibility: visible;
            }
        }

        #btnsup {
    margin: 20px;
    gap: 10px;
    overflow-x: scroll;  /* Enables horizontal scrolling */
    white-space: nowrap; /* Prevents wrapping */
    display: flex;
    padding: 10px;
    scroll-behavior: smooth;
    width: 95%;
    /* Adjust based on layout */
    border: 1px solid #ddd; /* Optional: Visible boundary */
    scrollbar-width: thin; /* For Firefox */
    scrollbar-color: rgb(37, 35, 35); /* Thumb and track color */
}

/* Custom scrollbar styling for WebKit browsers (Chrome, Edge, Safari) */
#btnsup::-webkit-scrollbar {
    height: 8px; /* Adjust scrollbar thickness */
}

#btnsup::-webkit-scrollbar-track {
    background:rgb(71, 68, 68); /* Light background */
    border-radius: 10px;
}

#btnsup::-webkit-scrollbar-thumb {
    background: #d3d3d3; /* Grey scrollbar */
    border-radius: 10px;
}

#btnsup::-webkit-scrollbar-thumb:hover {
    background:rgb(71, 68, 68); /* Darker on hover */
}

        .logo {
            height: 60px;
        }

  


    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg">
        <a class="navbar-brand" href="#">
        <img class="logo"    src="./images/logo.png" alt="">
        </a>
        <div class="ml-auto">
            <span class="text-white">Welcome visitor</span>
            <a href="logout.php" class="btn btn-sm btn-light ml-3">login</a>
        </div>
    </nav>

    <div id="btnsup" class="d-flex justify-content-right">
    <?php foreach (array_reverse($years) as $year): ?>
        <button class="btn btn-primary year-btn" data-year="<?= $year; ?>"><?= $year; ?></button>
    <?php endforeach; ?>
</div>


<script>
    document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".year-btn").forEach(button => {
        button.addEventListener("click", function () {
            const year = this.getAttribute("data-year");
            window.location.href = "?year=" + year;
        });
    });
});

</script>
    
    <div style="display: flex; justify-content: end;">
    <button id="btnd" class="btn btn-custom btn-sm" onclick="printTable()">Download</button>
</div>
</div>


<div id="yearResult" class="table-responsive">
    <!-- Event results will be injected here -->
</div>

            

       

            <style>
                body {
                    background-color: #f8f9fa;
                }

                .navbar {
                    background-color: navy;
                }

                .navbar-brand,
                .nav-link {
                    color: white !important;
                }

                .dashboard {
                    margin-top: 20px;
                }

                .container {
                    max-width: 95%;
                    box-shadow: 0 4px 20px rgba(8, 8, 8, 0.9);
                    background-color: white;
                    padding: 20px;
                    border-radius: 2px;
                }

                .btn-custom {
                    background-color: navy;
                    color: white;
                }

                footer {
                    background-color: navy;
                    color: white;
                    position: fixed;
                    bottom: 0;
                    width: 100%;
                }

                .yearb {
                    margin-top: 20px;
                }

                .yearb .btn {
                    margin: 5px 30px 2px;
                }

                #btnd {
                    background-color: navy;
                    color: white;

    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 14px;
    transition: background-color 0.3s ease-in-out;
    padding: 10px 16px;
    flex-shrink: 0; 
    margin-right: 70px;/* Prevents it from shrinking */

                }

                #btnd:hover {
                    background-color: white;
                    color: navy;
                    border: 1px solid navy;
                }

                @media print {
                    body * {
                        visibility: hidden;
                    }

                    .printable,
                    .printable * {
                        visibility: visible;
                    }

                    .actions-column {
                        display: none;
                    }

                    nav,
                    footer {
                        display: none;
                    }

                    .printable {
                        position: absolute;
                        top: 20px;
                        left: 50%;
                        transform: translateX(-50%);
                        width: 105%;
                    }

                    table {
                        width: 100%;
                        border-collapse: collapse;
                    }

                    th {
                        font-weight: bold;
                        color: black;
                    }

                    th,
                    td {
                        padding: 5px;
                        border: 1px solid black;
                        color: black;
                        font-size: 23px;
                    }

                    td:nth-child(5),
                    th:nth-child(5) {
                        display: none;
                    }

                    td:nth-child(1),
                    th:nth-child(1) {

                        /* visibility: visible; */
                        /* } */
                        body {
                            margin: 0;
                            padding: 0;
                        }

                        .print-footer {
                            visibility: visible;
                        }
                    }

                    #btnsup {
                        margin: 20px;
                        gap: 10px;
                        overflow-y: scroll;
                        width: 100%;
                    }

                    h4{
    display: block !important;
    font-size: 20px;
    font-weight: bold;
    margin-top: 20px;
    color: black; /* Ensure text color is visible */
}

            </style>
            </head>

            <body>


<div class="container dashboard" >
    <div class="events">
    <div class="table-responsive" id="yearResult">
            <div class="printable" >
                <?php if (!empty($eventsBySemester)): ?>
                    <?php foreach ($eventsBySemester as $semesterName => $events): ?>
                        <h3 class="mt-4 text-center text-primary"><?php echo htmlspecialchars($semesterName); ?></h3>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Week</th>
                                    <th>Date</th>
                                    <th>Semester Event</th>
                                    <th>Committee</th>
                                   
                                </tr>
                            </thead>
                            <tbody id="yearResult"></tbody>
                                <?php if (!empty($events)): ?>
                                    <?php foreach ($events as $event): 
                                        $formattedDate = formatDateRange($event['event_date'], $event['event_end_date'] ?? null);
                                        ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($event['week_no']); ?></td>
                                            <td><?php echo htmlspecialchars($formattedDate); ?></td>
                                            <td><?php echo ($event['semester_event']); ?></td>
                                            <td><?php echo ($event['committee']); ?></td>
                                            
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="text-center text-muted">No events found</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-center text-muted">No events classified by that year.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
                <script>
                 function confirmDelete() {
                    return confirm("Are you sure you want to delete this event?");
                     }
                 </script>


                <script>
                    // Function to confirm deletion
                    function confirmDelete() {
                        return confirm("Are you sure you want to delete this event?");
                    }

                    function printTable() {
                        window.print();
                    }
                </script>
            </body>

    
            <footer class="text-center py-3 w-100 fixed-bottom" ">
                &copy; 2025 Zetech University. All rights reserved.
            </footer>



            <script>
                // Function to confirm deletion
                function confirmDelete() {
                    return confirm("Are you sure you want to delete this event?");
                }

                function printTable() {
                    window.print();
                }


                function downloadFilteredEvents(year) {
    window.location.href = `download_events.php?year=${year}`;
}

            </script>
</body>
</html>