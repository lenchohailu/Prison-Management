<?php
// About.php - Information page about Woliso Prison
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Woliso Prison</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f9f9f9;
            color: #333;
            margin: 0;
            padding: 0;
        }
        header {
            background-color: #3498db;
            color: #fff;
            padding: 30px 20px;
            text-align: center;
        }
        header h1 {
            margin: 0;
            font-size: 2.5rem;
        }
        main {
            max-width: 900px;
            margin: 40px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        main h2 {
            color: #e67e22;
            margin-top: 0;
        }
        main p {
            line-height: 1.6;
            margin-bottom: 1.2em;
        }
        footer {
            text-align: center;
            padding: 20px;
            background-color: #3498db;
            color: #fff;
        }
        a.btn {
            display: inline-block;
            padding: 10px 20px;
            margin-top: 15px;
            background-color: #e67e22;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            transition: 0.3s;
        }
        a.btn:hover {
            background-color: #d35400;
        }
    </style>
</head>
<body>
    <header>
        <h1>About Woliso Prison</h1>
    </header>
    <main>
        <h2>Overview</h2>
        <p>
            Woliso Prison is a correctional facility located in Woliso, Oromia, Ethiopia. It serves the region by accommodating individuals sentenced by the legal system and aims to rehabilitate inmates through structured programs and services.
        </p>

        <h2>Facilities & Services</h2>
        <p>
            The prison provides secure housing, medical services, and vocational training to help inmates reintegrate into society. Education and skills programs are offered to improve literacy, technical knowledge, and employability after release.
        </p>

        <h2>Security & Management</h2>
        <p>
            Woliso Prison maintains strict security measures to ensure the safety of both inmates and staff. The facility is overseen by a professional administration team in collaboration with local law enforcement.
        </p>

        <h2>Community Engagement</h2>
        <p>
            The prison also engages with the local community through awareness campaigns, outreach programs, and partnerships aimed at supporting rehabilitation and reducing recidivism.
        </p>

        <a class="btn" href="index.php">Return to Homepage</a>
    </main>
    <footer>
        &copy; <?= date("Y"); ?> Woliso Prison. All rights reserved.
    </footer>
</body>
</html>
