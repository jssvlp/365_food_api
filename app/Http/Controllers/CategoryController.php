<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Services\CategoryService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    public function all(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'categories' => (new CategoryService())->getCategories()
        ]);
    }
}
