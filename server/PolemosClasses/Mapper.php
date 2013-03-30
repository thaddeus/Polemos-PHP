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
	public function LoadMap($mapnumber, $xlocation, $ylocation) {
		//Assign private variables
        $this->_mapnumber = $mapnumber;
        $this->_xlocation = $xlocation;
        $this->_ylocation = $ylocation;
        $this->_type = PLAYER_MAP;
    }

    //Mapper constructor override
    //Used for: retrieving custom tile areas
	public function LoadMap($mapnumber, $xlocation, $ylocation, $width, $height) {
		//Assign private variables
        $this->_mapnumber = $mapnumber;
        $this->_xlocation = $xlocation;
        $this->_ylocation = $ylocation;
        $this->_width	  = $width;
        $this->_height	  = $height;
        $this->_type 	  = CUSTOM_MAP;
    }

    //Retrieve an array of the current map object
    public function getMap()
    {
    	$mapTiles = array();
    	$tileCounter = 0;
    	//Are we asking for a player map or a custom map?
    	if ( getType() == PLAYER_MAP )
    	{
    		for ( $y = getY() - ((PLAYER_MAP_HEIGHT - 1) / 2); $y <= getY() + ((PLAYER_MAP_HEIGHT - 1) / 2); $y++ ) 
    		{ 
    			for ( $x = getX() - ((PLAYER_MAP_WIDTH - 1) / 2); $x <= getX() + ((PLAYER_MAP_WIDTH - 1) / 2); $x++ ) 
    			{ 
    				$mapTiles[$tileCounter] = getTile(getMapNumber(), $x, $y);
    				$tileCounter++;
    			}
    		}
    	}
    	elseif ( getType() == CUSTOM_MAP )
    	{
    		for ( $y = getY(); $y <= (getY() + getHeight()); $y++ ) 
    		{ 
    			for ( $x = getX(); $x <= (getX() + getWidth()); $x++ ) 
	    		{ 
	    			$mapTiles[$tileCounter] = getTile(getMapNumber(), $x, $y);
    				$tileCounter++;
	    		}
    		}
    	}
    }

    //Retrieve the tile info
    //For testin purposes, were just going to return one tile for now
    public function getTile($map, $x, $y)
    {
    	return 72;
    }


    //Basic accessors
	public function getType()
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