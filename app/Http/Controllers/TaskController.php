<?php

namespace App\Http\Controllers;

use App\Http\Requests\Task\IndexRequest;
use App\Http\Requests\Task\StoreRequest;
use App\Http\Requests\Task\UpdateRequest;
use App\Http\Resources\Collections\TaskCollection;
use App\Http\Resources\TaskResource;
use App\Services\TaskService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class TaskController extends Controller
{
    public function __construct(private TaskService $service) {}

    /**
     * @OA\Get(
     *     path="/tasks",
     *     operationId="index",
     *     summary="List tasks",
     *     tags={"tasks"},
     *     description="Get paginated list of tasks",
     *     security={{"apiAuth": {} }},
     * 
     *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         description="Filterable statuses: new, canceled, completed",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *             default="new"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Page number",
     *         required=false,
     *         @OA\Schema(
     *             type="integer",
     *             default=1
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Items per page",
     *         required=false,
     *         @OA\Schema(
     *             type="integer",
     *             default=15
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="sort_by",
     *         in="query",
     *         description="Sortable fields: id, status, created_at, updated_at",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *             default="id"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="sort",
     *         in="query",
     *         description="Sort type: asc, desc",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *             default="asc"
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="HTTP_OK",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="number", default="1"),
     *                     @OA\Property(property="title", type="string", default="test"),
     *                     @OA\Property(property="description", type="string", default="test"),
     *                     @OA\Property(property="status", type="string", default="new"),
     *                     @OA\Property(property="image", type="string", default="http://127.0.0.1:8000/storage/task_image_6614856520a32_2024_04_09_12_01_41.png"),
     *                     @OA\Property(property="created_at", type="date", default="2024-04-08T23:51:10.000000Z"),
     *                     @OA\Property(property="updated_at", type="date", default="2024-04-08T23:51:10.000000Z"),
     *                 ),
     *             ),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="HTTP_BAD_REQUEST",
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="HTTP_UNAUTHORIZED",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="HTTP_NOT_FOUND",
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="HTTP_INTERNAL_SERVER_ERROR",
     *     ),
     * )
     */
    public function index(IndexRequest $request)
    {
        $data = $this->service->index($request->validated());

        return response()->json(new TaskCollection($data));
    }

    /**
     * @OA\Post(
     *     path="/tasks",
     *     operationId="store",
     *     summary="Store task",
     *     tags={"tasks"},
     *     description="Store new task",
     *     security={{"apiAuth": {} }},
     * 
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"title", "description"},
     *                 @OA\Property(property="title", type="string", default="title"),
     *                 @OA\Property(property="description", type="string", default="description"),
     *                 @OA\Property(property="image", type="file"),
     *             ),
     *         ),
     *     ),
     * 
     *     @OA\Response(
     *         response=200,
     *         description="HTTP_OK",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="data",
     *                 @OA\Property(property="id", type="number", default="1"),
     *                 @OA\Property(property="title", type="string", default="test"),
     *                 @OA\Property(property="description", type="string", default="test"),
     *                 @OA\Property(property="status", type="string", default="new"),
     *                 @OA\Property(property="image", type="string", default="http://127.0.0.1:8000/storage/task_image_6614856520a32_2024_04_09_12_01_41.png"),
     *                 @OA\Property(property="created_at", type="date", default="2024-04-08T23:51:10.000000Z"),
     *                 @OA\Property(property="updated_at", type="date", default="2024-04-08T23:51:10.000000Z"),
     *             ),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="HTTP_BAD_REQUEST",
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="HTTP_UNAUTHORIZED",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="HTTP_NOT_FOUND",
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="HTTP_INTERNAL_SERVER_ERROR",
     *     ),
     * )
     */
    public function store(StoreRequest $request)
    {
        $task = $this->service->store($request->validated());

        return response()->json(['data' => new TaskResource($task)]);
    }

    /**
     * @OA\Get(
     *     path="/tasks/{id}",
     *     operationId="show",
     *     summary="Show task",
     *     tags={"tasks"},
     *     description="Get one task",
     *     security={{"apiAuth": {} }},
     * 
     *     @OA\Parameter(
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *     ),
     * 
     *     @OA\Response(
     *         response=200,
     *         description="HTTP_OK",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="data",
     *                 @OA\Property(property="id", type="number", default="1"),
     *                 @OA\Property(property="title", type="string", default="test"),
     *                 @OA\Property(property="description", type="string", default="test"),
     *                 @OA\Property(property="status", type="string", default="new"),
     *                 @OA\Property(property="image", type="string", default="http://127.0.0.1:8000/storage/task_image_6614856520a32_2024_04_09_12_01_41.png"),
     *                 @OA\Property(property="created_at", type="date", default="2024-04-08T23:51:10.000000Z"),
     *                 @OA\Property(property="updated_at", type="date", default="2024-04-08T23:51:10.000000Z"),
     *             ),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="HTTP_BAD_REQUEST",
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="HTTP_UNAUTHORIZED",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="HTTP_NOT_FOUND",
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="HTTP_INTERNAL_SERVER_ERROR",
     *     ),
     * )
     */
    public function show(int $id): JsonResponse
    {
        $task = $this->service->find($id);

        if ($task->user_id !== auth()->user()->id) {
            return response()->json([
                'message' => 'forbidden',
                'data' => [],
            ], Response::HTTP_FORBIDDEN);
        }

        return response()->json(['data' => new TaskResource($task)]);
    }

    /**
     * @OA\Post(
     *     path="/tasks/{id}",
     *     operationId="update",
     *     summary="update task",
     *     tags={"tasks"},
     *     description="update task",
     *     security={{"apiAuth": {} }},
     * 
     *     @OA\Parameter(
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *     ),
     * 
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(property="title", type="string", default="title"),
     *                 @OA\Property(property="description", type="string", default="description"),
     *                 @OA\Property(property="image", type="file"),
     *             ),
     *         ),
     *     ),
     * 
     *     @OA\Response(
     *         response=200,
     *         description="HTTP_OK",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="data",
     *                 @OA\Property(property="id", type="number", default="1"),
     *                 @OA\Property(property="title", type="string", default="test"),
     *                 @OA\Property(property="description", type="string", default="test"),
     *                 @OA\Property(property="status", type="string", default="new"),
     *                 @OA\Property(property="image", type="string", default="http://127.0.0.1:8000/storage/task_image_6614856520a32_2024_04_09_12_01_41.png"),
     *                 @OA\Property(property="created_at", type="date", default="2024-04-08T23:51:10.000000Z"),
     *                 @OA\Property(property="updated_at", type="date", default="2024-04-08T23:51:10.000000Z"),
     *             ),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="HTTP_BAD_REQUEST",
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="HTTP_UNAUTHORIZED",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="HTTP_NOT_FOUND",
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="HTTP_INTERNAL_SERVER_ERROR",
     *     ),
     * )
     */
    public function update(UpdateRequest $request, int $id)
    {
        $task = $this->service->find($id);

        if ($task->user_id !== auth()->user()->id) {
            return response()->json([
                'message' => 'forbidden',
                'data' => [],
            ], Response::HTTP_FORBIDDEN);
        }

        $this->service->update($task, $request->validated());

        return response()->json(['data' => new TaskResource($task->refresh())]);
    }

    /**
     * @OA\Delete(
     *     path="/tasks/{id}",
     *     operationId="delete",
     *     summary="delete task",
     *     tags={"tasks"},
     *     description="delete task",
     *     security={{"apiAuth": {} }},
     * 
     *     @OA\Parameter(
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *     ),
     * 
     *     @OA\Response(
     *         response=200,
     *         description="HTTP_OK",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", default="[]"),
     *             @OA\Property(property="message", default="success"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="HTTP_BAD_REQUEST",
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="HTTP_UNAUTHORIZED",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="HTTP_NOT_FOUND",
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="HTTP_INTERNAL_SERVER_ERROR",
     *     ),
     * )
     */
    public function destroy(int $id)
    {
        $task = $this->service->find($id);

        if ($task->user_id !== auth()->user()->id) {
            return response()->json([
                'message' => 'forbidden',
                'data' => [],
            ], Response::HTTP_FORBIDDEN);
        }

        if ($this->service->delete($task)) {
            return response()->json([
                'message' => 'success',
                'data' => [],
            ], Response::HTTP_OK);
        }
    }
}
