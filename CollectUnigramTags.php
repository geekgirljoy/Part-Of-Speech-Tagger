<?php
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

// Add additional TagSum & Tags field to the words table
echo 'Adding TagSum Field.' . PHP_EOL;
$sql = "ALTER TABLE `Words` ADD `TagSum` int(11) NOT NULL AFTER `Count`";
$conn->query($sql);

echo 'Adding Tags Field.' . PHP_EOL;
$sql = "ALTER TABLE `Words` ADD `Tags` TEXT NOT NULL AFTER `TagSum`";
$conn->query($sql);

$words = array();
echo 'Locating Unigrams.' . PHP_EOL;

// Return all Trigrams
$sql = "SELECT * FROM `Trigrams`"; 

// Query the trigrams table
$result = $conn->query($sql);

// If there are Trigrams collect the tags for the Words
if($result->num_rows > 0){
    $i=0; // Keep track of current Trigram 
    echo 'Counting Unigrams Tags.' . PHP_EOL;
    while($row = mysqli_fetch_assoc($result)) {
        echo ++$i . PHP_EOL; // echo current Trigram
        
        // $words[md5 word hash][tag] += 1;
        // Example:
        // Unhashed: $words['the']['at'] += 1;
        // Hashed:   $words['8fc42c6ddf9966db3b09e84365034357']['at']++;
        @$words[hash('md5', $row["Word_A"])][$row["Tag_A"]]++;
        @$words[hash('md5', $row["Word_B"])][$row["Tag_B"]]++;
        @$words[hash('md5', $row["Word_C"])][$row["Tag_C"]]++;
    }
}


echo 'Updating Words.' . PHP_EOL;
foreach($words as $hash=>&$tags){
    if(count($tags) > 0){
        $sum = array_sum($tags); // Count the total number of tags
        $tags = json_encode($tags, 1); // tags data
        
        // Update word using the Hash key
        $sql = "UPDATE `Words` SET `Tags` = '$tags', `TagSum` = '$sum' WHERE `Words`.`Hash` = '$hash';"; 
        $conn->query($sql);
        
        echo "$hash Updated!" . PHP_EOL; // Report the hash was updated
    }
}

$conn->close(); // disconnect from the database
