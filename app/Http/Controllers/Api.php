<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Game;

class Api extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		return response()->json(Game::get(), 200);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store( Request $request )
	{
		$request["gamer1"] = 1;
		$request["gamer2"] = 0;
		$request["game_status"] = "playing";
		$game = Game::create( $request->all() );
		return response()->json( $game, 201 ); 
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show( $id )
	{
		return response()->json( Game::find( $id ), 200 );
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $game
	 * @return \Illuminate\Http\Response
	 */
	public function update( Request $request, Game $game )
	{
		$is_finish = $this->is_finish( $game );
		if ( $is_finish == true) {
			return response()->json( "This Game Is Already Finished", 300 );
		}

		$correct_place = $this->check_correct_place( $request, $game );
		if ( $correct_place !== true ) {
			return response()->json( "Unknown Request or Wrong Place", 300 );
		} else {
			$request = $this->switch_play_case( $request, $game );
			$game->update( $request->all() );
			$result = $this->check_won_case( $request, $game );

			if ( $result === false ) {
				return response()->json( $game, 200 );
			} else {
			//	$request["game_status"] = "finish";
				$game->update( $request->all());
				return response()->json( $result . " " . "WON"   , 200 );
			}

		}

	}

	/**
	 * If a user cancel the game that game is deleted.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function destroy( Request $request, Game $game )
	{
		$game->delete();
		return response()->json( null, 204 );
	}

	public function check_won_case( Request $request, Game $game ) {
		// $win = [ 
		// 	[a1, a2, a3], // Check first row. 
		// 	[b1, b2, b3], // Check second Row 
		// 	[c1, c2, c3], // Check third Row 
		// 	[a1, b1, c1], // Check first column 
		// 	[a2, b2, c2], // Check second Column 
		// 	[a3, b3, c3], // Check third Column 
		// 	[a1, b2, c3], // Check first Diagonal 
		// 	[a3, b2, c1] // Check second Diagonal 
		// ]; 

		if ( $game["a1"] == $game["a2"] && $game["a2"] == $game["a3"] &&
		!empty ( $game["a1"] ) && !empty ( $game["a2"] ) && !empty ( $game["a3"] ) ) {
			return  $game["a1"] ;
		} else if ( $game["b1"] == $game["b2"] &&  $game["b2"] == $game["b3"] &&
		!empty ( $game["b1"] ) && !empty ( $game["b2"] ) && !empty ( $game["b3"] )
		) {
			return $game["b1"];
		} else if ( $game["c1"] == $game["c2"] &&  $game["c2"] == $game["c3"] &&
		!empty ( $game["c1"] ) && !empty ( $game["c2"] ) && !empty ( $game["c3"] )
		 ) {
			return $game["c1"];
		} else if ( $game["a1"] == $game["b1"] &&  $game["b1"] == $game["c1"] &&
		!empty ( $game["a1"] ) && !empty ( $game["b1"] ) && !empty ( $game["c1"] )
		) {
			return $game["a1"];
		} else if ( $game["a2"] == $game["b2"] &&  $game["b2"] == $game["c2"] &&
		!empty ( $game["a2"] ) && !empty ( $game["b2"] ) && !empty ( $game["c2"] )
		) {
			return $game["a2"];
		} else if ( $game["a3"] == $game["b3"] &&  $game["b3"] == $game["c3"] &&
		!empty ( $game["a3"] ) && !empty ( $game["b3"] ) && !empty ( $game["c3"] )
		) {
			return $game["a3"];
		} else if ( $game["a1"] == $game["b2"] &&  $game["b2"] == $game["c3"] &&
		!empty ( $game["a1"] ) && !empty ( $game["b2"] ) && !empty ( $game["c3"] )
		) {
			return $game["a1"];
		} else if ( $game["a3"] == $game["b2"] &&  $game["b2"] == $game["c1"] &&
		!empty ( $game["a3"] ) && !empty ( $game["b2"] ) && !empty ( $game["c1"] )
		) {
			return $game["a3"];
		} else {
			return false;
		}
	}


	/**
	 * X is defined for gamer1 and O is defined for gamer2. Another
	 * requests does not accept. This method also check to moving place 
	 * is empty or not. 
	 *
	 * @param [type] $request
	 * @param [type] $game
	 * @return void
	 */
	public function check_correct_place( Request $request, Game $game ) {
		$places = ['id', 'a1','a2','a3','b1','b2','b3','c1','c2','c3'];
		foreach ( $places as $place) {
			if ( $game['gamer1'] === 1 && empty( $game[$place] ) && $request[$place] == "X") {
				return true;
			}
			if ( $game['gamer2'] === 1 && empty( $game[$place] ) && $request[$place] == "O") {
				return true;
			}
		}

		return false;
	}

	/**
	 * Checking turn of player. When a player move, should wait for move of enemies.
	 * If a player try to move without wait it returns false. Gamer1 and Gamer2 keep in 
	 * database as boolean. Both of theme are can't be same value. Value 1 has the right to play.
	 *
	 * @param [type] $request
	 * @param [type] $game
	 * @return void
	 */
	public function switch_play_case( Request $request, Game $game ) {
		if ( $game['gamer1'] === 1 ){
				$game['gamer1'] = 0;
				$game['gamer2'] = 1;
			} else {
				$game['gamer1'] = 1;
				$game['gamer2'] = 0;
			}

		return $request;
	}

	/**
	 * Undocumented function
	 *
	 * @param Game $game
	 * @return boolean
	 */
	public function is_finish ( Game $game ) {
		if ( $game["game_status"] == "finish" ) {
			return true;
		} else {
			return false;
		}
	}
}