<?
	//	Es creen aquestes funcions perquè d'aquesta forma puc cridar el mateix
	//	Codi des de la fitxa en html i la creació de la fitxa en pdf
	
	function emissoresFitxa($id){
		$textTornar = "";
		$queryProgrames= consulta("pra2_programes","ID_Emissores",$id);
		while ($rowProgrames = mysql_fetch_object($queryProgrames)){
			$id_programa = $rowProgrames-> ID;
			$nom_programa = $rowProgrames-> Nom_Programa;
			$hora_inici = $rowProgrames-> Hora_Inici;
			$hora_fi = $rowProgrames-> Hora_Fi;
			$durada = $rowProgrames-> Durada;
			$num_oients_programa = $rowProgrames-> Num_Oients_Programa;
			$textTornar.= "<h4>$nom_programa</h4>";
			$textTornar.= "<p>$hora_inici - $hora_fi ($durada minuts)</p>";
			$textTornar.= "<p>Número d'oients programa: $num_oients_programa</p>";
			$queryEmissions = consultarelacio("pra2_emissions_programes","pra2_dias_setmana","ID_Dia","ID","ID_Programa",$id_programa,"ID_Dia","ASC");
			$totalEmissions = mysql_num_rows ($queryEmissions);
			$valor = "<p>Dies d'emissió: ";
			$a = 0;
			while ($rowEmissions = mysql_fetch_object($queryEmissions)){
				$a++;
				$dia_setmana = $rowEmissions-> Dia_Setmana;
				$valor.= $dia_setmana;
				if ($a != $totalEmissions) $valor.= " - ";
			}
			$valor.= "</p>";
			$textTornar.= $valor;
			$queryTertulians = consultarelacio("pra2_tertulians", "pra2_tertulians_programes","ID","ID_Tertulia","ID_Programa", $id_programa, "Cognoms", "ASC", "Nom", "ASC");
			$totalTertulians = mysql_num_rows ($queryTertulians);
			if ($totalTertulians != 0){
				$textTornar.= "<h5>Tertulians que col·laboren</h4>";
				$textTornar.= "<ul class=\"llistats\">";
				while ($rowTertulians = mysql_fetch_object($queryTertulians)){
					$dni = $rowTertulians-> DNI;
					$nom = $rowTertulians-> Nom;
					$cognoms = $rowTertulians-> Cognoms;
					$data_naixem = $rowTertulians-> Data_Naixem;
					$data_inici = $rowTertulians-> Data_Inici;
					$data_fi = $rowTertulians-> Data_Fi;
					$preu_colab = $rowTertulians-> Preu_Colab;
					$tertulia = $nom." ".$cognoms;
					$textTornar.= "<li><span class=\"textResaltatGlobus\">$tertulia </span>($dni) - Va néixer el ".FechaANormal($data_naixem).". Col·labora del ".FechaANormal($data_inici)." fins ".FechaANormal($data_fi).". Amb un preu hora de $preu_colab €</li>";
				}
				$textTornar.= "</ul>";
			}
		}
		return $textTornar;
	}
	
	function programesFitxa($id){
		$textTornar = "";
		$queryEmissions = consultarelacio("pra2_emissions_programes","pra2_dias_setmana","ID_Dia","ID","ID_Programa",$id,"ID_Dia","ASC");
		$totalEmissions = mysql_num_rows ($queryEmissions);
		$valor = "<p>Dies d'emissió: ";
		$a = 0;
		while ($rowEmissions = mysql_fetch_object($queryEmissions)){
			$a++;
			$dia_setmana = $rowEmissions-> Dia_Setmana;
			$valor.= $dia_setmana;
			if ($a != $totalEmissions) $valor.= " - ";
		}
		$valor.= "</p>";
		$textTornar.= $valor;
		$queryTertulians = consultarelacio("pra2_tertulians", "pra2_tertulians_programes","ID","ID_Tertulia","ID_Programa", $id, "Cognoms", "ASC", "Nom", "ASC");
		$totalTertulians = mysql_num_rows ($queryTertulians);
		if ($totalTertulians != 0){
			$textTornar.= "<h4>Tertulians que col·laboren</h4>";
			$textTornar.= "<ul class=\"llistats\">";
			while ($rowTertulians = mysql_fetch_object($queryTertulians)){
				$dni = $rowTertulians-> DNI;
				$nom = $rowTertulians-> Nom;
				$cognoms = $rowTertulians-> Cognoms;
				$data_naixem = $rowTertulians-> Data_Naixem;
				$data_inici = $rowTertulians-> Data_Inici;
				$data_fi = $rowTertulians-> Data_Fi;
				$preu_colab = $rowTertulians-> Preu_Colab;
				$tertulia = $nom." ".$cognoms;
				$textTornar.= "<li><span class=\"textResaltatGlobus\">$tertulia </span>($dni) - Va néixer el ".FechaANormal($data_naixem).". Col·labora del ".FechaANormal($data_inici)." fins ".FechaANormal($data_fi).". Amb un preu hora de $preu_colab €</li>";
			}
			$textTornar.= "</ul>";
		}
		return $textTornar;
	}

	function treballadorsFitxa($id){
		$textTornar = "";
		$sqlTreballadors = "SELECT E.ID AS ID_Emi, Nom_Emissora, Latitut, Longitut, Numero_Oients, Any_Fundacio, ";
		$sqlTreballadors.= "T.ID AS ID_Treb, DNI, Nom, Cognoms, Adreca, CP, Poblacio, ";
		$sqlTreballadors.= "Telefon1, Telefon2, Mail, Data_Alta, Anys_Treballats ";
		$sqlTreballadors.= "FROM pra2_treballadors T ";
		$sqlTreballadors.= "INNER JOIN pra2_codis_postals C ON (C.ID = T.ID_CP) ";
		$sqlTreballadors.= "INNER JOIN pra2_poblacions P ON (P.ID = C.ID_Poblacio) ";
		$sqlTreballadors.= "INNER JOIN pra2_emissores E ON (E.ID = T.ID_Emissores) ";
		$sqlTreballadors.= "WHERE T.ID = $id";
		$queryTreballadors = mysql_query( $sqlTreballadors ) or die(mysql_error());
		$rowTreballadors = mysql_fetch_object($queryTreballadors);
		$nom_emissora = $rowTreballadors-> Nom_Emissora;
		$any_fundacio = $rowTreballadors-> Any_Fundacio;
		$dni = $rowTreballadors-> DNI;
		$adreca = $rowTreballadors-> Adreca;
		$poblacio = $rowTreballadors-> Poblacio;
		$cp = $rowTreballadors-> CP;
		$telefon1 = $rowTreballadors-> Telefon1;
		$telefon2 = $rowTreballadors-> Telefon2;
		$mail = $rowTreballadors-> Mail;
		$textTornar.= "<p>Treballa a <span class=\"textResaltatGlobus\">$nom_emissora</span><p>";
		$textTornar.= "<h4>Dades personals:</h4>";
		$textTornar.= "<p style=\"margin-top: 10px;\">DNI: $dni</p>";
		$textTornar.= "<p style=\"margin-top: 10px;\">$adreca - $cp $poblacio</p>";
		$textTornar.= "<p style=\"margin-top: 10px;\">Telèfons: $telefon1";
		if ($telefon2 !=0) $textTornar.= " / $telefon2";
		$textTornar.= "</p>";
		$textTornar.= "<p style=\"margin-top: 10px;\">$mail</p>";
		return $textTornar;
	}
	function tertuliansFitxa($id){
		$textTornar = "";
		$sqlTertulians = "SELECT T.ID, DNI, Nom, Cognoms, Data_Naixem, ";
		$sqlTertulians.= "Nom_Emissora, Latitut, Longitut, Numero_Oients, Any_Fundacio, ";
		$sqlTertulians.= "P.ID AS ID_Pro, Nom_Programa, Hora_Inici, Hora_Fi, Durada, Num_Oients_Programa, ";
		$sqlTertulians.= "Data_Inici, Data_Fi, Preu_Colab ";
		$sqlTertulians.= "FROM pra2_tertulians T ";
		$sqlTertulians.= "INNER JOIN pra2_tertulians_programes TP ON (T.ID = ID_Tertulia) ";
		$sqlTertulians.= "INNER JOIN pra2_programes P ON (P.ID = ID_Programa) ";
		$sqlTertulians.= "INNER JOIN pra2_emissores E ON (E.ID = ID_Emissores) ";
		$sqlTertulians.= "WHERE T.ID = $id ";
		$queryTertulians = mysql_query( $sqlTertulians ) or die(mysql_error());
		while ($rowTertulians = mysql_fetch_object($queryTertulians)){
			$nom_emissora = $rowTertulians-> Nom_Emissora;
			$any_fundacio = $rowTertulians-> Any_Fundacio;
			$nom_programa = $rowTertulians-> Nom_Programa;
			$data_inici = $rowTertulians-> Data_Inici;
			$data_fi = $rowTertulians-> Data_Fi;
			$preu_colab = $rowTertulians-> Preu_Colab;
			$textTornar.= "<h4 style=\"margin-top: 25px\">Col·labora al programa $nom_programa</h4>";
			$textTornar.= "<p>Emissora: <span class=\"textResaltatGlobus\">$nom_emissora</span> fundada en $any_fundacio</p>";
			$textTornar.= "<p style=\"margin-top: 10px\">Des del $data_inici fins al $data_fi</p>";
			$textTornar.= "<p>Els seus honoraris son de <strong>$preu_colab €/hora</strong></p>";
		}
		return $textTornar;
	}
?>