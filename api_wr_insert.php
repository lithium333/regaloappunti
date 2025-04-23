<?php
header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json; charset=utf-8'); 
$jpost = json_decode($_POST['data'],true);
$id = $jpost['cat'];
$subcat = $jpost['sub'];

// CONTROLLO PRELIMINARE CATEGORIA
$jcfg_raw=file_get_contents("cfg.json");
$jcfg_obj=json_decode($jcfg_raw,true);
if(!isset($jcfg_obj["courses"][$id]) and $id!="gen") {
	die("{\"status\":-1}"); // ERRORE -1 : categoria non valida
}

// CONTROLLO PRELIMINARE SUB CATEGORIA
if(!isset($jcfg_obj["subcats"][$id][$subcat]) and $subcat!=null) {
	die("{\"status\":-2}"); // ERRORE -2 : sub categoria non valida
}

// CONTROLLO LINK
$link = $jpost['link'];
$startwith = "https://t.me/appuntipolito/";
if(!(substr($link, 0, strlen($startwith)) === $startwith)) {
    die("{\"status\":-3}"); // ERRORE -3 : link non permesso
}

// SUB-CAT FNAME
if($subcat==null)
    $fname = "data/link_".$id.".txt";
else
    $fname = "data/link_".$id."_sub_".$subcat.".txt";

// SALVA
$objdat = fopen($fname, "a+");
$i=0;
$N=10;
while(!flock($objdat, LOCK_EX | LOCK_NB)) {
	$i++;
	if($i==$N) {
		die("{\"status\":-4}"); // ERRORE -4 : errore file lock txt DB
		fclose($objdat);
	}
	sleep(1);
}
$date = (new DateTime('NOW'))->format("Y-m-d");
fwrite($objdat, $date.": ".$jpost['desc']."\n".$link."\n");
fclose($objdat);

echo "{\"status\":0}"; // STATO 0 : INSERZIONE ESEGUITA
?>
