<?php
	require_once "../config.php";
	
	$film_error = null;
	$year = 1940;
	$duration = 60;
	if(isset($_POST["film_submit"])){
		if(!empty($_POST["title_input"]) and !empty($_POST["year_input"]) and !empty($_POST["duration_input"]) and !empty($_POST["genre_input"]) and !empty($_POST["studio_input"]) and !empty($_POST["director_input"])){
			$title = $_POST["title_input"];
			$year = $_POST["year_input"];
			$duration = $_POST["duration_input"];
			$genre = $_POST["genre_input"];
			$studio = $_POST["studio_input"];
			$director = $_POST["director_input"];
		} else {
			$film_error = "Kõik lüngad ei ole täidetud!";
		}
		$year = $_POST["year_input"];
		$duration = $_POST["duration_input"];
		
		if(empty($film_error)){
			
			//tahab saada kasutaja, server, parool, andmebaas
			$conn = new mysqli($server_host, $server_user_name, $server_password, $database);
			//määran suhtlemiseks kasutatava tabeli
			$conn->set_charset("utf8");
			//valmistame ette andmete saatmise SQL käsu
			$stmt = $conn->prepare("INSERT INTO film (pealkiri, aasta, kestus, zanr, tootja, lavastaja) VALUES(?,?,?,?,?,?)");
			echo $conn->error;
			//seome SQL käsu õigete andmetega
			//andmetüübid i - integer	d - decimal		s - string
			$stmt->bind_param("siisss", $title, $year, $duration, $genre, $studio, $director);
			if($stmt->execute()){
				$title = null;
				$year = 1940;
				$duration = 60;
				$genre = null;
				$studio = null;
				$director = null;
			}
			//sulgeme käsu
			$stmt->close();
			//andmebaasi ühenduse kinni
			$conn->close();
		}
	}
?>

<!DOCTYPE html>
<html lang="et">

<head>
	<meta charset="utf-8">
	<title>Filmi sisestamine</title>
</head>

<body>
	<form method="POST">
		<label for="title_input">Filmi pealkiri</label>
		<input type="text" name="title_input" id="title_input" placeholder="filmi pealkiri">
		<br>
		<label for="year_input">Valmimisaasta</label>
		<input type="number" name="year_input" id="year_input" min="1912" step="1" value="<?php echo $year ?>">
		<br>
		<label for="duration_input">Kestus</label>
		<input type="number" name="duration_input" id="duration_input" min="1" max="600" step="1" value="<?php echo $duration ?>">
		<br>
		<label for="genre_input">Filmi žanr</label>
		<input type="text" name="genre_input" id="genre_input" placeholder="žanr">
		<br>
		<label for="studio_input">Filmi tootja</label>
		<input type="text" name="studio_input" id="studio_input" placeholder="filmi tootja">
		<br>
		<label for="director_input">Filmi režissöör</label>
		<input type="text" name="director_input" id="director_input" placeholder="filmi režissöör">
		<br>
		<input type="submit" name="film_submit" value="Salvesta">
		<span><?php echo $film_error ?></span>
	</form>
</body>