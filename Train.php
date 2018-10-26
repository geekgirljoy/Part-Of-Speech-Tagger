<?php

// Get contents of a training file as a string
function GetFile($filename){
  $filename =  'brown' . DIRECTORY_SEPARATOR . $filename;
  $handle = fopen($filename, 'r');
  $contents = fread($handle, filesize($filename));
  fclose($handle);
  return $contents;
}

// We can use less memory by processing the files in batches instead of all together
$training_files = array(
  array('ca01', 'ca02', 'ca03', 'ca04', 'ca05', 'ca06', 'ca07', 'ca08', 'ca09', 'ca10'),
  array('ca11', 'ca12', 'ca13', 'ca14', 'ca15', 'ca16', 'ca17', 'ca18', 'ca19', 'ca20'),
  array('ca21', 'ca22', 'ca23', 'ca24', 'ca25', 'ca26', 'ca27', 'ca28', 'ca29', 'ca30'),
  array('ca31', 'ca32', 'ca33', 'ca34', 'ca35', 'ca36', 'ca37', 'ca38', 'ca39', 'ca40'),
  array('ca41', 'ca42', 'ca43', 'ca44', 'cb01', 'cb02', 'cb03', 'cb04', 'cb05', 'cb06'),
  array('cb07', 'cb08', 'cb09', 'cb10', 'cb11', 'cb12', 'cb13', 'cb14', 'cb15', 'cb16'),
  array('cb17', 'cb18', 'cb19', 'cb20', 'cb21', 'cb22', 'cb23', 'cb24', 'cb25', 'cb26'),
  array('cb27', 'cc01', 'cc02', 'cc03', 'cc04', 'cc05', 'cc06', 'cc07', 'cc08', 'cc09'),
  array('cc10', 'cc11', 'cc12', 'cc13', 'cc14', 'cc15', 'cc16', 'cc17', 'cd01', 'cd02'),
  array('cd03', 'cd04', 'cd05', 'cd06', 'cd07', 'cd08', 'cd09', 'cd10', 'cd11', 'cd12'),
  array('cd13', 'cd14', 'cd15', 'cd16', 'cd17', 'ce01', 'ce02', 'ce03', 'ce04', 'ce05'),
  array('ce06', 'ce07', 'ce08', 'ce09', 'ce10', 'ce11', 'ce12', 'ce13', 'ce14', 'ce15'),
  array('ce16', 'ce17', 'ce18', 'ce19', 'ce20', 'ce21', 'ce22', 'ce23', 'ce24', 'ce25'),
  array('ce26', 'ce27', 'ce28', 'ce29', 'ce30', 'ce31', 'ce32', 'ce33', 'ce34', 'ce35'),
  array('ce36', 'cf01', 'cf02', 'cf03', 'cf04', 'cf05', 'cf06', 'cf07', 'cf08', 'cf09'),
  array('cf10', 'cf11', 'cf12', 'cf13', 'cf14', 'cf15', 'cf16', 'cf17', 'cf18', 'cf19'),
  array('cf20', 'cf21', 'cf22', 'cf23', 'cf24', 'cf25', 'cf26', 'cf27', 'cf28', 'cf29'),
  array('cf30', 'cf31', 'cf32', 'cf33', 'cf34', 'cf35', 'cf36', 'cf37', 'cf38', 'cf39'),
  array('cf40', 'cf41', 'cf42', 'cf43', 'cf44', 'cf45', 'cf46', 'cf47', 'cf48', 'cg01'),
  array('cg02', 'cg03', 'cg04', 'cg05', 'cg06', 'cg07', 'cg08', 'cg09', 'cg10', 'cg11'),
  array('cg12', 'cg13', 'cg14', 'cg15', 'cg16', 'cg17', 'cg18', 'cg19', 'cg20', 'cg21'),
  array('cg22', 'cg23', 'cg24', 'cg25', 'cg26', 'cg27', 'cg28', 'cg29', 'cg30', 'cg31'),
  array('cg32', 'cg33', 'cg34', 'cg35', 'cg36', 'cg37', 'cg38', 'cg39', 'cg40', 'cg41'),
  array('cg42', 'cg43', 'cg44', 'cg45', 'cg46', 'cg47', 'cg48', 'cg49', 'cg50', 'cg51'),
  array('cg52', 'cg53', 'cg54', 'cg55', 'cg56', 'cg57', 'cg58', 'cg59', 'cg60', 'cg61'),
  array('cg62', 'cg63', 'cg64', 'cg65', 'cg66', 'cg67', 'cg68', 'cg69', 'cg70', 'cg71'),
  array('cg72', 'cg73', 'cg74', 'cg75', 'ch01', 'ch02', 'ch03', 'ch04', 'ch05', 'ch06'),
  array('ch07', 'ch08', 'ch09', 'ch10', 'ch11', 'ch12', 'ch13', 'ch14', 'ch15', 'ch16'),
  array('ch17', 'ch18', 'ch19', 'ch20', 'ch21', 'ch22', 'ch23', 'ch24', 'ch25', 'ch26'),
  array('ch27', 'ch28', 'ch29', 'ch30', 'cj01', 'cj02', 'cj03', 'cj04', 'cj05', 'cj06'),
  array('cj07', 'cj08', 'cj09', 'cj10', 'cj11', 'cj12', 'cj13', 'cj14', 'cj15', 'cj16'),
  array('cj17', 'cj18', 'cj19', 'cj20', 'cj21', 'cj22', 'cj23', 'cj24', 'cj25', 'cj26'),
  array('cj27', 'cj28', 'cj29', 'cj30', 'cj31', 'cj32', 'cj33', 'cj34', 'cj35', 'cj36'),
  array('cj37', 'cj38', 'cj39', 'cj40', 'cj41', 'cj42', 'cj43', 'cj44', 'cj45', 'cj46'),
  array('cj47', 'cj48', 'cj49', 'cj50', 'cj51', 'cj52', 'cj53', 'cj54', 'cj55', 'cj56'),
  array('cj57', 'cj58', 'cj59', 'cj60', 'cj61', 'cj62', 'cj63', 'cj64', 'cj65', 'cj66'),
  array('cj67', 'cj68', 'cj69', 'cj70', 'cj71', 'cj72', 'cj73', 'cj74', 'cj75', 'cj76'),
  array('cj77', 'cj78', 'cj79', 'cj80', 'ck01', 'ck02', 'ck03', 'ck04', 'ck05', 'ck06'),
  array('ck07', 'ck08', 'ck09', 'ck10', 'ck11', 'ck12', 'ck13', 'ck14', 'ck15', 'ck16'),
  array('ck17', 'ck18', 'ck19', 'ck20', 'ck21', 'ck22', 'ck23', 'ck24', 'ck25', 'ck26'),
  array('ck27', 'ck28', 'ck29', 'cl01', 'cl02', 'cl03', 'cl04', 'cl05', 'cl06', 'cl07'),
  array('cl08', 'cl09', 'cl10', 'cl11', 'cl12', 'cl13', 'cl14', 'cl15', 'cl16', 'cl17'),
  array('cl18', 'cl19', 'cl20', 'cl21', 'cl22', 'cl23', 'cl24', 'cm01', 'cm02', 'cm03'),
  array('cm04', 'cm05', 'cm06', 'cn01', 'cn02', 'cn03', 'cn04', 'cn05', 'cn06', 'cn07'),
  array('cn08', 'cn09', 'cn10', 'cn11', 'cn12', 'cn13', 'cn14', 'cn15', 'cn16', 'cn17'),
  array('cn18', 'cn19', 'cn20', 'cn21', 'cn22', 'cn23', 'cn24', 'cn25', 'cn26', 'cn27'),
  array('cn28', 'cn29', 'cp01', 'cp02', 'cp03', 'cp04', 'cp05', 'cp06', 'cp07', 'cp08'),
  array('cp09', 'cp10', 'cp11', 'cp12', 'cp13', 'cp14', 'cp15', 'cp16', 'cp17', 'cp18'),
  array('cp19', 'cp20', 'cp21', 'cp22', 'cp23', 'cp24', 'cp25', 'cp26', 'cp27', 'cp28'),
  array('cp29', 'cr01', 'cr02', 'cr03', 'cr04', 'cr05', 'cr06', 'cr07', 'cr08', 'cr09')
);

// Write down when we started the entire process
$first_start_time = microtime(true);

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


$total_sets = count($training_files); // 50 sets of 10

// Used for echoing progress
$current_set = 0;  // Which set are we processing
$current_file = 0; // Which file are we processing 
// How many files we processed so far
$total_training_files_processed = 0; 

// for all the sets of files
foreach($training_files as $set_num=>$training_file_set){
  
  $start_time = microtime(true); // Write down when we started this set
  
  // Echo that we are starting a new set
  $current_set = $set_num + 1;
  echo PHP_EOL . "Set $current_set of $total_sets." . PHP_EOL;
  
  // Create arrays to store our data 
  $trigrams = array(); // $trigrams is an array
  $u_words = array(); // $u_words is an array
  $u_tags = array(); // $u_tags is an array
   
  $total_set_files = count($training_file_set); // 50
  
  // All the files in the set
  foreach($training_file_set as $filenumber=>$training_file){
  
    // Echo that we are starting a new file
    $current_file = $filenumber + 1;
    echo "Processing file $current_file of $total_set_files." . PHP_EOL;

    // Get training text from file
    $training_text = GetFile($training_file);

    // Use regex to find (word)/(tag) pattern
    $regex = '/([^\s]+)[\/]([^\s]+)/m';
    preg_match_all($regex, $training_text, $matches, PREG_SET_ORDER, 0);

    // The number of regex pattern matches we found
    $num_of_matches = count($matches);
    foreach($matches as $key=>$match){ // for each match (word)/(tag)
      
      // We collect all words and tags at this time which will result
      // in duplicates however we can later use array_count_values() 
      // to quickly get a clean list of unique values with the number 
      // of times each was present in the array
      $u_words[] = $matches[$key][1]; // add all words to u_words
      $u_tags[] = $matches[$key][2];  // add all tags to u_tags
      
      
      // Now we can create the trigram
      // While there are still 3 or more words/tags left
      if($key <= ($num_of_matches - 3)){
        // Hash the plain text words concatenated together to determine 
        // a unique key for this trigram. The key combines the 3 words 
        // which allows for less comparisons when determining if a trigram
        // has been seen before.
        // example: hash('md5', 'Thequickbrown') = a05d6d1139097bfd312c9f1009691c2a
        $hash_key = hash('md5', $matches[$key][1] . $matches[$key+1][1] . $matches[$key+2][1]);

        // Is this a new trigram?
        // If $hash_key isn't in the $trigrams array then it hasn't
        // been seen before
        if(!in_array($hash_key, $trigrams)){
          $trigrams[$hash_key]['count'] = 1;
          $trigrams[$hash_key]['sources'][]=$training_file;
          $trigrams[$hash_key]['words'] = array($matches[$key][1], $matches[$key+1][1], $matches[$key+2][1]);
          $trigrams[$hash_key]['tags'] = array($matches[$key][2], $matches[$key+1][2], $matches[$key+2][2]);
        }
        else{// This trigram is not new
          $trigrams[$hash_key]['count']++; // increment
          
          // Has this training_file been added to the sources list
          // for this trigram?
          if(!in_array($training_file, $trigrams[$hash_key]['sources'])){
             // Add this training file name to the list of
             // Sources for this trigram
            $trigrams[$hash_key]['sources'][]=$training_file;
          }
        }    
      } // Create/Process trigram
      
    } // For each match in the file
  } // For all the files in the set
  // add this set count to the report
  $total_training_files_processed += $total_set_files;
  
  
  // Echo that we are starting a SQL write
  echo PHP_EOL . 'Writing SQL ' . PHP_EOL;
  
  // Echo that we are starting Trigrams
  echo count($trigrams) . ' Trigrams ';
  foreach ($trigrams as $hash=>$trigram){

    // Implode the sources array into a comma delimited string
    $Sources = trim(implode(', ', $trigram['sources']));
    
    // Trigrams
    $trigram_sql = 'INSERT INTO `Trigrams` (`Hash`, `Count`, `Word_A`, `Word_B`, `Word_C`, `Tag_A`, `Tag_B`, `Tag_C`, `Sources`) ';
    $trigram_sql .= "VALUES('$hash', {$trigram['count']}, '{$trigram['words'][0]}', '{$trigram['words'][1]}', '{$trigram['words'][2]}', '{$trigram['tags'][0]}', '{$trigram['tags'][1]}', '{$trigram['tags'][2]}', '$Sources') "; 
    $trigram_sql .= 'ON DUPLICATE KEY UPDATE ';
    $trigram_sql .= "`Count` = `Count` + {$trigram['count']}, ";
    $trigram_sql .= "`Sources` = concat(`Sources`, ' $Sources')";    
    $conn->query($trigram_sql);
  }
   echo 'Done! ' . PHP_EOL;
  
  
  
   // Echo that we are starting Words
   $u_words = array_count_values($u_words);
   echo count($u_words) . ' Words ';
   foreach($u_words as $word=>$count){
    $hash = hash('md5', $word);
    $word = mysqli_real_escape_string($conn, $word);

    // Word sql
    $words_sql = 'INSERT INTO `Words` (`Hash`, `Word`, `Count`) ';
    $words_sql .= "VALUES('$hash', '$word', $count) "; 
    $words_sql .= 'ON DUPLICATE KEY UPDATE ';
    $words_sql .= "`Count` = `Count` + $count;";
    $conn->query($words_sql);    
   }
   echo 'Done! ' . PHP_EOL;


   // Echo that we are starting Tags
    $u_tags = array_count_values($u_tags);
   echo count($u_tags) . ' Tags ';
   foreach($u_tags as $tag=>$count){
     
    $hash = hash('md5', $tag);
    $tag = mysqli_real_escape_string($conn, $tag);
    // Tag sql
    $tags_sql = 'INSERT INTO `Tags` (`Hash`, `Tag`, `Count`) ';
    $tags_sql .= "VALUES('$hash', '$tag', $count) "; 
    $tags_sql .= 'ON DUPLICATE KEY UPDATE ';
    $tags_sql .= "`Count` = `Count` + $count;";
    $conn->query($tags_sql);
   } 
   echo 'Done! ' . PHP_EOL;

  // Echo how long it tool to process the set
  $current_time = microtime(true);
  $time = $current_time - $start_time;
  echo PHP_EOL . "Processed $total_set_files files in $time seconds." . PHP_EOL . PHP_EOL;
  
  
  
  // Reclaim memory by setting the value of $trigrams to NULL
  // this makes sure that $trigrams is clear for next set of files
  echo PHP_EOL . 'Clearing Arrays.' . PHP_EOL;
  $trigrams = NULL;
  $u_words = NULL;
  $u_tags = NULL;

} // all the training files

// Disconnect $conn from MySQL
$conn->close();

$current_time = microtime(true);
$time = $current_time - $first_start_time;
echo "Processed $total_training_files_processed files in $time seconds." . PHP_EOL;

