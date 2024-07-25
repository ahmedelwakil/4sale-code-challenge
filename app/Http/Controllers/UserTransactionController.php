<?php

namespace App\Http\Controllers;

use App\Services\UserTransactionService;
use App\Utils\DataProviderUtil;
use App\Utils\HttpStatusCodeUtil;
use App\Utils\UserTransactionStatusUtil;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserTransactionController extends Controller
{
    protected $userTransactionService;

    public function __construct(UserTransactionService $userTransactionService)
    {
        $this->userTransactionService = $userTransactionService;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'provider' => ['sometimes', 'string', 'in:' . implode(',', DataProviderUtil::getAllProviders())],
            'statusCode' => ['sometimes', 'string', 'in:' . implode(',', UserTransactionStatusUtil::getAllStatuses())],
            'balanceMin' => ['sometimes', 'integer'],
            'balanceMax' => ['sometimes', 'integer'],
            'currency' => ['sometimes', 'string'],
        ]);

        if ($validator->fails())
            return $this->response($validator->errors()->toArray(), HttpStatusCodeUtil::BAD_REQUEST, 'Validation Error!');

        $data = $this->userTransactionService->list($request->all());
        $response = [
            'count' => count($data),
            'data' => $data
        ];
        return $this->response($response, HttpStatusCodeUtil::OK, 'Data Retrieved Successfully!');
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function import(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => ['required', 'file', 'mimes:json'],
            'provider' => ['required', 'string', 'in:' . implode(',', DataProviderUtil::getAllProviders())],
        ]);

        if ($validator->fails())
            return $this->response($validator->errors()->toArray(), HttpStatusCodeUtil::BAD_REQUEST, 'Validation Error!');

        $file = $request->file('file');
        $provider = $request->get('provider');

        try {
            $this->userTransactionService->importFromUploadedFile($file, $provider);
        } catch (\Exception $e) {
            return $this->response(['exception' => $e->getMessage()], HttpStatusCodeUtil::SERVER_ERROR, 'Error Importing Data!');
        }

        return $this->response([], HttpStatusCodeUtil::OK, 'Data Imported Successfully!');
    }
}