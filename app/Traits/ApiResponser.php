<?php
namespace App\Traits;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Jenssegers\Agent\Agent;

trait ApiResponser
{
    private function successResponse($data, $code)
    {
        return response()->json($data, $code);
    }

    protected function errorResponse($message, $code)
    {
        return response()->json(['error' => $message, 'code' => $code], $code);
    }

    protected function showAll(Collection $collection, $code = 200)
    {
        if ($collection->isEmpty()) {
            return $this->successResponse(['data' => $collection], $code);
        }

        $transformer = $collection->first()->transformer;

        $collection = $this->sortData($collection, $transformer);

        $collection = $this->filterData($collection, $transformer);

        $collection = $this->paginate($collection);

        // $collection = $this->cacheResponse($collection);

        $collection = $this->transformData($collection, $transformer);

        return $this->successResponse($collection, $code);
    }

    protected function showOne(Model $model, $code = 200)
    {
        $transformer = $model->transformer;

        $model = $this->transformData($model, $transformer);

        return $this->successResponse($model, $code);
    }

    protected function showList(Collection $collection, $transformId, $transformName, $hashingModel, $code = 200)
    {
        foreach ($collection as $collect) {
            $collectist[] = [
                $transformId => \Hashids::connection($hashingModel)->encode($collect->id),
                $transformName => $collect->role,
            ];
        }
        $data = ["data" => $collectist];
        return $this->successResponse($data, $code);
    }

    protected function showMessage($message, $code = 200)
    {
        return $this->successResponse(['data' => $message], $code);
    }

    protected function transformData($data, $transformer)
    {
        $transformation = fractal($data, new $transformer);

        return $transformation->toArray();
    }

    protected function sortData(Collection $collection, $transformer)
    {
        if (request()->has('sort_by')) {
            $attribute = $transformer::originalAttribute(request()->sort_by);

            // Added for asc and desc
            if (request()->has('order_by') && request()->order_by === 'desc') {
                $collection = $collection->sortByDesc->$attribute;
            } else {
                $collection = $collection->sortBy->$attribute;
            }
        }

        return $collection;
    }

    protected function filterData(Collection $collection, $transformer)
    {
        foreach (request()->query() as $query => $value) {
            $attribute = $transformer::originalAttribute($query);

            if (isset($attribute, $value)) {
                $collection = $collection->where($attribute, $value);
            }
        }

        return $collection;
    }

    protected function paginate(Collection $collection)
    {
        $page = LengthAwarePaginator::resolveCurrentPage();

        $perPage = 15;
        if (request()->has('per_page')) {
            $perPage = (int) request()->per_page;
        }

        $results = $collection->slice(($page - 1) * $perPage, $perPage)->values();

        $paginated = new LengthAwarePaginator($results, $collection->count(), $perPage, $page, [
            'path' => LengthAwarePaginator::resolveCurrentPath(),
        ]);

        $paginated->appends(request()->all());

        return $paginated;
    }

    protected function cacheResponse($data)
    {
        $url = request()->url();
        $queryParams = request()->query();

        ksort($queryParams);

        $queryString = http_build_query($queryParams);

        $fullUrl = "{$url}?{$queryString}";

        return Cache::remember($fullUrl, Carbon::now()->addMinutes(5), function () use ($data) {
            return $data;
        });
    }

    protected function applicationDetector()
    {
        $agent = new Agent();
        $platform = $agent->platform();
        $browser = $agent->browser();
        $version = $agent->version($platform);
        $device = $agent->device();

        $clientDetials = array(
            'UserId' => '',
            'UserName' => '',
            'Date' => date('Y-m-d G:i:s'),
            'Os' => $platform,
            'OsVersion' => $version,
            'Browser' => $browser,
            'Device' => $device,
        );
        return json_encode($clientDetials);
    }
}
