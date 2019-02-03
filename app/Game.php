<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Game extends Model
{    
	public $timestamps = false;    
	protected $fillable = ['0','1','2','3','4','5','6','7','8', 'id', 'gamer1', 'gamer2'];
	protected $table = 'game';
}
