<?php

namespace App\Http\Controllers;

use App\Models\Creditor;
use Illuminate\Http\Request;
use App\Services\CreditorService;
use App\Http\Requests\CreateCreditor;
use App\Http\Requests\UpdateCreditor;

class CreditorController extends Controller
{
    protected $service;

    public function __construct(CreditorService $creditorService)
    {
        $this->service = $creditorService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $creditors   = $this->service->index();

        return response()->json($creditors);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  CreateCreditor  $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(CreateCreditor $request)
    {
        $creditor   = $this->service->create($request);

        return response()->json($creditor);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCreditor $request)
    {
        $creditor   = $this->service->update($request);

        return response()->json($creditor);
    }
}
