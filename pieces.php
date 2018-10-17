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
        <!-- <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css"> -->

    </head>
        
    <body>
        <h1>Work in progress</h1>

    </body>

</html>