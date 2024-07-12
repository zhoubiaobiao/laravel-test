<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DevController extends Controller
{
    public function index()
    {
        return view('dev.index', ['sql' => request('sql')]);
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function excute(Request $request)
    {
        $sql = $request->get('sql');

        // get total
        $select = preg_match("|select([^^]*?)from|u", $sql,$result);
        if (empty($select)) {
            return response()->json(['code' => 1, 'error' => 'Only SELECT is allowed']);
        }
        $countSql = str_replace($result[1], ' count(*) as total ', $sql);
        $count = DB::select($countSql)[0]->total;

        // page
        $page = $request->get('page') ? : 1;
        $limit = $request->get('limit') ? : 10;
        $sql = $sql . " limit {$limit} offset " . ($page-1) * $limit;

        // get data
        $list = DB::connection('mysql')->select($sql);

        return response()->json(
            [
                'code' => 0,
                'data' => $list,
                'count' => $count,
                'sql' => $request->get('sql')
            ], 200);
    }
}
