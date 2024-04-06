<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
    /**
     * @param $data
     * @param $request
     * @return array
     * @throws \Exception
     * @author João Pedro B Santos.
     */
    public function validated($data = null, $request = null)
    {
        $validate = Validator::make($request ?? request()->all(), $this->rules ?? $data);
        if ($validate->fails()) {
            $errors = $validate->getMessageBag()->toArray();
            $messages = [];
            foreach ($errors as $key => $error) {
                foreach ($error as $e) {
                    $messages[] = $e;
                }
            }
            $messages = implode(' | ', $messages);
            throw new \Exception('Error: ' . $messages);
        }
        return $validate->getData();
    }

    /**
     * @param $data
     * @param $status
     * @return \Illuminate\Http\JsonResponse
     * @author João Pedro B Santos
     */
    public function setResponse($data, $status = 200)
    {
        return response()->json($data, $status);
    }

    /**
     * @param $exception
     * @param $status
     * @return \Illuminate\Http\JsonResponse
     * @author João Pedro B Santos
     */
    public function setError($exception, $code = 400)
    {
        if ($exception instanceof \Throwable) {
            return response()->json([
                'message' => $exception->getMessage(),
                'code' => $code,
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'trace' => $exception->getTrace(),
            ], $code ?: 400);
        }
        return response()->json([
            'message' => $exception,
        ], $code ?: 400);
    }

    /**
     * @param $items
     * @param int $perPage
     * @param $page
     * @param array $options
     * @return LengthAwarePaginator
     */
    protected function paginate($items, int $perPage = 5, $page = null, array $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);

        $items = $items instanceof Collection ? $items->values() : Collection::make($items)->values();

        return new LengthAwarePaginator($items->forPage($page, $perPage)->values(), $items->count(), $perPage, $page, $options);
    }

}
