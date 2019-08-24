<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;


class Project extends Model
{
    //
	use RecordsActivity;

	protected $fillable = [
		'title',
		'description',
		'notes',
		'owner_id'
	];

	public function path(){
		return "/projects/".$this->id;
	}

	public function owner(){
		return $this->belongsTo('App\User');
	}

	public function tasks(){
		return $this->hasMany('App\Task');
	}

	public function addTask($body){
		$task = $this->tasks()->create(compact('body'));

		return $task;
	}

	public function activity(){
		return $this->hasMany('App\Activity')->latest();
	}

}
