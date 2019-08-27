<?php

namespace Tests\Feature;

use Facades\Tests\Setup\ProjectFactory;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class InvitationsTest extends TestCase
{

	/** @test **/
	public function non_owners_may_not_invites_users(){


		$project = ProjectFactory::create();

		$user =  factory('App\User')->create();

		$assertInvitationForbidden = function() use($user,$project){

			$this->actingAs($user)
				->post($project->path().'/invitations')
				->assertStatus(403);
		};

		$assertInvitationForbidden();

		$project->invite($user);

		$assertInvitationForbidden();
	}


	/** @test **/
	public function a_project_owner_can_invite_a_user(){

		$this->withoutExceptionHandling();

		$project = ProjectFactory::create();

		$userToInvite = factory('App\User')->create();

		$this->actingAs($project->owner)
			->post($project->path().'/invitations',[
			'email' => $userToInvite->email
		])
			->assertRedirect($project->path());

		$this->assertTrue($project->members->contains($userToInvite));


	}

	/** @test **/
	public function the_invited_email_address_must_be_associated_with_a_tasksboard_account(){


		$project = factory('App\Project')->create();

		$this->actingAs($project->owner)
			->post($project->path().'/invitations',[
			'email' => 'notauser@example.com'
		])
			->assertSessionHasErrors([
				'email' => 'The user you are inviting must have a TasksBoard account.'
			],null,'invitations');
	}

    /** @test  **/
    public function invited_users_may_update_project_details(){

    	$project = ProjectFactory::create();

    	$project->invite($newUser = factory('App\User')->create());

    	$this->signIn($newUser);

    	$this->post(action('ProjectTasksController@store',$project),$task = ['body' => 'Test task']);

    	$this->assertDatabaseHas('tasks',$task);



	}

}
