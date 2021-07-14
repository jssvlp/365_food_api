<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Category;
use App\Services\CategoryService;
use App\Http\Controllers\Controller;
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
