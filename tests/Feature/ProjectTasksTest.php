<?php

namespace Tests\Feature;

use App\Project;
use Facades\Tests\Setup\ProjectFactory;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProjectTasksTest extends TestCase
{
	use WithFaker;


	/** @test */
	public function a_project_can_have_tasks(){

//		$this->signIn();
//
//		$project = auth()->user()->projects()->create(factory('App\Project')->raw());


		$project = ProjectFactory::create();

		$this->actingAs($project->owner)
			->post($project->path().'/tasks',['body' => 'test task 1']);

		$this->get($project->path())->assertSee('test task 1');


	}


	/** @test */
	public function a_task_can_be_updated(){

		$this->withoutExceptionHandling();


		$project = ProjectFactory::ownedBy($this->signIn())
			->withTasks(1)
			->create();


		$this->patch($project->tasks->first()->path(),[
			'body'=>'Changed',
		]);

		$this->assertDatabaseHas('tasks',['body' => 'Changed']);

	}

	/** @test */
	public function a_task_can_be_completed(){

		$this->withoutExceptionHandling();

		$project = ProjectFactory::ownedBy($this->signIn())
			->withTasks(1)
			->create();

		$this->patch($project->tasks->first()->path(),[
			'body'=>'Changed',
			'completed' => true
		]);

		$this->assertDatabaseHas('tasks',['body' => 'Changed','completed'=> true]);

	}

	/** @test */
	public function a_task_can_be_marked_as_incomplete(){

		$this->withoutExceptionHandling();

		$project = ProjectFactory::ownedBy($this->signIn())
			->withTasks(1)
			->create();

		$this->patch($project->tasks->first()->path(),[
			'body'=>'Changed 12',
			'completed' => true
		]);

		$this->patch($project->tasks->first()->path(),[
			'body'=>'Changed 12',
			'completed' => false
		]);

		$this->assertDatabaseHas('tasks',['body' => 'Changed 12','completed'=> false]);

	}

	/** @test */
	public function a_task_required_a_body(){

		$project = ProjectFactory::create();

		$attributes = factory('App\Task')->raw(['body' => '']);

		$this->actingAs($project->owner)
			->post($project->path().'/tasks')->assertSessionHasErrors('body');

	}

	/** @test */
	public function guests_cannot_add_task_to_project(){

		$project = factory('App\Project')->create();
		$this->post($project->path().'/tasks')->assertRedirect('login');

	}

	/** @test */
	public function only_the_owner_of_project_may_add_task(){

		$this->signIn();

		$project = factory('App\Project')->create();

		$this->post($project->path().'/tasks',['body' => $this->faker->sentence])->assertStatus(403);

		$this->assertDatabaseMissing('tasks',['body' => $this->faker->sentence]);


	}

	/** @test */
	public function only_the_owner_of_project_may_update_a_task(){

		$this->signIn();

		$project = ProjectFactory::withTasks(1)
			->create();


		$this->patch($project->tasks->first()->path(),['body' => 'test new 57','completed'=>true])->assertStatus(403);

		$this->assertDatabaseMissing('tasks',['body' => 'test new 57']);


	}


}
