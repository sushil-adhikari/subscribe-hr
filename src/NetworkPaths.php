<?php

namespace SubHr;

class NetworkPaths {

	/**
	* @var total possible paths
	*/
	protected $paths;

	/**
	* @var array node data structure
	*/
	protected $data = [];

	/**
	* @var intval time
	*/
	private $time;

	/**
	* @return object NetworkStructure instance
	*/
	static function getInstance() {
		return new self();
	}

	/**
	* @return array 
	* Possible path
	*/
	protected function getDataStructure() {
		return $this->data;
		/*return  [
			'a' => ['b' => 10, 'c' => 20  ],
			'b' => ['d' => 100 ],
			'c' => ['d' => 30 ],
			'd' => ['e' => 10  ],
			'e' => ['f' => 1000 ],
			'f' => []
		];*/
	}

	/**
	* @param array  $data
	* @return void
	*/
	public function setDataStructure( $data) {
		$this->data = $data;
	}

	/**
	* @param string $source
	* @param string $destination
	* @return array
	*/
	private function getPossiblePath($source, $destination, $visited, $paths ) {
		$node = array_keys( $source );
		$node = array_pop( $node );
	
		$visited[$node] = true;
		$paths[] = $source;
		$dataStructure = self::getDataStructure();

		if( $node === $destination ) {
			$this->setPaths($paths);

		} else {
			foreach ($dataStructure[$node] as $key => $latency ) {
				if( isset($visited[$key]) && $visited[$key] === false ) {
					
					$this->getPossiblePath( [$key=>$latency], $destination, $visited, $paths );
				}
			}
		}
		array_pop($paths);
		$visited[$node] = false;
	}

	/**
	* @param int $time
	* @return void
	*/
	private function setTime( $time ) {
		$this->time = intval($time);
	}

	/**
	* @return int
	*/
	private function getTime() {
		return $this->time;
	}


	/**
	* @param array $input
	*/
	public function generatePossiblePath($input) {
		$visited =  array_fill_keys(array_keys( self::getDataStructure() ), false );
		$paths = [];
		$start = isset( $input[0] ) ? strtolower( $input[0] ) : false;
		$destination = isset( $input[1] ) ? strtolower($input[1]) : false;
		$time = isset( $input[2] ) ? intval($input[2]) : false;

		$this->setTime( $time );
		$this->getPossiblePath( [$start=>0], $destination, $visited, $paths );
	}


	/**
	* @return array possiblepaths
	*/
	public function getPaths() {
		return $this->paths;
	}

	/**
	* @return void
	* Set @var paths
	*/
	protected function setPaths($newPaths) {
		$this->paths[] = $newPaths;
	}


	/**
	* @param int $time
	* @return mixed bool|array
	*/
	protected function checkTimeConstant( $time ) {
		$getPaths = $this->getPaths();
		if( empty( $getPaths ) ) return false;

		foreach ($getPaths as $index => $nodes ) {
			$totalLatency = 0;
			
			foreach ($nodes as $index => $node ) {

				$totalLatency += end($node);
			}
			if( $totalLatency <= $time ) {
				$nodes[] = [ 'shortestLatency' => $totalLatency ];
				return $nodes;
			}
		}

		return false;
	}

	/**
	* @return string
	*/
	public function getResults() {
		$isValid = $this->checkTimeConstant( $this->getTime() );
		
		if( false === $isValid ) {
			return  "Path not found";
		}
		if( is_array( $isValid ) && !empty( $isValid ) ) {
			$output = [];
			
			foreach ($isValid as $index => $nodes ) {
				$getKeys = array_keys( $nodes );
				$keys = array_pop( $getKeys );
				if( $keys === "shortestLatency" ) {
					$output[] = $nodes['shortestLatency'];
				} else {
					$output[] = $keys;
				}
				
			}
			return implode( '=>', $output );
		}

	}

}