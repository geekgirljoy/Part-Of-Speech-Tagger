<?php

/*

This code is ~126x faster to tag a sentence than the method used in Test.php

For simplicity I have excluded the Tokenize() and Remove() processes and split 
the string into an array words and removed the spaces manually so you can focus 
on the important changes.

Basiclly this just uses the precomputed proabilites in the Words table rather 
than doing the computation on at run time.

On my test system with a cold MySQL database it takes 3 - 4 seconds to run this code.

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



// lexemes
$words = array('The', 'quick', 'brown', 'fox', 'jumps', 'over', 'the', 'lazy', 'dog', '.', 'A', 'long-term', 'contract', 'with', '``', 'zero-liability', "''", 'protection', '!', "Let's", 'think', 'it', 'over', '.');

$lexemes = array();

echo 'Sentence: ';

foreach($words as $word){
	
	echo $word . ' ';
	
	$word = mysqli_escape_string($conn, $word);
	
    $sql = "SELECT * FROM `Words` WHERE `Word` = '$word' LIMIT 1";
	$result = $conn->query($sql);

	if(@$result->num_rows > 0){// We know this Uni-gram
	  // Collect the tags for the Uni-gram
	  while($row = mysqli_fetch_assoc($result)){
		  
		   // Decode Uni-gram tags from json into associtive array
          $tags = json_decode($row["Tags"], 1);
          
          // if there are known tags for the Uni-gram
          if(!empty($tags)){
			  // Sort the tags and compute %
			  arsort($tags);
			  $sum = array_sum($tags);
			 foreach($tags as $tag=>&$score){
				  $score = $score . ' : ' . ($score/$sum * 100) . '%';
			  }
		  }else{
			 $tags = array('unk'=>'1 : 100%');
		  }
          
		  $lexemes[$word] = array('lexeme'=>$word, 'tags'=> $tags);
	  }
	}else{ // We don't know this Tag
		$lexemes[$word] = array('lexeme'=>$word, 'tags'=> array('unk'=>'1 : 100%'));
	}
}
echo PHP_EOL . PHP_EOL;


// Echo the Tagged Sentence
$unique_tags = array();
echo 'Tagged Sentence: ';
foreach($lexemes as $key=>$lexeme){
	
	if(is_array($lexeme['tags'])){
      $tag = key($lexeme['tags']);
    }
    else{
		$tag = $lexeme['lexeme'];
	}
  
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
	//var_dump($lexeme);
	
  echo '[' . $lexeme['lexeme'] . ']'. PHP_EOL;
  
  $tags = '';
  foreach (@$lexeme['tags'] as $tag=>$value){
    $tags .= "$tag($value)" . PHP_EOL;
  }
  
  echo trim($tags) . ' ' . PHP_EOL . PHP_EOL;
}



/*
 * Results
 * 
 * 
Sentence: The quick brown fox jumps over the lazy dog . A long-term contract with `` zero-liability '' protection ! Let's think it over . 

Tagged Sentence: The/at quick/jj brown/jj fox/nn jumps/vbz over/in the/at lazy/jj dog/nn ./. A/at long-term/nn contract/nn with/in ``/`` zero-liability/unk \'\'/'' protection/nn !/. Let\'s/vb+ppo think/vb it/pps 

Tags: 
12 unique tags, 22 total.
nn(5) - 22.73% of the sentence.
at(3) - 13.64% of the sentence.
jj(3) - 13.64% of the sentence.
in(2) - 9.09% of the sentence.
.(2) - 9.09% of the sentence.
vbz(1) - 4.55% of the sentence.
``(1) - 4.55% of the sentence.
unk(1) - 4.55% of the sentence.
''(1) - 4.55% of the sentence.
vb+ppo(1) - 4.55% of the sentence.
vb(1) - 4.55% of the sentence.
pps(1) - 4.55% of the sentence.


Detailed Report: 
[The]
at(119596 : 99.472677368377%)
at-tl(334 : 0.27780088164352%)
at-hl(247 : 0.20543957414955%)
at-nc(47 : 0.039091740830076%)
nil(6 : 0.0049904349995841%) 

[quick]
jj(165 : 87.301587301587%)
rb(18 : 9.5238095238095%)
rb-hl(3 : 1.5873015873016%)
nn(3 : 1.5873015873016%) 

[brown]
jj(175 : 93.582887700535%)
nn(12 : 6.4171122994652%) 

[fox]
nn(27 : 100%) 

[jumps]
vbz(3 : 50%)
nns(3 : 50%) 

[over]
in(2140 : 69.255663430421%)
rp(929 : 30.064724919094%)
in-hl(11 : 0.35598705501618%)
rp-hl(6 : 0.19417475728155%)
jj(4 : 0.1294498381877%) 

[the]
at(119596 : 99.472677368377%)
at-tl(334 : 0.27780088164352%)
at-hl(247 : 0.20543957414955%)
at-nc(47 : 0.039091740830076%)
nil(6 : 0.0049904349995841%) 

[lazy]
jj(27 : 100%) 

[dog]
nn(185 : 100%) 

[.]
.(98596 : 98.951234933411%)
.-hl(1045 : 1.0487650665891%) 

[A]
at(48403 : 99.732141017452%)
at-hl(88 : 0.18131992664785%)
at-nc(15 : 0.03090680567861%)
fw-in(11 : 0.022664990830981%)
nil(7 : 0.014423175983352%)
nn(3 : 0.0061813611357221%)
fw-in-tl(3 : 0.0061813611357221%)
at-tl(3 : 0.0061813611357221%) 

[long-term]
nn(78 : 89.655172413793%)
nn-hl(9 : 10.344827586207%) 

[contract]
nn(149 : 89.759036144578%)
vb(17 : 10.240963855422%) 

[with]
in(17087 : 99.714052287582%)
in-hl(33 : 0.19257703081232%)
in-nc(9 : 0.052521008403361%)
rb(7 : 0.040849673202614%) 

[``]
``(15452 : 100%) 

[zero-liability]
unk(1 : 100%) 

[\'\']
''(16242 : 100%) 

[protection]
nn(168 : 100%) 

[!]
.(2640 : 99.472494348154%)
.-hl(14 : 0.52750565184627%) 

[Let\'s]
vb+ppo(92 : 100%) 

[think]
vb(16 : 100%) 

[it]
pps(6228 : 51.154004106776%)
ppo(5923 : 48.64887063655%)
pps-hl(9 : 0.073921971252567%)
pps-nc(8 : 0.06570841889117%)
ppo-nc(3 : 0.024640657084189%)
ppo-hl(3 : 0.024640657084189%)
uh(1 : 0.0082135523613963%)


*/
