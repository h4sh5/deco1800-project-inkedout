<?php

include("./words.php");

$errno = NULL;


$trove_keywords = array(
    "At head of page: Forty-third Expeditionary Force number. Title: The Roll of Honour. Queensland casualties at the front. ",

    "Our latest success", //short text, no pictures

    "From Gallipoli. Caption: Central News, photo. Caption: Returning from the trenches through a deep gully", //not correct article

    "Captain Graham Butler, D.S.O., a Brisbane doctor, who has been awarded the Distinguished Service Order for bravery at the Dardanelles", //correct article but words further down

    "Anzac sentinel sunset", //not the correct article, no image
    
    "The residential club for returned soldiers and sailors"
);


class Question {
    public $article, $options;
    private $answer;

    function __construct($article) {
        $smudgeResult = smudge_random_word($article);
        $this->article = $smudgeResult[0];
        $this->answer = $smudgeResult[1];
        //use original article to generate options
        $this->options = gen_options($article, $this->answer);
    }

    function check_answer($answer) {
        return strtolower($answer) == strtolower($this->answer) ? TRUE : FALSE;
    }

    function get_article() {
        return $this->article;
    }

    function get_options() {
        //generate questions again
        // $this->options = gen_options($this->article, $this->answer);
        return $this->options;
    }

}



//query to pull all photos digitised from the queenslanders from SLQ
$query = "SELECT * from \"5bc00f98-2d96-47d6-a0ca-2089ebd1130d\"" . 
    "WHERE \"500_pixel\" LIKE 'Digitised%' LIMIT 500";

$slqUrl = "https://data.gov.au/api/3/action/datastore_search_sql?sql=" . 
    urlencode($query);

//trove base url for searching newspapers and returning 1 result
$troveBaseUrl = "http://api.trove.nla.gov.au/result?key=9ts58ahlu0gb22cc&zone=newspaper&encoding=json&n=1&q=";


// print_r($data);

function warning_handler($level, $msg) {
    global $errno;
    error_log("warning message: " . $msg);
    if (in_array('500', explode(' ', $msg))) {
        $errno = 500;
    }
}



/*
Gets the plain text of a newspaper article given an articleId, and strip all
tags before returning to make sure it's plain text.
*/
function get_article_txt($articleId) {
    global $errno;
    if ($articleId == NULL || $articleId == '') {
        return NULL;
    }
    $txtBaseUrl = "https://trove.nla.gov.au/newspaper/rendition/nla.news-article";
    $txtUrl = $txtBaseUrl . $articleId . ".txt";

    //this getting the text needs handling of error 500 returned by the API
    set_error_handler("warning_handler", E_WARNING);
    $errno = NULL; //reset global errno before it might change
    $article = strip_tags(file_get_contents($txtUrl)); 
    if ($errno == 500) {
        restore_error_handler();
        return NULL;
    }
    restore_error_handler();

    if (count(explode(' ', $article)) > 300) { //truncate text if its > 300 words
        $article = explode(' ', $article);
        $article = array_slice($article, 0, 300);
        array_push($article, "------- snipped ---------");
        $article = join(' ', $article);
    }
    return $article;
}

/*
get the text url of a newspaper article based on the search term
*/
function get_article_id($searchTerm) {
    global $troveBaseUrl;

    $cacheFileName = 'cache/' . urlencode($searchTerm);

    if (!file_exists($cacheFileName)) {
        $troveUrl = $troveBaseUrl . urlencode($searchTerm);
        set_error_handler("warning_handler", E_WARNING);
        $errno = NULL; //reset global errno before it might change
        $troveResults = file_get_contents($troveUrl);
        if ($errno == 500) {
            restore_error_handler();
            return NULL;
        }

        $troveResults = json_decode($troveResults, true);
        $articleUrl = $troveResults["response"]["zone"][0]["records"]["article"][0]["url"];
        //returns '/article/id'
        $articleId = explode("/", $articleUrl)[2];
        file_put_contents($cacheFileName, $articleId);

    } else {
        error_log("article id cached, using cache..");
        $articleId = file_get_contents($cacheFileName);
    }

    return $articleId;

}


/**
gets the content from $url and stores it in cache/$file
*/
function create_cache($data, $file) {
    file_put_contents($file, $data);
}

/** gets the image from SLQ newspaper photos dataset using the story keywords
corresponding to the trove_keywords 
*/
function get_image($story_number) {
    global $slqUrl, $trove_keywords;

    $keywords = explode(" ", $trove_keywords[$story_number]);
    $first10 = join(" ", array_slice($keywords, 0 ,10));

    $query = "SELECT * from \"5bc00f98-2d96-47d6-a0ca-2089ebd1130d\"" . 
    "WHERE \"decsription\" LIKE '%" . $trove_keywords[$story_number] . "%' LIMIT 500";

    $image_url = "https://data.gov.au/api/3/action/datastore_search_sql?sql=" . urlencode($query);
    
    // $image_url = urlencode($image_url);

    $data = json_decode(file_get_contents($image_url), true);
    
    $record = $data['result']['records'][0]["1000_pixel"];

    error_log($record);

    return $record;

    //TODO: finish this!

}

/**
* @return new question
creates a new question by getting data from SLQ and trove.
*/
function create_question($story_number) {
    global $slqUrl, $trove_keywords;
    
    // if (!file_exists("cache/slq.json")) {
    //     error_log("getting slq data from: " . $slqUrl . "\n");
    //     $data = file_get_contents($slqUrl);
    //     error_log("getting slq data done.\n");
    //     create_cache(serialize($data), "cache/slq.json");
    //     $data = json_decode($data);

    // } else {
    //     error_log("slq content already cached, using cache instead...");
    //     $data = json_decode(unserialize(file_get_contents("cache/slq.json")), true);
    // }
    // //get random record out of results
    // $record = $data['result']['records'][array_rand($data['result']['records'])];

    // $wordsToRemove = array("Title: ", "Caption: ", '"');

    // $keywords = explode(" ", str_replace($wordsToRemove, "", $record["decsription"]));
    //get first 10 keywords
    $keywords = explode(" ", $trove_keywords[$story_number]);
    $first10 = join(" ", array_slice($keywords, 0 ,10));

    $text = get_article_txt(get_article_id($first10));

    //if text is useless, run recursively
    // if ($text == NULL || get_random_word($text)is_text_useless($text)) {
    if  (is_text_useless($text)) {
        error_log("running create_question again");
        return create_question($story_number);
    }
    return new Question($text);

}

function startsWith($haystack, $needle)
{
     $length = strlen($needle);
     return (substr($haystack, 0, $length) === $needle);
}



?>