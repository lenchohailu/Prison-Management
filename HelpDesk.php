<?php
include("DB.php");

// Initialize messages
$success = '';
$error = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name  = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $issue = trim($_POST['issue'] ?? '');

    // Validation
    if (empty($name) || empty($email) || empty($issue)) {
        $error = "All fields are required. Please fill out the form completely.";
    }
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address.";
    }
    else {

        // INSERT INTO DATABASE (NOT EMAIL)
        $stmt = $conn->prepare("
            INSERT INTO helpdesk (name, email, issue)
            VALUES (?, ?, ?)
        ");

        $stmt->bind_param("sss", $name, $email, $issue);

        if ($stmt->execute()) {
            $success = "Thank you, $name. Your request has been sent successfully to admin.";

            // clear form
            $name = $email = $issue = '';
        } else {
            $error = "Failed to submit your request. Please try again later.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Help Desk - Woliso Prison</title>

<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

<style>
body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background-color: #f5f7fa;
    margin: 0;
    padding: 0;
}

header {
    background: #3498db;
    color: white;
    text-align: center;
    padding: 25px;
}

main {
    max-width: 700px;
    margin: 40px auto;
    background: white;
    padding: 25px;
    border-radius: 8px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

h2 {
    color: #e67e22;
}

label {
    display: block;
    margin-top: 15px;
    font-weight: bold;
}

input, textarea {
    width: 100%;
    padding: 10px;
    margin-top: 5px;
    border: 1px solid #ccc;
    border-radius: 5px;
}

textarea {
    min-height: 120px;
}

button {
    margin-top: 20px;
    width: 100%;
    padding: 12px;
    background: #e67e22;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

button:hover {
    background: #d35400;
}

.message {
    margin-top: 15px;
    padding: 10px;
    border-radius: 5px;
}

.error {
    background: #f8d7da;
    color: #721c24;
}

.success {
    background: #d4edda;
    color: #155724;
}

a.btn {
    display: inline-block;
    margin-top: 15px;
    padding: 10px 15px;
    background: #3498db;
    color: white;
    text-decoration: none;
    border-radius: 5px;
}
</style>

</head>

<body>

<header>
    <h1>Woliso Prison Help Desk</h1>
</header>

<main>

<h2>Submit Your Issue</h2>

<?php if ($error): ?>
    <div class="message error"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<?php if ($success): ?>
    <div class="message success"><?= htmlspecialchars($success) ?></div>
<?php endif; ?>

<form method="POST">

    <label>Name</label>
    <input type="text" name="name" value="<?= htmlspecialchars($name ?? '') ?>" required>

    <label>Email</label>
    <input type="email" name="email" value="<?= htmlspecialchars($email ?? '') ?>" required>

    <label>Issue / Problem</label>
    <textarea name="issue" required><?= htmlspecialchars($issue ?? '') ?></textarea>

    <button type="submit">
        <i class="fas fa-paper-plane"></i> Send Request
    </button>

</form>

<a class="btn" href="index.php">Return to Home</a>

</main>

</body>
</html>