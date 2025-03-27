<?php

require_once 'login.php';

$connection = new mysqli($db_hostname, $db_username, $db_password, $db_database);
if ($connection->connect_error) die("Connection failed: " . $connection->connect_error);

// DELETE REQUEST
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete']) && isset($_POST['courseNumber'])) {
    $courseNumber = get_post($connection, 'courseNumber');
    
    $query = "DELETE FROM course1 WHERE courseNumber = ?";
    $stmt = $connection->prepare($query);
    $stmt->bind_param("s", $courseNumber);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "Course deleted successfully.";
    } else {
        echo "Error deleting course.";
    }
    $stmt->close();
}

// ADD REQUEST
if ($_SERVER["REQUEST_METHOD"] == "POST" &&
    isset($_POST['yearSemester']) &&
    isset($_POST['courseNumber']) &&
    isset($_POST['courseTitle']) &&
    isset($_POST['instructor']) &&
    isset($_POST['instructorNETID']) &&
    isset($_POST['dateTime']) &&
    isset($_POST['location'])) {
    
    $yearSemester = get_post($connection, 'yearSemester');
    $courseNumber = get_post($connection, 'courseNumber');
    $courseTitle = get_post($connection, 'courseTitle');
    $instructor = get_post($connection, 'instructor');
    $instructorNETID = get_post($connection, 'instructorNETID');
    $dateTime = get_post($connection, 'dateTime');
    $location = get_post($connection, 'location');
    $timeCreated = date("Y-m-d H:i:s");

    $query = "INSERT INTO course1 (yearSemester, courseNumber, courseTitle, instructor, instructorNETID, dateTime, location, timeCreated) 
              VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $connection->prepare($query);
    $stmt->bind_param("ssssssss", $yearSemester, $courseNumber, $courseTitle, $instructor, $instructorNETID, $dateTime, $location, $timeCreated);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "Course added successfully.";
    } else {
        echo "Error adding course.";
    }
    $stmt->close();
}

// UPDATE REQUEST
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update']) && isset($_POST['courseNumber']) && isset($_POST['courseTitle'])) {
    $courseNumber = get_post($connection, 'courseNumber');
    $courseTitle = get_post($connection, 'courseTitle');
    $timeUpdated = date("Y-m-d H:i:s");

    $query = "UPDATE course1 SET courseTitle = ?, timeUpdated = ? WHERE courseNumber = ?";
    $stmt = $connection->prepare($query);
    $stmt->bind_param("sss", $courseTitle, $timeUpdated, $courseNumber);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "Course updated successfully.";
    } else {
        echo "Error updating course.";
    }
    $stmt->close();
}

// DISPLAY FORM FOR ADDING COURSES
echo <<<_END
<h2>Course Management</h2>
<form action="task2.php" method="post">
    <input type="text" name="yearSemester" placeholder="Year-Semester" required>
    <input type="text" name="courseNumber" placeholder="Course Number" required>
    <input type="text" name="courseTitle" placeholder="Course Title" required>
    <input type="text" name="instructor" placeholder="Instructor" required>
    <input type="text" name="instructorNETID" placeholder="Instructor NETID" required>
    <input type="text" name="dateTime" placeholder="Date & Time" required>
    <input type="text" name="location" placeholder="Location" required>
    <input type="submit" value="ADD COURSE">
</form>
_END;

// DISPLAY COURSES
$query = "SELECT * FROM course1";
$result = $connection->query($query);
if (!$result) die("Database access failed: " . $connection->error);

$rows = $result->num_rows;
for ($j = 0; $j < $rows; ++$j) {
    $result->data_seek($j);
    $row = $result->fetch_assoc();

    echo <<<_END
    <pre>
    Year-Semester: $row[yearSemester]
    Course Number: $row[courseNumber]
    Course Title: $row[courseTitle]
    Instructor: $row[instructor]
    Instructor NETID: $row[instructorNETID]
    Date & Time: $row[dateTime]
    Location: $row[location]
    Time Created: $row[timeCreated]
    Time Updated: $row[timeUpdated]
    </pre>

    <form action="task2.php" method="post">
        <input type="hidden" name="delete" value="yes">
        <input type="hidden" name="courseNumber" value="$row[courseNumber]">
        <input type="submit" value="DELETE COURSE">
    </form>

    <form action="task2.php" method="post">
        <input type="hidden" name="update" value="yes">
        <input type="hidden" name="courseNumber" value="$row[courseNumber]">
        <input type="text" name="courseTitle" placeholder="New Course Title">
        <input type="submit" value="UPDATE COURSE">
    </form>
    <hr>
_END;
}

$result->close();
$connection->close();

function get_post($connection, $var) {
    return $connection->real_escape_string($_POST[$var]);
}
?>

