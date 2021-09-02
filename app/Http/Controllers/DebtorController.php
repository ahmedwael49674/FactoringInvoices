<?php

namespace App\Http\Controllers;

use App\Models\Debtor;
use Illuminate\Http\Request;
use App\Services\DebtorService;
use App\Http\Requests\CreateDebtor;
use App\Http\Requests\UpdateDebtor;

class DebtorController extends Controller
{
    protected $service;

    public function __construct(DebtorService $debtorService)
    {
        $this->service = $debtorService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $debtors   = $this->service->index();

        return response()->json($debtors);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  CreateDebtor  $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(CreateDebtor $request)
    {
        $debtor   = $this->service->create($request);

        return response()->json($debtor);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateDebtor $request)
    {
        $debtor   = $this->service->update($request);

        return response()->json($debtor);
    }
}
