<?php

	require_once ("../config.php");

	//loon andmebaasiga ühenduse
	//server, kasutaja, parool, andmebaas
	$conn = new mysqli($server_host, $server_user_name, $server_password, $database);
	//määran suhtklemisel kasuatatava kooditabeli
	$conn->set_charset("utf8");
	
	//valmistame ette andmete saatmise SQL käsu
	$stmt = $conn->prepare("SELECT COMMENT, GRADE, ADDED FROM vp_daycomment");
	echo $conn->error;
	//seome saadavad andmed muutujatega
	$stmt->bind_result($comment_from_db, $grade_from_db, $added_from_db);
	//täidame käsu
	$stmt->execute();
	//kui saan ühe kirje
	//if($stmt->fetch()){
		//mis selle ühe kirjega teha
	//}
	//kui tuleb teadmata arv kirjeid
	$comment_html = null;
	while($stmt->fetch()){
		//echo $comment_from_db;
		//<p>kommentaar, hinne päevale: 6, lisatud xxxxxxx</p>
		$comment_html .= "<p>" .$comment_from_db .", hinne päevale: " .$grade_from_db;
		$comment_html .= ", lisatud " .$added_from_db .".</p> \n";
	}
	// sulgeme käsu
	$stmt->close();

	// sulgeme andmebaasi uhenduse
	$conn->close();
		
?>


<!DOCTYPE html>
<html lang="et">
<head>
	<meta charset="utf-8">
	<img src="https://greeny.cs.tlu.ee/~rinde/vp_2022/vp_banner_gs.png">
	<title> Silla Antsu uus murutraktor on t�iesti v�imas </title>
 </head>
<body>
<h1> Silla Antsu uus murutraktor on suht tuus </h1>
<p>See leht on loodud õppetöö raames ja ei sisalda tõsiseltvõetavat sisu!</p>
<p> Õppetöö toimub <a href="https://www.tlu.ee" target="blank">Tallinna Ülikoolis</a> Digitehnoloogiate instituudis </p>  
<a href="https://www.tlu.ee" target="blank"><img src="Pildid/tlu_41.jpg" alt="Tallina ülikool ja troll Tondile"></a>
<h1> Minu nimi on Ändrue ja mina tulen Maidlast </h1>
<P> Maidlas on mul sõbrad Kristofer ja Rando. Randole meeldib leiba süüa ja Kristoferile saia. Mulle meeldib napoolioni kook.  </P>
<?php echo $comment_html; ?>
</body>
</html>