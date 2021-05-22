<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Exceptions\ImportirException;
use App\Models\Transaction;

class TransactionController extends Controller
{
    public function in(Request $request)
    {
        $credential = Validator::make($request->all(), [
            'item_id'       => 'required|exists:items,id',
            'quantity'      => 'required|numeric',
            'entry_date'    => 'required|date_format:Y-m-d'
        ]);

        if ($credential->fails()) {
            return response()->json(['status'   => 'fail', 
                                    'message'   => $credential->errors(), 
                                    'data'      => null
                                    ], 422);
        }

        try {
            $data = new Transaction();
            $data->item_id      = $request->item_id;
            $data->quantity     = $request->quantity;
            $data->entry_date   = $request->entry_date;
            $data->status       = 1;
            $data->save();

            return response()->json(['status'   => 'success', 
                                    'message'   => 'Incoming item created successfully', 
                                    'data'      => ['transaction' => $data]
                                    ], 201);
        } catch (\Exception $ex) {
            return response()->json(['status'   => 'error', 
                                    'message'   => 'Oops, Something Went Wrong', 
                                    'data'      => null
                                    ], 500);
        }
    }

    public function out(Request $request)
    {
        $credential = Validator::make($request->all(), [
            'item_id'       => 'required|exists:items,id',
            'quantity'      => 'required|numeric',
            'entry_date'    => 'required|date_format:Y-m-d'
        ]);

        if ($credential->fails()) {
            return response()->json(['status'   => 'fail', 
                                    'message'   => $credential->errors(), 
                                    'data'      => null
                                    ], 422);
        }

        try {
            $totalItemIn = Transaction::where('status', 1)->where('item_id', $request->item_id)->sum('quantity');
            $totalItemOut = Transaction::where('status', 0)->where('item_id', $request->item_id)->sum('quantity');
            $totalItem = $totalItemIn - $totalItemOut;
            if($totalItem < $request->quantity){
                return response()->json(['status'   => 'success', 
                                        'message'   => "Quantity must be smaller than $totalItem", 
                                        'data'      => null
                                        ], 422);
            }
            $data = new Transaction();
            $data->item_id      = $request->item_id;
            $data->quantity     = $request->quantity;
            $data->entry_date   = $request->entry_date;
            $data->status       = 0;
            $data->save();

            return response()->json(['status'   => 'success', 
                                    'message'   => 'Outcoming item created successfully', 
                                    'data'      => ['transaction' => $data]
                                    ], 201);
        } catch (\Exception $ex) {
            return response()->json(['status'   => 'error', 
                                    'message'   => 'Oops, Something Went Wrong', 
                                    'data'      => null
                                    ], 500);
        }
    }
}
