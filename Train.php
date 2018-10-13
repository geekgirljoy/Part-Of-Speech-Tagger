<?php
// Create & return $conn object to hold connection to MySQL
function ConnectToMySQL($servername, $username, $password, $dbname){

  // Create connection
  $conn = new mysqli($servername, $username, $password, $dbname);
  // Check connection
  if ($conn->connect_error) {
    die("MYSQL DB Connection failed: " . $conn->connect_error);
  }
  
  return $conn;
}

// Disconnect $conn object that holds the connection to MySQL
function DisconnectFromMySQL(&$conn){
  $conn->close();
}


// If the word is in memory we know it, move on
// otherwise try adding it to the database
// if we add it to the database keep a copy in memory 
// to avoid unnecessary DB queries in the future
function AddWordToMySQLAndMemory($word, &$conn){
  
  global $words_to_id;
  
  // if the word isn't in memory try to add it to the database
  if(empty($words_to_id[$word])){
    $sql = "INSERT INTO `Words` (`ID`, `Word`) VALUES (NULL, '$word')";
    if ($conn->query($sql) === TRUE) {
        //echo "New word added successfully" . PHP_EOL;
      // add to memory for faster look up in the future
      $words_to_id[$word] = GetIDForWord($word, $conn); // get ID DB Assigned
    } else {
      // weird the Word exists - did you reboot?
      // echo "Word exists" . PHP_EOL; 
      // add to memory for faster look up in the future
      $words_to_id[$word] = GetIDForWord($word, $conn); // get ID DB Assigned
    }
  }  
}


// If the tag is in memory we know it, move on
// otherwise try adding it to the database
// if we add it to the database keep a copy in memory 
// to avoid unnecessary DB queries in the future
function AddTagToMySQLAndMemory($tag, &$conn){
  
  global $tags_to_id;
  
  // if the tag isn't in memory try to add it to the database
  if(empty($tags_to_id[$tag])){
    
    $sql = "INSERT INTO `Tags` (`ID`, `Tag`) VALUES (NULL, '$tag')";

    if ($conn->query($sql) === TRUE) {
        //echo "New tag added successfully" . PHP_EOL;
      // add to memory for faster look up in the future
      $tags_to_id[$tag] = GetIDForTag($tag, $conn); // get ID DB Assigned
    } else {
      // weird the Tag exists - did you reboot?
      // echo "Tag exists" . PHP_EOL;
      // add to memory for faster look up in the future
      $tags_to_id[$tag] = GetIDForTag($tag, $conn); // get ID DB Assigned
    }
  }
}



function AddTrigramToMySQL($gram_set, &$conn){
  
  
  $Word_A = GetIDForWord($gram_set['words'][0], $conn);
  $Word_B = GetIDForWord($gram_set['words'][1], $conn);
  $Word_C = GetIDForWord($gram_set['words'][2], $conn);
  $Tag_A = GetIDForTag($gram_set['tags'][0], $conn);
  $Tag_B = GetIDForTag($gram_set['tags'][1], $conn);
  $Tag_C = GetIDForTag($gram_set['tags'][2], $conn);
  
  $complete_trigram_set = true;
  
  if($Word_A == NULL || $Word_B == NULL || $Word_C == NULL ||
     $Tag_A == NULL || $Tag_B == NULL || $Tag_C == NULL){
    $complete_trigram_set = false;
  }

  if($complete_trigram_set == true){
      
    // Select the trigram if it exists in the database
    $sql = "SELECT * FROM `Trigrams` WHERE `Word_A`=$Word_A AND `Word_B`=$Word_B AND `Word_C`=$Word_C AND `Tag_A`=$Tag_A AND `Tag_B`=$Tag_B AND `Tag_C`=$Tag_C  LIMIT 1";

    $result = $conn->query($sql);

    // there is an instance of this pair
    if ($result->num_rows > 0) {
      
      // Obtain the record for the gram_set
      while($row = $result->fetch_assoc()) {
        $id = $row['ID'];
        $count = $row['Count'];
      }
      $count++; //gram_set encountered again, increment it.
      
      // push updated count to database
      $sql = "UPDATE `Trigrams` SET Count='$count' WHERE ID=$id";

      if ($conn->query($sql) === TRUE) {
          //echo "Trigram Count updated successfully" . PHP_EOL;
      } else {
          //echo "Error: " . $sql . PHP_EOL . $conn->error . PHP_EOL;
      }
    } else { // no previous gram_set instance
      
      // Add this gram_set
      $sql = "INSERT INTO `Trigrams` (`Count`, `Word_A`, `Word_B`, `Word_C`, `Tag_A`, `Tag_B`, `Tag_C`) VALUES ('1', '$Word_A', '$Word_B', '$Word_C', '$Tag_A', '$Tag_B', '$Tag_C')";
      if ($conn->query($sql) === TRUE) {
          //echo "New Trigram added successfully";
      } else {
          //echo "Error: " . $sql . PHP_EOL . $conn->error . PHP_EOL;
      }    
    }
  }
}


// Pull the id for a given word from memory if available
// fall back to the database if its not in memory
// return NULL if it's not in the database
function GetIDForWord($word, &$conn){
  
  global $words_to_id;
  
  // if the word isn't in memory try to get it from the database
  if(empty($words_to_id[$word])){
  
    $sql = "SELECT * FROM `Words` WHERE `Word`='$word' LIMIT 1";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {// word exists
      // Output the ID for this Word
      while($row = $result->fetch_assoc()) {
        return $row['ID'];
      }
    }
    return NULL; // not in DB
  }
  else{
    return $words_to_id[$word];
  }  
}


// Pull the word for a given id from memory if available
// fall back to the database if its not in memory
// return NULL if it's not in the database
function GetWordForID($ID, &$conn){
  global $ids_to_words;
  
  // if the ID isn't in memory try to get it from the database
  if(empty($ids_to_words[$ID])){
    
    $sql = "SELECT * FROM `Words` WHERE `ID`='$ID' LIMIT 1";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {// id exists
      // Output the Word for this ID
      while($row = $result->fetch_assoc()) {
        return $row['Word'];
      }
    }
    return NULL; // not in DB
  }
  else{
    return $ids_to_words[$ID];
  }
}


// Pull the id for a given tag from memory if available
// fall back to the database if its not in memory
// return NULL if it's not in the database
function GetIDForTag($tag, &$conn){
  global $tags_to_id;
  
  // if the Tag isn't in memory try to get it from the database
  if(empty($tags_to_id[$tag])){
    $sql = "SELECT * FROM `Tags` WHERE `Tag`='$tag' LIMIT 1";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {// tag exists
      // Output the ID for this tag
      while($row = $result->fetch_assoc()) {
        return $row['ID'];
      }
    }
    return NULL; // not in DB
  }
  else{
    return $tags_to_id[$tag];
  }
}


// Pull the tag for a given id from memory if available
// fall back to the database if its not in memory
// return NULL if it's not in the database
function GetTagForID($ID, &$conn){
  global $ids_to_tags;
  
  // if the Tag isn't in memory try to get it from the database
  if(empty($ids_to_tags[$ID])){
    $sql = "SELECT * FROM `Tags` WHERE `ID`='$ID' LIMIT 1";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {// ID exists
      // Output the Tag for this ID
      while($row = $result->fetch_assoc()) {
        return $row['Tag'];
      }
    }
    return NULL; // not in DB
  }
  else{
    return $ids_to_tags[$ID];
  }
}


// Get contents of a training file as a string
function GetFile($filename){
  $filename =  'brown' . DIRECTORY_SEPARATOR . $filename;
  $handle = fopen($filename, 'r');
  $contents = fread($handle, filesize($filename));
  fclose($handle);
  return $contents;
}


// data is a text file with word/tag
// capture the word and tag as group 1 & 2 split by a forward slash.
// example: (word || symbol)[/](tag)   the/article blue/adjective cat/noun ./.
// (1)(2): (the)(article) (blue)(adjective) (cat)(noun) (.)(.)
function PrepareData($textdata){
  
  $re = '/([^\s]+)[\/]([^\s]+)/m';
  preg_match_all($re, $textdata, $matches, PREG_SET_ORDER, 0);
  
  $data = array();
  foreach($matches as $key=>$match){
    $data['words'][$key] = $match[1];
    $data['tags'][$key] = $match[2];
  }
  return $data;
}


// data is an array
// $data['words'][i] = word or symbol
// $data['tags'][i] = tag for the assoceated word
function ExtractTrigrams($data){
  
  $trigrams = array();
  
  $word_count = count($data['words']);
  for($i=2; $i < $word_count; $i++){

    $w_a = $data['words'][$i-2];
    $w_b = $data['words'][$i-1];
    $w_c = $data['words'][$i];
    $t_a = $data['tags'][$i-2];
    $t_b = $data['tags'][$i-1];
    $t_c = $data['tags'][$i];
    
    $pack['words'] = array($w_a, $w_b, $w_c);
    $pack['tags'] = array($t_a, $t_b, $t_c);
    
    $trigrams[] = $pack;
  }
  
  return $trigrams;
}


// Get all the words from the DB with the word as the key and the id as the value
function GetAllWords(&$conn){
  $sql = "SELECT * FROM `Words`";
  $result = $conn->query($sql);
  
  if ($result->num_rows > 0) {// id exists
    $words = array();
    // Output the Word for this ID
    while($row = $result->fetch_assoc()) {
      $words[$row['Word']] = $row['ID'];
    }
    return $words;
  }
  return NULL;
}


// Get all the words from the DB with the id as the key and the word as the value
function GetAllWordIDs(&$conn){
  $sql = "SELECT * FROM `Words`";
  $result = $conn->query($sql);
  
  if ($result->num_rows > 0) {// id exists
    $words = array();
    // Output the Word for this ID
    while($row = $result->fetch_assoc()) {
      $words[$row['ID']] = $row['Word'];
    }
    return $words;
  }
  return NULL;
}


// Get all the tags from the DB with the tag as the key and the id as the value
function GetAllTags(&$conn){
  $sql = "SELECT * FROM `Tags`";
  $result = $conn->query($sql);
  
  if ($result->num_rows > 0) {// id exists
    $words = array();
    // Output the Word for this ID
    while($row = $result->fetch_assoc()) {
      $words[$row['Tag']] = $row['ID'];
    }
    return $words;
  }
  return NULL;
}


// Get all the tags from the DB with the id as the key and the tag as the value
function GetAllTagIDs(&$conn){
  $sql = "SELECT * FROM `Tags`";
  $result = $conn->query($sql);
  
  if ($result->num_rows > 0) {// id exists
    $words = array();
    // Output the Word for this ID
    while($row = $result->fetch_assoc()) {
      $words[$row['ID']] = $row['Tag'];
    }
    return $words;
  }
  return NULL;
}



$training_files = array('ca01', 'ca02', 'ca03', 'ca04', 'ca05', 'ca06', 'ca07', 'ca08', 'ca09', 'ca10', 'ca11', 'ca12', 'ca13', 'ca14', 'ca15', 'ca16', 'ca17', 'ca18', 'ca19', 'ca20', 'ca21', 'ca22', 'ca23', 'ca24', 'ca25', 'ca26', 'ca27', 'ca28', 'ca29', 'ca30', 'ca31', 'ca32', 'ca33', 'ca34', 'ca35', 'ca36', 'ca37', 'ca38', 'ca39', 'ca40', 'ca41', 'ca42', 'ca43', 'ca44', 'cb01', 'cb02', 'cb03', 'cb04', 'cb05', 'cb06', 'cb07', 'cb08', 'cb09', 'cb10', 'cb11', 'cb12', 'cb13', 'cb14', 'cb15', 'cb16', 'cb17', 'cb18', 'cb19', 'cb20', 'cb21', 'cb22', 'cb23', 'cb24', 'cb25', 'cb26', 'cb27', 'cc01', 'cc02', 'cc03', 'cc04', 'cc05', 'cc06', 'cc07', 'cc08', 'cc09', 'cc10', 'cc11', 'cc12', 'cc13', 'cc14', 'cc15', 'cc16', 'cc17', 'cd01', 'cd02', 'cd03', 'cd04', 'cd05', 'cd06', 'cd07', 'cd08', 'cd09', 'cd10', 'cd11', 'cd12', 'cd13', 'cd14', 'cd15', 'cd16', 'cd17', 'ce01', 'ce02', 'ce03', 'ce04', 'ce05', 'ce06', 'ce07', 'ce08', 'ce09', 'ce10', 'ce11', 'ce12', 'ce13', 'ce14', 'ce15', 'ce16', 'ce17', 'ce18', 'ce19', 'ce20', 'ce21', 'ce22', 'ce23', 'ce24', 'ce25', 'ce26', 'ce27', 'ce28', 'ce29', 'ce30', 'ce31', 'ce32', 'ce33', 'ce34', 'ce35', 'ce36', 'cf01', 'cf02', 'cf03', 'cf04', 'cf05', 'cf06', 'cf07', 'cf08', 'cf09', 'cf10', 'cf11', 'cf12', 'cf13', 'cf14', 'cf15', 'cf16', 'cf17', 'cf18', 'cf19', 'cf20', 'cf21', 'cf22', 'cf23', 'cf24', 'cf25', 'cf26', 'cf27', 'cf28', 'cf29', 'cf30', 'cf31', 'cf32', 'cf33', 'cf34', 'cf35', 'cf36', 'cf37', 'cf38', 'cf39', 'cf40', 'cf41', 'cf42', 'cf43', 'cf44', 'cf45', 'cf46', 'cf47', 'cf48', 'cg01', 'cg02', 'cg03', 'cg04', 'cg05', 'cg06', 'cg07', 'cg08', 'cg09', 'cg10', 'cg11', 'cg12', 'cg13', 'cg14', 'cg15', 'cg16', 'cg17', 'cg18', 'cg19', 'cg20', 'cg21', 'cg22', 'cg23', 'cg24', 'cg25', 'cg26', 'cg27', 'cg28', 'cg29', 'cg30', 'cg31', 'cg32', 'cg33', 'cg34', 'cg35', 'cg36', 'cg37', 'cg38', 'cg39', 'cg40', 'cg41', 'cg42', 'cg43', 'cg44', 'cg45', 'cg46', 'cg47', 'cg48', 'cg49', 'cg50', 'cg51', 'cg52', 'cg53', 'cg54', 'cg55', 'cg56', 'cg57', 'cg58', 'cg59', 'cg60', 'cg61', 'cg62', 'cg63', 'cg64', 'cg65', 'cg66', 'cg67', 'cg68', 'cg69', 'cg70', 'cg71', 'cg72', 'cg73', 'cg74', 'cg75', 'ch01', 'ch02', 'ch03', 'ch04', 'ch05', 'ch06', 'ch07', 'ch08', 'ch09', 'ch10', 'ch11', 'ch12', 'ch13', 'ch14', 'ch15', 'ch16', 'ch17', 'ch18', 'ch19', 'ch20', 'ch21', 'ch22', 'ch23', 'ch24', 'ch25', 'ch26', 'ch27', 'ch28', 'ch29', 'ch30', 'cj01', 'cj02', 'cj03', 'cj04', 'cj05', 'cj06', 'cj07', 'cj08', 'cj09', 'cj10', 'cj11', 'cj12', 'cj13', 'cj14', 'cj15', 'cj16', 'cj17', 'cj18', 'cj19', 'cj20', 'cj21', 'cj22', 'cj23', 'cj24', 'cj25', 'cj26', 'cj27', 'cj28', 'cj29', 'cj30', 'cj31', 'cj32', 'cj33', 'cj34', 'cj35', 'cj36', 'cj37', 'cj38', 'cj39', 'cj40', 'cj41', 'cj42', 'cj43', 'cj44', 'cj45', 'cj46', 'cj47', 'cj48', 'cj49', 'cj50', 'cj51', 'cj52', 'cj53', 'cj54', 'cj55', 'cj56', 'cj57', 'cj58', 'cj59', 'cj60', 'cj61', 'cj62', 'cj63', 'cj64', 'cj65', 'cj66', 'cj67', 'cj68', 'cj69', 'cj70', 'cj71', 'cj72', 'cj73', 'cj74', 'cj75', 'cj76', 'cj77', 'cj78', 'cj79', 'cj80', 'ck01', 'ck02', 'ck03', 'ck04', 'ck05', 'ck06', 'ck07', 'ck08', 'ck09', 'ck10', 'ck11', 'ck12', 'ck13', 'ck14', 'ck15', 'ck16', 'ck17', 'ck18', 'ck19', 'ck20', 'ck21', 'ck22', 'ck23', 'ck24', 'ck25', 'ck26', 'ck27', 'ck28', 'ck29', 'cl01', 'cl02', 'cl03', 'cl04', 'cl05', 'cl06', 'cl07', 'cl08', 'cl09', 'cl10', 'cl11', 'cl12', 'cl13', 'cl14', 'cl15', 'cl16', 'cl17', 'cl18', 'cl19', 'cl20', 'cl21', 'cl22', 'cl23', 'cl24', 'cm01', 'cm02', 'cm03', 'cm04', 'cm05', 'cm06', 'cn01', 'cn02', 'cn03', 'cn04', 'cn05', 'cn06', 'cn07', 'cn08', 'cn09', 'cn10', 'cn11', 'cn12', 'cn13', 'cn14', 'cn15', 'cn16', 'cn17', 'cn18', 'cn19', 'cn20', 'cn21', 'cn22', 'cn23', 'cn24', 'cn25', 'cn26', 'cn27', 'cn28', 'cn29', 'cp01', 'cp02', 'cp03', 'cp04', 'cp05', 'cp06', 'cp07', 'cp08', 'cp09', 'cp10', 'cp11', 'cp12', 'cp13', 'cp14', 'cp15', 'cp16', 'cp17', 'cp18', 'cp19', 'cp20', 'cp21', 'cp22', 'cp23', 'cp24', 'cp25', 'cp26', 'cp27', 'cp28', 'cp29', 'cr01', 'cr02', 'cr03', 'cr04', 'cr05', 'cr06', 'cr07', 'cr08', 'cr09');
$total_files = count($training_files);

$server = 'localhost';
$username = 'root';
$password = 'password';
$db = 'PartsOfSpeechTagger';
$conn = ConnectToMySQL($server, $username, $password, $db);


// Get all known current words and id's inefficient redundant calls but it's a run once.
$words_to_id = GetAllWords($conn);
$ids_to_words = GetAllWordIDs($conn);
$tags_to_id = GetAllTags($conn);
$ids_to_tags = GetAllWordIDs($conn);

$log = fopen('Log.txt', 'w+'); // log file

foreach($training_files as $filenumber=>$training_file){
  echo "Processing file $filenumber of $total_files." . PHP_EOL;
  fwrite($log, $training_file . PHP_EOL); // log the name of the file we are working on
  
  // Get data and get it ready for the bot to learn
  $training_data = GetFile($training_file);
  $training_data = PrepareData($training_data);
  $training_data = ExtractTrigrams($training_data);
  //var_dump($training_data);
  
  foreach($training_data as $key=>$set){
    foreach($set as $group=>$trigrams){
      if($group == 'words'){
        // add words
        AddWordToMySQLAndMemory($trigrams[0], $conn);
        AddWordToMySQLAndMemory($trigrams[1], $conn);
        AddWordToMySQLAndMemory($trigrams[2], $conn);
      }
      elseif($group == 'tags'){
        // add tags
        AddTagToMySQLAndMemory($trigrams[0], $conn);
        AddTagToMySQLAndMemory($trigrams[1], $conn);
        AddTagToMySQLAndMemory($trigrams[2], $conn);
      }
    }
    // We know the words and tags are now in the DB & Memory
    // process the trigrams
    AddTrigramToMySQL($set, $conn);
  }
}
fclose($log);


DisconnectFromMySQL($conn);
