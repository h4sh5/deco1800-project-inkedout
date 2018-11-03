<?php
set_time_limit(5);
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $result = explode(" ", $_POST["result"]);
    $result = array_slice($result, 0, 6);
    error_log("result: " . print_r($result, true));
    // error_log("answer: " . print_r($_SESSION["order"], true));
    if ($result == array(0, 1, 2, 3, 4, 5)) {
        echo "right";
    } else {
        echo "wrong";
    }
    die();
} else {
    $elements = array(0, 1, 2, 3, 4, 5);
    shuffle($elements);
    $_SESSION["order"] = $elements;
}

?>

<!DOCTYPE html>
<html>
    <head>
        <title>Inked Out</title>
        <link rel="stylesheet" type="text/css" href="css/style.css">
        <meta name="viewport" content="width=device-width, initial-scale=1">

    </head>
        
    <body>

        <h2 id='question'>Arrange the snippets in chronological order</h2>

        <!-- think about dynamically generated drag and drop -->
        <div id="articles-box">

            <?php
                //load all the articles into the boxes, with index i
                //TODO: apply shuffled order
                $i = 0;
                require_once("game.php");
                foreach ($_SESSION['order'] as $index) {
                    $keywords = $trove_keywords[$index];
                    $keywords = explode(" ", $keywords);
                    $first10 = join(" ", array_slice($keywords, 0 ,10));
                    $text = get_article_txt(get_article_id($first10));
                    echo '<div class="dragdrop" id="div' . $index . '" ondrop="drop(event)" ondragover="allowDrop(event)"><p class="item dragdrop" id="' . $index . '" draggable="true" ondragstart="drag(event)">'. snipp_txt($text, 40, 90) . '</p></div>';
                    // echo '<p class="item dragdrop" draggable="true" ondragstart="drag(event)">'. snipp_txt($text, 20, 60) . '</p>';
                    $i++;
                }

            ?>

            </div>
            
            <div id="targets-box">
                
                <div class="dragdrop target" ondrop="drop(event)" ondragover="allowDrop(event)"></div>
                
                <div class="dragdrop target" ondrop="drop(event)" ondragover="allowDrop(event)"></div>
                
                <div class="dragdrop target" ondrop="drop(event)" ondragover="allowDrop(event)"></div>
                
                <div class="dragdrop target" ondrop="drop(event)" ondragover="allowDrop(event)"></div>

                <div class="dragdrop target" ondrop="drop(event)" ondragover="allowDrop(event)"></div>
                
                <div class="dragdrop target" ondrop="drop(event)" ondragover="allowDrop(event)"></div>
                
            </div>

            <button class='fancybutton' id="pieces-submit">Submit</button>

    </body>
    <script src="js/script.js"></script>

</html>