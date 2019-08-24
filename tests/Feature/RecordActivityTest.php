<?php

namespace Tests\Feature;

use Facades\Tests\Setup\ProjectFactory;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RecordActivityTest extends TestCase
{
    /** @test */
    public function creating_a_project(){

    	$project = ProjectFactory::create();

    	$this->assertCount(1,$project->activity);

    	$this->assertEquals('created_project',$project->activity[0]->description);

    	$this->assertNull($project->activity[0]->changes);

	}

	/** @test */
	public function updating_a_project(){

		$project = ProjectFactory::create();

		$orginalTitle = $project->title;
		$project->update(['title' => 'updated']);

		$this->assertCount(2,$project->activity);

		$this->assertEquals('updated_project',$project->activity->last()->description);


		$expected = [
			'before'=>['title'=>$orginalTitle],
			'after' => ['title'=>'updated']
		];

		$this->assertEquals($expected,$project->activity->last()->changes);

	}


	/** @test */
	public function creating_a_new_task(){

		$project = ProjectFactory::create();

		$project->addTask('Some task');

		$this->assertCount(2,$project->activity);

		//dd($project->activity->last()->description);


		$this->assertEquals('created_task',$project->activity->last()->description);
		$this->assertInstanceOf('App\Task',$project->activity->last()->subject);
	}

	/** @test */
	public function completing_a_task(){

		$project = ProjectFactory::withTasks(1)->create();

		$this->actingAs($project->owner)->patch($project->tasks[0]->path(),[
			'body'=>'foobar',
			'completed'=>true
		]);

		$this->assertCount(3,$project->activity);
		$this->assertEquals('completed_task',$project->activity->last()->description);
		$this->assertInstanceOf('App\Task',$project->activity->last()->subject);

	}

	/** @test */
	public function incompleting_a_task(){

		$project = ProjectFactory::withTasks(1)->create();

		$this->actingAs($project->owner)->patch($project->tasks[0]->path(),[
			'body'=>'foobar',
			'completed'=>true
		]);

		$this->assertCount(3,$project->activity);

		$this->actingAs($project->owner)->patch($project->tasks[0]->path(),[
			'body'=>'foobar',
			'completed'=>false
		]);
		$project->refresh();

		$this->assertCount(4,$project->activity);


		//dd($project->activity->last()->description);
		$this->assertEquals('incompleted_task',$project->activity->last()->description);
		$this->assertInstanceOf('App\Task',$project->activity->last()->subject);

	}
}
