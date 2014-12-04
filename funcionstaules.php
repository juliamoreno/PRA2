<?
	//	Funció carregarTaula
	//	Autor: 	Julià Moreno Alonso
	//	Utilitza un argument obligatori que es el nom de la taula
	// 	I tres arguments opcions per això el null, també es podria haver fet amb ''
	//	Els arguments de la taula son $campCerca, $criteri i $campOrdenacio

	function consulta($taula,$campCerca1=null,$criteri1=null,$campCerca2=null,$criteri2=null,$campOrdenacio=null,$tipus=null){	
		$sql = "SELECT $taula.* ";
		$sql.= "FROM $taula ";
		if (!empty($criteri1)) $sql.= "WHERE $campCerca1 like '$criteri1' ";
		if (!empty($criteri2)) $sql.= " AND $campCerca2 like '$criteri2' ";
		if (!empty($campOrdenacio)) $sql.= "ORDER BY $campOrdenacio ";
		if (!empty($tipus)) $sql.= $tipus;
		$rs = mysql_query( $sql ) or die(mysql_error());
	//	echo $sql;
		return $rs;
	}
	function consultarelacio($taula1,$taula2,$campTaula1,$campTaula2,$campCerca=null,$criteri=null,$campOrdenacio1=null,$tipus1=null,$campOrdenacio2=null,$tipus2=null,$campOrdenacio3=null,$tipus3=null){
		$sql = "SELECT $taula1.*, $taula2.* ";
		$sql.= "FROM $taula1, $taula2 ";
		$sql.= "WHERE $taula1.$campTaula1 = $taula2.$campTaula2 ";
		if (!empty($criteri)) $sql.= "AND $campCerca = '$criteri' ";
		if (!empty($campOrdenacio1)) $sql.= "ORDER BY $campOrdenacio1 ";
		if (!empty($tipus1)) $sql.= $tipus1;
		if (!empty($campOrdenacio2)) $sql.= ", $campOrdenacio2 ";
		if (!empty($tipus2)) $sql.= $tipus2;
		if (!empty($campOrdenacio3)) $sql.= ", $campOrdenacio3 ";
		if (!empty($tipus3)) $sql.= $tipus3;
		$rs = mysql_query( $sql ) or die(mysql_error());
		return $rs;
	}
?>