<?php
require_once('CifSnmpPrinter.php');
define('DEBUG', false);
#define('DEBUG', true);

function printDebug(&$prnObj) {
	if(DEBUG) {
		print '<pre><tt>';
		print "General Printer Info:\n";
		print_r($prnObj->snmptable('.1.3.6.1.2.1.43.5.1'));
		print "Display Buffer:\n";
		print_r($prnObj->snmptable('.1.3.6.1.2.1.43.16.5'));
		print "Indicators:\n";
		print_r($prnObj->snmptable('.1.3.6.1.2.1.43.17.6'));
		print "Alerts:\n";
		print_r($prnObj->snmptable('.1.3.6.1.2.1.43.18.1'));
		print "Input:\n";
		print_r($prnObj->snmptable('.1.3.6.1.2.1.43.8.2'));
		print "Output:\n";
		print_r($prnObj->snmptable('.1.3.6.1.2.1.43.9.2'));
		print "Marker:\n";
		print_r($prnObj->snmptable('.1.3.6.1.2.1.43.10.2'));
		print "Marker Supplies:\n";
		print_r($prnObj->snmptable('.1.3.6.1.2.1.43.11.1'));
		print "Marker Colorant:\n";
		print_r($prnObj->snmptable('.1.3.6.1.2.1.43.12.1'));
		print "Printer Covers:\n";
		print_r($prnObj->snmptable('.1.3.6.1.2.1.43.6.1'));
		print '</tt></pre>';
	}
}

print '<html><head><title>CIF Printer Status</title>';
if(!DEBUG) {
	print '<meta http-equiv="refresh" content="15" />';
}
print '<body>';
$brPrn = new CifSnmpPrinter('192.168.77.6', 'public', 'STATUS');
//print $brPrn->getConsoleDisplayHtml().$brPrn->getIndicatorsDisplayHtml();
print $brPrn->getConsoleFullHtml();
print $brPrn->getAlertsHtml();
print $brPrn->getInputsHtml();
print $brPrn->getSuppliesHtml();
printDebug($brPrn);

$hpPrn = new CifSnmpPrinter('192.168.77.5', 'public', true);
//print $hpPrn->getConsoleDisplayHtml().$hpPrn->getIndicatorsDisplayHtml();
print $hpPrn->getConsoleFullHtml();
print $hpPrn->getAlertsHtml();
print $hpPrn->getInputsHtml();
print $hpPrn->getSuppliesHtml();
printDebug($hpPrn);
print_r(snmptable('192.168.77.5', 'public', '1.3.6.1.2.1.43.16.5'));
print_r(snmptable('192.168.77.5', 'public', '1.3.6.1.2.1.43.17.6'));
print_r(snmptable('192.168.77.5', 'public', '1.3.6.1.2.1.43.18.1'));
print_r(snmptable('192.168.77.5', 'public', '1.3.6.1.2.1.43.5.1'));
print '<p>This page is updated every minute. Last updated: '.date('r').'</p>';
print '</body></html>';
print "\n";
