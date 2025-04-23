<?php
header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json; charset=utf-8');
$jdata = [];
$jdata["status"]=0;
$jdata["cat"]=[];
$jdata["ext"]=[];
$jdata["int"]=[];

// CONTROLLO PRELIMINARE RICERCA
if (!isset($_GET['id'])) $searchw=""; else $searchw=$_GET['id'];
if($searchw==null or $searchw=="") {
	$jdata["status"]=-1; // ERRORE -1 : keyword non specificata o vuota
	echo json_encode($jdata);
	exit();
}

// LOAD JSON CONFIG
$jcfg_raw = file_get_contents("cfg.json");
$jcfg_obj = json_decode($jcfg_raw,true);


// ESPANSIONE CATEGORIE
$arrdb = [];
foreach ($jcfg_obj['courses'] as $jkey => $jval) {
	$arrdb[] = [$jval,$jkey,null,null];
	// SEARCH SUB
	if(isset($jcfg_obj['subcats'][$jkey])) {
		foreach ($jcfg_obj['subcats'][$jkey] as $jkey2 => $jval2) {
			$arrdb[] = [$jval,$jkey,$jval2,$jkey2];
		}
	}
}

// RICERCA NELLE CATEGORIE
foreach ($arrdb as $elm) {
	$show=false;
	if(($elm[2]==null)&&strpos(strtolower($elm[0]), strtolower($searchw))!==false)
		$show=true;
	if(($elm[2]!=null)&&strpos(strtolower($elm[2]), strtolower($searchw))!==false)
		$show=true;
	if($show==true) {
		$jdata['cat'][] = $elm;
	}
}

// RICERCA LINK NEI DB GENERICI
$fpart1="data/link_gen";
$fname=$fpart1."_add.txt";
$objdat = fopen($fname, "r");
if($objdat) {
	while (($subrow=fgets($objdat))!=false) {
		$strdesc=str_replace(array("\n","\r"),"",$subrow);
		$rellink=str_replace(array("\n","\r"),"",fgets($objdat));
		if(strpos(strtolower($strdesc), strtolower($searchw))!==false) {
			$jdata["ext"][]=["gen",null,$rellink,$strdesc];
		}
	}
	fclose($objdat);
}
$fname=$fpart1.".txt";
$objdat = fopen($fname, "r");
if($objdat) {
	while (($subrow=fgets($objdat))!=false) {
		$strdesc=str_replace(array("\n","\r"),"",$subrow);
		$rellink=str_replace(array("\n","\r"),"",fgets($objdat));
		if(strpos(strtolower($strdesc), strtolower($searchw))!==false) {
			$jdata["int"][]=["gen",null,$rellink,$strdesc];
		}
	}
	fclose($objdat);
}

// RICERCA LINK NEI DB DELLE CATEGORIE
foreach ($arrdb as $elm) {
	if($elm[2]!=null)
		$fpart1="data/link_".$elm[1].'_sub_'.$elm[3];
	else
		$fpart1="data/link_".$elm[1];
	// LISTA RISORSE ESTERNE
	$fname=$fpart1."_add.txt";
	$objdat = fopen($fname, "r");
	if($objdat) {
		while (($subrow=fgets($objdat))!=false) {
			$strdesc=str_replace(array("\n","\r"),"",$subrow);
			$rellink=str_replace(array("\n","\r"),"",fgets($objdat));
			if(strpos(strtolower($strdesc), strtolower($searchw))!==false) {
				$jdata["int"][]=[$elm[1],$elm[3],$rellink,$strdesc];
			}
		}
		fclose($objdat);
	}
	// LISTA RISORSE STANDARD (TG)
	$fname=$fpart1.".txt";
	$objdat = fopen($fname, "r");
	if($objdat) {
		while (($subrow=fgets($objdat))!=false) {
			$strdesc=str_replace(array("\n","\r"),"",$subrow);
			$rellink=str_replace(array("\n","\r"),"",fgets($objdat));
			if(strpos(strtolower($strdesc), strtolower($searchw))!==false) {
				$jdata["int"][]=[$elm[1],$elm[3],$rellink,$strdesc];
			}
		}
		fclose($objdat);
	}
}

echo json_encode($jdata);
?>
