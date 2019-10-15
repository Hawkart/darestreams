<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\InquireRequest;
use App\Models\Inquire;

/**
 * @group Inquire
 */
class InquireController extends Controller
{
    /**
     * Create new inquire.
     *
     * @bodyParam name string required
     * @bodyParam title string required
     * @bodyParam phone string required
     * @bodyParam email string required
     *
     * @param InquireRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(InquireRequest $request)
    {
        $inquire = new Inquire();
        $inquire->fill($request->all());
        $inquire->save();

        return response()->json([
            'success' => true,
            'message'=> trans('api/inquire.success_created')
        ], 200);
    }
}