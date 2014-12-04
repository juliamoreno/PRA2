<?
	#	Creem el Mapa
	$MAP_OBJECT = new GoogleMapAPI(); $MAP_OBJECT->_minify_js = isset($_REQUEST["min"])?FALSE:TRUE;

	$MAP_OBJECT->_minify_js = isset($_REQUEST["min"])?FALSE:TRUE;
	//$MAP_OBJECT->setMapType("map");  => Indica el tipus de mapa a mostrar. Si decomenteu la linia veureu com canvia el tipus de mapa.
	$MAP_OBJECT->setHeight('500');   
	$MAP_OBJECT->setWidth('100%');    

	$marker_web_location = "http://comoras.uoc.edu/~jmorenoal/PRA2/images/"; 
	$default_icon = $marker_web_location."green_triangle_icon.png";
	$blue_icon  = $marker_web_location."blue_triangle_icon.png";
	$red_icon  = $marker_web_location."triangle_icon.png";

	if (empty($sqlTreballadors) && empty($sqlEmissores) && empty($sqlProgrames) && empty($sqlTertulians)){
		$resultats = consulta("pra2_emissores");
		while ($row = mysql_fetch_object($resultats)){	
			$id_emi = $row-> ID;
			$nom_emissora = $row-> Nom_Emissora;
			$latitut = $row-> Latitut;
			$longitut = $row-> Longitut;
			$numero_oients = $row-> Numero_Oients;
			$any_fundacio = $row-> Any_Fundacio;
			$coordenades = $latitut.",".$longitut;
			$default_icon_key = $MAP_OBJECT->setMarkerIcon($default_icon);
			$html_info = "<div class=\"globusMapa\">";
			$html_info.= "<p class=\"textResaltatGlobus\"><a target=\"_blank\" href=\"fitxa.php?zonaCerca=emissores&id=$id_emi\"><strong>$nom_emissora</strong></a></p>";
			$html_info.= "<p>Fundada el $any_fundacio</p>";
			$html_info.= "<p>Número d'oients: $numero_oients</p>";
			$html_info.= "</div>";
			$MAP_OBJECT->addMarkerByAddress($coordenades,$nom_emissora,$html_info);
		}
	}
	else{
		if (!empty($sqlTreballadors)){
			$resultats = mysql_query($sqlTreballadors)
				or die ("No s'ha pogut executar la consulta de Treballadors");
			while ($row = mysql_fetch_object($resultats)){	
				$id_emi = $row-> ID_Emi;
				$nom_emissora = $row-> Nom_Emissora;
				$latitut = $row-> Latitut;
				$longitut = $row-> Longitut;
				$numero_oients = $row-> Numero_Oients;
				$any_fundacio = $row-> Any_Fundacio;
				$id_treb = $row-> ID_Treb;
				$dni = $row-> DNI;
				$treballador = $row-> Treballador;
				$adreca = $row-> Adreca;
				$poblacio = $row-> Poblacio;
				$cp = $row-> CP;
				$telefon1 = $row-> Telefon1;
				$telefon2 = $row-> Telefon2;
				$mail = $row-> Mail;
				$data_alta = $row-> Data_Alta;
				$anys_treballats = $row-> Anys_Treballats;
				$coordenades = $latitut.",".$longitut;
				$default_icon_key = $MAP_OBJECT->setMarkerIcon($blue_icon);
				$html_info = "<div class=\"globusMapa\">";
				$html_info.= "<p><a target=\"_blank\" href=\"fitxa.php?zonaCerca=treballadors&id=$id_treb\"><strong>$treballador</strong></a></p>";
				$html_info.= "<p class=\"textResaltatGlobus\">$nom_emissora</p>";
				$html_info.= "<p>$adreca - $cp $poblacio</p>";
				$html_info.= "<p>Telèfons: $telefon1";
				if ($telefon2 !=0) $html_info.= " / $telefon2";
				$html_info.= "</p>";
				$html_info.= "<p>$mail</p>";
				$html_info.= "</div>";
				//	Geoposiciono al treballador, directament amb l'adreça i la població
				$MAP_OBJECT->addMarkerByAddress($adreca.", ".$poblacio,$treballador,$html_info);
				$default_icon_key = $MAP_OBJECT->setMarkerIcon($default_icon);
				$html_info = "<div class=\"globusMapa\">";
				$html_info.= "<p class=\"textResaltatGlobus\"><a target=\"_blank\" href=\"fitxa.php?zonaCerca=emissores&id=$id_emi\"><strong>$nom_emissora</strong></p></a>";
				$html_info.= "<p>Fundada el $any_fundacio</p>";
				$html_info.= "<p>Número d'oients: $numero_oients</p>";
				$html_info.= "</div>";
				//	Geoposiciono l'emissora, amb les coordenades
				$MAP_OBJECT->addMarkerByAddress($coordenades,$nom_emissora,$html_info);
			}
		}
		if (!empty($sqlEmissores)){
			$resultats = mysql_query($sqlEmissores)
				or die ("No s'ha pogut executar la consulta d'Emissores");
			while ($row = mysql_fetch_object($resultats)){	
				$id_emi = $row-> ID_Emi;
				$nom_emissora = $row-> Nom_Emissora;
				$latitut = $row-> Latitut;
				$longitut = $row-> Longitut;
				$numero_oients = $row-> Numero_Oients;
				$any_fundacio = $row-> Any_Fundacio;
				$id_treb = $row-> ID_Treb;
				$treballador = $row-> Treballador;
				$adreca  = $row-> Adreca;
				$cp = $row-> CP;
				$poblacio = $row-> Poblacio;
				$telefon1 = $row-> Telefon1;
				$telefon2 = $row-> Telefon2;
				$mail = $row-> Mail;
				$coordenades = $latitut.",".$longitut;
				$default_icon_key = $MAP_OBJECT->setMarkerIcon($default_icon);
				$html_info = "<div class=\"globusMapa\">";
				$html_info.= "<p class=\"textResaltatGlobus\"><a target=\"_blank\" href=\"fitxa.php?zonaCerca=emissores&id=$id_emi\"><strong>$nom_emissora</strong></p></a>";
				$html_info.= "<p>Fundada el $any_fundacio</p>";
				$html_info.= "<p>Número d'oients: $numero_oients</p>";
				//	Busco els treballadors per posar-les en una llista al globus a sota de l'emissora.
				$consulta = consulta("pra2_treballadors","ID_Emissores",$id_emi);
				$totaltreb = mysql_num_rows ($consulta);
				if ($totaltreb != 0){
					$html_info.= "<ul class=\"llistats;\">";
					while ($rowtreb = mysql_fetch_object($consulta)){
						$id_treba = $rowtreb-> ID;
						$nom = $rowtreb-> Nom;
						$cognoms = $rowtreb-> Cognoms;
						$html_info.= "<li> - <a target=\"_blank\" href=\"fitxa.php?zonaCerca=treballadors&id=$id_treba\" style=\"font-size: 10px;\">$nom $cognoms</a></li>";
					}
					$html_info.= "</ul>";
				}
				$html_info.= "</div>";
				//	Geoposiciono l'emissora, amb les coordenades
				$MAP_OBJECT->addMarkerByAddress($coordenades,$nom_emissora,$html_info);
				$default_icon_key = $MAP_OBJECT->setMarkerIcon($blue_icon);
				$html_info = "<div class=\"globusMapa\">";
				$html_info.= "<p><a target=\"_blank\" href=\"fitxa.php?zonaCerca=treballadors&id=$id_treb\"><strong>$treballador</strong></a></p>";
				$html_info.= "<p class=\"textResaltatGlobus\">$nom_emissora</p>";
				$html_info.= "<p>$adreca - $cp $poblacio</p>";
				$html_info.= "<p>Telèfons: $telefon1";
				if ($telefon2 !=0) $html_info.= " / $telefon2";
				$html_info.= "</p>";
				$html_info.= "<p>$mail</p>";
				$html_info.= "</div>";
				//	Geoposiciono al treballador, directament amb l'adreça i la població
				$MAP_OBJECT->addMarkerByAddress($adreca.", ".$poblacio,$treballador,$html_info);
			}
		}
		if (!empty($sqlProgrames)){
			$resultats = mysql_query($sqlProgrames)
				or die ("No s'ha pogut executar la consulta de programes");
			while ($row = mysql_fetch_object($resultats)){	
				$id_emi = $row-> ID_Emi;
				$nom_emissora = $row-> Nom_Emissora;
				$latitut = $row-> Latitut;
				$longitut = $row-> Longitut;
				$numero_oients = $row-> Numero_Oients;
				$any_fundacio = $row-> Any_Fundacio;
				$id_pro= $row-> ID_Pro;
				$coordenades = $latitut.",".$longitut;
				$default_icon_key = $MAP_OBJECT->setMarkerIcon($default_icon);
				$html_info = "<div class=\"globusMapa\">";
				$html_info.= "<p class=\"textResaltatGlobus\"><strong>$nom_emissora</strong></p>";
				//	Busco els programes per posar-les en una llista al globus a sota l'emissora
				$queryProgrames = consulta("pra2_programes","ID_emissores",$id_emi,"Nom_Programa");
				$totalProgrames = mysql_num_rows ($queryProgrames);
				if ($totalProgrames !=0){
					$html_info.= "<ul class=\"llistats;\">";
					while ($rowpro = mysql_fetch_object($queryProgrames)){
						$id_pro= $rowpro-> ID;
						$nom_programa = $rowpro-> Nom_Programa;
						$hora_inici = $rowpro-> Hora_Inici;
						$hora_fi = $rowpro-> Hora_Fi;
						$durada = $rowpro-> Durada;
						$num_oients_programa = $rowpro-> Num_Oients_Programa;
						$html_info.= "<p class=\"textResaltatGlobus\"><a target=\"_blank\" href=\"fitxa.php?zonaCerca=programes&id=$id_pro\">$nom_programa</a> <span style=\"font-size: 10px;\">($hora_inici - $hora_fi)</span></p>";
						$html_info.= "<p style=\"font-size: 10px;\">Número d'oients programa: $num_oients_programa</p>";
						//	Busco els tertulians per posar-les en una llista al globus a sota de el programa.
						$queryTertulians = consultarelacio("pra2_tertulians","pra2_tertulians_programes","ID","ID_Tertulia","ID_Programa",$id_pro,"Cognoms","ASC","Nom","ASC");
						$totalTertulians = mysql_num_rows ($queryTertulians);
						if ($totalTertulians != 0){
							$html_info.= "<ul class=\"llistats;\">";
							while ($rowtert = mysql_fetch_object($queryTertulians)){
								$id_ter = $rowtert-> ID_Tertulia;
								$nom = $rowtert-> Nom;
								$cognoms = $rowtert-> Cognoms;
								$html_info.= "<li> - <a target=\"_blank\" href=\"fitxa.php?zonaCerca=tertulians&id=$id_ter\" style=\"font-size: 10px;\">$nom $cognoms</a></li>";
							}
							$html_info.= "</ul>";
						}
					}
				}
				$html_info.= "</div>";
				//	Geoposiciono l'emissora, amb les coordenades
				$MAP_OBJECT->addMarkerByAddress($coordenades,$nom_emissora,$html_info);

			}
		}
	
		if (!empty($sqlTertulians)){
			$resultats = mysql_query($sqlTertulians)
				or die ("No s'ha pogut executar la consulta de programes");
			while ($row = mysql_fetch_object($resultats)){	
				$nom_emissora = $row-> Nom_Emissora;
				$latitut = $row-> Latitut;
				$longitut = $row-> Longitut;
				$id_pro = $row-> ID_Pro;
				$coordenades = $latitut.",".$longitut;
				$default_icon_key = $MAP_OBJECT->setMarkerIcon($default_icon);
				$html_info = "<div class=\"globusMapa\">";
				$html_info.= "<p class=\"textResaltatGlobus\"><strong>$nom_emissora</strong></p>";
				//	Busco els tertulians per posar-les en una llista al globus a sota de el programa.
				$queryTertulians = consultarelacio("pra2_tertulians","pra2_tertulians_programes","ID","ID_Tertulia","ID_Programa",$id_pro,"Cognoms","ASC","Nom","ASC");
				$totalTertulians = mysql_num_rows ($queryTertulians);
				if ($totalTertulians != 0){
					$html_info.= "<ul class=\"llistats;\">";
					while ($rowtert = mysql_fetch_object($queryTertulians)){
						$id_ter = $rowtert-> ID_Tertulia;
						$nom = $rowtert-> Nom;
						$cognoms = $rowtert-> Cognoms;
						$preu_colab = $rowtert-> Preu_Colab;
						$data_inici = $rowtert-> Data_Inici;
						$data_fi = $rowtert-> Data_Fi;
						$html_info.= "<li> - <a target=\"_blank\" href=\"fitxa.php?zonaCerca=tertulians&id=$id_ter\" style=\"font-size: 10px;\">$nom $cognoms</a> <span style=\"font-size: 10px;\">$preu_colab € (".FechaANormal($data_inici)." - ".FechaANormal($data_fi).")</span></li>";
					}
					$html_info.= "</ul>";
				}
				$html_info.= "</div>";
				//	Geoposiciono l'emissora, amb les coordenades
				$MAP_OBJECT->addMarkerByAddress($coordenades,$nom_emissora,$html_info);
			}
		}
	}
	
	echo $MAP_OBJECT->getHeaderJS();
	echo $MAP_OBJECT->getMapJS();
?>