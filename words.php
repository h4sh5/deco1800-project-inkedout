<?php

$deadwords = explode("\n", file_get_contents("./deadwords.txt"));
array_push($deadwords, " ");
$punctuations = array(".", "'", ",", "\"", ":", ";", "\\", "/", "!", "?", "-",
'"');

/**
* @return TRUE/FALSE if the text is empty after all punctuations have been removed
*/
function is_text_useless($text) {
    global $deadwords, $punctuations;

    $wordlist = explode(" ", $text);
    //removes all deadwords and whitespace elements
    for ($i = 0; $i < count($wordlist); $i++) {
        $word = rtrim($wordlist[$i]);
        if (in_array($word, $deadwords) || $word == '' || in_array($word, $punctuations)) {
            unset($wordlist[$i]);
        }
    }

    if (empty($wordlist)) {
        error_log("text is useless..");
        return TRUE;
    }

    return FALSE;

}


/**
* @return random word from text
Get a random word from the text
*/
function get_random_word($text) {
    global $deadwords, $punctuations;

    $wordlist = explode(" ", $text);
    //removes all deadwords and whitespace elements
    for ($i = 0; $i < count($wordlist); $i++) {
        $word = rtrim($wordlist[$i]);
        if (in_array($word, $deadwords) || $word == '' || in_array($word, $punctuations)) {
            unset($wordlist[$i]);
        }
    }
    
    if (empty($wordlist)) {
        error_log("get_random_word: wordlist is empty");
        return NULL;
    }

    $word = $wordlist[array_rand($wordlist)];
    return $word;
}

/**
* @return array(modifiedText, smudgedWord)

Get a random word from the text and replace the word from the with "xxxxx". 
Removes all punctuations before picking the word.
*   
*/
function smudge_random_word($text) {
    global $deadwords, $punctuations;

    $smudge = '<img src="images/smudge1.png" id="smudge">';
    //make a copy of text
    $origText = $text;
    
    $word = get_random_word($text);
    
    //make word into a regex pattern by first removing its dangerous chararcters
    $word = str_replace("(" , "", $word);
    $word = str_replace(")" , "", $word);
    $wordPattern = '/' . $word . '/';

    //replace first occurrence of the chosen word with smudge image
    error_log("smudging: " . $word);
    // $modifiedText = str_replace($word, $smudge, $origText);
    $modifiedText = preg_replace($wordPattern, $smudge, $origText, 1);

    return array($modifiedText, $word);
}

/**
* @return array(option1, option2, option3, option4)
if there is an external file, open it and use it as a resource instead of article. Otherwise, pick 4 random words from the article (that are not dead words) and return them in random order.
*/
function gen_options($article, $word, $externalFile = NULL) {
    $article = strip_tags($article);
    $words = array();

    for ($i = 0; $i < 3; $i++) {
        $words[$i] = strtolower(get_random_word($article));
     }
    $words[3] = strtolower($word);
    shuffle($words);

    return $words;
}


?>