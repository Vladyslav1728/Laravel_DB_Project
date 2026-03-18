<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Category;

class CategoryController extends Controller
{
    /**
     * GET /api/categories
     */
    public function index()
    {
        $categories = Category::all();
        return response()->json(['categories' => $categories], 200);
    }

    /**
     * POST /api/categories
     */
    public function store(Request $request)
    {
        $category = Category::create([
            'name' => $request->name
        ]);

        return response()->json(['category' => $category], 201);
    }

    /**
     * GET /api/categories/{id}
     */
    public function show(string $id)
    {
        $category = Category::find($id);
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
        $category = Category::find($id);
        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }
        $category->update(['name' => $request->name]);
        return response()->json(['category' => $category], 200);
    }

    /**
     * DELETE /api/categories/{id}
     */
    public function destroy(string $id)
    {
        $category = Category::find($id);
        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }
        $category->delete();
        return response()->json(['message' => 'Category deleted'], 200);
    }
}
