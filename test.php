<?php

try {
    $pdo = new PDO('sqlite:C:\Users\27837\test2.db');
    echo 'Connection was successful';

    $tableName = 'csv_import';

    // Define SQL statement to select all data from the table
    $selectDataSQL = "SELECT * FROM $tableName";

    // Prepare and execute the SQL statement
    $stmt = $pdo->prepare($selectDataSQL);
    $stmt->execute();

    // Fetch all rows as an associative array
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Display the data
    echo '<pre>';
    print_r($result);
    echo '</pre>';
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
}
?>
