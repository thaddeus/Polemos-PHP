<?php

/**
 * This is the object which retrieves specific areas of a map within the Polemos engine
 * The information is not cached within the object, but rather is a structured accessor
 * @author  Thaddeus Bond <thaddeus@thaddeusbond.com>
 */

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

	/**
	 * Creates a map object with the specified locations
	 * @param [Integer] $mapnumber The map number to load from
	 * @param [Integer] $xlocation The upper-left X coordinate of the area to load
	 * @param [Integer] $ylocation The upper-left Y coordinate of the area to load
	 * @param [Integer] $width     The width of tiles to load
	 * @param [Integer] $height    The height of tiles to load
	 */
	public function __construct( $mapnumber, $xlocation, $ylocation, $width, $height ) {
		//Assign private variables
		$this->mapnumber = $mapnumber;
		$this->xlocation = $xlocation;
		$this->ylocation = $ylocation;
		$this->width     = $width;
		$this->height    = $height;
	}

	/**
	 * Returns an array containing all the important information about this map
	 * @return [Array] An array containing the array of tiles, their information, and general map info
	 */
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

	/**
	 * Retrieves the tiles within the specified area of this object
	 * @return [Array] Array of all the tiles in this objects' area
	 */
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
	 * Retrieves the information about a specific tile
	 * @param  [Integer] $map   The map number to retrieve the tile from
	 * @param  [Integer] $x     The x location of the tile to retrieve
	 * @param  [Integer] $y     The y location of the tile to retrieve
	 * @param  [Integer] $layer The layer which we want to retrieve from
	 * @return [Array]          An array of the tile's information useful to the client for displaying it
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


	/**
	 * Get this object's upper-left X location
	 * @return [Integer] The upper-left X of the loaded map area
	 */
	private function getX()
	{
		return $this->xlocation;
	}

	/**
	 * Get this object's upper-left Y location
	 * @return [Integer] The upper-left Y of the loaded map area
	 */
	private function getY()
	{
		return $this->ylocation;
	}

	/**
	 * Get this object's loaded map width in tiles
	 * @return [Integer] The width of the loaded map area in tiles
	 */
	private function getWidth()
	{
		return $this->width;
	}

	/**
	 * Get this object's loaded map height in tiles
	 * @return [Integer] The height of the loaded map area in tiles
	 */
	private function getHeight()
	{
		return $this->height;
	}

	/**
	 * Get this object's loaded map number
	 * @return [Integer] The number of the map which is loaded
	 */
	private function getMapNumber()
	{
		return $this->mapnumber;
	}

	//Mapper is being destroyed
	public function __destruct() {}
}
?>