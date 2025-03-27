<?php
require_once 'login.php';
$connection = new mysqli($db_hostname, $db_username, $db_password, $db_database);

if ($connection->connect_error) die("Connection failed: " . $connection->connect_error);

$query  = "SELECT * FROM course1";
$result = $connection->query($query);

$courses = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $courses[] = $row;
    }
}
$result->close();
$connection->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Management</title>
    <link rel="stylesheet" href="task3.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <header>
    <h1><img src="logo2.svg" alt="The University of Texas at Dallas" />Course Catalog</h1>
    </header>
        <nav>
            <ul>
                <li><a href="task3.php">Courses</a></li>
                <li><a href="task3Add.php">Add Course</a></li>
                <li><a href="task3Search.php">Search</a></li>
            </ul>
        </nav>
    

    <main>
        
        <section id="add-course">
            <h2>Add New Course</h2>
            <form id="addCourseForm">
                <input type="text" name="yearSemester" placeholder="Year Semester">
                <input type="text" name="courseNumber" placeholder="Course Number">
                <input type="text" name="courseTitle" placeholder="Course Title">
                <input type="text" name="instructor" placeholder="Instructor">
                <input type="text" name="instructorNETID" placeholder="Instructor NETID">
                <input type="text" name="dateTime" placeholder="Date & Time">
                <input type="text" name="location" placeholder="Location">
                <button type="button" onclick="addCourse()">Add Course</button>
            </form>
        </section>
    </main>

    <footer>
        <p>&copy; 2025 The University of Texas at Dallas</p>
    </footer>

    <script>
        function toggleDetails(courseElement) {
            $(courseElement).find('.details').slideToggle();
        }

        

        function addCourse() {
            let formData = $("#addCourseForm").serialize();
            $.post('task2.php', formData, function(response) {
                alert("Course Added!");
                location.reload();
            });
        }

        function deleteCourse(courseNumber) {
            $.post('task2.php', { delete: "yes", courseNumber: courseNumber }, function(response) {
                alert("Course Deleted!");
                location.reload();
            });
        }

        function updateCourse(courseNumber) {
            let newTitle = prompt("Enter new title:");
            if (newTitle) {
                $.post('task2.php', { update: "yes", courseNumber: courseNumber, courseTitle: newTitle }, function(response) {
                    alert("Course Updated!");
                    location.reload();
                });
            }
        }
    </script>
</body>
</html>
