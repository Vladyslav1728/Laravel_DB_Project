<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Note;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CommentController extends Controller
{
    use AuthorizesRequests;

    // GET /notes/{note}/comments
    public function noteComments(Note $note)
    {
        $this->authorize('viewAny', Comment::class);
        $comments = $note->comments()->with('user:id,name')->get();

        return response()->json(['comments' => $comments], Response::HTTP_OK);
    }

    // GET /tasks/{task}/comments
    public function taskComments(Task $task)
    {
        $this->authorize('viewAny', Comment::class);
        $comments = $task->comments()->with('user:id,name')->get();

        return response()->json(['comments' => $comments], Response::HTTP_OK);
    }

    // POST /notes/{note}/comments
    public function storeForNote(Request $request, Note $note)
    {
        $this->authorize('create', Comment::class);
        $validated = $request->validate(['body' => ['required','string','max:500']]);

        $comment = $note->comments()->create([
            'user_id' => $request->user()->id,
            'body' => $validated['body']
        ]);
        return response()->json(['comment' => $comment], Response::HTTP_CREATED);
    }

    // POST /tasks/{task}/comments
    public function storeForTask(Request $request, Task $task)
    {
        $this->authorize('create', Comment::class);
        $validated = $request->validate(['body' => ['required','string','max:500']]);

        $comment = $task->comments()->create([
            'user_id' => $request->user()->id,
            'body' => $validated['body']
        ]);
        return response()->json(['comment' => $comment], Response::HTTP_CREATED);
    }

    // PATCH /comments/{comment}
    public function update(Request $request, Comment $comment)
    {
        $this->authorize('update', $comment);
        $validated = $request->validate(['body' => ['required','string','max:500']]);

        $comment->update($validated);
        return response()->json(['comment' => $comment], Response::HTTP_OK);
    }

    // DELETE /comments/{comment}
    public function destroy(Comment $comment)
    {
        $this->authorize('delete', $comment);
        $comment->delete();

        return response()->json(['message' => 'Comment deleted'], Response::HTTP_OK);
    }
}
