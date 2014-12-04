<?
//	Funció cercar emissores
function emissores($text){
	$emissora = "";
	$emissoraAnt = "";
	$b=0;
	//	Primer faig una consulta simple per aconseguir el número d'emissores introduïdes
	$queryEmissores= consulta("pra2_emissores","Nom_Emissora","%$text%");
	$totalEmissores = mysql_num_rows ($queryEmissores);
	// -----------------------------------------------------------------------------------					
	$sqlEmissores = "SELECT E.ID AS ID_Emi, Nom_Emissora, Latitut, Longitut, Numero_Oients, Any_Fundacio, ";
	$sqlEmissores.= "T.ID As ID_Treb, DNI, CONCAT(Nom, \" \",Cognoms) AS Treballador, Adreca, CP, Poblacio, ";
	$sqlEmissores.= "Telefon1, Telefon2, Mail, Data_Alta, Anys_Treballats ";
	$sqlEmissores.= "FROM pra2_treballadors T ";
	$sqlEmissores.= "INNER JOIN pra2_codis_postals C ON (C.ID = T.ID_CP) ";
	$sqlEmissores.= "INNER JOIN pra2_poblacions P ON (P.ID = C.ID_Poblacio) ";
	$sqlEmissores.= "RIGHT JOIN pra2_emissores E ON (E.ID = T.ID_Emissores) ";
	$sqlEmissores.= "WHERE Nom_Emissora like \"%$text%\"";
	$sqlEmissores.= "OR Poblacio like '%$text%' ";
	$sqlEmissores.= "OR Mail like '%$text%' ";
	$sqlEmissores.= "OR Poblacio like '%$text%' ";
	$sqlEmissores.= "OR Adreca like '%$text%' ";
	$sqlEmissores.= "OR CONCAT(Nom,\" \",Cognoms) like '%$text%' ";
	$sqlEmissores.= "ORDER BY Nom_Emissora;";
	$queryEmissores = mysql_query($sqlEmissores) or die (mysql_error());
	$totalEmissores = mysql_num_rows ($queryEmissores);
	if ($totalEmissores == 0) echo "<p>No s'han trobat registres a la taula seleccionada</p>";
	else echo "<p>S'han trobat <strong>$totalEmissores</strong> coincidències amb el text <strong>\"$text\"</strong></p>";
	echo "<ul class=\"llistats\">";
	while ($rowEmissores = mysql_fetch_assoc($queryEmissores)){
		$emissora = $rowEmissores['Nom_Emissora'];
		if ($emissora != $emissoraAnt){
			if ($b>0) echo "</ul></li>";
			echo "<li><a target=\"_blank\" href=\"fitxa.php?zonaCerca=emissores&id=".$rowEmissores['ID_Emi']."\">".$emissora."</a>";
			echo "<ul>";
		}
		if ($rowEmissores['DNI']){
			echo "<li style=\"font-weight: normal; color: #bbb\">".$rowEmissores['Treballador']." (".$rowEmissores['DNI'].") - ".$rowEmissores['Telefon1']." / ".$rowEmissores['Telefon2']." - ".$rowEmissores['Mail']." - Alta: ".FechaANormal($rowEmissores['Data_Alta'])."</li>";
		}
	$emissoraAnt = $emissora;
	$b++;
	}
	echo "</ul></li></ul>";
	return $sqlEmissores;
}
//	Fi cerca emissores				


//	Funció cercar treballadors
function treballadors($text,$criteriPob){
	$textPob = "";
	$sqlTreballadors = "SELECT E.ID AS ID_Emi, Nom_Emissora, Latitut, Longitut, Numero_Oients, Any_Fundacio, ";
	$sqlTreballadors.= "T.ID AS ID_Treb, DNI, CONCAT(Nom, \" \",Cognoms) AS Treballador, Adreca, CP, Poblacio, ";
	$sqlTreballadors.= "Telefon1, Telefon2, Mail, Data_Alta, Anys_Treballats ";
	$sqlTreballadors.= "FROM pra2_treballadors T ";
	$sqlTreballadors.= "INNER JOIN pra2_codis_postals C ON (C.ID = T.ID_CP) ";
	$sqlTreballadors.= "INNER JOIN pra2_poblacions P ON (P.ID = C.ID_Poblacio) ";
	$sqlTreballadors.= "INNER JOIN pra2_emissores E ON (E.ID = T.ID_Emissores) ";
	$sqlTreballadors.= "WHERE (CONCAT(Nom,\" \",Cognoms) like '%$text%' ";
	$sqlTreballadors.= "OR Mail like '%$text%' ";
	$sqlTreballadors.= "OR Adreca like '%$text%' ";
	$sqlTreballadors.= "OR Nom_Emissora like '%$text%' )";
	if ($criteriPob != 0){
		$sqlTreballadors.=" AND P.ID = $criteriPob ";
		//	Busco el número de la població per aconseguir el nom de la població
		$queryPoblacions = consulta("pra2_poblacions","ID",$criteriPob,null,null,"Poblacio");
		$rowPoblacions = mysql_fetch_assoc($queryPoblacions);
		$nomPoblacio = $rowPoblacions['Poblacio'];
		$textPob = " i amb la Població <strong>$nomPoblacio</strong>";
	}
	$sqlTreballadors.= "ORDER BY Treballador ";
	$queryTreballadors = mysql_query( $sqlTreballadors ) or die(mysql_error());
	$totalTreballadors = mysql_num_rows ($queryTreballadors);
	if ($totalTreballadors == 0) echo "<p>No s'han trobat registres a la taula seleccionada</p>";
	else echo "<p>S'han trobat <strong>$totalTreballadors</strong> coincidències amb el text <strong>\"$text\"</strong>$textPob</p>";
	echo "<ul class=\"llistats\">";
	while ($rowTreballadors= mysql_fetch_assoc($queryTreballadors)){
		echo "<li><a target=\"_blank\" href=\"fitxa.php?zonaCerca=treballadors&id=".$rowTreballadors['ID_Treb']."\">".$rowTreballadors['DNI']." - ".$rowTreballadors['Treballador']." - ".$rowTreballadors['Nom_Emissora']."</a>";
		echo "<ul><li style=\"list-style-type: none; font-weight: normal; color: #bbb\">".$rowTreballadors['Adreca']." - ".$rowTreballadors['CP']." ".$rowTreballadors['Poblacio']." - Mail: ".$rowTreballadors['Mail']."</li></ul>";
		echo "</li>";
	}
	echo "</ul>";
	//	Torno la consulta per desprès utilitzar-la al mapa
	return $sqlTreballadors;
}
//	Fi cerca treballadors


//	Funció cercar programes
function programes($text){
	$sqlProgrames = "SELECT E.ID AS ID_Emi, Nom_Emissora, Latitut, Longitut, Numero_Oients, Any_Fundacio, ";
	$sqlProgrames.= "P.ID AS ID_Pro, Nom_Programa, Hora_Inici, Hora_Fi, Durada, Num_Oients_Programa ";
	$sqlProgrames.= "FROM pra2_programes P ";
	$sqlProgrames.= "INNER JOIN pra2_emissores E ON (E.ID = P.ID_Emissores) ";
	$sqlProgrames.= "WHERE Nom_Programa like '%$text%' ";
	$sqlProgrames.= "OR Nom_Emissora like '%$text%' ";
	$sqlProgrames.= "ORDER BY Nom_Emissora ";
	$queryProgrames = mysql_query( $sqlProgrames ) or die(mysql_error());
	$totalProgrames = mysql_num_rows ($queryProgrames);
	if ($totalProgrames == 0) $totalProgrames = "No s'han trobat registres a la taula seleccionada";
	echo "<p>Total registres trobats a Programes: $totalProgrames</p>";
	echo "<ul class=\"llistats\">";
	while ($rowProgrames = mysql_fetch_assoc($queryProgrames)){
		echo "<li>".$rowProgrames['Nom_Emissora']." - ".$rowProgrames['Nom_Programa']." (Número d'Oients: ".$rowProgrames['Num_Oients_Programa'].")</li>";
		$queryTertulians = consultarelacio("pra2_tertulians","pra2_tertulians_programes","ID","ID_Tertulia","ID_Programa",$rowProgrames['ID_Pro'],"Cognoms","ASC","Nom","ASC");
		$totalTertulians = mysql_num_rows ($queryTertulians);
		if ($totalTertulians > 0){
			echo "<p>S'han trobat $totalTertulians registres en taules relacionades</p>";
			echo "<ul>";
			while ($rowTertulians = mysql_fetch_assoc($queryTertulians)){
				echo "<li style=\"font-weight:normal; color: #bbb; list-style-type: circle;\">".$rowTertulians['Nom']." ".$rowTertulians['Cognoms']." (".$rowTertulians['DNI'].")</li>";
			}
			echo "</ul>";
		}
	}
	echo "</ul>";
	return $sqlProgrames;
}
//	Fi cerca emissores


//	Funció cercar tertulians
function tertulians($text){
	$sqlTertulians = "SELECT T.ID, DNI, CONCAT(Nom,\" \",Cognoms) AS Tertulia, Data_Naixem, ";
	$sqlTertulians.= "Nom_Emissora, Latitut, Longitut, Numero_Oients, Any_Fundacio, ";
	$sqlTertulians.= "P.ID AS ID_Pro, Nom_Programa, Hora_Inici, Hora_Fi, Durada, Num_Oients_Programa, ";
	$sqlTertulians.= "Data_Inici, Data_Fi, Preu_Colab ";
	$sqlTertulians.= "FROM pra2_tertulians T ";
	$sqlTertulians.= "INNER JOIN pra2_tertulians_programes TP ON (T.ID = ID_Tertulia) ";
	$sqlTertulians.= "INNER JOIN pra2_programes P ON (P.ID = ID_Programa) ";
	$sqlTertulians.= "INNER JOIN pra2_emissores E ON (E.ID = ID_Emissores) ";
	$sqlTertulians.= "WHERE CONCAT(Nom,\" \",Cognoms) like '%$text%' ";
	$sqlTertulians.= "OR Nom_Emissora like '%$text%' ";
	$sqlTertulians.= "OR Nom_Programa like '%$text%' ";
	$sqlTertulians.= "ORDER BY Tertulia ";
	$queryTertulians = mysql_query( $sqlTertulians ) or die(mysql_error());
	$totalTertulians = mysql_num_rows ($queryTertulians);
	if ($totalTertulians == 0) $totalTertulians = "No s'han trobat registres a la taula seleccionada";
	echo "<p>Total registres trobats a Tertulians: $totalTertulians</p>";
	echo "<ul class=\"llistats\">";
	while ($rowTertulians= mysql_fetch_assoc($queryTertulians)){
		echo "<li>".$rowTertulians['Tertulia']." (".$rowTertulians['DNI'].") <span style=\"font-weight: normal; color: #bbb\"> - ".$rowTertulians['Nom_Programa']." de ".$rowTertulians['Nom_Emissora']."</span></li>";
	}
	echo "</ul>";
	return $sqlTertulians;
}
//	Fi cerca tertulians



//Convierte la fecha de SQL a normal
function FechaANormal($MySQLFecha) 
{ 
if (($MySQLFecha == "") or ($MySQLFecha == "0000-00-00") ) 
	{return "";} 
else 
	{return date("d/m/Y",strtotime($MySQLFecha));} 
}

//	La variable $valorQuadre serà una matriu
function inserirSelect($valorQuadre, $campComparacio){
	$quantitatEntrades = count($valorQuadre);
	for ($i=0; $i<$quantitatEntrades; $i=$i+2){
		if ($valorQuadre[$i]==$campComparacio){
			$select = "selected=\"SELECTED\"";
		}
		else{
			$select = "";
		}
		print "<option value=\"".$valorQuadre[$i]."\" ".$select.">".$valorQuadre[$i+1]."</option>";
	}
}
?>


