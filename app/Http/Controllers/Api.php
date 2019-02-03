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
		if ( $is_finish === true) {
			return response()->json( "This game is already finished", 300 );
		}

		$correct_place = $this->check_correct_place( $request, $game );
		if ( $correct_place !== true ) {
			return response()->json( "Unknown request or Wrong Place", 300 );
		}

		$play_case = $this->play_case( $request, $game );
		if ( $play_case === false ) {
			return response()->json( "It is not your turn", 300 );
		} else {
			$request = $play_case;
			$game->update( $request->all() );
			$result = $this->check_won_case( $request, $game );
			if ( $result === true ) {
				$request[0] = "finish";
				$game->update( $request->all() );
				return response()->json( "You Won", 200 );
			} else {
				return response()->json( $game, 200 );
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
		$win = [ 
			[0, 1, 2], // Check first row. 
			[3, 4, 5], // Check second Row 
			[6, 7, 8], // Check third Row 
			[0, 3, 6], // Check first column 
			[1, 4, 7], // Check second Column 
			[2, 5, 8], // Check third Column 
			[0, 4, 8], // Check first Diagonal 
			[2, 4, 6] // Check second Diagonal 
		]; 

			if ( $game["0"] = $game["1"] && $game["1"] = $game["2"] ) {
				return true;
			} elseif ( $game["3"] = $game["4"] && $game["4"] = $game["5"] ){
				return true;
			} elseif ( $game["6"] = $game["7"] && $game["7"] = $game["8"] ){
				return true;
			} elseif ( $game["0"] = $game["3"] && $game["3"] = $game["6"] ){
				return true;
			} elseif ( $game["1"] = $game["4"] && $game["4"] = $game["7"] ){
				return true;
			} elseif ( $game["2"] = $game["5"] && $game["5"] = $game["8"] ){
				return true;
			} elseif ( $game["0"] = $game["4"] && $game["4"] = $game["8"] ){
				return true;
			} elseif ( $game["2"] = $game["4"] && $game["4"] = $game["6"] ){
				return true;
			}else {
				return false;
			}
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
	public function play_case( Request $request, Game $game ) {
		if ( $request['gamer1'] !== $game['gamer1'] || $request['gamer2'] !== $game['gamer2'] ) {

			return false;
		} 
		// else if ( $request['gamer1'] === 1 ){
		// 		$request['gamer1'] = 0;
		// 		$request['gamer2'] = 1;
		// 	} else {
		// 		$request['gamer1'] = 1;
		// 		$request['gamer2'] = 0;
		// 	}

		return $request;
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
		$places = ['0','1','2','3','4','5','6','7','8'];
		foreach ( $places as $place) {
			if ( $request['gamer1'] === 1 && empty( $game[$place] ) && $request[$place] == "X") {

				return true;
			}
			if ( $request['gamer2'] === 1 && empty( $game[$place] ) && $request[$place] == "O") {

				return true;
			}
		}
	}
	/**
	 * Undocumented function
	 *
	 * @param Game $game
	 * @return boolean
	 */
	public function is_finish ( Game $game ) {
		if ( $game[0] === "finish" ) {
			return true;
		} else {
			return false;
		}

	}
}