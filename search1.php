<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $search = trim($_POST['search'] ?? '');
    
    if ($search === '') {
        echo "Please enter a search term.";
    } else {
        // Example: search in database
        $stmt = $conn->prepare("SELECT * FROM prisoners WHERE prison_fname LIKE ?");
        $stmt->execute(["%$search%"]);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($results as $row) {
            echo htmlspecialchars($row['prison_fname']) . "<br>";
        }
    }
}
?>
