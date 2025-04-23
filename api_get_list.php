<?php
header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json; charset=utf-8'); 
$jdata = [];
$jdata["status"]=0;
$jdata["subs"]=[];
$jdata["ext"]=[];
$jdata["int"]=[];

// CONTROLLO PRELIMINARE CATEGORIA
if (!isset($_GET['id'])) $cat="gen"; else $cat=$_GET['id'];
$jcfg_raw=file_get_contents("cfg.json");
$jcfg_obj=json_decode($jcfg_raw,true);
if(!isset($jcfg_obj["courses"][$cat]) and $cat!="gen") {
	$jdata["status"]=-1; // ERRORE -1 : categoria non valida
	echo json_encode($jdata);
	exit();
}

// LINK ESTERNI
$objdat = fopen('data/link_'.$cat.'_add.txt', "r");
if($objdat) {
	while (($objrow=fgets($objdat))!=false) {
		$rellink=fgets($objdat);
		$jcur=[];
		$jcur["link"]=rtrim($rellink);
		$jcur["desc"]=rtrim($objrow);
		$jcur["sub"]=null;
		$jdata["ext"][]=$jcur;
	}
	fclose($objdat);
}
// LINK TELEGRAM
$objdat = fopen('data/link_'.$cat.'.txt', "r");
if($objdat) {
	while (($objrow=fgets($objdat))!=false) {
		$rellink=fgets($objdat);
		$jcur=[];
		$jcur["link"]=rtrim($rellink);
		$jcur["desc"]=rtrim($objrow);
		$jcur["sub"]=null;
		$jdata["int"][]=$jcur;
	}
	fclose($objdat);
}
// LINK SUBS
if(isset($jcfg_obj["subcats"][$cat])) {
	$jdata["subs"]=$jcfg_obj["subcats"][$cat];
	foreach ($jdata["subs"] as $subid=>$subdesc) {
		$objdat = fopen('data/link_'.$cat.'_sub_'.$subid.'.txt', "r");
		if($objdat) {
			while (($objrow=fgets($objdat))!=false) {
				$rellink=fgets($objdat);
				$jcur=[];
				$jcur["link"]=rtrim($rellink);
				$jcur["desc"]=rtrim($objrow);
				$jcur["sub"]=$subid;
				$jdata["int"][]=$jcur;
			}
			fclose($objdat);
		}
	}
}

echo json_encode($jdata);
?>
