<?php

if (!file_exists('output')) {
    mkdir('output');
    echo "file created";
} else {
    echo 'file exists. <a href="upload.html">Upload document</a>"';
}

function generateCSV($value) {
    //1. Created two arrays one for Names and another for Surnames each with 20 values.
    $arrNames = ['Liam', 'Olivia', 'Noah', 'Emma', 'Ava', 'Sophia', 'Isabella', 'Mia', 'Jackson', 'Aiden', 'Lucas', 'Elijah', 'James', 'Benjamin', 'Logan', 'Alexander', 'Ethan', 'Harper', 'Evelyn', 'Abigail'];
    $arrSurnames = ['Smith', 'Johnson', 'Williams', 'Jones', 'Brown', 'Davis', 'Miller', 'Wilson', 'Moore', 'Taylor', 'Anderson', 'Thomas', 'Jackson', 'White', 'Harris', 'Martin', 'Thompson', 'Garcia', 'Martinez', 'Robinson'];

    // Load the last used ID from the existing CSV file.
    $id = 0;
    $existingData = [];
    $existingFile = 'output/output.csv';
    if (file_exists($existingFile)) {
        $existingData = array_map('str_getcsv', file($existingFile));
        if (!empty($existingData)) {
            $id = end($existingData)[0];
        }
    }

    $id=0;

    
    //Created an array that the data that is randomly generated will be returned to.
    $arrData = [];

    //2. Method to generate random records that will be returned to $arrData
    for($i = 0; $i < $value; $i++){
            $id++;
            $randomNames = $arrNames[array_rand($arrNames)];
            $randomSurnames = $arrSurnames[array_rand($arrSurnames)];
            $initials = strtoupper($randomNames[0]);

            $age = rand(1, 90);
            $randomMonth = rand(1, 12); 
            $randomDay = rand(1, 28);   

            $dateofBirth = date('Y-m-d', strtotime("-{$age} years -{$randomMonth} months -{$randomDay} days"));

            $id = (int) $id;
      
            $isDuplicate = false;

        //Check for duplicates
        //If duplicate, keep generating new entries until a unique one is found
        if($isDuplicate) {
            //echo($isDuplicate);
            $randomNames = $arrNames[array_rand($arrNames)];
            $randomSurnames = $arrSurnames[array_rand($arrSurnames)];
            $age = rand(1, 90);
            $dateofBirth = date('Y-m-d', strtotime("-{$age} years -{$randomMonth} months -{$randomDay} days"));
            //if there is no duplicate continue generating new entries
            $isDuplicate = false;
            foreach ($arrData as $entry) {
                if (
                    $entry['name'] === $randomNames &&
                    $entry['surname'] === $randomSurnames &&
                    $entry['age'] === $age &&
                    $entry['dateOfBirth'] === $dateofBirth
                ) {
                    $isDuplicate = true;
                    //echo($isDuplicate);
                    break;
                }
            }
        }
        //return the random generated values != duplicate to $arrData
        $arrData[] = ['id' => $id, 'name' => $randomNames, 'surname' => $randomSurnames, 'initials' => $initials, 'age' => $age, 'dateOfBirth' => $dateofBirth];
    }
    //print_r($arrData);

    //Print the values in the array into .csv
    $file = fopen($existingFile, 'w');
    fputcsv($file, ['Id', 'Name', 'Surname', 'Initials', 'Age', 'DateOfBirth']);
    // if (empty($existingData)) {
    //     // Write the header only if the file is empty.
    //     fputcsv($file, ['Id', 'Name', 'Surname', 'Initials', 'Age', 'DateOfBirth']);
    // }
    foreach ($arrData as $row) {
        fputcsv($file, $row);
    }
    fclose($file);
}

generateCSV($_POST['numRecords']);

?>


