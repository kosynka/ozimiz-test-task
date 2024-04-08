<?php

namespace App\Http\Controllers;

use App\Http\Requests\Task\StoreRequest;
use App\Http\Requests\Task\UpdateRequest;
use App\Http\Resources\Collections\TaskCollection;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class TaskController extends Controller
{
    public function index()
    {
        $data = Task::where('user_id', auth()->user()->id)->get();

        return response()->json(new TaskCollection($data));
    }

    public function store(StoreRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = auth()->user()->id;

        $task = Task::create($data);

        return response()->json([
            'data' => new TaskResource($task)
        ]);
    }

    public function show(Task $task): JsonResponse
    {
        if ($task->user_id !== auth()->user()->id) {
            return response()->json([
                'message' => 'forbidden',
                'data' => [],
            ], Response::HTTP_FORBIDDEN);
        }

        return response()->json([
            'data' => new TaskResource($task)
        ]);
    }

    public function update(UpdateRequest $request, Task $task)
    {
        if ($task->user_id !== auth()->user()->id) {
            return response()->json([
                'message' => 'forbidden',
                'data' => [],
            ], Response::HTTP_FORBIDDEN);
        }

        $task->update($request->validated());

        return response()->json([
            'data' => new TaskResource($task->refresh())
        ]);
    }

    public function destroy(Task $task)
    {
        if ($task->delete()) {
            return response()->json([
                'message' => 'success',
                'data' => [],
            ], Response::HTTP_OK);
        }
    }
}
