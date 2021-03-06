<?php

namespace Tests\Feature;

use App\Project;
use Facades\Tests\Setup\ProjectFactory;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ManageProjectsTest extends TestCase
{

	use WithFaker;

	/** @test  */
	public function guests_cannot_create_a_project(){


		$project = factory('App\Project')->raw(['owner_id' => null]);

		$this->get('projects/create')->assertRedirect('login');

		$this->post('projects',$project)->assertRedirect('login');

	}


	/** @test  */
	public function guest_cannot_view_projects(){

		$this->get('projects')->assertRedirect('login');

	}

	/** @test  */
	public function guests_cannot_view_a_single_project(){

		$project = factory('App\Project')->create();

		$this->get($project->path())->assertRedirect('login');

	}

	/** @test */


	/** @test */
	public function a_user_can_view_his_project(){



		$this->withoutExceptionHandling();

//		$this->signIn();
//		$project = factory('App\Project')->create(['owner_id' => Auth()->id()]);

		$project = ProjectFactory::ownedBy($this->signIn())->create();

		$this->actingAs($project->owner)->get($project->path())->assertSee($project->title)->assertSee($project->description);

	}

	/** @test */
	public function a_user_cannot_view_others_projects(){

		$this->signIn();

//		$this->withoutExceptionHandling();

		$project = factory('App\Project')->create();

		$this->get($project->path())->assertStatus(403);
	}


	/** @test */
	public function a_user_cannot_update_others_projects(){

		$this->signIn();

//		$this->withoutExceptionHandling();

		$project = factory('App\Project')->create();

		$this->patch($project->path(),['notes'=>'change1'])->assertStatus(403);
	}


	/** @test */
	public function a_user_can_create_a_project(){

		$this->signIn();

		$this->get('projects/create')->assertStatus(200);


		$this->followingRedirects()->post('projects',$attributes = factory('App\Project')->raw())
		->assertSee($attributes['title'])
		->assertSee($attributes['description'])
		->assertSee($attributes['notes']);


	}

	/** @test  **/
	public function a_user_can_see_all_project_they_have_been_invited_to_on_their_dashboard(){

		$project = tap(ProjectFactory::create())->invite($this->signIn());

		$this->get('/projects')
			->assertSee($project->title);

	}

	/** @test  **/
	public function unauthorized_users_cannot_delete_a_project(){


		$project = ProjectFactory::create();

		$this->delete($project->path())
			->assertRedirect('/login');

		$user = $this->signIn();
		$this->delete($project->path())->assertStatus(403);

		$project->invite($user);

		$this->actingAs($user)->delete($project->path())->assertStatus(403);

	}

	/** @test  **/
	public function a_user_can_delete_a_project(){

		$this->withoutExceptionHandling();

		$project = ProjectFactory::create();

		$this->actingAs($project->owner)
			->delete($project->path())
			->assertRedirect('/projects');

		$this->assertDatabaseMissing('projects',$project->only('id'));


	}

	/** @test */
	public function a_user_can_update_a_project(){

		$this->withoutExceptionHandling();

		$project = ProjectFactory::create();


		$this->actingAs($project->owner)->patch($project->path(),['title'=>'changed1','description'=>'test','notes'=>'changed']);

		$this->get($project->path().'/edit')->assertOk();
		$this->assertDatabaseHas('projects',['title'=>'changed1','description'=>'test','notes'=>'changed']);
		$this->get($project->path())->assertSee('changed');

	}

	/** @test  */
	public function a_project_requires_a_title(){

//		$this->withoutExceptionHandling();


		$this->signIn();

		$attributes = factory('App\Project')->raw(['title' => '']);

		$this->post('projects',$attributes)->assertSessionHasErrors('title');

	}

	/** @test */
	public function a_project_requires_a_description(){

//		$this->withoutExceptionHandling();

		$this->signIn();

		$attributes = factory('App\Project')->raw(['description' => '']);

		$this->post('projects')->assertSessionHasErrors('description');

	}




}
