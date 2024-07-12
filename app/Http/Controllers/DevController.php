<?php

namespace App\Http\Controllers;

use App\Models\ExcuteLog;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

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
        if ( ! $sql = $request->get('sql')) {
            return response()->json(['code' => 0, 'data' => [], 'count' => 0]);
        }

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
            $list = DB::select($sql);

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

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|int
     */
    public function export(Request $request)
    {
        try {
            ini_set('memory_limit','2048M');

            if ( ! $sql = $request->get('sql')) {
                throw new \Exception('please input sql');
            }
            // get data
            $list = DB::select($sql);

            // 导出存储路径
            $path = "/app/export/";
            $filePath = $path . date("YmdHis",time()) . '_' . rand(1000,9999);
            $publicPath = $request->getSchemeAndHttpHost() . "/storage" . $filePath;

            if ($request->get('type') == 'json') {
                // export json
                $filePath .= '.txt'; $publicPath .= '.txt';
                Storage::drive('public')->put($filePath, json_encode($list));
            } elseif ($request->get('type') == 'excel') {
                // export excel
                $filePath .= '.csv'; $publicPath .= '.csv';
                $content = "ID,Name,Email,Role,Create Time,Update Time" . PHP_EOL;
                foreach ($list as $item) {
                    $content .= "{$item->id},{$item->name},{$item->email},{$item->role},{$item->created_at},{$item->updated_at}" . PHP_EOL;
                }
                Storage::drive('public')->put($filePath, $content);
            } else {
                throw new \Exception('error export type');
            }

            return response()->json(['code' => 0, 'path' => $publicPath]);

        } catch (\Exception $exception) {
            return response()->json(['code' => 1, 'error' => $exception->getMessage()]);
        }
    }
}
