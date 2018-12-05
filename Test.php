<?php


// This is our Tokenize function from 
function Tokenize($text, $delimiters, $compound_word_symbols, $contraction_symbols){  
      
  $temp = '';                   // A temporary string used to hold incomplete lexemes
  $lexemes = array();           // Complete lexemes will be stored here for return
  $chars = str_split($text, 1); // Split the text sting into characters.
  
  // Step through all character tokens in the $chars array
  foreach($chars as $key=>$char){
        
    // If this $char token is in the $delimiters array
    // Then stop building $temp and add it and the delimiter to the $lexemes array
    if(in_array($char, $delimiters)){
      
      // Does temp contain data?
      if(strlen($temp) > 0){
        // $temp is a complete lexeme add it to the array
        $lexemes[] = $temp;
      }      
      $temp = ''; // Make sure $temp is empty
      
      $lexemes[] = $char; // Capture delimiter as a whole lexeme
    }
    else{// This $char token is NOT in the $delimiters array
      // Add $char to $temp and continue to next $char
      $temp .= $char; 
    }
    
  } // Step through all character tokens in the $chars array


  // Check if $temp still contains any residual lexeme data?
  if(strlen($temp) > 0){
    // $temp is a complete lexeme add it to the array
    $lexemes[] = $temp;
  }
  
  // We have processed all character tokens in the $chars array
  // Free the memory and garbage collect $chars & $temp
  $chars = NULL;
  $temp = NULL;
  unset($chars);
  unset($temp);


  // We now have the simplest lexems extracted. 
  // Next we need to recombine compound-words, contractions 
  // And do any other processing with the lexemes.

  // If there are $chars in the $compound_word_symbols array
  if(!empty($compound_word_symbols)){
    
    // Count the number of $lexemes
    $number_of_lexemes = count($lexemes);
    
    // Step through all lexeme tokens in the $lexemes array
    foreach($lexemes as $key=>&$lexeme){
      
      // Check if $lexeme is in the $compound_word_symbols array
      if(in_array($lexeme, $compound_word_symbols)){
        
        // If this isn't the first $lexeme in $lexemes
        if($key > 0){ 
          // Check the $lexeme $before this
          $before = $lexemes[$key - 1];
          
          // If $before isn't a $delimiter
          if(!in_array($before, $delimiters)){
            // Merge it with the compound symbol
            $lexeme = $before . $lexeme;
            // And remove the $before $lexeme from $lexemes
            $lexemes[$key - 1] = NULL;
          }
        }
        
        // If this isn't the last $lexeme in $lexemes
        if($key < $number_of_lexemes){
          // Check the $lexeme $after this
          $after = $lexemes[$key + 1];
          
          // If $after isn't a $delimiter
          if(!in_array($after, $delimiters)){
            // Merge the $lexeme it with
            $lexemes[$key + 1] = $lexeme . $after;
            // And remove the $lexeme
            $lexeme = NULL;
          }
        }
        
      } // Check if lexeme is in the $compound_word_symbols array
    } // Step through all tokens in the $lexemes array      
  } // If there are $chars in the $compound_word_symbols array
  
  // Filter out any NULL values in the $lexemes array
  // created during the compound word merges using array_filter()
  // and then re-index so the $lexemes array is nice and sorted using array_values().
  $lexemes = array_values(array_filter($lexemes));
  
  
  // If there are $chars in the $contraction_symbols array
  if(!empty($contraction_symbols)){
    
    // Count the number of $lexemes
    $number_of_lexemes = count($lexemes);
    
    // Step through all lexeme tokens in the $lexemes array
    foreach($lexemes as $key=>&$lexeme){
      
      // Check if $lexeme is in the $contraction_symbols array
      if(in_array($lexeme, $contraction_symbols)){
        
        // If this isn't the first $lexeme in $lexemes
        // and If this isn't the last $lexeme in $lexemes
        if($key > 0 && $key < $number_of_lexemes){ 
          // Check the $lexeme $before this
          $before = $lexemes[$key - 1];
          
          // Check the $lexeme $after this
          $after = $lexemes[$key + 1];
          
          
          // If $before isn't a $delimiter
          // and $after isn't a $delimiter
          if(!in_array($before, $delimiters) && !in_array($after, $delimiters)){
            // Merge the contraction tokens
            $lexemes[$key + 1] = $before . $lexeme . $after;
            
            // Remove $before
            $lexemes[$key - 1] = NULL;
            // And remove this $lexeme
            $lexeme = NULL;            
          }

        }
        
      } // Check if lexeme is in the $contraction_symbols array
    } // Step through all tokens in the $lexemes array      
  } // If there are $chars in the $contraction_symbols array
  
  // Filter out any NULL values in the $lexemes array
  // created during the contraction merges using array_filter()
  // and then re-index so the $lexemes array is nice and sorted using array_values().
  $lexemes = array_values(array_filter($lexemes));
  

  // Return the $lexemes array.
  return $lexemes;
} // Tokenize()

// Remove unwanted Delimiters or symbols from Lexems array
function Remove($lexemes, $remove_values){
    
    foreach($lexemes as &$lexeme){
        
        // if the lexeme is one that should  be removed
        if(in_array($lexeme, $remove_values)){
            $lexeme = NULL; // set it to null
        }
    }
    // Remove NULL, FALSE & "" but leaves values of 0 (zero)
    $lexemes = array_filter( $lexemes, 'strlen' );
  
    return array_values($lexemes);
}

// This takes an array of lexemes produced by the Tokenize() function 
// and returns an associative array containing tri-grams, bi-grams and skip-grams
function ExtractGrams($lexemes, $hash = true){
  
  $grams = array();
  
  $lexeme_count = count($lexemes);
  for($i=2; $i < $lexeme_count; $i++){
      if($hash == true){// hashed string - default
        $grams['trigrams'][] = hash('md5', $lexemes[$i-2] . $lexemes[$i-1] . $lexemes[$i]);
        $grams['skipgrams'][] = hash('md5', $lexemes[$i-2] . $lexemes[$i]);
     }
     else{// unhashed string
         $grams['trigrams'][] = $lexemes[$i-2] . $lexemes[$i-1] . $lexemes[$i];
         $grams['skipgrams'][] = $lexemes[$i-2] . $lexemes[$i];
     }
  }
  for($i=1; $i < $lexeme_count; $i++){
       if($hash == true){// hashed string - default
           $grams['bigrams'][] = hash('md5', $lexemes[$i-1] . $lexemes[$i]);
       }
       else{// unhashed string
           $grams['bigrams'][] = $lexemes[$i-1] . $lexemes[$i];
       }
  }
  
  return $grams;
}




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

// Delimiters (Lexeme Boundaries)
$delimiters = array('~', '!', '@', '#', '$', '%', '^', '&', '*', '(', ')', '_', '+', '`', '-', '=', '{', '}', '[', ']', '\\', '|', ':', ';', '"', '\'', '<', '>', ',', '.', '?', '/', ' ', "\t", "\n");

// Symbols used to detect compound-words
$compound_word_symbols = array('-', '_');

// Symbols used to detect contractions
//$contraction_symbols = array("'", '.', '@');
$contraction_symbols = array("'", '@');

// The text we want to tag
$text = 'The quick brown fox jumps over the lazy dog. A long-term contract with "zero-liability" protection! Let\'s think it over.';

// Tokenize and extract the $lexemes from $text
$lexemes = Tokenize($text, $delimiters, $compound_word_symbols, $contraction_symbols);

// Filter unwanted lexemes, in this case, we want to remove spaces since 
// the Brown Corpus doesn't use them and we don't really need them for anything.
$lexemes = Remove($lexemes, array(' '/*, Add other values to remove here*/));

// Extract the Lexemes into Bi-grams, Tri-grams & Skip-grams 
// using the new ExtractGrams() function
$grams = ExtractGrams($lexemes);

// Lookup all the grams using their hashes to simplify and speedup 
// the queries due to reduced number of field comparisons.
foreach($grams as $skey=>&$gramset){
  foreach($gramset as $gkey=>&$gram){
    if($skey == 'trigrams'){
      $sql = "SELECT * FROM `Trigrams` WHERE `Hash` = '$gram' ORDER BY `Count` DESC"; 
    }
    elseif($skey == 'bigrams'){
      $sql = "SELECT * FROM `Trigrams` WHERE `Hash_AB` = '$gram' OR `Hash_BC` = '$gram' ORDER BY `Count` DESC"; 
    }
    elseif($skey == 'skipgrams'){
      $sql = "SELECT * FROM `Trigrams` WHERE `Hash_AC` = '$gram' ORDER BY `Count` DESC"; 
    }
    $gram = array('hash'=>$gram, 'sql'=>$sql);
    
    
    $result = $conn->query($gram['sql']);
    $gram['data'] = array();
    if($result->num_rows > 0){
       
      // Collect the data for this gram result
      while($row = mysqli_fetch_assoc($result)) {
        $gram['data'][] = array(
               'Hash'=> $row["Hash"],
               'Count'=> $row["Count"],
               'Word_A'=> $row["Word_A"],
               'Word_B'=> $row["Word_B"],
               'Word_C'=> $row["Word_C"],
               'Tag_A'=> $row["Tag_A"],
               'Tag_B'=> $row["Tag_B"],
               'Tag_C'=> $row["Tag_C"]);
      }
    }
  }
}


// Get a list of Unique lexemes
$unique_lexemes = array_keys(array_count_values($lexemes));

// Process the gram data for each word
foreach($grams as $skey=>&$gramset){
  foreach($gramset as $gkey=>&$gram){
    foreach($gram['data'] as $data){
            
      // If the word being considered is one we're looking for
      // collect the tag and increment it's value
      if(in_array($data['Word_A'], $unique_lexemes)){
        @$unique_lexemes[$data['Word_A']][$data['Tag_A']]++;
      }
      if(in_array($data['Word_B'], $unique_lexemes)){
        @$unique_lexemes[$data['Word_B']][$data['Tag_B']]++;
      }
      if(in_array($data['Word_C'], $unique_lexemes)){
        @$unique_lexemes[$data['Word_C']][$data['Tag_C']]++;
      }
    }
  }
}

// Organize the data a little better and calculate the tag score
foreach ($unique_lexemes as $key => &$value) 
{ 
  // remove the strings in the numeric indexes
  if(is_numeric($key)){
    unset($unique_lexemes[$key]); 
  }
  else{// this array index is associate
     // sort the tags and compute %
    arsort($value);
    $sum = array_sum($value);
    foreach($value as $tag=>&$score){
      $score = $score . ' : ' . ($score/$sum * 100) . '%';
    }
  }
  
}
// Merge unique lexemes (with tag data) into the lexemes
$in_citation = false;
foreach($lexemes as $key=>$lexeme){
  // If we have a tag for the word 
  if(array_key_exists($lexeme, $unique_lexemes)){
    $lexemes[$key] = array('lexeme'=>$lexeme, 'tags'=> $unique_lexemes[$lexeme]);
  }else{
    // No Bi-gram, Skip-gram or Tri-gram

    // Try to look up the Unigram
    $sql = "SELECT * FROM `Words` WHERE `Word` = '$lexeme'";
    $result = $conn->query($sql);
    if($result->num_rows > 0){// We know this Uni-gram
      // Collect the tags for the Uni-gram
      while($row = mysqli_fetch_assoc($result)) {
		  // Decode Uni-gram tags from json into associtive array
          $tags = json_decode($row["Tags"], 1);
          
          // Sort the tags and compute %
		  arsort($tags);
          $sum = array_sum($tags);
          foreach($tags as $tag=>&$score){
              $score = $score . ' : ' . ($score/$sum * 100) . '%';
          }
		  $lexemes[$key] = array('lexeme'=>$lexeme, 'tags'=> $tags);
      }
    }else{ // We don't know this Uni-gram/word
        // Could it be a tag like punctuation or something?
    
        // check if lexeme is a quote and convert to open or closed unigram/tag
        $quotes = array('"', "''", "``");
        if(in_array($lexeme, $quotes) ){// is this a quote/citation?
            if($in_citation == true){
                // this is a close quote
                $lexeme = "''";
                $in_citation = false;
            }else{
                // this is an open quote
                $lexeme = "``";
                $in_citation = true;
            }
        }
    
        // It's basiclly "do or die" at this point and if the lexeme
        // can't be located after this it's time to give up and call it an unknown 
        // lexeme/tag
        $l = mysqli_real_escape_string($conn, $lexeme);
        $sql = "SELECT * FROM `Tags` WHERE `Tag` = '$l'";
        $result = $conn->query($sql);

        if($result->num_rows > 0){// We know this Uni-gram
            // Collect the tags for the Uni-gram
            while($row = mysqli_fetch_assoc($result)){
                $lexemes[$key] = array('lexeme'=>$lexeme, 'tags'=>array($row["Tag"]=>$row["Count"] . ' : 100%'));
            }
        }else{ // We don't know this Tag
            $lexemes[$key] = array('lexeme'=>$lexeme, 'tags'=> array('unk'=>'1 : 100%'));
        }
     }
  }
}
$conn->close(); // disconnect from the database

// Echo Original Sentence
echo 'Sentence: ' . $text . PHP_EOL;
echo PHP_EOL;

// Echo the Tagged Sentence
$unique_tags = array();
echo 'Tagged Sentence: ';
foreach($lexemes as $key=>$lexeme){
  $tag = key($lexeme['tags']);
  echo $lexeme['lexeme'] . '/' . $tag . ' ';
  @$unique_tags[$tag]++;
}
echo PHP_EOL . PHP_EOL;

// Echo the Basic Tags report
echo 'Tags: ' . PHP_EOL;
arsort($unique_tags);
$sum = array_sum($unique_tags);
echo count($unique_tags) . " unique tags, $sum total." . PHP_EOL;
foreach($unique_tags as $tag=>$count){

  echo "$tag($count) - " . number_format($count/$sum * 100, 2) . '% of the sentence.' . PHP_EOL;
}
echo PHP_EOL . PHP_EOL;

// Echo the Detailed Tags report
echo 'Detailed Report: ' . PHP_EOL;
foreach($lexemes as $key=>$lexeme){
  echo '[' . $lexeme['lexeme'] . ']'. PHP_EOL;
  
  $tags = '';
  foreach ($lexeme['tags'] as $tag=>$value){
    $tags .= "$tag($value)" . PHP_EOL;
  }
  
  echo trim($tags) . ' ' . PHP_EOL . PHP_EOL;
}


/*
 * Results
 * 
 * 
Sentence: The quick brown fox jumps over the lazy dog. A long-term contract with "zero-liability" protection! Let's think it over.

Tagged Sentence: The/at quick/jj brown/jj fox/np jumps/nns over/in the/at lazy/jj dog/nn ./. A/at long-term/nn contract/vb with/in ``/`` zero-liability/unk ''/'' protection/nn-hl !/. Let's/vb+ppo think/vb it/ppo over/in ./. 

Tags: 
14 unique tags, 24 total.
at(3) - 12.50% of the sentence.
jj(3) - 12.50% of the sentence.
in(3) - 12.50% of the sentence.
.(3) - 12.50% of the sentence.
nn(2) - 8.33% of the sentence.
vb(2) - 8.33% of the sentence.
np(1) - 4.17% of the sentence.
nns(1) - 4.17% of the sentence.
``(1) - 4.17% of the sentence.
unk(1) - 4.17% of the sentence.
''(1) - 4.17% of the sentence.
nn-hl(1) - 4.17% of the sentence.
vb+ppo(1) - 4.17% of the sentence.
ppo(1) - 4.17% of the sentence.


Detailed Report: 
[The]
at(3 : 100%) 

[quick]
jj(1 : 100%) 

[brown]
jj(2 : 100%) 

[fox]
np(6 : 66.666666666667%)
nn-tl(3 : 33.333333333333%) 

[jumps]
nns(1 : 100%) 

[over]
in(522 : 81.5625%)
rp(114 : 17.8125%)
in-hl(4 : 0.625%) 

[the]
at(539 : 100%) 

[lazy]
jj(27 : 100%) 

[dog]
nn(22 : 100%) 

[.]
.(1599 : 98.764669549104%)
.-hl(20 : 1.2353304508956%) 

[A]
at(1380 : 97.802976612332%)
at-hl(26 : 1.8426647767541%)
nn(2 : 0.1417434443657%)
np-hl(2 : 0.1417434443657%)
at-tl-hl(1 : 0.070871722182849%) 

[long-term]
nn(1 : 100%) 

[contract]
vb(2 : 50%)
nn(2 : 50%) 

[with]
in(6 : 85.714285714286%)
rb(1 : 14.285714285714%) 

[``]
``(8837 : 100%) 

[zero-liability]
unk(1 : 100%) 

['']
''(8789 : 100%) 

[protection]
nn-hl(10 : 100%) 

[!]
.(6 : 100%) 

[Let's]
vb+ppo(9 : 100%) 

[think]
vb(31 : 100%) 

[it]
ppo(150 : 71.428571428571%)
pps(60 : 28.571428571429%) 

[over]
in(522 : 81.5625%)
rp(114 : 17.8125%)
in-hl(4 : 0.625%) 

[.]
.(1599 : 98.764669549104%)
.-hl(20 : 1.2353304508956%) 



*/
