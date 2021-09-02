<?php

namespace App\Repositories;

use App\Models\Creditor;
use Illuminate\Pagination\LengthAwarePaginator;

class CreditorRepository
{
    /**
     * find by id or fail
     *
     * @param int $id
     *
     * @return Creditor
     */
    public function findOrFail(int $id):Creditor
    {
        return Creditor::findOrFail($id);
    }

    /**
     * index with pagination of 10 creditors
     *
     * @return LengthAwarePaginator
     */
    public function index():LengthAwarePaginator
    {
        return Creditor::paginate(10);
    }

    /**
     * create new creditor with given attributes
     *
     * @param array $attributes
     *
     * @return Creditor
     */
    public function create(array $attributes):Creditor
    {
        return Creditor::create($attributes);
    }
    
    /**
     * update given creditor with given attributes
     *
     * @param Creditor $creditor
     * @param array $attributes
     *
     * @return Creditor
     */
    public function update(Creditor $creditor, array $attributes):Creditor
    {
        $creditor->update($attributes);

        return $creditor;
    }
}
