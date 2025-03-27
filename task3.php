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
        

        <section id="course-list">
            <?php foreach ($courses as $course): ?>
                <div class="course" onclick="toggleDetails(this)">
                    <h3><?php echo $course['courseNumber'] . " - " . $course['courseTitle']; ?></h3>
                    <div class="details">
                        <p><strong>Instructor:</strong> <?php echo $course['instructor']; ?></p>
                        <p><strong>Time:</strong> <?php echo $course['dateTime']; ?></p>
                        <p><strong>Location:</strong> <span id="location"><?php echo $course['location']; ?></span></p>
                        <button onclick="deleteCourse('<?php echo $course['courseNumber']; ?>')">Delete</button>
                        <button onclick="updateCourse('<?php echo $course['courseNumber']; ?>')">Update</button>
                    </div>
                </div>
            <?php endforeach; ?>
        </section>

        
    </main>

    <footer>
        <p>&copy; 2025 The University of Texas at Dallas</p>
    </footer>

    <script>
        function toggleDetails(courseElement) {
            $(courseElement).find('.details').slideToggle();
        }

        function searchCourses() {
            let query = $("#searchBox").val().toLowerCase();
            $("#course-list .course").each(function() {
                let text = $(this).text().toLowerCase();
                $(this).toggle(text.includes(query));
            });
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
