<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use Illuminate\Http\Request;
use App\Http\Requests\CategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\ListCategoryResource;
use App\Http\Resources\DetailCategoryResource;

class CategoryController extends Controller
{
    public function index()
    {
        $indexCategory = Categories::all();
        return ListCategoryResource::collection(Categories::paginate(1));
    }

    public function store(CategoryRequest $request)
    {
        $create = Categories::create($request->validated());
        return response()->json(['Category Created.', new ListCategoryResource($create)]);
    }

    public function show($id)
    {
        $showCategory = Categories::findOrFail($id);
        return new DetailCategoryResource($showCategory);
    }

    public function update(CategoryRequest $request, $id)
    {
        $updateCategory = Categories::findOrFail($id);
        $updateCategory->update($request->validated());
        return response()->json(['Category Updated.', new ListCategoryResource($updateCategory)], 200);
    }

    public function destroy($id)
    {
        $deleteCategory = Categories::findOrFail($id);
        $deleteCategory->delete();

        return response()->json(['Category Deleted.']);
    }
}
