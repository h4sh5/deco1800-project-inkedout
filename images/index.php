<?php
set_time_limit(5);
session_start();

//reset progress
$_SESSION["story"] = NULL; 
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Inked Out</title>
        <link rel="stylesheet" type="text/css" href="css/style.css">
        <meta name="viewport" content="width=device-width, initial-scale=1">

    </head>
        
    
    <body class="home">
        <div id="background"></div>
        <h1 id='title'>INKED <span id="title-red">OUT</span></h1>

        <h2 id="description">Explore the ink and blood of World War I</h2>

        <a class="fancybutton" id="start-button" href="journey.php">Start Journey</a>

    </body>
</html>