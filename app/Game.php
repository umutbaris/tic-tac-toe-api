<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
	public $timestamps = false;
	protected $fillable = ['id', 'a1','a2','a3','b1','b2','b3','c1','c2','c3', 'gamer1', 'gamer2'];
	protected $table = 'game';
}
