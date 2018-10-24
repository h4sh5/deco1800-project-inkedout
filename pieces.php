<?php
set_time_limit(5);
session_start();
if ($_SESSION["story"] == NULL) {
    error_log("new session!");
    $_SESSION["story"] = 0; //the index of story
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

        <!-- think about dynamically generated drag and drop -->
        <div id="articles-box">

            <?php
                //load all the articles into the boxes

                require_once("game.php");
                foreach ($trove_keywords as $keywords) {
                    $keywords = explode(" ", $keywords);
                    $first10 = join(" ", array_slice($keywords, 0 ,10));
                    $text = get_article_txt(get_article_id($first10));
                    echo '<div class="dragdrop" ondrop="drop(event)" ondragover="allowDrop(event)">
            <p class="item dragdrop" draggable="true" ondragstart="drag(event)">'. snipp_txt($text, 40, 90) . '</p></div>';
                    // echo '<p class="item dragdrop" draggable="true" ondragstart="drag(event)">'. snipp_txt($text, 20, 60) . '</p>';
                }

            ?>

            </div>
            
            <div id="targets-box">
                
                <div class="dragdrop" ondrop="drop(event)" ondragover="allowDrop(event)">
                </div>
                
                <div class="dragdrop" ondrop="drop(event)" ondragover="allowDrop(event)">
                </div>
                
                <div class="dragdrop" ondrop="drop(event)" ondragover="allowDrop(event)">
                </div>
                
                <div class="dragdrop" ondrop="drop(event)" ondragover="allowDrop(event)">
                </div>

                <div class="dragdrop" ondrop="drop(event)" ondragover="allowDrop(event)">
                </div>
                
                <div class="dragdrop" ondrop="drop(event)" ondragover="allowDrop(event)">
                </div>
                
            </div>

    </body>
    <script src="js/script.js"></script>

</html>