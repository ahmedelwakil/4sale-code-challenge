<?php

namespace App\Http\Controllers;

use App\Services\UserTransactionService;
use App\Utils\DataProviderUtil;
use App\Utils\HttpStatusCodeUtil;
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

    public function index()
    {

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
            return $this->response(['exception' => $e->getMessage()], HttpStatusCodeUtil::SERVER_ERROR, 'Server Error!');
        }

        return $this->response([], HttpStatusCodeUtil::OK, 'Data Imported Successfully!');
    }
}