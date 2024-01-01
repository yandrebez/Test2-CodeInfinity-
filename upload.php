<?php
require_once __DIR__ . '/vendor/autoload.php';

try {
    $pdo = new PDO('sqlite:C:\Users\27837\test2.db');
    echo 'Connection was successful';

    $tableName = 'csv_import3';

    // Set the PDO attribute to throw exceptions on errors
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Define SQL statement to create the table
    $createTableSQL = "
        CREATE TABLE IF NOT EXISTS $tableName (
            Id INTEGER PRIMARY KEY AUTOINCREMENT,
            Name TEXT,
            Surname TEXT,
            Initials TEXT,
            Age TEXT,
            dateOfBirth TEXT
        );
    ";

    // Execute the SQL statement to create the table
    $pdo->exec($createTableSQL);
    echo 'Table created successfully';

    // Check if a file was uploaded
    if (isset($_FILES['csvFile']) && $_FILES['csvFile']['error'] == UPLOAD_ERR_OK) {
        $csvFilePath = $_FILES['csvFile']['tmp_name'];

        // Load CSV data
        $csvData = array_map('str_getcsv', file($csvFilePath));

        // Remove header row
        $header = array_shift($csvData);

        // Define SQL statement to insert data
        $insertDataSQL = "
            INSERT INTO $tableName (ID, Name, SURNAME, INITIALS, AGE, dateOfBirth)
            VALUES (?, ?, ?, ?, ?, ?);
        ";

        // Prepare the SQL statement
        $stmt = $pdo->prepare($insertDataSQL);

        // Iterate through CSV data and insert into the table
        foreach ($csvData as $row) {
            $stmt->execute($row);
        }

        echo "Data inserted successfully.";
    } else {
        echo 'No file uploaded or an error occurred.';
    }
} catch (PDOException $e) {
    echo 'Connection failed:\n ' . $e->getMessage();
}
?>









