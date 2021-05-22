<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ReportController extends Controller
{
    public function stock(Request $request)
    {
        $credential = Validator::make($request->all(), [
            'type'      => 'required|string',
            'date'    => 'required|date_format:Y-m-d'
        ]);

        if ($credential->fails()) {
            return response()->json(['status'   => 'fail', 
                                    'message'   => $credential->errors(), 
                                    'data'      => null
                                    ], 422);
        }

        $date = Carbon::parse($request->date);
        $filter = DB::raw("((SELECT IFNULL(SUM(tr.quantity), 0) 
                            FROM transactions tr
                            WHERE tr.item_id = it.id AND tr.entry_date = '$request->date' AND tr.status=1
                            ) -
                            (SELECT IFNULL(SUM(tr.quantity), 0)
                            FROM transactions tr
                            WHERE tr.item_id = it.id AND tr.entry_date = '$request->date' AND tr.status=0
                            )) as stock
                        ");
        if ($request->type == 'week' || $request->type == 'month' || $request->type === 'year') {
            $startDate = $date->subWeek()->format('Y-m-d');
            if($request->type == 'month'){
                $startDate = $date->subMonth()->format('Y-m-d');
            } else if ($request->type === 'year') {
                $startDate = $date->subYear()->format('Y-m-d');
            } 
            $filter = DB::raw("((SELECT IFNULL(SUM(tr.quantity), 0)
                                FROM transactions tr
                                WHERE tr.item_id = it.id 
                                AND tr.status=1 
                                AND (tr.entry_date BETWEEN '$startDate' AND '$request->date')) -
                                (SELECT IFNULL(SUM(tr.quantity), 0)
                                FROM transactions tr
                                WHERE tr.item_id = it.id 
                                AND tr.status=0 
                                AND (tr.entry_date BETWEEN '$startDate' AND '$request->date'))
                                ) as stock
                            ");
        } 

        $stocks = DB::table('items AS it')
            ->select(
                'it.id',
                DB::raw("(SELECT name FROM categories WHERE id=it.category_id) AS category"),
                'it.code',
                'it.name',
                $filter,
            )->get();
        return response()->json(['status'   => 'success', 
                                'message'   => 'Showing data item', 
                                'data'      => ['items' => $stocks]
                                ], 200);
    }

    public function incoming(Request $request)
    {
        $credential = Validator::make($request->all(), [
            'type'      => 'required|string',
            'date'    => 'required|date_format:Y-m-d'
        ]);

        if ($credential->fails()) {
            return response()->json(['status'   => 'fail', 
                                    'message'   => $credential->errors(), 
                                    'data'      => null
                                    ], 422);
        }

        $date = Carbon::parse($request->date);
        $filter = DB::raw("(SELECT IFNULL(SUM(tr.quantity), 0) 
                            FROM transactions tr
                            WHERE tr.item_id = it.id AND tr.entry_date = '$request->date' AND tr.status=1
                            ) as item_in
                        ");
        if ($request->type == 'week' || $request->type == 'month' || $request->type === 'year') {
            $startDate = $date->subWeek()->format('Y-m-d');
            if($request->type == 'month'){
                $startDate = $date->subMonth()->format('Y-m-d');
            } else if ($request->type === 'year') {
                $startDate = $date->subYear()->format('Y-m-d');
            } 
            $filter = DB::raw("(SELECT IFNULL(SUM(tr.quantity), 0)
                                FROM transactions tr
                                WHERE tr.item_id = it.id 
                                AND tr.status=1 
                                AND (tr.entry_date BETWEEN '$startDate' AND '$request->date')
                                ) as item_in
                            ");
        } 

        $stocks = DB::table('items AS it')
            ->select(
                'it.id',
                DB::raw("(SELECT name FROM categories WHERE id=it.category_id) AS category"),
                'it.code',
                'it.name',
                $filter,
            )->get();
        return response()->json(['status'   => 'success', 
                                'message'   => 'Showing data item in', 
                                'data'      => ['items' => $stocks]
                                ], 200);
    }

    public function outcoming(Request $request)
    {
        $credential = Validator::make($request->all(), [
            'type'      => 'required|string',
            'date'    => 'required|date_format:Y-m-d'
        ]);

        if ($credential->fails()) {
            return response()->json(['status'   => 'fail', 
                                    'message'   => $credential->errors(), 
                                    'data'      => null
                                    ], 422);
        }

        $date = Carbon::parse($request->date);
        $filter = DB::raw("(SELECT IFNULL(SUM(tr.quantity), 0)
                            FROM transactions tr
                            WHERE tr.item_id = it.id AND tr.entry_date = '$request->date' AND tr.status=0
                            ) as item_out
                        ");
        if ($request->type == 'week' || $request->type == 'month' || $request->type === 'year') {
            $startDate = $date->subWeek()->format('Y-m-d');
            if($request->type == 'month'){
                $startDate = $date->subMonth()->format('Y-m-d');
            } else if ($request->type === 'year') {
                $startDate = $date->subYear()->format('Y-m-d');
            } 
            $filter = DB::raw("(SELECT IFNULL(SUM(tr.quantity), 0)
                                FROM transactions tr
                                WHERE tr.item_id = it.id 
                                AND tr.status=0 
                                AND (tr.entry_date BETWEEN '$startDate' AND '$request->date')
                                ) as item_out
                            ");
        } 

        $stocks = DB::table('items AS it')
            ->select(
                'it.id',
                DB::raw("(SELECT name FROM categories WHERE id=it.category_id) AS category"),
                'it.code',
                'it.name',
                $filter,
            )->get();
        return response()->json(['status'   => 'success', 
                                'message'   => 'Showing data item out', 
                                'data'      => ['items' => $stocks]
                                ], 200);
    }
}
