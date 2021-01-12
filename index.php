<?php

use SubHr\prepareCsv;
use SubHr\NetworkPaths;

require __DIR__ . '/vendor/autoload.php';

$csvDir = readline("Add CSV file directory:");
if( !file_exists( $csvDir ) ) {
	readline('**File not found, Click Enter to exit' );
	exit();
}

$input = readline("Type input in format (Device From, Device To, Latency): ");

$input = preg_split('/\s+/', $input);

if( count( $input ) !== 3 || $input === 'exit' ) {
	readline('**Wrong input format, Click Enter to exit' );
	exit();
}

/*$csvDir = '/Users/sushiladhikari/Sites/subscribe-hr/subhr.csv';
$input = ['e', 'a', 80 ];*/

$prepareCsv = prepareCsv::getInstance();
$dataStructure = $prepareCsv->readCsv( $csvDir );

if( empty( $dataStructure )) {
	readline('**Wrong data at CSV, Click Enter to exit' );
	exit();
}

$networkPath = NetworkPaths::getInstance();
$networkPath->setDataStructure( $dataStructure );
$networkPath->generatePossiblePath( $input );
readline($networkPath->getResults());
exit();


