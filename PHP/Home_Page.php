<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance System - Home</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #eef2f5;
        }
        header {
            background-color: #8911adff; /* Violet theme */
            color: white;
            padding: 20px;
            text-align: center;
            font-size: 1.8em;
            font-weight: bold;
        }
        .container {
            max-width: 1000px;
            margin: 40px auto;
            text-align: center;
        }
        .welcome {
            margin-bottom: 30px;
            font-size: 1.2em;
        }
        .menu {
            display: flex;
            justify-content: center;
            gap: 30px;
            flex-wrap: wrap;
        }
        .card {
            background: white;
            border-radius: 10px;
            padding: 40px 30px;
            width: 250px;
            box-shadow: 0px 4px 12px rgba(0,0,0,0.1);
            text-align: center;
            cursor: pointer;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            text-decoration: none;
            color: black;
            font-size: 1.1em;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0px 6px 15px rgba(0,0,0,0.15);
        }
        .card.green {
            border-top: 5px solid #4CAF50;
        }
        .card.violet {
            border-top: 5px solid #8911adff;
        }
    </style>
</head>
<body>

<header>Student Attendance System</header>

<div class="container">
    <div class="welcome">
        Welcome to the Student Attendance System!<br>
        Please choose an option below.
    </div>

    <div class="menu">
        <a href="Student_Attendance.php" class="card green">
            📋 Log Attendance
        </a>
        <a href="Student_Enrollment.php" class="card violet">
            📊 Enroll Student
        </a>
    </div>
</div>

</body>
</html>
