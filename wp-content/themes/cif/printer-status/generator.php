<?php

require_once 'CifSnmpPrinter.php';
define( 'DEBUG', false );

function printDebug( & $prnObj ) {
	if ( DEBUG ) {
		echo '<pre><tt>';
		echo "General Printer Info:\n";
		print_r($prnObj->snmptable('.1.3.6.1.2.1.43.5.1'));
		echo "Display Buffer:\n";
		print_r($prnObj->snmptable('.1.3.6.1.2.1.43.16.5'));
		echo "Indicators:\n";
		print_r($prnObj->snmptable('.1.3.6.1.2.1.43.17.6'));
		echo "Alerts:\n";
		print_r($prnObj->snmptable('.1.3.6.1.2.1.43.18.1'));
		echo "Input:\n";
		print_r($prnObj->snmptable('.1.3.6.1.2.1.43.8.2'));
		echo "Output:\n";
		print_r($prnObj->snmptable('.1.3.6.1.2.1.43.9.2'));
		echo "Marker:\n";
		print_r($prnObj->snmptable('.1.3.6.1.2.1.43.10.2'));
		echo "Marker Supplies:\n";
		print_r($prnObj->snmptable('.1.3.6.1.2.1.43.11.1'));
		echo "Marker Colorant:\n";
		print_r($prnObj->snmptable('.1.3.6.1.2.1.43.12.1'));
		echo "Printer Covers:\n";
		print_r($prnObj->snmptable('.1.3.6.1.2.1.43.6.1'));
		echo '</tt></pre>';
	}
}

$brPrn = new CifSnmpPrinter('192.168.77.10', 'public', 'STATUS');
//echo $brPrn->getConsoleDisplayHtml().$brPrn->getIndicatorsDisplayHtml();
echo $brPrn->getConsoleFullHtml();
 echo $brPrn->getAlertsHtml();
 echo $brPrn->getInputsHtml();
 echo $brPrn->getSuppliesHtml();

// printDebug($brPrn);

// $hpPrn = new CifSnmpPrinter('192.168.77.11', 'public', true);
//echo $hpPrn->getConsoleDisplayHtml().$hpPrn->getIndicatorsDisplayHtml();
// echo $hpPrn->getConsoleFullHtml();
// echo $hpPrn->getAlertsHtml();
// echo $hpPrn->getInputsHtml();
// echo $hpPrn->getSuppliesHtml();

// printDebug($hpPrn);

/*
TODO These throw a fatal error about snmptable() not being defined.
print_r( snmptable( '192.168.77.5', 'public', '1.3.6.1.2.1.43.16.5' ) );
print_r( snmptable( '192.168.77.5', 'public', '1.3.6.1.2.1.43.17.6' ) );
print_r( snmptable( '192.168.77.5', 'public', '1.3.6.1.2.1.43.18.1' ) );
print_r( snmptable( '192.168.77.5', 'public', '1.3.6.1.2.1.43.5.1' ) );
*/

echo '<p>This page is updated every minute. Last updated: ' . date( 'r' ) . '</p>';
