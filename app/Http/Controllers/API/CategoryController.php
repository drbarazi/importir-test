<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Exceptions\ImportirException;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {
        return response()->json(['status'   => 'success', 
                                'message'   => 'Retrieved successfully', 
                                'data'      => ['categories' => Category::all()]
                                ], 200);
    }

    public function store(Request $request)
    {
        $credential = Validator::make($request->all(), [
            'name' => 'required|string|unique:categories,name'
        ]);

        if ($credential->fails()) {
            return response()->json(['status'   => 'fail', 
                                    'message'   => $credential->errors(), 
                                    'data'      => null
                                    ], 422);
        }

        try {
            return response()->json(['status'   => 'success', 
                                    'message'   => 'Created successfully', 
                                    'data'      =>   ['category' => Category::create($request->all())]
                                    ], 201);
        } catch (\Exception $ex) {
            return response()->json(['status'   => 'error', 
                                    'message'   => 'Oops, Something Went Wrong', 
                                    'data'      => null
                                    ], 500);
        }
    }

    public function show($id)
    {
        $category = Category::find($id);
        if(!$category){
            return response()->json(['status'   => 'fail', 
                                    'message'   => 'Category not found', 
                                    'data'      => null
                                    ], 404);
        }
        try {
            return response()->json(['status'   => 'success', 
                                    'message'   => 'Retrieved successfully', 
                                    'data'      => ['category' => $category]
                                    ], 200);
        } catch (\Exception $ex) {
            return response()->json(['status'   => 'error', 
                                    'message'   => 'Oops, Something Went Wrong', 
                                    'data'      => null
                                    ], 500);
        }
    }

    public function update(Request $request, Category $category)
    {
        $credential = Validator::make($request->all(), [
            'name' => 'required|string|unique:categories,name,' . $category->id
        ]);

        if ($credential->fails()) {
            return response()->json(['status'   => 'fail', 
                                    'message'   => $credential->errors(), 
                                    'data'      => null
                                    ], 422);
        }

        try {
            $category->update($request->all());
            return response()->json(['status'   => 'success', 
                                    'message'   => 'Update successfully', 
                                    'data'      => ['category' => $category]
                                    ], 201);
        } catch (\Exception $ex) {
            return response()->json(['status'   => 'error', 
                                    'message'   => 'Oops, Something Went Wrong', 
                                    'data'      => null
                                    ], 500);
        }    
    }

    public function destroy($id)
    {
        $category = Category::find($id);
        if(!$category){
            return response()->json(['status'   => 'fail', 
                                    'message'   => 'Category not found', 
                                    'data'      => null
                                    ], 404);
        }
        try {
            $category = Category::findOrFail($id);
            $category->delete();
            return response()->json(['status'   => 'success', 
                                    'message'   => 'Deleted successfully', 
                                    'data'      => null
                                    ], 200);
        } catch (\Exception $ex) {
            return response()->json(['status'   => 'error', 
                                    'message'   => 'Oops, Something Went Wrong', 
                                    'data'      => null
                                    ], 500);
        }
    }
}
