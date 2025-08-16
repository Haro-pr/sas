<?php
$dsn = 'mysql:host=localhost;dbname=class_rec';
$pdo = new PDO($dsn, 'root', '');

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_student'])) {
        $rfid = $_POST['rfid'];
        $subject = $_POST['subject'];

        $stmt = $pdo->prepare("SELECT rfid, year_level, section, student_no, f_name, l_name, m_name FROM enrollment WHERE rfid = ?");
        $stmt->execute([$rfid]);
        $student = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($student) {
            $insert = $pdo->prepare("INSERT INTO attendance_log (rfid, year_level, section, student_no, subject, f_name, l_name, m_name, login_time) 
                                     VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())");
            $insert->execute([
                $student['rfid'],
                $student['year_level'],
                $student['section'],
                $student['student_no'],
                $subject,
                $student['f_name'],
                $student['l_name'],
                $student['m_name']
            ]);

            $success_msg = "Attendance logged successfully!";
        } else {
            $error_msg = "Student is not enrolled under the entered RFID.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Attendance</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            background-color: #eef2f5;
            font-family: Arial, sans-serif;
            margin: 0;
        }
        header {
            background-color: #4CAF50;
            color: white;
            padding: 15px;
            text-align: center;
            font-size: 1.5em;
            font-weight: bold;
        }
        .container {
            width: 90%;
            max-width: 1000px;
            margin: 30px auto;
        }
        .card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0px 4px 12px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        .card h2 {
            text-align: center;
            margin-bottom: 15px;
        }
        form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        input, select, button {
            padding: 8px;
            border-radius: 6px;
            border: 1px solid #ccc;
            width: 250px;
            margin-top: 10px;
        }
        button {
            background: #4CAF50;
            color: white;
            border: none;
            font-size: 1em;
            cursor: pointer;
        }
        button:hover {
            background: #45a049;
        }
        .message {
            margin-top: 5px;
            font-size: 0.95em;
        }
        .message.error {
            color: red;
        }
        .message.success {
            color: green;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0px 4px 12px rgba(0,0,0,0.1);
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: center;
        }
        th {
            background-color: #8911adff;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
    </style>
</head>
<body>

<header>Student Attendance System</header>

<div class="container">

    <!-- Form Card -->
    <div class="card">
        <h2>Log Attendance</h2>
        <form action="Student_Attendance.php" method="POST">
            <input type="text" name="rfid" placeholder="RFID" required>
            
            <?php if (!empty($error_msg)): ?>
                <div id="errorMessage" class="message error"><?php echo htmlspecialchars($error_msg); ?></div>
            <?php endif; ?>
            
            <?php if (!empty($success_msg)): ?>
                <div id="successMessage" class="message success"><?php echo htmlspecialchars($success_msg); ?></div>
            <?php endif; ?>
            
            <select name="subject" required>
                <option value="Integrative Programming">Integrative Programming</option>
                <option value="Object Oriented Programming">Object Oriented Programming</option>
                <option value="Information Management">Information Management</option>  
                <option value="Ethics">Ethics</option>  
                <option value="Readings in Philippine History">Readings in Philippine History</option>  
            </select>
            <button type="submit" name="add_student">Log In</button>
        </form>
    </div>

    <!-- Attendance List -->
    <div class="card">
        <h2>Attendance List</h2>
        <table>
            <thead>
                <tr>
                    <th>RFID</th>
                    <th>Student No</th>
                    <th>Subject</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Middle Name</th>
                    <th>Year Level</th>
                    <th>Section</th>
                    <th>Login Time</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $attendance_log = $pdo->query("SELECT * FROM attendance_log");
                foreach ($attendance_log as $attendance_logs) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($attendance_logs['RFID']); ?></td>
                        <td><?php echo htmlspecialchars($attendance_logs['student_no']); ?></td>
                        <td><?php echo htmlspecialchars($attendance_logs['subject']); ?></td>
                        <td><?php echo htmlspecialchars($attendance_logs['f_name']); ?></td>
                        <td><?php echo htmlspecialchars($attendance_logs['l_name']); ?></td>
                        <td><?php echo htmlspecialchars($attendance_logs['m_name']); ?></td>
                        <td><?php echo htmlspecialchars($attendance_logs['year_level']); ?></td>
                        <td><?php echo htmlspecialchars($attendance_logs['section']); ?></td>
                        <td><?php echo htmlspecialchars($attendance_logs['login_time']); ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

</div>

<script>
    window.addEventListener('DOMContentLoaded', () => {
        const errorDiv = document.getElementById('errorMessage');
        const successDiv = document.getElementById('successMessage');

        [errorDiv, successDiv].forEach(div => {
            if (div) {
                setTimeout(() => {
                    div.style.display = 'none';
                }, 3000);
            }
        });
    });
</script>

</body>
</html>
