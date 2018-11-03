<?php

session_start();
$_SESSION["score"] = 0;

//query to pull all photos digitised from the queenslanders from SLQ
$query = "SELECT * from \"5bc00f98-2d96-47d6-a0ca-2089ebd1130d\"" . 
    "WHERE \"500_pixel\" LIKE 'Digitised%' LIMIT 1";

$url = "https://data.gov.au/api/3/action/datastore_search_sql?sql=" . 
    urlencode($query);

//trove base url for searching newspapers and returning 1 result
$troveBaseUrl = "http://api.trove.nla.gov.au/result?key=9ts58ahlu0gb22cc&zone=newspaper&encoding=json&n=1&q=";

$data = json_decode(file_get_contents($url), true);
// print_r($data);

/*
Gets the plain text of a newspaper article given an articleId, and strip all
tags before returning to make sure it's plain text.
*/
function getArticleTxt($articleId) {
    $txtBaseUrl = "https://trove.nla.gov.au/newspaper/rendition/nla.news-article";
    $txtUrl = $txtBaseUrl . $articleId . ".txt";
    return strip_tags(file_get_contents($txtUrl));
}

/*
get the text url of a newspaper article based on the search term
*/
function getArticleId($searchTerm) {
    global $troveBaseUrl;
    
    $troveUrl = $troveBaseUrl . urlencode($searchTerm);
    $troveResults = json_decode(file_get_contents($troveUrl), true);

    $articleUrl = $troveResults["response"]["zone"][0]["records"]["article"][0]["url"];
    //returns '/article/id'

    $articleId = explode("/", $articleUrl)[2];

    return $articleId;

}


?>

<!doctype html>
<html>
    <head>
        <title>Inked Out</title>
    </head>


    <body>

        <?php 
            include("./words.php");
            foreach ($data['result']['records'] as $record) {
                //SLQ spelled description "decsription", so we improvise.
                //echo "<h3>" . $record["decsription"] . "</h3>";
                echo "<h4>" . $record["500_pixel"] . "</h4>";
                //remove the first instance of "Title: "
                $wordsToRemove = array("Title: ", "Caption: ", '"');
                $keywords = explode(" ", str_replace($wordsToRemove, "", 
                    $record["decsription"]));
                $first10 = join(" ", array_slice($keywords, 0 ,10));
                

                echo "<p>" . $first10 . "</p>";
                echo "Trove first result:" . "<pre>";
                
                $articleId = getArticleId($first10);
                $text = getArticleTxt($articleId);

                var_dump(smudgeRandomWord($text));

                echo "</pre>";
                echo "<hr>";
            }


        ?>

    </body>


</html>