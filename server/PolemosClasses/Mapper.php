<?php

//Mapper class for Polemos engine
//Author: Thaddeus Bond
//Last Modified: March 29, 2013

namespace Polemos;
class Mapper
{
	//Private class variables
	private $_mapnumber; //The map number to retrieve
	private $_xlocation; //If player map: Center x location of map, If custom tile area: top of map
	private $_ylocation; //If player map: Center y location of map, If custom tile area: left of map
	private $_width; //Width of tiles to retrieve
	private $_height; //Height of tiles to retrieve
	private $_type; //Type of map retrieval

	//Class constants
	const PLAYER_MAP 		= 1;
	const CUSTOM_MAP 		= 2;
	const PLAYER_MAP_WIDTH 	= 31;
	const PLAYER_MAP_HEIGHT = 23;


	//Mapper class constructor
	//Used for: player map retrieval
	public function LoadPlayerMap($mapnumber, $xlocation, $ylocation) {
		//Assign private variables
        $this->_mapnumber = $mapnumber;
        $this->_xlocation = $xlocation;
        $this->_ylocation = $ylocation;
        $this->_type = self::PLAYER_MAP;
    }

    //Mapper constructor override
    //Used for: retrieving custom tile areas
	public function LoadCustomMap($mapnumber, $xlocation, $ylocation, $width, $height) {
		//Assign private variables
        $this->_mapnumber = $mapnumber;
        $this->_xlocation = $xlocation;
        $this->_ylocation = $ylocation;
        $this->_width	  = $width;
        $this->_height	  = $height;
        $this->_type 	  = self::CUSTOM_MAP;
    }

    //Retrieve an array of the current map object
    public function getMap()
    {
    	$mapTiles = array();
    	$tileCounter = 0;
    	//Are we asking for a player map or a custom map?
    	if ( $this->getMapType() == self::PLAYER_MAP )
    	{
    		for ( $y = $this->getY() - ((self::PLAYER_MAP_HEIGHT - 1) / 2); $y <= $this->getY() + ((self::PLAYER_MAP_HEIGHT - 1) / 2); $y++ ) 
    		{ 
    			for ( $x = $this->getX() - ((self::PLAYER_MAP_WIDTH - 1) / 2); $x <= $this->getX() + ((self::PLAYER_MAP_WIDTH - 1) / 2); $x++ ) 
    			{ 
    				$mapTiles[$tileCounter] = $this->getTile($this->getMapNumber(), $x, $y);
    				$tileCounter++;
    			}
    		}
    	}
    	elseif ( $this->getMapType() == self::CUSTOM_MAP )
    	{
    		for ( $y = $this->getY(); $y <= (getY() + $this->getHeight()); $y++ ) 
    		{ 
    			for ( $x = $this->getX(); $x <= ($this->getX() + $this->getWidth()); $x++ ) 
	    		{ 
	    			$mapTiles[$tileCounter] = $this->getTile($this->getMapNumber(), $x, $y);
    				$tileCounter++;
	    		}
    		}
    	}
    	return $mapTiles;
    	unset($mapTiles);
    	unset($tileCounter);
    }

    //Retrieve the tile info
    //For testing purposes, were just going to return one tile for now
    public function getTile($map, $x, $y)
    {
    	$tile = array();
    	$tile[0] = 72;
    	$tile[1] = 1;
    	return $tile;
    	unset($tile);
    }


    //Basic accessors
	public function getMapType()
	{
		return $this->_type;
	}

	public function getX()
	{
		return $this->_xlocation;
	}

	public function getY()
	{
		return $this->_ylocation;
	}

	public function getWidth()
	{
		return $this->_width;
	}

	public function getHeight()
	{
		return $this->_height;
	}

	public function getMapNumber()
	{
		return $this->_mapnumber;
	}

    //Mapper is being destroyed
    public function __destruct() {}
}
?>