<?php

namespace App\Repositories\Contract;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

interface BaseRepositoryContract
{
    public function getByAttribute(string $attribute, $value, $operator = '='): mixed;

    public function getByAttributes(array $attributes, array $values, array $operators = ['=']): mixed;

    public function getById(string $id): ?Model;

    public function create(array $data): Model;

    public function update(string $id, array $data): Model;

    public function delete(string $id): void;

    public function findBy(string $attribute, $value): Model;

    public function findById(int $id): Model;

    public function updateOrCreate(array $dataFind, array $data): Model;

    public function findByOrNull(string $attribute, $value): ?Model;

    public function findByLastOrNull(string $attribute, $value): ?Model;

    public function firstOrCreate(array $array1, array $array2): Model;

    public function getAll(int $perPage = 10): LengthAwarePaginator;
}
