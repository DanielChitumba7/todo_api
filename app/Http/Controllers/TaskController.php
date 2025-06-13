<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Services\TaskService;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Tasks",
 *     description="Tasks management endpoints"
 * )
 */

class TaskController extends Controller

{

        protected $TaskService;

        public function __construct(TaskService $TaskService)
        {
            $this->TaskService = $TaskService;
        }

   /**
     * @OA\Get(
     *     path="/tasks",
     *     tags={"Tasks"},
     *     summary="Listar todas as tarefas do usuário autenticado",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response=200, description="Lista de tarefas retornada com sucesso"),
     *     @OA\Response(response=404, description="Nenhuma tarefa encontrada")
     * )
     */
    public function index()
    {
        $tasks = $this->TaskService->GetAll();
        if ($tasks->isEmpty()) {
            return response()->json(['message' => 'No tasks found'], 404);
        }
        return response()->json($tasks, 200);
    }

    public function create()
    {
        return view('tasks.create');
    }

    /**
     * @OA\Post(
     *     path="/tasks",
     *     tags={"Tasks"},
     *     summary="Criar nova tarefa",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"title", "status"},
     *             @OA\Property(property="title", type="string", example="Comprar pão"),
     *             @OA\Property(property="description", type="string", example="Ir à padaria comprar pão às 7h"),
     *             @OA\Property(property="status", type="string", example="pending")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Tarefa criada com sucesso"),
     *     @OA\Response(response=500, description="Falha ao criar tarefa")
     * )
     */
    public function store(StoreTaskRequest $request)
    {
        $data = $request->validated();
        $task = $this->TaskService->create($data);
        if (!$task) {
            return response()->json(['message' => 'Failed to create task'], 500);
        }
        return response()->json(['message' => 'Task created successfully', 'task' => $task], 201);
    }

    /**
     * @OA\Get(
     *     path="/tasks/{id}",
     *     tags={"Tasks"},
     *     summary="Buscar tarefa pelo ID",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID da tarefa",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(response=200, description="Tarefa encontrada"),
     *     @OA\Response(response=404, description="Tarefa não encontrada")
     * )
     */
    public function show(string $id)
    {
        $task = $this->TaskService->GetById($id);
        if (!$task) {
            return response()->json(['message' => 'Task not found'], 404);
        }
        return response()->json($task);
    }

    public function edit(string $id)
    {
        $task = $this->TaskService->GetById($id);
        if (!$task) {
            return response()->json(['message' => 'Task not found'], 404);
        }
        return view('tasks.edit', compact('task'));
    }

    /**
     * @OA\Put(
     *     path="/tasks/{id}",
     *     tags={"Tasks"},
     *     summary="Atualizar uma tarefa existente",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID da tarefa",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"title", "status"},
     *             @OA\Property(property="title", type="string", example="Atualizar tarefa"),
     *             @OA\Property(property="description", type="string", example="Nova descrição da tarefa"),
     *             @OA\Property(property="status", type="string", enum={"pending", "completed"}, example="completed")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Tarefa atualizada com sucesso"),
     *     @OA\Response(response=500, description="Erro ao atualizar tarefa")
     * )
     */
    public function update(Request $request, string $id)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|string|in:pending,completed',
        ]);

        $task = $this->TaskService->Edit($id, $data);
        if (!$task) {
            return response()->json(['message' => 'Failed to update task'], 500);
        }
        return response()->json(['message' => 'Task updated successfully', 'task' => $task]);
    }

    /**
     * @OA\Delete(
     *     path="/tasks/{id}",
     *     tags={"Tasks"},
     *     summary="Excluir uma tarefa pelo ID",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID da tarefa",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(response=200, description="Tarefa excluída com sucesso"),
     *     @OA\Response(response=500, description="Erro ao excluir tarefa")
     * )
     */
    public function destroy(string $id)
    {
        $task = $this->TaskService->Delete($id);
        if (!$task) {
            return response()->json(['message' => 'Failed to delete task'], 500);
        }
        return response()->json(['message' => 'Task deleted successfully']);
    }

    /**
     * @OA\Get(
     *     path="/tasks/status/{status}",
     *     tags={"Tasks"},
     *     summary="Filtrar tarefas por status",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="status",
     *         in="path",
     *         required=true,
     *         description="Status da tarefa (pending ou completed)",
     *         @OA\Schema(type="string", enum={"pending", "completed"})
     *     ),
     *     @OA\Response(response=200, description="Tarefas com o status especificado"),
     *     @OA\Response(response=404, description="Nenhuma tarefa com esse status")
     * )
     */
    public function filterByStatus(string $status)
    {
        $tasks = $this->TaskService->filterBystatus($status);
        if ($tasks->isEmpty()) {
            return response()->json(['message' => 'No tasks found with the specified status'], 404);
        }
        return response()->json($tasks);
    }
}