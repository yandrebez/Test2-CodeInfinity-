<?php
try {
    $db = new PDO('sqlite:C:\Users\27837\test2.db');
    $tableName = 'csv_import';

    // Set the PDO attribute to throw exceptions on errors
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Execute a query to get the count of records in the table
    $countQuery = "SELECT COUNT(*) AS recordCount FROM $tableName";
    $result = $db->query($countQuery);

    if ($result !== false) {
        $recordCount = $result->fetchColumn();
        echo "Number of records in the table: $recordCount";
    } else {
        echo "Error executing count query.";
    }
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
}
?>
