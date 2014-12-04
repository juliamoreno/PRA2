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
	

	# Carreguem la classe de gmaps
		include_once("gmaps/GoogleMap.php");
	?>
	<link rel="stylesheet" type="text/CSS" href="css/estils.css">
	<link rel="stylesheet" type="text/CSS" href="css/estilsforms.css">
	
	
	<script type="text/javascript">

		// 	Funció per fer que aparegui el quadre de selecció de població
		//	Si es selecciona de la llista l'opció treballadors.
		function comprovarPob(){
			var quadrePob = document.getElementById('idPob');
			 if (consultaEmissores.tipus.options[consultaEmissores.tipus.selectedIndex].value == "Treballadors")
				quadrePob.style.display = 'block';
			else 
				quadrePob.style.display = 'none';
		}
	
	</script>
	
</head>
<body>
<div id="contenedorGeneral">
	<div id="contenedorContenido">
	<header>
		<hgroup>
			<h1>PRA 2 - <span class="nom">Disseny de bases de dades</span></h1>
			<h2>Cercador d'emissores de radio</h2>
			<div id="logoInfo">
				<a href="info.html">Informació</a>
			</div>
		</hgroup>
	</header>
	<section>

		<h3>Cercar emissores segons criteris i mostrar-ho al mapa</h3>
		<?
		
			//	Connectem a la base de dades
				include("db_Conex.php");
				
				include("funcions.php");
				include("funcionstaules.php");

			
			//	Inicilitzem les variables
				$text = "";
				$poblacio = "";
				$nomPoblacio = "";
				$marcat = "";
				$tipus = "";
				$textTipus = "";
				$hi_ha_errors = FALSE;
				$error_Text = FALSE;
				$error_Tipus = FALSE;
				$select = "";
				$sqlEmissores = "";
				$sqlTreballadors = "";
				$sqlProgrames = "";
				$sqlTertulians = "";
		
			//	Verifiquem si l'usuari arriba a la pàgina després d'introduir les dades al formulari
			//	o bé si acaba de carregar la pàgina i encara no ha omplert cap dada al formulari.
			
			if (!empty($_GET)){
				// Comprovem si la variable ha estat definida amb anterioritat.
				if(isset($_GET['text'])){
					$text = $_GET['text'];
				}
				if(isset($_GET['poblacio'])){
					$poblacio = $_GET['poblacio'];
				}
				if(isset($_GET['tipus'])){
					$tipus = $_GET['tipus'];
				}
				include("validarform.php");
			}

		?>
		<aside>
		<?	if ($hi_ha_errors || empty($_GET)): ?>
			<form name="consultaEmissores" method="GET" action="<?=$_SERVER['PHP_SELF']?>">
				<ul>
					<li <? if ($error_Text) echo "class=\"marcaError\""; ?>>
						<label for="idText">Text a cercar:</label>
						<input type="text" name="text" id="idText" value="<?=$text;?>">
					</li>
					<li <? if ($error_Tipus) echo "class=\"marcaError\""; ?>>
						<label for="idTipus">Opcions de cerca:<br>
						<span style="display: block; width: 290px; margin-top: 10px; margin-bottom: 5px; font-size: 9px;">(Si selecciones treballadors, pots escollir una població)</span>
						</label>
						<select name="tipus" id="idTipus" onchange="comprovarPob()">
							<? $select = "selected=\"SELECTED\"";?>
							<option value="">- Selecciona una opció -</option>
							<option value="Emissores" <?if ($tipus=="Emissores") echo $select; ?>>Emissores</option>
							<option value="Treballadors" <?if ($tipus=="Treballadors") echo $select; ?>>Treballadors</option>
							<option value="Programes" <?if ($tipus=="Programes") echo $select; ?>>Programes</option>
							<option value="Tertulians" <?if ($tipus=="Tertulians") echo $select; ?>>Tertulians</option>
						</select>
					</li>
					<?
						//	*********************************************************************
						//	Carrego una matriu amb els valors pel desplegable de poblacions
						//	*********************************************************************
							$queryPoblacions = consulta("pra2_poblacions",null,null,null,null,"Poblacio");
							$valors = 0;
							$pob = null;
							while ($rowPoblacions = mysql_fetch_assoc($queryPoblacions)){
								$pob[$valors] = $rowPoblacions['ID'];
								$pob[$valors+1] = $rowPoblacions['Poblacio'];
								$valors = $valors + 2;
							}
						//	*********************************************************************
						//	Fin cargar matriz
						//	*********************************************************************
					?>
					<li id="idPob" style="display: none">
						<label for="idPoblacio">Població treballador:<br>
						<span style="display: block; width: 290px; margin-top: 10px; margin-bottom: 10px; font-size: 9px;">(La població no es tindrà en compte si no selecciones Treballadors a les opcions de cerca)</span>
						</label>
						<select name="poblacio" id="idPoblacio">
							<option value="0">Totes les poblacions</option>
							<?
								
								for ($i=0; $i<count($pob); $i=$i+2){
									if (!empty($poblacio) && $pob[$i] == $poblacio) $select = "selected=\"SELECTED\"";
									else $select = "";
									echo "<option value=\"$pob[$i]\" $select>".$pob[$i+1]."</option>";
								}
							?>
						</select>
					</li>
					<li>
						<input class="botons" type="submit" value="Cercar">
					</li>
				</ul>
			</form>
			<script type="text/javascript">
				comprovarPob();
			</script>
		<? else: ?>
				<div id="tornar"><a href="./">Tornar a cercar</a></div>
				
			<?
				switch ($tipus){
					case "Emissores":
						$sqlEmissores = emissores($text);
						break;
					case "Treballadors":
						$sqlTreballadors = treballadors($text, $poblacio);
						break;
					case "Programes":
						$sqlProgrames = programes($text);
						break;
					case "Tertulians":
						$sqlTertulians = tertulians($text);
						break;
				}
			?>
			
		<? endif; ?>
		</aside>
		
		<article class="mapa">

			<? include("mapa_Google.php"); ?>
				<?
				//	Dibuixem el mapa
				$MAP_OBJECT->printOnLoad();
				$MAP_OBJECT->printMap();
				//	Aquesta linia col·loca les entrades en una línia d'estat a sota del mapa
				// $MAP_OBJECT->printSidebar();
			?>

			<iframe width="425" height="350" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.es/maps?f=q&amp;source=s_q&amp;hl=es&amp;geocode=&amp;q=Professionalscat.net,+El+Pla+del+Pened%C3%A8s&amp;aq=0&amp;oq=Professionalscat.net+&amp;sll=41.419252,1.712588&amp;sspn=0.001802,0.004128&amp;ie=UTF8&amp;hq=Professionalscat.net,&amp;hnear=El+Pla+del+Pened%C3%A8s,+Barcelona,+Catalunya&amp;ll=41.4186,1.712243&amp;spn=0.006295,0.006295&amp;t=m&amp;output=embed"></iframe><br /><small><a href="https://maps.google.es/maps?f=q&amp;source=embed&amp;hl=es&amp;geocode=&amp;q=Professionalscat.net,+El+Pla+del+Pened%C3%A8s&amp;aq=0&amp;oq=Professionalscat.net+&amp;sll=41.419252,1.712588&amp;sspn=0.001802,0.004128&amp;ie=UTF8&amp;hq=Professionalscat.net,&amp;hnear=El+Pla+del+Pened%C3%A8s,+Barcelona,+Catalunya&amp;ll=41.4186,1.712243&amp;spn=0.006295,0.006295&amp;t=m" style="color:#0000FF;text-align:left">Ver mapa más grande</a></small>
		</article>

		
		
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