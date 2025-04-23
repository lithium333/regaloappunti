<?php
// comment "exit();" line to enable this test tool
exit();

// FILE TO TEST (RELATIVE PATH)
$fname = "data/link_dummy.txt";

// SALVA
$objdat = fopen($fname, "a+");
$i=0;
$N=30;
while(!flock($objdat, LOCK_EX | LOCK_NB)) {
	$i++;
	if($i==$N)
		exit();
	sleep(1);
}
for($i=0;$i<15;$i++) {
	sleep(1);
}
$date = (new DateTime('NOW'))->format("Y-m-d");
fclose($objdat);
?>
