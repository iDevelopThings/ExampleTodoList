<?php

namespace App\Http\Controllers;

use App\Http\Resources\TaskResource;
use App\Models\Task;

class TaskController extends Controller
{
	public function list()
	{
		$tasks = auth()->user()->tasks()->get();

		return TaskResource::collection($tasks);
	}

	public function create()
	{
		$task = Task::create([
			'user_id'     => auth()->id(),
			'title'       => request('title'),
			'description' => request('description'),
			'completed'   => false,
		]);

		return new TaskResource($task);
	}

	public function complete(Task $task)
	{
		if (auth()->id() !== $task->user_id) {
			return response()->json(['message' => 'computer says no.'], 401);
		}

		$task->completed = !$task->completed;
		$task->save();

		return new TaskResource($task);
	}

	public function delete(Task $task)
	{
		$task->delete();

		return response()->json([
			'success' => true,
		]);
	}

}
