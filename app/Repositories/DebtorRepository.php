<?php

namespace App\Repositories;

use App\Models\Debtor;
use Illuminate\Pagination\LengthAwarePaginator;

class DebtorRepository
{
    /**
     * find by id or fail
     * select only given attributes if provided
     *
     * @param int $id
     * @param array $attributes = ['*']
     *
     * @return Debtor
     */
    public function findOrFail(int $id, array $attributes = ['*']):Debtor
    {
        return Debtor::select($attributes)->findOrFail($id);
    }

    /**
     * index with pagination of 10 debtors
     *
     * @return LengthAwarePaginator
     */
    public function index():LengthAwarePaginator
    {
        return Debtor::paginate(10);
    }

    /**
     * create new debtor with given attributes
     *
     * @param array $attributes
     *
     * @return Debtor
     */
    public function create(array $attributes):Debtor
    {
        return Debtor::create($attributes);
    }
    
    /**
     * update given debtor with given attributes
     *
     * @param Debtor $debtor
     * @param array $attributes
     *
     * @return Debtor
     */
    public function update(Debtor $debtor, array $attributes):Debtor
    {
        $debtor->update($attributes);

        return $debtor;
    }
}
