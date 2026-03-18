<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    /**
     * GET /api/categories
     */
    public function index()
    {
        $categories = DB::table('categories')->get();
        return response()->json(['categories' => $categories], 200);
    }

    /**
     * POST /api/categories
     */
    public function store(Request $request)
    {
        $id = DB::table('categories')->insertGetId([
            'name' => $request->name,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $category = DB::table('categories')->where('id', $id)->first();
        return response()->json(['category' => $category], 201);
    }

    /**
     * GET /api/categories/{id}
     */
    public function show(string $id)
    {
        $category = DB::table('categories')->where('id', $id)->first();
        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }
        return response()->json(['category' => $category], 200);
    }

    /**
     * PUT/PATCH /api/categories/{id}
     */
    public function update(Request $request, string $id)
    {
        DB::table('categories')->where('id', $id)->update([
            'name' => $request->name,
            'updated_at' => now(),
        ]);

        $category = DB::table('categories')->where('id', $id)->first();
        return response()->json(['category' => $category], 200);
    }

    /**
     * DELETE /api/categories/{id}
     */
    public function destroy(string $id)
    {
        DB::table('categories')->where('id', $id)->delete();
        return response()->json(['message' => 'Category deleted'], 200);
    }
}
