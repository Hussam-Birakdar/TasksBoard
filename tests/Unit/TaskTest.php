<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TaskTest extends TestCase
{
	/** @test */
	public function has_a_path()
	{

		$this->signIn();

		$task = factory('App\Task')->create();

		$this->assertEquals('/projects/' . $task->project->id . '/tasks/' . $task->id, $task->path());

	}

	/** @test */
	public function belongs_to_project(){
		$this->signIn();
		$task = factory('App\Task')->create();
		$this->assertInstanceOf('App\Project',$task->project);

	}

	/** @test */
	public function it_can_be_completed(){

		$this->signIn();
		$task = factory('App\Task')->create();

		$this->assertFalse($task->completed);

		$task->complete();

		$this->assertTrue($task->fresh()->completed);

	}

	/** @test */
	public function it_can_be_marked_as_incomplete(){

		$this->signIn();
		$task = factory('App\Task')->create(['completed'=>true]);

		$this->assertTrue($task->completed);

		$task->incomplete();

		$this->assertFalse($task->fresh()->completed);
	}
}
