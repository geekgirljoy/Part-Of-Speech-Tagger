# Part-Of-Speech-Tagger
A PHP & MySQL based Parts of Speech Tagger that uses the Brown Corpus.

Currently this project is still under active development however it is at a point where it is useful.

## Project Files And Overview

* The original Brown Corpus is available for use and review in the [Brown](https://github.com/geekgirljoy/Part-Of-Speech-Tagger/tree/master/brown) subfolder.

Please note my [Disclaimer](https://github.com/geekgirljoy/Part-Of-Speech-Tagger/blob/master/DISCLAIMER) where I explicitly state that I do not own the Brown corpus and I am not selling it to you.

* There is an SQL setup file ([Create.PartsOfSpeech.DB.sql](https://github.com/geekgirljoy/Part-Of-Speech-Tagger/blob/master/Create.PartsOfSpeech.DB.sql)) that you can use to create the MySQL database necessary to use this Parts of Speech Tagger.

* Once you have your database setup you could use [Train.php](https://github.com/geekgirljoy/Part-Of-Speech-Tagger/blob/master/Train.php) to process the brown corpus into your PartsOfSpeech MySQL database. The extracted Trigrams can be used to do many different things however the goal in this case is to associate the word tri-grams with the part of speech that it represents. This efficiently models English to allow us to use the tri-grams as a pattern lookup table. The Train process can take several hours to run on a fast machine. My tests on a Raspberry Pi took over 10 hours to complete the training from scratch. 

**it is highly recommended that you do not train from scratch**.

* The pretrained database is available as both .SQL dump and .CSV for use and review in the [Data](https://github.com/geekgirljoy/Part-Of-Speech-Tagger/tree/master/data) subfolder. Loading the .SQL files into the database takes only minutes to set up and opening the .CSV in Excel or in your own programs is super easy.

* After you have the data in your MySQL database you will need to run [AddHashes.php]() which will compute AB & BC bi-grams as well as AC skip-gram hashes for each already known tri-gram. This enables a "backoff" of "failover" approach where if you fail to find the exact pattern you are looking for (a trigram in this case being considered the ideal) then instead of failing to tag the text the tagger can try a slightly different (less specific) pattern to see if it can match the text. This approach should significantly improve performance in terms of tagging accuracy and speed up the overall pattern lookup process.

**it is highly recommended that you run this after training from scratch or importing the data into the database.**.


* [Test.php](https://github.com/geekgirljoy/Part-Of-Speech-Tagger/blob/master/Test.php)  - COMING SOON!!!


# More Reading

If you would like a more thorough guide to this project I have a series of blog posts covering the development.

[Can a Bot Understand a Sentence?](https://geekgirljoy.wordpress.com/2018/09/24/can-a-bot-understand-a-sentence/)

[Tokenizing & Lexing Natural Language](https://geekgirljoy.wordpress.com/2018/10/04/tokenizing-and-lexing-natural-language/)

[The Brown Corpus](https://geekgirljoy.wordpress.com/2018/10/12/the-brown-corpus/)

[The Brown Corpus Database](https://geekgirljoy.wordpress.com/2018/10/19/the-brown-corpus-database/)

[Building A Faster Bot](https://geekgirljoy.wordpress.com/2018/10/26/building-a-faster-bot/)

[Adding Bigrams & Skipgrams](https://geekgirljoy.wordpress.com/2018/11/09/adding-bigrams-skipgrams/)







