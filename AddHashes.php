<?php

/*
This programm will connect to the PartsOfSpeechTagger database and add 3 additional fields directlyu after the 'Hash' field

We need to add 2 fields for 'Bigrams'
// hash(A && B) 
// hash(B && C) 

We also need 1 field for 'Skip-grams'
// hash(A && C) 

*/

// MySQL Server Credentials
$server = 'localhost';
$username = 'root';
$password = 'password';
$db = 'PartsOfSpeechTagger';

// Create connection
$conn = new mysqli($server, $username, $password, $db);

// Check connection
if ($conn->connect_error) {
  die("MYSQL DB Connection failed: " . $conn->connect_error);
}


// Add additional Hash fields
$sql = "ALTER TABLE `Trigrams` ADD `Hash_AB` VARCHAR(33) NOT NULL AFTER `Hash`, ADD `Hash_BC` VARCHAR(33) NOT NULL AFTER `Hash_AB`, ADD `Hash_AC` VARCHAR(33) NOT NULL AFTER `Hash_BC`";
$conn->query($sql);


// Add the Bigram and Skipgram hashes
$sql = "SELECT * FROM `Trigrams` WHERE `Hash_AB` = '' OR `Hash_BC` = '' OR `Hash_AC` = ''";

$result = $conn->query($sql);

$i = 1;
if ($result->num_rows > 0) {

  // output data of each row
  while($row = mysqli_fetch_assoc($result)) {
    
     // We already generated the Trigrams
     // A && B && C

     // Generate Bigram hashes
     // A && B 
     $Hash_AB = hash('md5', $row["Word_A"] . $row["Word_B"]);
     // B && C
     $Hash_BC = hash('md5', $row["Word_B"] . $row["Word_C"]);
     
     // Generate Skip-gram hashes
     // A && C
    $Hash_AC = hash('md5', $row["Word_A"] . $row["Word_C"]);
     
     // Generate SQL
     $sql_AB = "UPDATE `Trigrams` SET `Hash_AB` = '$Hash_AB' WHERE `Trigrams`.`Hash` = '" . $row["Hash"] . "'";
     $sql_BC = "UPDATE `Trigrams` SET `Hash_BC` = '$Hash_BC' WHERE `Trigrams`.`Hash` = '" . $row["Hash"] . "'";
     $sql_AC = "UPDATE `Trigrams` SET `Hash_AC` = '$Hash_AC' WHERE `Trigrams`.`Hash` = '" . $row["Hash"] . "'";
     
     // Update Database
     $conn->query($sql_AB);
     $conn->query($sql_BC);
     $conn->query($sql_AC);
     echo $i . PHP_EOL;
     $i++;
  }
}
$conn->close();
