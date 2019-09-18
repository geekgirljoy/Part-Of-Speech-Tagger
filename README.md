# Part-Of-Speech-Tagger
A PHP & MySQL based Parts of Speech Tagger that uses the Brown Corpus.


## Project Files And Overview

Currently this project is still under active development however it is at a point where it is useful.

* The original Brown Corpus is available for use and review in the [Brown](https://github.com/geekgirljoy/Part-Of-Speech-Tagger/tree/master/brown) subfolder.

Please note my [Disclaimer](https://github.com/geekgirljoy/Part-Of-Speech-Tagger/blob/master/DISCLAIMER) where I explicitly state that I do not own the Brown corpus and I am not selling it to you.


## Starting From Scratch

**it is highly recommended that you do not train from scratch - SEE THE DATA FILES SECTION BELOW**.

* There is an SQL setup file ([Create.PartsOfSpeech.DB.sql](https://github.com/geekgirljoy/Part-Of-Speech-Tagger/blob/master/Create.PartsOfSpeech.DB.sql)) that you can use to create the MySQL database necessary to use this Parts of Speech Tagger.

* Once you have your database setup you could use [Train.php](https://github.com/geekgirljoy/Part-Of-Speech-Tagger/blob/master/Train.php) to process the brown corpus into your PartsOfSpeech MySQL database. The extracted Trigrams can be used to do many different things however the goal in this case is to associate the word tri-grams with the part of speech that it represents. This efficiently models English to allow us to use the tri-grams as a pattern lookup table. The Train process can take several hours to run on a fast machine. My tests on a Raspberry Pi took over 10 hours to complete the training from scratch. 

* The pretrained database is available as both .SQL dump and .CSV for use and review in the [Data](https://github.com/geekgirljoy/Part-Of-Speech-Tagger/tree/master/data) subfolder. Loading the .SQL files into the database takes only minutes to set up and opening the .CSV in Excel or in your own programs is super easy.

* After you have the data in your MySQL database you will need to run [AddHashes.php]() which will compute AB & BC bi-grams as well as AC skip-gram hashes for each already known tri-gram. This enables a "backoff" of "failover" approach where if you fail to find the exact pattern you are looking for (a trigram in this case being considered the ideal) then instead of failing to tag the text the tagger can try a slightly different (less specific) pattern to see if it can match the text. This approach should significantly improve performance in terms of tagging accuracy and speed up the overall pattern lookup process.

At this point you can Test your Part of Speech Tagger:

# Testing & Tagging

* [Test.php](https://github.com/geekgirljoy/Part-Of-Speech-Tagger/blob/master/Test.php)

* [FastTest.php](https://github.com/geekgirljoy/Part-Of-Speech-Tagger/blob/master/FastTest.php)


# Data files

There is no need to train your parts of speech tagger from scratch. The data is avalable as CSV & SQL for your convenience.


## CSV

* **[Words.csv](https://github.com/geekgirljoy/Part-Of-Speech-Tagger/blob/master/data/csv/Words.csv)** Contains all 56,057 words.

* **[Tags.csv](https://github.com/geekgirljoy/Part-Of-Speech-Tagger/blob/master/data/csv/Tags.csv)** Contains all 472 tags.

* **[Trigrams_1.csv](https://github.com/geekgirljoy/Part-Of-Speech-Tagger/blob/master/data/csv/Trigrams_1.csv)** Contains 0 - 212315 trigrams.

* **[Trigrams_2.csv](https://github.com/geekgirljoy/Part-Of-Speech-Tagger/blob/master/data/csv/Trigrams_2.csv)** Contains 212316 - 424631 trigrams.

* **[Trigrams_3.csv](https://github.com/geekgirljoy/Part-Of-Speech-Tagger/blob/master/data/csv/Trigrams_3.csv)** Contains 424632 - 636947 trigrams.

* **[Trigrams_4.csv](https://github.com/geekgirljoy/Part-Of-Speech-Tagger/blob/master/data/csv/Trigrams_4.csv)** Contains 636948 - 849262 trigrams.



## SQL

* **[Words_Data.sql](https://github.com/geekgirljoy/Part-Of-Speech-Tagger/blob/master/data/mysql/Words_Data.sql)** Contains all 56,057 words.

* **[Words_Structure.sql](https://github.com/geekgirljoy/Part-Of-Speech-Tagger/blob/master/data/mysql/Words_Structure.sql)** Contains the structure the Words table.

* **[Tags_Data.sql](https://github.com/geekgirljoy/Part-Of-Speech-Tagger/blob/master/data/mysql/Tags_Data.sql)** Contains all 472 tags.

* **[Tags_Structure.sql](https://github.com/geekgirljoy/Part-Of-Speech-Tagger/blob/master/data/mysql/Tags_Structure.sql)** Contains the structure the Tags table.

* **[Trigrams_Data_1.sql](https://github.com/geekgirljoy/Part-Of-Speech-Tagger/blob/master/data/mysql/Trigrams_Data_1.sql)** Contains 0 - 212315 trigrams.

* **[Trigrams_Data_2.sql](https://github.com/geekgirljoy/Part-Of-Speech-Tagger/blob/master/data/mysql/Trigrams_Data_2.sql)** Contains 212316 - 424631 trigrams.

* **[Trigrams_Data_3.sql](https://github.com/geekgirljoy/Part-Of-Speech-Tagger/blob/master/data/mysql/Trigrams_Data_3.sql)** Contains 424632 - 636947 trigrams.

* **[Trigrams_Data_4.sql](https://github.com/geekgirljoy/Part-Of-Speech-Tagger/blob/master/data/mysql/Trigrams_Data_4.sql)** Contains 636948 - 849262 trigrams.

* **[Trigrams_Structure.sql](https://github.com/geekgirljoy/Part-Of-Speech-Tagger/blob/master/data/mysql/Trigrams_Structure.sql)** Contains the structure the Trigrams table.



# More Reading

If you would like a more thorough guide to this project I have a series of blog posts covering the development.

[Can a Bot Understand a Sentence?](https://geekgirljoy.wordpress.com/2018/09/24/can-a-bot-understand-a-sentence/)

[Tokenizing & Lexing Natural Language](https://geekgirljoy.wordpress.com/2018/10/04/tokenizing-and-lexing-natural-language/)

[The Brown Corpus](https://geekgirljoy.wordpress.com/2018/10/12/the-brown-corpus/)

[The Brown Corpus Database](https://geekgirljoy.wordpress.com/2018/10/19/the-brown-corpus-database/)

[Building A Faster Bot](https://geekgirljoy.wordpress.com/2018/10/26/building-a-faster-bot/)

[Adding Bigrams & Skipgrams](https://geekgirljoy.wordpress.com/2018/11/09/adding-bigrams-skipgrams/)

[Parts of Speech Tagging](https://geekgirljoy.wordpress.com/2018/11/15/parts-of-speech-tagging/)

[Unigrams](https://geekgirljoy.wordpress.com/2018/11/23/unigrams/)
