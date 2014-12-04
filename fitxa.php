<!doctype html>
<html lang="es-ES">
<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
	<title>Disseny de base de dades - PRA 2</title>
	
	<?

	// Possem aquestes dues linies per a mostrar els errors del nostre codi PHP al navegador.
	// Quan una web deixa d'estar en desenvolupament s'han de treure aquestes intruccions 
	// perquè es una vulnerabilitat que pot permetre a  un usuari extern pugui atacar la nostra web.
	
	//	Per penjar-ho al servidor, es comenten les línies per què no mostri errors en cas d'haver-hi
	
	
		error_reporting(E_ALL);
		ini_set('display_errors', '1');
	?>
	<link rel="stylesheet" type="text/CSS" href="css/estils.css">

		<script type="text/javascript">
			function tancar(){
			window.close();
			}
		</script>
</head>
<body>
<?
	//	Connectem a la base de dades
		include("db_Conex.php");

		include("funcions.php");
		include("funcionstaules.php");
		include("funcionsfitxes.php");

		$zonaCerca = $_GET['zonaCerca'];
		$id = $_GET['id'];
		//	utilitzo el traspàs de la zonaCerca per fer una consulta de la 
		//	taula en qüestió i mostrar les primeres dades, com la imatge
		$query = consulta("pra2_".$zonaCerca,"ID",$id);
		$row= mysql_fetch_object($query);
		$id = $row-> ID;
		switch ($zonaCerca){
			case 'emissores':
				$titol = $row-> Nom_Emissora;
				$dada1 = $row-> Numero_Oients;
				$dada1 = "Número d'oients: $dada1";
				$dada2 = $row-> Any_Fundacio;
				$dada2 = "Fundada en $dada2";
				break;
			case 'programes':
				$titol = $row-> Nom_Programa;
				$dada1 = $row-> Num_Oients_Programa;
				$dada1 = "Número d'oients: $dada1";
				$hora_inici = $row-> Hora_Inici;
				$hora_fi = $row-> Hora_Fi;
				$dada2 = "Horari del programa: $hora_inici - $hora_fi";
				break;
			case 'treballadors':
				$nom = $row-> Nom;
				$cognoms = $row-> Cognoms;
				$titol = $nom." ".$cognoms;
				$dada1 = $row-> Data_Alta;
				$dada1 = "Data d'alta: ".FechaANormal($dada1);
				$dada2 = $row-> Anys_Treballats;
				$dada2 = "Anys treballats: $dada2";
				break;
			case 'tertulians':
				$nom = $row-> Nom;
				$cognoms = $row-> Cognoms;
				$titol = $nom." ".$cognoms;
				$dada1 = $row-> Data_Naixem;
				$dada1 = FechaANormal($dada1);
				$dada1 = "Data de naixement: ".$dada1;
				break;
		}
?>
<div id="contenedorGeneral">
	<div id="contenedorContenido">
	<header>
		<hgroup>
			<h1>PRA 2 - <span class="nom">Disseny de bases de dades</span></h1>
			<h2>Cercador d'emissores de radio</h2>
			<div id="logoTancar">
				<a href="#" onclick="tancar()">Tancar</a>
			</div>
			<div id="logoPDF">
				<a href="create_pdf.php?zonaCerca=<?=$zonaCerca;?>&id=<?=$id;?>">Generar</a>
			</div>
		</hgroup>
	</header>
	<section>

		<h3 class="h3Fitxa"><?=$titol;?></h3>

		<div id="esquerra">
			<p><?=$dada1;?></p>
			<p><? if (isset($dada2)) echo $dada2;?></p>
			<?			
			switch ($zonaCerca){
				case 'emissores':
					echo emissoresFitxa($id);
					break;
				case 'programes':
					echo programesFitxa($id);
					break;
				case 'treballadors':
					echo treballadorsFitxa($id);
					break;
				case 'tertulians':
					echo tertuliansFitxa($id);
					break;
			}
			?>
			
		</div>
		<div id="dret">
			<img src="images/imatgesfitxes/<?=$zonaCerca.$id;?>.jpg">
		</div>

	</section>
	</div>
	<div id="clearfooter"></div>
	<footer>
		<hgroup>
			<h3>
			<span class="nom">Julià Moreno Alonso</span><br>
			Disseny de bases de dades
			</h3>
			<h4>
			Grau en Multimèdia<br>
			<span class="inicials">E</span>studis d'<span class="inicials">I</span>nformàtica <span class="inicials">M</span>ultimèdia i <span class="inicials">T</span>elecomunicació
			</h3>
		</hgroup>
	</footer>
</div>
</body>
</html>