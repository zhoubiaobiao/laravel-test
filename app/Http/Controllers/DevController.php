<?php

namespace App\Http\Controllers;

use App\Models\ExcuteLog;
use Illuminate\Database\QueryException;
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
     * excute input sql
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function excute(Request $request)
    {
        $startTime = microtime(true);
        $sql = $request->get('sql');

        $log = new ExcuteLog();
        $log->user_id = Auth::id();
        $log->email = Auth::user()->email;
        $log->sql = $sql;
        $log->error = '';

        try {
            // get total sql
            $select = preg_match("|select([^^]*?)from|u", $sql,$result);
            if (empty($select)) {
                return response()->json(['code' => 1, 'error' => 'Only SELECT is allowed']);
            }
            $countSql = str_replace($result[1], ' count(*) as total ', $sql);

            // get page data sql
            $page = $request->get('page') ? : 1;
            $limit = $request->get('limit') ? : 10;
            $sql = $sql . " limit {$limit} offset " . ($page-1) * $limit;

            // get count and data
            $count = DB::select($countSql)[0]->total;
            $list = DB::connection('mysql')->select($sql);

            $log->time = round((microtime(true) - $startTime) * 1000);
            $log->save();

        } catch (QueryException $exception) {
            $log->time = round((microtime(true) - $startTime) * 1000);
            $log->error = $exception->errorInfo[2];
            $log->save();
            return response()->json(['code' => 1, 'error' => $exception->errorInfo[2]]);
        }

        return response()->json(
            [
                'code' => 0,
                'data' => $list,
                'count' => $count,
                'sql' => $request->get('sql')
            ], 200);
    }

    public function export()
    {
        return [];
    }
}
