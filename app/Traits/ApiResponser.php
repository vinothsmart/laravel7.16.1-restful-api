<?php
namespace App\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

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

        $collection = $this->transformData($collection, $transformer);

        return $this->successResponse($collection, $code);
    }

    protected function showOne(Model $model, $code = 200)
    {
        $transformer = $model->transformer;

        $model = $this->transformData($model, $transformer);

        return $this->successResponse($model, $code);
    }

    protected function showList(Collection $collection, $code = 200)
    {
        $transformer = $collection->first()->transformer;

        $collection = $this->transformData($collection, $transformer);

        return $this->successResponse($collection, $code);
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
}
