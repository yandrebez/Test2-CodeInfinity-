<?php

// if (!file_exists('output')) {
//     mkdir('output');
//     echo "file created";
// } else {
//     echo 'file exists. <a href="upload.html">Upload document</a>"';
// }

function generateCSV($value) {
    //1. Created two arrays one for Names and another for Surnames each with 20 values.
    $arrNames = ['Liam', 'Olivia', 'Noah', 'Emma', 'Ava', 'Sophia', 'Isabella', 'Mia', 'Jackson', 'Aiden', 'Lucas', 'Elijah', 'James', 'Benjamin', 'Logan', 'Alexander', 'Ethan', 'Harper', 'Evelyn', 'Abigail'];
    $arrSurnames = ['Smith', 'Johnson', 'Williams', 'Jones', 'Brown', 'Davis', 'Miller', 'Wilson', 'Moore', 'Taylor', 'Anderson', 'Thomas', 'Jackson', 'White', 'Harris', 'Martin', 'Thompson', 'Garcia', 'Martinez', 'Robinson'];

    // Load the last used ID from the existing CSV file.
    $id = 0;
    $existingData = [];
    //2. Csv File path 
    $existingFile = 'output/output.csv';
    if (file_exists($existingFile)) {
        $existingData = array_map('str_getcsv', file($existingFile));
        echo 'file exists. <a href="upload.html">Upload document</a>"';
        if (!empty($existingData)) {
            $id = end($existingData)[0];
        }
    }

    $id=0;

    
    //Created an array that the data that is randomly generated will be returned to.
    $arrData = [];

    //3. Method to generate random records that will be returned to $arrData
    for($i = 0; $i < $value; $i++){
            $id++;
            $randomNames = $arrNames[array_rand($arrNames)];
            $randomSurnames = $arrSurnames[array_rand($arrSurnames)];
            $initials = strtoupper($randomNames[0]);

            $age = rand(1, 90);
            $randomMonth = rand(1, 12); 
            $randomDay = rand(1, 28);   

            $dateofBirth = date('d/m/Y', strtotime("-{$randomDay} days -{$randomMonth} months -{$age} years"));

            $id = (int) $id;
      
            $isDuplicate = true;
            $uniqueCheck = [];

        while ($isDuplicate) {
            $randomNames = $arrNames[array_rand($arrNames)];
            $randomSurnames = $arrSurnames[array_rand($arrSurnames)];
            $age = rand(1, 90);
            $randomMonth = rand(1, 12); 
            $randomDay = rand(1, 28); 
            $dateofBirth = date('d/m/Y', strtotime("-{$randomDay} days -{$randomMonth} months -{$age} years"));
            
            $key = $randomNames . $randomSurnames . $age . $dateofBirth;

            if (!isset($uniqueCheck[$key])) {
                $uniqueCheck[$key] = true;
                $arrData[] = ['id' => $id, 'name' => $randomNames, 'surname' => $randomSurnames, 'initials' => $initials, 'age' => $age, 'dateOfBirth' => $dateofBirth];
                break;
            } else {
                echo "Duplicate found. Re-generating...\n";
            }
        }
    }
    //print_r($arrData);
    $csvString = '';
    foreach ($arrData as $row) {
        $csvString .= '"' . implode('","', $row) . "\"\n";
    }

    //echo $csvString;

    // Save the CSV string to a file
    $file = fopen($existingFile, 'w');
    fwrite($file, $csvString);
    fclose($file);
}

generateCSV($_POST['numRecords']);

?>


