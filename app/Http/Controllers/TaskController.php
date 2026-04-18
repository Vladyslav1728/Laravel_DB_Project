<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Note;
use App\Models\Task;
use Illuminate\Http\Response;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class TaskController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of tasks for a given note.
     */
    /*
    public function index(Note $note)
    {
        $tasks = $note->tasks()->get();
        return response()->json(['tasks' => $tasks,], Response::HTTP_OK);
    }
    */
    public function index(Note $note)
    {
        // kto môže vidieť note, môže vidieť aj jej tasky
        $this->authorize('view', $note);

        $tasks = $note->tasks()
            ->orderBy('created_at')
            ->get();

        return response()->json([
            'tasks' => $tasks,
        ], Response::HTTP_OK);
    }
    /**
     * Store a newly created task in storage.
     */
    public function store(Request $request, Note $note)
    {
        $this->authorize('create', [Task::class, $note]);
        // Validacia vstupných údajov
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'is_done' => ['sometimes', 'boolean'],
            'due_at' => ['nullable', 'date'],
        ]);

        $task = $note->tasks()->create($validated);
        return response()->json(['message' => 'Úloha bola úspešne vytvorená.', 'task' => $task,], Response::HTTP_CREATED);
    }

    /**
     * Display the specified task of a note.
     */
    public function show($noteId, $taskId)
    {
        $note = Note::find($noteId);
        if (!$note) {
            return response()->json(['message' => 'Poznámka nenájdená.'], 404);
        }
        $this->authorize('view', $note);
        $task = $note->tasks()->find($taskId);
        if (!$task) {
            return response()->json(['message' => 'Úloha nenájdená.'], 404);
        }
        return response()->json(['task' => $task], 200);
    }

    /**
     * Update the specified task in storage.
     */
    public function update(Request $request, Note $note, Task $task)
    {
        $this->authorize('update', $task);
        if ($task->note_id !== $note->id) {
            return response()->json(['message' => 'Úloha nenájdená.'], Response::HTTP_NOT_FOUND);
        }

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'is_done' => ['sometimes', 'boolean'],
            'due_at' => ['nullable', 'date'],
        ]);

        $task->update($validated);
        return response()->json(['message' => 'Úloha bola úspešne aktualizovaná.', 'task' => $task,], Response::HTTP_OK);
    }

    /**
     * Remove the specified task from storage.
     */
    public function destroy(Note $note, Task $task)
    {
        $this->authorize('delete', $task);
        if ($task->note_id !== $note->id) {
            return response()->json(['message' => 'Úloha nenájdená.'], Response::HTTP_NOT_FOUND);
        }

        $task->delete();
        return response()->json(['message' => 'Úloha bola úspešne zmazaná.'], Response::HTTP_OK);
    }
}
