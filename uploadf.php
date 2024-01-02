<?php
ini_set('upload_max_filesize', '-1');
ini_set('post_max_size', '-1');
ini_set('max_execution_time', 300);
ini_set('memory_limit', '-1');

require_once __DIR__ . '/vendor/autoload.php';

try {
    // Check for POST method
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $dataBase = new PDO('sqlite:C:\Users\27837\test2.db');
        echo 'Connection was successful';

        $tableName = 'csv_import';

        // Set the PDO attribute to throw exceptions on errors
        $dataBase->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

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
        $dataBase->exec($createTableSQL);
        echo 'Table created successfully';

        //Making sure db is clear for upload of csv
        $clearTable = [$tableName];

        foreach ($clearTable as $clearTable) {
            $dataBase->exec("DELETE FROM $clearTable");
        }

        // Check if a file was uploaded
        if (isset($_FILES['csvFile']) && $_FILES['csvFile']['error'] == UPLOAD_ERR_OK) {
            $csvFilePath = $_FILES['csvFile']['tmp_name'];

            //Im deviding the csv into Chunk sizes of 10000 rows per chunk
            $csvChunks = array_chunk(file($csvFilePath), 10000);

            // Prepare the SQL statement
            $insertDataSQL = "
                INSERT INTO $tableName (Id, Name, Surname, Initials, Age, dateOfBirth)
                VALUES (?, ?, ?, ?, ?, ?);
            ";

            $statement = $dataBase->prepare($insertDataSQL);

            $dataBase->beginTransaction();

            foreach ($csvChunks as $chunk) {
                foreach ($chunk as $row) {
                    if (strpos($row, 'Name') !== false) {
                        continue;
                    }
                    $data = str_getcsv($row);

                    $statement->bindParam(1, $data[0], PDO::PARAM_STR);
                    $statement->bindParam(2, $data[1], PDO::PARAM_STR);
                    $statement->bindParam(3, $data[2], PDO::PARAM_STR);
                    $statement->bindParam(4, $data[3], PDO::PARAM_STR);
                    $statement->bindParam(5, $data[4], PDO::PARAM_STR);
                    $statement->bindParam(6, $data[5], PDO::PARAM_STR);

                    $statement->execute();
                }
            }

            // Commit the transaction
            $dataBase->commit();

            echo "Data inserted successfully.";
        } else {
            echo 'File upload error';
            var_dump($_FILES);
        }
    } else {
        echo 'Invalid request method.';
    }
} catch (PDOException $e) {
    echo 'Connection failed:\n ' . $e->getMessage();
}
?>
