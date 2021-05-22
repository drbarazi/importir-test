<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Exceptions\ImportirException;
use App\Models\Item;

class ItemController extends Controller
{
    public function index()
    {
        return response()->json(['status'   => 'success', 
                                'message'   => 'Retrieved successfully', 
                                'data'      => ['items' => Item::all()]
                                ], 200);
    }

    public function store(Request $request)
    {
        $credential = Validator::make($request->all(), [
            'name'          => 'required|string',
            'code'          => 'required|string|unique:items,code',
            'category_id'   => 'required|exists:categories,id'
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
                                    'data'      => ['item' => Item::create($request->all())]
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
        $item = Item::find($id);
        if(!$item){
            return response()->json(['status'   => 'fail', 
                                    'message'   => 'Category not found', 
                                    'data'      => null
                                    ], 404);
        }
        try {
            return response()->json(['status'   => 'success', 
                                    'message'   => 'Retrieved successfully', 
                                    'data'      => ['item' => $item]
                                    ], 200);
        } catch (\Exception $ex) {
            return response()->json(['status'   => 'error', 
                                    'message'   => 'Oops, Something Went Wrong', 
                                    'data'      => null
                                    ], 500);
        }
    }

    public function update(Request $request, item $item)
    {
        $credential = Validator::make($request->all(), [
            'name'          => 'required|string',
            'code'          => 'required|string|unique:items,code,' . $item->id,
            'category_id'   => 'required|exists:categories,id'
        ]);

        if ($credential->fails()) {
            return response()->json(['status'   => 'fail', 
                                    'message'   => $credential->errors(), 
                                    'data'      => null
                                    ], 422);
        }

        try {
            $item->update($request->all());
            return response()->json(['status'   => 'success', 
                                    'message'   => 'Update successfully', 
                                    'data'      => ['item' => $item]
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
        $item = Item::find($id);
        if(!$item){
            return response()->json(['status'   => 'fail', 
                                    'message'   => 'Item not found', 
                                    'data'      => null
                                    ], 404);
        }
        try {
            $item = Item::findOrFail($id);
            $item->delete();
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
