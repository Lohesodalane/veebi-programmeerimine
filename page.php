<?php
	require_once "../config.php";
	//echo $server_host;
	$author_name = "Andre Tate";
	$full_time_now = date("d.m.Y H:i:s");
	$weekday_now = date("N");
	//echo $weekday_now;
	$weekdaynames_et = ["esmaspäev", "teisipäev", "kolmapäev", "neljapäev", "reede", "laupäev", "pühapäev"];
	//echo $weekdaynames_et[$weekday_now - 1];
	
	$oldwords_et = ["Tervis on parem kui rikkus.", "Naist kiida hommikul, ilma õhtul.", "Laisa tööpäev on ikka homme.", " Silmad on hinge peegel.", " Tublidus ei tule tööta, osavus ei hooleta."]; 
	$random_oldwords = $oldwords_et[mt_rand(0, count($oldwords_et) - 1)];
	$hours_now = date("H");
	//echo $hours_now;
	$part_of_day = "suvaline päeva osa";
	//  <   > >=  <=   ==  !=
	if($weekday_now <= 5)
	{
		if($hours_now < 7)
		{
			$part_of_day = "uneaeg";
		}		
		if ($hours_now > 7 & $hours_now < 18)
		{
		 $part_of_day = "kooliaeg";
		}
		if ($hours_now >= 18)
		{
		$part_of_day = "puhkeaeg";
		}
	}
	//   and   or
	else{
		if ($hours_now < 10)
		{
			$part_of_day = "uneaeg";
		}
		if ($hours_now >10)  
		{	
		$part_of_day = "pidu aeg";
		}
	}
		
	//uurime semestri kestmist
	$semester_begin = new DateTime("2022-9-5");
	$semester_end = new DateTime("2022-12-18");
	$semester_duration = $semester_begin->diff($semester_end);
	//echo $semester_duration;
	$semester_duration_days = $semester_duration->format("%r%a");
	$from_semester_begin = $semester_begin->diff(new DateTime("now"));
	$from_semester_begin_days = $from_semester_begin->format("%r%a");
	
	//juhuslik arv
	//küsin massiivi/array pikkust
	//echo count($weekdaynames_et);
	//echo mt_rand(0, count($weekdaynames_et) -1);
	//echo $weekdaynames_et[mt_rand(0, count($weekdaynames_et) -1)];
	
	
	//juhuslik foto
	$photo_dir = "Pildid"; 
	//loen kataloogi sisu
	//$all_files = scandir($photo_dir);
	//var_dump($all_files);
	$all_files = array_slice(scandir($photo_dir), 2);
	//kontrollin kas on foto ikka
	$allowed_photo_types = ["image/jpeg", "image/png"];
	//muutuja väärtuse suurendamine  $muutuja = $muutuja + 5 
	// $muutuja += 5 
	//kui vaja liita 1
	//$muutuja ++
	//samamoodi $muutuja -=5    $muutuja -- 
	
	
	$photo_files = [];
	foreach($all_files as $filename){
		//echo $filename;
		$file_info = getimagesize($photo_dir. "/" .$filename);
		//var_dump($file_info); 
		//kas on lubatud tüüpide nimekirjas
		if(isset($file_info["mime"])){
			if (in_array($file_info["mime"], $allowed_photo_types)){
				array_push($photo_files, $filename);
			} // if_in_array
		} //isset
	} //foreach
	
	//<img src="katalog/fail" alt="tekst">
	$photo_html = '<img src="' .$photo_dir . "/" . $photo_files[mt_rand(0, count($photo_files) -1)] .'"';
	$photo_html .= 'alt="Tallinna pilt">';
	//SEEEEEE$photo_number = mt_rand(0, count($photo_files) -1);
	// KODUS if $photo_number = 
	
	//echo $photo_html
	////$photo_number = mt_rand(0, count($photo_files) -1);
	//vaatame mida vormis sisestati
	//var_dump($_POST);
	$todays_adjective = "Pole midagi sisestatud";
	if (isset($_POST["todays_adjective_input"]) and !empty($_POST["todays_adjective_input"]))
		{
		$todays_adjective = $_POST["todays_adjective_input"];
		}	
		
		//loome rippmenüü valikud
		//<option value="0">tln_100.jpg</option>
		//<option value="1">tln_100.jpg</option>
		//<option value="2">tln_100.jpg</option>
		//<option value="3">tln_100.jpg</option>
		//<option value="4">tln_100.jpg</option>
		//<option value="5">tln_100.jpg</option>
		
	$select_html = '<option value="0" selected disabled >Vali pilt</option>'; //null; //'option value="select_file"
	for($i = 0;$i < count($photo_files); $i ++){
		/*if($i ==$photo_number){
			$select_html .= "selected";
		}*/
		$select_html .= '<option value="'.$i .'">';
		$select_html .= $photo_files[$i];
		$select_html .= "</option>";
	}	
	if(isset($_POST["photo_select"]) and $_POST["photo_select"] >= 0){
		echo "Valiti pilt nr:" .$_POST["photo_select"];
	}
	
		//echo "Valiti pilt nr. " .$_POST["photo_select"];
		////$photo_number = $POST["photo_select"];	
		 
			
	
	
	
	$comment_error= null;
	//kas klikiti päeva kommentaari nuppu
	 if(isset($_POST["comment_submit"]))
	 if(isset($_POST["comment_input"]) and !empty($_POST["comment_input"])){
			 $comment = $_POST["comment_input"];
		}	else {
				 $comment_error= "Kommentaar jäi kirjutamatta";
			}
			$grade = $_POST["grade_input"];
			
		if(empty("$comment_error")){
		
			//loon andmebaasiga ühenduse
			//tahab kõigepealt server kasutaja parool ja siis andmebaas
			$conn = new mysqli($server_host, $server_user_name, $server_password, $database);
			//määran suhtlemisel kasutatava kooditabeli
			$conn->set_charset("utf8");
			//valmistame ette andmete saatise sqli käsu
			$stmt = $conn->prepare("INSERT INTO vp_daycomment (COMMENT, GRADE) values(?,?)");
			echo $conn->error;	
			//seome õigete andmetega
			//andmetüübid i-integer d-decimal s-string
			$stmt->bind_param("si",$comment, $grade);
			if($stmt->execute()){
				$grade = 7;
				$comment = null;
			}
			//sulgeme käsu
			$stmt->close();
			//andmebaasi ühenduse kinni
			$conn->close();
			}		
	 
?>
<!DOCTYPE html>
<html lang="et">
<head>
	<meta charset="utf-8">
	<img src="https://greeny.cs.tlu.ee/~rinde/vp_2022/vp_banner_gs.png">
	<title><?php echo $author_name;?> Silla Antsu uus murutraktor on täiesti võimas </title>
 </head>
<body>
<h1> Silla Antsu uus murutraktor on suht tuus </h1>
<p>See leht on loodud õppetöö raames ja ei sisalda tõsiseltvõetavat sisu!</p>
<p> Õppetöö toimub <a href="https://www.tlu.ee" target="blank">Tallinna Ülikoolis</a> Digitehnoloogiate instituudis </p>  
<p> Lehe avamise hetk: <?php echo $weekdaynames_et[$weekday_now-1].", ". $full_time_now;?></p>
<a href="https://www.tlu.ee" target="blank"><img src="Pildid/tlu_41.jpg" alt="Tallina ülikool ja troll Tondile"></a>
<p> Praegu on <?php echo $part_of_day; ?> </p>
<p> Semestri pikkus on <?php echo $semester_duration_days;?> päeva. See on kestnud juba <?php echo $from_semester_begin_days; ?> päeva.</p>
<p> Loe ja pane kõrvataha: <?php echo $random_oldwords;?> <p>
<h1> Minu nimi on Ändrue ja mina tulen Maidlast </h1>
<P> Maidlas on mul sõbrad Kristofer ja Rando. Randole meeldib leiba süüa ja Kristoferile saia. Mulle meeldib napoolioni kook.  </P>
<br>
<br>
<br>
<form method="POST">
	<label for="comment_input">Kommentaar tänase päeva kohta (140 tähte)</label>
	<br>
	<textarea id="comment_input" name="comment_input" cols="35" rows="4" placeholder="kommentaar"></textarea>
	</br>
	<label for="grade_input">Hinne tänasele päevale (0-10)</label>
	<input type="number" id="grade_input" name="grade_input" min="0" max="10" step="1" value="7">
	<br>
	<input type="submit" id="comment_submit" name="comment_submit" value="Salvesta">
	<span><?php echo $comment_error;?></span>
</form>
	<br>
	<hr>
	
<form method="POST">
	<input type="text" id="todays_adjective_input" name="todays_adjective_input" placeholder="Kirjuta siia omadussõna tänase päeva kohta">
	<input type="submit" id=todays_adjective_submit" name="todays_adjective_submit" value="saada omadussõna">
</form>

<p> Omadussõna tänase päeva kohta: <?php echo $todays_adjective; ?> </p>

<hr>
<form method="POST">
	<select id="photo_select" name="photo_select">
		<?php echo $select_html; ?>
	</select>
		<input type="submit" id="photo_submit" name="photo_submit" value="Vali foto">
	</form>
<?php
	if(isset($_POST["photo_select"]) and ($_POST["photo_select"] >=0))
	{
		$photo_html ='<img src="' .$photo_dir . "/" . $photo_files[$_POST["photo_select"]] .'"alt="Tallinna pilt">';
		echo $photo_html;
	}
	else
	{
	echo $photo_html;
	}
	?>	
	
<hr>


</body>
</html>