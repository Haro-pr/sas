<?php
// database connection
$dsn = 'mysql:host=localhost;dbname=class_rec';
$pdo = new PDO($dsn, 'root', '');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_student'])) {
        // Add student
        $stmt = $pdo->prepare("INSERT INTO enrollment (rfid, course, year_level, section, student_no, f_name, l_name, m_name) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $_POST['rfid'],
            $_POST['course'],
            $_POST['year_level'],
            $_POST['section'],
            $_POST['student_no'],
            $_POST['f_name'],
            $_POST['l_name'],
            $_POST['m_name'],
        ]);
    } elseif (isset($_POST['delete_student'])) {
        // Delete student
        $rfid = $_POST['rfid'];
        $stmt = $pdo->prepare("DELETE FROM enrollment WHERE rfid = ?");
        $stmt->execute([$rfid]);
    }
}

// Handle AJAX request for RFID search
if (isset($_GET['search_rfid'])) {
    $rfid = $_GET['search_rfid'];
    $stmt = $pdo->prepare("SELECT * FROM enrollment WHERE rfid = ?");
    $stmt->execute([$rfid]);
    $student = $stmt->fetch(PDO::FETCH_ASSOC);
    echo json_encode($student);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Enrollment</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            margin: 0;
            padding: 0;
        }

        header {
            background: #4CAF50;
            color: white;
            padding: 15px 20px;
            text-align: center;
            font-size: 1.5em;
            font-weight: bold;
        }

        .container {
            max-width: 1200px;
            margin: auto;
            padding: 20px;
        }

        .card {
            background: white;
            border-radius: 8px;
            box-shadow: 0px 4px 8px rgba(0,0,0,0.1);
            padding: 20px;
            margin-bottom: 30px;
        }

        form {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            justify-content: center;
        }

        input, select {
            padding: 10px;
            border-radius: 6px;
            border: 1px solid #ccc;
            font-size: 14px;
            min-width: 150px;
            text-transform: capitalize;
        }

      button {
    padding: 8px 14px; /* Matches input height */
    font-size: 14px;
    border: 1px solid #ccc; /* Same border as inputs */
    border-radius: 6px; /* Rounded corners like inputs */
    background-color: white; /* Same background as inputs */
    color: black; /* Text color */
    cursor: pointer;
    box-sizing: border-box;
}



        .table-container {
            background: white;
            border-radius: 8px;
            box-shadow: 0px 4px 8px rgba(0,0,0,0.1);
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            text-align: center;
            padding: 10px;
            border: 1px solid #ddd;
        }

        th {
        background-color: #8911adff;
        color: white;
        }

        tr:nth-child(even) {
            background: #f9f9f9;
        }

        tr:hover {
            background: #f1f1f1;
        }

        label {
        display: block;         /* Moves label above input */
        font-size: 14px;        /* Smaller text size */
        font-weight: bold;      /* Keep bold for readability */
        color: black;          /* Same pink/violet as before */
        margin-bottom: 4px;     /* Space between label and input */
        }

        .group {
    display: flex;
    align-items: center; /* Align buttons & inputs vertically */
    gap: 10px; /* Space between fields */
    margin-top: 23px;
}
    </style>
</head>
<body>

<header>Student Enrollment System</header>

<div class="container">
    <div class="card">
        <form action="Student_Enrollment.php" method="POST">

<div class="form-group">
        <label for="student_no">Student No</label>
        <input id="student_no" type="text" placeholder="Student No">
</div>

<div class="form-group">
        <label for="student_id">Student ID</label>
        <input id="student_id" type="text" placeholder="Student ID">
</div>

<div class="form-group">
        <label for="course">Course</label>
    <select id="course">
        <option>Select Course</option>
        <option value="BSIT">BSIT</option>
        <option value="BOT">BOT</option>
        <option value="BSED">BSED</option>
    </select>
</div>

<div class="form-group">
            <label for="year_level">Year Level</label>
            <select id="year_level" name="year_level" required>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
            </select>
</div>

<div class="form-group">
            <label for="section">Section</label>
            <select id="section" name="section" required>
                <option value="A">A</option>
                <option value="B">B</option>
                <option value="C">C</option>
            </select>
</div>
<div class="form-group">
            <label for="f_name">First Name</label>
            <input id="f_name" type="text" name="f_name" placeholder="First Name" required>
</div>

<div class="form-group">
            <label for="l_name">Last Name</label>
            <input id="l_name" type="text" name="l_name" placeholder="Last Name" required>
</div>

<div class="form-group">
            <label for="m_name">Middle Name</label>
            <input id="m_name" type="text" name="m_name" placeholder="Middle Name" required>
</div>
<div class="group">
            <button type="submit" name="add_student" style="background: #04ae34ff;">Enroll Student</button>
            <button type="submit" name="delete_student" style="background: #dc3a28ff;">Delete Student</button>
</div>


        </form>     
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>RFID</th>
                    <th>Course</th>
                    <th>Year Level</th>
                    <th>Section</th>
                    <th>Student ID</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Middle Name</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $enrollment = $pdo->query("SELECT * FROM enrollment");
                foreach ($enrollment as $enrollments) { ?>
                <tr>
                    <td><?= htmlspecialchars($enrollments['RFID']); ?></td>
                    <td><?= htmlspecialchars($enrollments['Course']); ?></td>
                    <td><?= htmlspecialchars($enrollments['year_level']); ?></td>
                    <td><?= htmlspecialchars($enrollments['section']); ?></td>
                    <td><?= htmlspecialchars($enrollments['student_no']); ?></td>
                    <td><?= htmlspecialchars($enrollments['f_name']); ?></td>
                    <td><?= htmlspecialchars($enrollments['l_name']); ?></td>
                    <td><?= htmlspecialchars($enrollments['m_name']); ?></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<script>
// Auto-fill when RFID is scanned
document.getElementById("rfid").addEventListener("input", function () {
    let rfidValue = this.value.trim();

    if (rfidValue.length >= 7) {
        fetch("Student_Enrollment.php?search_rfid=" + encodeURIComponent(rfidValue))
            .then(response => response.json())
            .then(data => {
                if (data) {
                    document.getElementById("course").value = data.course;
                    document.getElementById("year_level").value = data.year_level;
                    document.getElementById("section").value = data.section;
                    document.getElementById("student_no").value = data.student_no;
                    document.getElementById("f_name").value = data.f_name;
                    document.getElementById("l_name").value = data.l_name;
                    document.getElementById("m_name").value = data.m_name;
                }
            })
            .catch(error => console.error("Error:", error));
    }
});
</script>

</body>
</html>
