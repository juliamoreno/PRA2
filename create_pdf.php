<?php 

//El primer pas és declarar el fitxer HTML i fer els includes de les llibreries


// Possem aquestes dues linies per a mostrar els errors del nostre codi PHP al navegador.
// Quan una web deixa d'estar en desenvolupament s'han de treure aquestes intruccions 
// perquè es una vulnerabilitat que pot permetre a  un usuari extern pugui atacar la nostra web.

error_reporting(E_ALL);
ini_set('display_errors', '1');


//Convierte la fecha de SQL a normal
function FechaANormal($MySQLFecha) 
{ 
if (($MySQLFecha == "") or ($MySQLFecha == "0000-00-00") ) 
	{return "";} 
else 
	{return date("d/m/Y",strtotime($MySQLFecha));} 
}



// Incluim el path de la llibreria tcpdf.

include('tcpdf/tcpdf.php');



//	Recuperem els paràmetres passats per l'usuari.

	$zonaCerca = $_GET['zonaCerca'];
	$id = $_GET['id'];



// Ens conectem a la base de dades.
	include('db_Conex.php');
	
	include("funcionstaules.php");
	include('funcionsfitxes.php');

//	Declarem el fitxer pdf i algunes de les seves característiques


$pdf = new TCPDF('P', PDF_UNIT, 'A4', true, 'UTF-8', false);

# Afegim informació descriptiva sobre el document PDF
$pdf->SetCreator (PDF_CREATOR);
$pdf->SetAuthor ('El UOC - Disseny de bases de dades');
$pdf->SetTitle ('Fitxa de la taula '.$zonaCerca);
$pdf->SetSubject ('Creació de pdf amb intercacció amb una base de dades');



//	Trobo això per internet que es per col·locar els marges del pdf
//	Ordre dels valors:
//	Esquerra, Superior, Dret

$pdf->SetMargins(30, 40, 20);

//	Col·loca un encapçalament de pàgina
$pdf->SetHeaderData('', '', "PRA2 - Disseny de bases de dades", "Julià Moreno Alonso");
//	Marge de la capçalera (només marge superior)
$pdf->setHeaderMargin(10);

//	Faig que no aparequi el peu de pàgina, i per tant treu la línia inferior
$pdf->setPrintFooter(false);

// Fuente de la cabecera y el pie de página
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', 20));

//	Es crea el contingut del fitxer Pfd




# S'insereix la primera pàgina

$pdf->AddPage();




/* 
	Comencem el codi html per imprimir el PDF
	Utilitzem la variable $html per anar afegint els
	continguts que volem col·locar al PDF.
*/


// Donem una mica d'estil al pdf de sortida.
$html = "<style>";

$html.= "h1{font-family: verdana, sans-serif; color: #ff9a00; font-size: 30pt;}";
$html.= "h2{font-family: verdana, sans-serif; color: #354c66; font-size: 25pt;}";
$html.= "h3{font-family: verdana, sans-serif; color: #ff9a00; font-size: 15pt;}";
$html.= "h4{font-family: verdana, sans-serif; color: #ff9a00; font-size: 12pt;}";
$html.= "h5{font-family: verdana, sans-serif; color: #ff9a00; font-size: 10pt;}";
$html.= "li{font-size: 10pt; font-family: verdana, sans-serif; color:#354c66;}";
$html.= "p{font-size: 12pt; font-family: verdana, sans-serif; color:#354c66;}";
$html.= ".centrarImatge{text-align: center;}";
$html.= "img{width: 200px;}";
$html.= "</style>";


		// Recuperem les dades que volem afegir a la sortida
		$sql = "SELECT * FROM pra2_$zonaCerca WHERE ID = $id";
		$query= mysql_query( $sql ) or die(mysql_error());
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
		$html.= "<h1>$titol</h1>";
		$html.= "<div class=\"centrarImatge\"><img src=\"images/imatgesfitxes/$zonaCerca".$id.".jpg\"></div>";
		$html.= "<p></p>";
		$html.= "<p></p>";
		$html.= "<p>$dada1</p>";
		if (isset($dada2)) $html.= "<p>$dada2</p>";
		switch ($zonaCerca){
			case 'emissores':
				$html.= emissoresFitxa($id);
				break;

			case 'programes':
				$html.= programesFitxa($id);
				break;
			case 'treballadors':
				$html.=treballadorsFitxa($id);
				break;
			case 'tertulians':
				$html.= tertuliansFitxa($id);
				break;
		}
		




// Afegeixo el contingut de la pàgina que guardava la variable $html
//	S'escriu el fitxer PDF

$pdf->writeHTML($html , true, 0, true, 0);

# Genero el fitxer PDF
// Si en comptes de "I" indiquesseim "D", el pdf no es visualitzaria al navegador, sino que es descarregaria. 
$pdf->Output ('llistat_agencies.pdf', 'I');
?>