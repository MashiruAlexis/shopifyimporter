<?php
/**
 *	Shopify Simple Product Importer
 *	@author Ramon Alexis Celis
 *	@since 3/5/19
 *	@version 1.0
 */

define( 'DS', DIRECTORY_SEPARATOR );
define( "BP", dirname(__FILE__) );

# first check if the library was included.
$csvAutoload = BP . DS . 'csv' . DS . 'autoload.php';
if( file_exists($csvAutoload) ) {
	require $csvAutoload;
}else{
	exit('CSV library not found!. BRB');
}

# require also the logger so we can whats happening on the variables.
require BP . DS . 'logger' . DS . 'log.php';

# functions
require BP . DS . 'functions.php';

# this is the path to the csv file
$csvFilePath = BP . DS . 'import.csv';

# call this for the csv
use League\Csv\Reader;

$reader = Reader::createFromPath( $csvFilePath, 'r');
$records = $reader->getRecords();
foreach ($records as $offset => $record) {
	if( !isset($header) ){
		$header = $record;
	}
	if( $offset < 1 ) { continue; }
	foreach( $record as $address => $rec ) {
		$pretty[ $header[$address] ] = $rec;
		# FORMATING!
		if( $header[$address] == 'Image URL' ) {
			$rec = explode('|', $rec);
		}

		if( $header[$address] == 'Product categories' ) {
			$rec = str_replace('>', ',', $rec);
			$pretty['Tags'] = $rec;
		}

		if( $header[$address] == 'Product tags' ) {
			$rec = ',' . str_replace('|', ',', $rec);
			$pretty['Tags'] .= $rec;
		}

		if( $header[$address] == 'wc_ps_subtitle' ) {
			$pretty['Title'] .=  ' ' . $rec;
		}
		
		if( $header[$address] == 'Author Username' ) {
			$pretty['Vendor'] = $rec;
			unset($pretty['Author Username']);
			unset($pretty['Author First Name']);
			unset($pretty['Author Last Name']);
		}

		if( $header[$address] == 'Content' ) {
			$pretty['Body (HTML)'] = $rec;
			unset($pretty['Content']);
		}

		
	}
	Log::print( $pretty );
}