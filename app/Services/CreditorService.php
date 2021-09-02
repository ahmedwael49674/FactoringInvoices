<?php

namespace App\Services;

use App\Models\Creditor;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use App\Repositories\CreditorRepository;
use Illuminate\Pagination\LengthAwarePaginator;

class CreditorService
{
    protected $repository;

    public function __construct(CreditorRepository $creditorRepository)
    {
        $this->repository = $creditorRepository;
    }

    /**
     * index with pagination of 10 creditors
     *
     * @return LengthAwarePaginator
     */
    public function index():LengthAwarePaginator
    {
        $creditors   = $this->repository->index();

        return $creditors;
    }

    /**
     * create new creditor with given attributes
     *
     * @param Request $request
     *
     * @return Creditor
     */
    public function create(Request $request):Creditor
    {
        $creditors   = $this->repository->create($request->all());

        return $creditors;
    }

    /**
     * update given creditor with given attributes
     *
     * @param Request $request
     *
     * @return Creditor
     */
    public function update(Request $request):Creditor
    {
        $creditor   = $this->repository->findOrFail($request->id);
        $creditor   = $this->repository->update($creditor, $request->only(Creditor::UpdatableAttributes));

        return $creditor;
    }
}
