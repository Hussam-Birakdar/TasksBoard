<?php

namespace Tests\Unit;

use Facades\Tests\Setup\ProjectFactory;
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

	/** @test */
	public function a_user_has_accessible_projects(){

		$sami = $this->signIn();

		ProjectFactory::ownedBy($sami)->create();

		$this->assertCount(1,$sami->accessibleProjects());

		$amer = factory('App\User')->create();
		$ahmed = factory('App\User')->create();


		$project = tap(ProjectFactory::ownedBy($amer)->create())->invite($ahmed);
		$project->invite($ahmed);

		$this->assertCount(1,$sami->accessibleProjects());

		$project->invite($sami);

		$this->assertCount(2,$sami->accessibleProjects());

	}

}
