<?php
set_time_limit(5);
session_start();
if ($_SESSION["story"] == NULL) {
	error_log("new session!");
	$_SESSION["story"] = 0; //the journey of story
}

require_once('game.php');

//TODO: debug when there's no right answer ever
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$question = unserialize($_SESSION['question']);
	$answer = $_POST['answer'];

	//redirect the user if he's right
	if ($question->check_answer($answer)) {
		$_SESSION['story'] += 1;
		header('Location: '. "win.php");
	} else {
		header('Location: '. "lose.php");
	}

} else {

	if ($_SESSION['story'] == 6) {
		header('Location: '. "pieces.php");
		die();
	}

	if ($_GET['q'] == "old") {
		error_log("requesting previous question");
		$question = unserialize($_SESSION['question']);
		// error_log(print_r($question, true));
	} else if (startsWith($_SERVER['REQUEST_URI'], "/journey.php")) {
		error_log("creating new question");
		$question = create_question($_SESSION["story"]);
		$_SESSION['question'] = serialize($question);
	}
}

?>

<!DOCTYPE html>
<html>
	<head>
		<title>Inked Out</title>
		<link rel="stylesheet" type="text/css" href="css/style.css">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">

	</head>
		
	
	<body class="fancybutton">
		<div id="background"></div>
		
		<!-- restart -->
		<a href='journey.php' class='fancybutton' id='home-button'>Home</a>

		<h1 id='title'>INKED <span id="title-red">OUT</span></h1>

		<h3 id='story'>
			<!--story: -->
			<div class="w3-black w3-round-large" id="progress-bar">
				<div class="w3-container w3-white" id="progress" style="width:<?php echo ($_SESSION['story'] + 1 / 7 * 100); ?>%"><?php echo ($_SESSION['story'] + 1)?>/7</div>
			</div>

		</h3>



		<section>
			<aside>
				<img src=<?php echo get_image($_SESSION['story']);?>>
			</aside>

			<h2 id='question'>What is the missing word?</h2>

			<article>
				<?php
					echo $question->get_article();
				?>
			</article>
		</section>


		<form method="post" action="journey.php" id="question-form">

			<!-- <a href='journey.php' class='fancybutton'>Skip</a> -->
			<div id='options'>
			<?php
				$options = $question->get_options(); //generate new options here
				for ($i = 0; $i < 4; $i++) {
					echo "<input class='options' type='radio' name='answer' id='" . $options[$i] . "' value='" . $options[$i] . "'>";
					echo "<label class='options' for='" . $options[$i] . "'>" . $options[$i] . "</label>";
				}
			?>
			</div>
				
			<button type="submit" class="fancybutton">Submit</button>
		</form>


	</body>

	<script type="text/javascript" src="js/script.js"></script>


</html>