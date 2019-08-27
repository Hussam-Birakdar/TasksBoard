<?php

namespace Tests\Unit;

use App\Project;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProjectTest extends TestCase
{

	/** @test */
	public function has_path(){

		$this->signIn();
		$project = factory('App\Project')->create();

		$this->assertEquals('/projects/'.$project->id,$project->path());

	}

	/**@test*/
	public function belongs_to_owner(){
		$project = factory('App\Project')->create();
		$this->assertInstanceOf('App\User',$project->owner);

	}

	/** @test */
	public function it_can_add_task(){

		$this->signIn();
		$project = factory('App\Project')->create();

		$task = $project->addTask('task body');

		$this->assertCount(1,$project->tasks);
		$this->assertTrue($project->tasks->contains($task));
	}

	/** @test **/
	public function it_can_invite_a_user(){

		$project = factory('App\Project')->create();

		$project->invite($newUser = factory('App\User')->create());

		$this->assertTrue($project->members->contains($newUser));

	}
}
