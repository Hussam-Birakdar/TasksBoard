<?php

namespace Tests\Unit;

use Illuminate\Database\Eloquent\Collection;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{

	/** @test */
	public function has_projects(){

		$user = factory('App\User')->create();

		$this->assertInstanceOf('Illuminate\Database\Eloquent\Collection',$user->projects);

	}

}
