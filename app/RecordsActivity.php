<?php
/**
 * Created by PhpStorm.
 * User: hp
 * Date: 8/24/2019
 * Time: 12:53 PM
 */

namespace App;

use Illuminate\Support\Arr;


trait RecordsActivity
{

	public $oldAttributes = [];

	/**
	 * Boot the trait
	 */
	public static function bootRecordsActivity(){



		foreach (self::recordableEvents() as $event){

			static::$event(function ($model) use ($event){
				$model->recordActivity($model->activityDescription($event));
			});

			if($event === "updated"){
				static::updating(function ($model){
					$model->oldAttributes = $model->getOriginal();
				});

			}
		}

	}

	protected function activityDescription($description){
		return "$description"."_".strtolower(class_basename($this));//created_task
	}

	/**
	 * @return array
	 */
	public static function recordableEvents(): array
	{
		if (isset(static::$recordableEvents)) {
			return static::$recordableEvents;

		}

		return  ['created', 'updated', 'deleted'];
	}


	/**
	 * Record activity for a project
	 * @param string $description
	 */
	public function recordActivity($decription){

		$this->activity()->create([
			'user_id' =>($this->project ?? $this)->owner->id,
			'description' => $decription,
			'changes' => $this->activityChanges(),
			'project_id' => class_basename($this) === 'Project' ? $this->id : $this->project_id
		]);
	}


	/**
	 * The activity feed for the project
	 * @return mixed
	 */
	public function activity(){
		return $this->morphMany('App\Activity','subject')->latest();
	}


	/**
	 * Fetch the changes to the model
	 * @return array
	 */
	protected function activityChanges(){

		if($this->wasChanged()){
			return [
				'before' => Arr::except(array_diff($this->oldAttributes,$this->getAttributes()),'updated_at'),
				'after' => Arr::except($this->getChanges(),'updated_at')
			];
		}
	}



}