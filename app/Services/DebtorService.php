<?php

namespace App\Services;

use App\Models\Debtor;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use App\Repositories\DebtorRepository;
use Illuminate\Pagination\LengthAwarePaginator;

class DebtorService
{
    protected $repository;

    public function __construct(DebtorRepository $debtorRepository)
    {
        $this->repository = $debtorRepository;
    }

    /**
     * index with pagination of 10 debtors
     *
     * @return LengthAwarePaginator
     */
    public function index():LengthAwarePaginator
    {
        $debtors   = $this->repository->index();

        return $debtors;
    }

    /**
     * create new debtor with given attributes
     *
     * @param Request $request
     *
     * @return Debtor
     */
    public function create(Request $request):Debtor
    {
        $debtors   = $this->repository->create($request->except(Debtor::ProtectedAttributes));

        return $debtors;
    }

    /**
     * update given debtor with given attributes
     *
     * @param Request $request
     *
     * @return Debtor
     */
    public function update(Request $request):Debtor
    {
        $debtor   = $this->repository->findOrFail($request->id);
        $debtor   = $this->repository->update($debtor, $request->only(Debtor::UpdatableAttributes));

        return $debtor;
    }
}
