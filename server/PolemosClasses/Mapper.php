<?php

//Mapper class for Polemos engine
//Author: Thaddeus Bond

namespace Polemos;
class Mapper
{
	//Private class variables
	private $mapnumber; //The map number to retrieve
	private $xlocation; //If player map: Center x location of map, If custom tile area: top of map
	private $ylocation; //If player map: Center y location of map, If custom tile area: left of map
	private $width; //Width of tiles to retrieve
	private $height; //Height of tiles to retrieve

	//Class constants
	const GROUND_LAYER      = 0; //Ground layer
	const MASK_LAYER        = 1; //Above ground layer, below sprites
	const MASK_LAYER_TWO    = 2; //Second above ground layer, below sprites
	const FRINGE_LAYER      = 3; //Above ground layer, above sprites
	const FRINGE_LAYER_TWO  = 4; //Second above ground layer, above sprites

	//Mapper constructor override
	//Used for: retrieving custom tile areas
	public function __construct( $mapnumber, $xlocation, $ylocation, $width, $height ) {
		//Assign private variables
		$this->mapnumber = $mapnumber;
		$this->xlocation = $xlocation;
		$this->ylocation = $ylocation;
		$this->width     = $width;
		$this->height    = $height;
	}

	public function getMapObject()
	{
		return array(
			'map'       => $this->getMap(),
			'mapnumber' => $this->getMapNumber(),
			'left'      => $this->getX(),
			'top'       => $this->getY(),
			'right'     => $this->getX() + $this->getWidth() - 1,
			'bottom'    => $this->getY() + $this->getHeight() - 1
		);
	}

	//Retrieve an array of the current map object
	private function getMap()
	{
		$mapTiles = array();
		$tileCounter = 0;
		for ( $y = $this->getY(); $y < ($this->getY() + $this->getHeight()); $y++ )
		{
			for ( $x = $this->getX(); $x < ($this->getX() + $this->getWidth()); $x++ )
			{
				$mapTiles[ self::GROUND_LAYER ][ $tileCounter ]     = $this->getTile( $this->getMapNumber(), $x, $y, self::GROUND_LAYER ); // Ground layer
				$mapTiles[ self::MASK_LAYER ][ $tileCounter ]       = $this->getTile( $this->getMapNumber(), $x, $y, self::MASK_LAYER );
				$mapTiles[ self::MASK_LAYER_TWO ][ $tileCounter ]   = $this->getTile( $this->getMapNumber(), $x, $y, self::MASK_LAYER_TWO );
				$mapTiles[ self::FRINGE_LAYER ][ $tileCounter ]     = $this->getTile( $this->getMapNumber(), $x, $y, self::FRINGE_LAYER );
				$mapTiles[ self::FRINGE_LAYER_TWO ][ $tileCounter ] = $this->getTile( $this->getMapNumber(), $x, $y, self::FRINGE_LAYER_TWO );
				$tileCounter++;
			}
		}
		return $mapTiles;
	}

	/**
	 * Retrieve the info about a specific tile
	 * @param $map   [Map number]
	 * @param $x     [Tile x location]
	 * @param $y     [Tile y location]
	 * @param $layer [Layer number]
	 * @return [Array containing this tile's information]
	 */
	private function getTile( $map, $x, $y, $layer )
	{
		$tile    = array();

		//DEBUG CODE
		if($layer == self::GROUND_LAYER)
			$tile[0] = 72; //Tile number
		else if($layer == self::MASK_LAYER)
			$tile[0] = 482; //Tile number
		else if($layer == self::FRINGE_LAYER_TWO)
			$tile[0] = 484; //Tile number
		else
			$tile[0] = 0; //Blank tile
		$tile[1] = 1; //Tileset number

		$tile[2] = $x;
		$tile[3] = $y;
		return $tile;
	}


	//Basic accessors
	private function getX()
	{
		return $this->xlocation;
	}

	private function getY()
	{
		return $this->ylocation;
	}

	private function getWidth()
	{
		return $this->width;
	}

	private function getHeight()
	{
		return $this->height;
	}

	private function getMapNumber()
	{
		return $this->mapnumber;
	}

	//Mapper is being destroyed
	public function __destruct() {}
}
?>