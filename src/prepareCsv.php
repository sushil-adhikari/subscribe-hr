<?php

namespace SubHr;

class prepareCsv {


	/**
	* @return object prepareCsv instance
	*/
	static function getInstance() {
		return new self();
	}


	public function readCsv( $csvPath ) {
		$dataStructure = [];
		$count = 0;
		if ( ( $handle = fopen( $csvPath, 'r' ) ) !== FALSE ) {
			while ( ( $data = fgetcsv($handle, 0, ',' ) ) !== FALSE) {
				$count++;
				//skip heading
				if( $count==1) continue;
				$start = isset(  $data[0] ) ?  strtolower($data[0]) : false;
				$destination = isset(  $data[1] ) ?  strtolower($data[1]) : false;
				$latency = isset(  $data[2] ) ?  intval($data[2]) : false;

				if( !$start || !$destination || !$latency ) {
					printf( 'Couldn\'t get proper data on row %d', $count );
					continue;

				}

				$dataStructure[ $start ][$destination] = $latency;
				$dataStructure[ $destination ][$start] = $latency;
				
			}
		  fclose($handle);
		  return $dataStructure;
		}
	}

}