<?
	//	Codi de validaciló del formulari
	//	Comprovo si estan buits
	
	if (empty($text) || empty($tipus)){
		$hi_ha_errors = TRUE;
		echo "<div class=\"missatge\"><p>No es pot enviar el formulari, hi ha certa informació que es necessària:</p>";
		echo "<ul class=\"llistaErrors\">";
		if (empty($text)){
			$error_Text = TRUE;
			echo "<li>El quadre de cerca no pot estar buït.</li>";
		}
		if (empty($tipus)){
			$error_Tipus = TRUE;
			echo "<li>S'ha d'escollir una opció de cerca.</li>";
		}
		echo "</ul></div>";
	}
?>