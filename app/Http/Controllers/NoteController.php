<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

use App\Models\Note;

class NoteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    /* --OLD--
    public function index()
    {
        $notes = DB::table('notes')
            ->whereNull('deleted_at')
            ->orderBy('updated_at', 'desc')
            ->get();
        return response()->json(['notes' => $notes], Response::HTTP_OK);
    }
    */
    public function index()
    {
        $notes = Note::query()
            ->select(['id', 'user_id', 'title', 'body', 'status', 'is_pinned', 'created_at'])
            ->with([
                'user:id,name',
                'categories:id,name,color',
            ])
            ->orderByDesc('is_pinned')
            ->orderByDesc('created_at')
            ->get();

        return response()->json(['notes' => $notes,], Response::HTTP_OK);
    }
    /**
     * Store a newly created resource in storage.
     */
    /* -- OLD --
    public function store(Request $request)
    {
        $note = Note::create([
            'user_id' => $request->user_id,
            'title' => $request->title,
            'body' => $request->body,
        ]);
        return response()->json([
            'message' => 'Poznámka bola úspešne vytvorená.',
            'note' => $note,
        ], Response::HTTP_CREATED);
    }
    */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'user_id' => ['required', 'integer', 'exists:users,id'],
                'title' => ['required', 'string', 'min:3', 'max:255'],
                'body'  => ['nullable', 'string'],
                'status' => ['sometimes', 'required', 'string', Rule::in(['draft', 'published', 'archived'])],
                'is_pinned' => ['sometimes', 'boolean'],
                'categories' => ['sometimes', 'array', 'max:3'],
                'categories.*' => ['integer', 'distinct', 'exists:categories,id'],
            ]);

            $note = Note::create([
                'user_id'   => $validated['user_id'],
                'title'     => $validated['title'],
                'body'      => $validated['body'] ?? null,
                'status'    => $validated['status'] ?? 'draft',
                'is_pinned' => $validated['is_pinned'] ?? false,
            ]);

            if (!empty($validated['categories'])) {
                $note->categories()->sync($validated['categories']);
            }

            return response()->json([
                'message' => 'Poznámka bola úspešne vytvorená.',
                'note' => $note->load([
                    'user:id,name',
                    'categories:id,name,color',
                ]),
            ], Response::HTTP_CREATED);

        } catch (\Illuminate\Validation\ValidationException $ve) {
            // Возврат ошибок валидации
            return response()->json([
                'message' => 'Ошибка валидации',
                'errors' => $ve->errors(),
            ], 422);
        } catch (\Exception $e) {
            // Любые другие ошибки
            return response()->json([
                'message' => 'Произошла ошибка на сервере',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    /**
     * Display the specified resource.
     */
    /* --OLD--
    public function show(string $id)
    {
        $note = DB::table('notes')
            ->whereNull('deleted_at')
            ->where('id', $id)
            ->first();

        if (!$note) {
            return response()->json([
                'message' => 'Poznámka nenájdená.'
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'note' => $note
        ], Response::HTTP_OK);
    }
    */
    public function show(string $id)
    {
        $note = Note::find($id);

        if (!$note) {
            return response()->json(['message' => 'Poznámka nenájdená.'], Response::HTTP_NOT_FOUND);
        }

        return response()->json(['note' => $note], Response::HTTP_OK);
    }
    /**
     * Update the specified resource in storage.
     */
    /* --OLD--
    public function update(Request $request, string $id)
    {
        $note = Note::find($id);
        if (!$note) {
            return response()->json(['message' => 'Poznámka nenájdená.'], Response::HTTP_NOT_FOUND);
        }
        $note->update([
            'title' => $request->title,
            'body' => $request->body,
        ]);
        return response()->json(['note' => $note], Response::HTTP_OK);
    }
    */
    public function update(Request $request, string $id)
    {
        try {
            $note = Note::find($id);

            if (!$note) {
                return response()->json(
                    ['message' => 'Poznámka nenájdená.'],
                    Response::HTTP_NOT_FOUND
                );
            }

            $validated = $request->validate([
                'title' => ['required', 'string', 'max:255'],
                'body'  => ['nullable', 'string'],
                'status' => ['sometimes', 'required', 'string', Rule::in(['draft', 'published', 'archived'])],
                'is_pinned' => ['sometimes', 'boolean'],
                'categories' => ['sometimes', 'array'],
                'categories.*' => ['integer', 'distinct', 'exists:categories,id'],
            ]);

            // обновляем только валидированные поля
            $note->update($validated);

            // синхронизируем связи с категориями, если переданы
            if (array_key_exists('categories', $validated)) {
                $note->categories()->sync($validated['categories']);
            }

            return response()->json([
                'message' => 'Poznámka bola aktualizovaná.',
                'note' => $note->load([
                    'user:id,name',
                    'categories:id,name,color',
                ]),
            ], Response::HTTP_OK);

        } catch (\Illuminate\Validation\ValidationException $ve) {
            return response()->json([
                'message' => 'Ошибка валидации',
                'errors' => $ve->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Произошла ошибка на сервере',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    /**
     * Remove the specified resource from storage.
     */

    /* --OLD--
    public function destroy(string $id) // toto je soft delete
    {
        $note = DB::table('notes')
            ->whereNull('deleted_at')
            ->where('id', $id)
            ->first();

        if (!$note) {
            return response()->json(['message' => 'Poznámka nenájdená.'], Response::HTTP_NOT_FOUND);
        }

        DB::table('notes')->where('id', $id)->update([
            'deleted_at' => now(),
            'updated_at' => now(),
        ]);

        // DB::table('notes')->where('id', $id)->delete();

        return response()->json(['message' => 'Poznámka bola úspešne odstránená.'], Response::HTTP_OK);
    }
    */
    public function destroy(string $id)
    {
        $note = Note::find($id);

        if (!$note) {
            return response()->json(['message' => 'Poznámka nenájdená.'], Response::HTTP_NOT_FOUND);
        }

        $note->delete(); // soft delete

        return response()->json(['message' => 'Poznámka bola úspešne odstránená.'], Response::HTTP_OK);
    }
    /* --OLD--
    public function statsByStatus()
    {
        $stats = DB::table('notes')
            ->select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->get();

        return response()->json([
            'stats' => $stats
        ]);
    }
    */
    public function statsByStatus()
    {
        $stats = Note::query()
            //->whereNull('deleted_at')
            ->select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->orderBy('status')
            ->get();

        return response()->json(['stats' => $stats], Response::HTTP_OK);
    }
    /* --OLD--
    public function archiveOldDrafts()
    {
        $affected = DB::table('notes')
            ->where('status', 'draft')
            ->where('updated_at', '<', now()->subDays(30))
            ->update([
                'status' => 'archived',
                'updated_at' => now(),
            ]);

        return response()->json([
            'message' => 'Staré koncepty boli archivované.',
            'affected_rows' => $affected
        ]);
    }
    */
    public function archiveOldDrafts()
    {
        $affected = Note::query()
            //->whereNull('deleted_at')
            ->where('status', 'draft')
            ->where('updated_at', '<', now()->subDays(30))
            ->update([
                'status' => 'archived',
                'updated_at' => now(),
            ]);

        return response()->json([
            'message' => 'Staré koncepty boli archivované.',
            'affected_rows' => $affected,
        ]);
    }
    /* --OLD--
    public function userNotesWithCategories(string $userId)
    {
        $notes = DB::table('notes')
            ->join('note_category', 'notes.id', '=', 'note_category.note_id')
            ->join('categories', 'note_category.category_id', '=', 'categories.id')
            ->where('notes.user_id', $userId)
            ->orderBy('notes.updated_at', 'desc')
            ->select('notes.id', 'notes.title', 'categories.name as category')
            ->get();

        return response()->json([
            'notes' => $notes
        ]);
    }
    */
    public function userNotesWithCategories(string $userId)
    {
        $rows = Note::query()
            ->join('note_category', 'notes.id', '=', 'note_category.note_id')
            ->join('categories', 'note_category.category_id', '=', 'categories.id')
            ->where('notes.user_id', $userId)
            //->whereNull('notes.deleted_at')
            ->orderBy('notes.updated_at', 'desc')
            ->select('notes.id', 'notes.title', 'categories.name as category')
            ->get();

        return response()->json(['notes' => $rows], Response::HTTP_OK);
    }
    /* --OLD--
    public function search(Request $request)
    {
        $q = trim((string) $request->query('q', ''));

        $notes = DB::table('notes')
            ->whereNull('deleted_at')
            ->where('status', 'published')
            ->where(function ($x) use ($q) {
                $x->where('title', 'like', "%{$q}%")
                    ->orWhere('body', 'like', "%{$q}%");
            })
            ->orderBy('updated_at', 'desc')
            ->limit(20)
            ->get();

        return response()->json([
            'query' => $q,
            'notes' => $notes,
        ], Response::HTTP_OK);
    }
    */
    // ORM
    public function search(Request $request)
    {
        $q = trim((string) $request->query('q', ''));
        $notes = Note::searchPublished($q);
        return response()->json(['query' => $q, 'notes' => $notes], Response::HTTP_OK);
    }
    public function pinnedNotes()
    {
        $notes = Note::query()
            ->where('is_pinned', 1)
            //->whereNull('deleted_at')
            ->get();

        return response()->json(['notes' => $notes], Response::HTTP_OK);
    }

    // PATCH /api/notes/{id}/pin
    public function pin(string $id)
    {
        $note = Note::find($id);
        if (!$note) {
            return response()->json(['message' => 'Poznamka nenajdena.'], 404);
        }
        $note->pin();
        return response()->json(['note' => $note, 'message' => 'Poznamka pripnuta'], 200);
    }

    // PATCH /api/notes/{id}/unpin
    public function unpin(string $id)
    {
        $note = Note::find($id);
        if (!$note) {
            return response()->json(['message' => 'Poznamka nenajdena.'], 404);
        }
        $note->unpin();
        return response()->json(['note' => $note, 'message' => 'Poznamka odopnuta'], 200);
    }

    // PATCH /api/notes/{id}/publish
    public function publish(string $id)
    {
        $note = Note::find($id);
        if (!$note) {
            return response()->json(['message' => 'Poznamka nenajdena.'], 404);
        }
        $note->publish();
        return response()->json(['note' => $note, 'message' => 'Poznamka publikovana'], 200);
    }

    // PATCH /api/notes/{id}/archive
    public function archive(string $id)
    {
        $note = Note::find($id);
        if (!$note) {
            return response()->json(['message' => 'Poznamka nenajdena.'], 404);
        }
        $note->archive();
        return response()->json(['note' => $note, 'message' => 'Poznamka archivovana'], 200);
    }

    // GET /api/users/{id}/latest-notes
    public function latestUserNotes(string $userId)
    {
        $notes = Note::latestByUser($userId);
        return response()->json(['notes' => $notes], Response::HTTP_OK);
    }
}
