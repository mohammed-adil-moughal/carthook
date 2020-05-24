<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

use App\Cache;

use DateTime;
use DateInterval;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * @param string $tableName
     * @return Cache $cache
     */
    protected function resetCache($tableName)
    {
        $dateTimeObj= new DateTime();
        $dateTimeObj->add(new DateInterval("PT1H"));

        $cache = Cache::where('table_name', $tableName)->first();
        if (!$cache) {
            $cache = new Cache;
        }

        $cache->table_name = $tableName;
        $cache->time_to_live = $dateTimeObj;
        $cache->save();

        return $cache;
    }

    /**
     * @param string $tableName
     * @return bool
     */
    protected function checkExpCache($tableName)
    {
        $cache = Cache::where('table_name', $tableName)->first();
        if (!$cache) {
            $cache = $this->resetCache($tableName);
        }

        $currentDateTime = new DateTime();
        $currentDateTime->format("Y-m-d H:i:s");

        $prevDateTime = new DateTime($cache->time_to_live);
        $prevDateTime->format("Y-m-d H:i:s");

        return $cache->time_to_live < $currentDateTime ? true : false;
    }
}
