<?php

namespace App\Repositories;

use App\Repositories\Contract\BaseRepositoryContract;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class BaseRepository implements BaseRepositoryContract
{
    public function getByAttribute(string $attribute, $value, $operator = '='): mixed
    {
        return $this->model->where($attribute, $operator, $value)->get();
    }

    public function getByAttributes(array $attributes, array $values, array $operators = ['=']): mixed
    {
        $where = [];
        $uniqueOperator = count($operators) == 1;

        foreach ($attributes as $index => $attribute) {
            $operator = $uniqueOperator ? $operators[0] : $operators[$index];
            $where[] = [$attribute, $operator, $values[$index]];
        }

        return $this->model->where($where)->get();
    }

    public function getById(string $id): ?Model
    {
        return $this->model->where('id', $id)->first();
    }

    public function create(array $data): Model
    {
        return $this->model->create($data);
    }

    public function update(string $id, array $data): Model
    {
        $wallet = $this->getById($id);
        $wallet->update($data);

        return $wallet;
    }

    public function delete(string $id): void
    {
        $wallet = $this->getById($id);
        $wallet->delete();
    }

    public function findBy(string $attribute, $value): Model
    {
        return $this->model::where($attribute, $value)->firstOrFail();
    }

    public function updateOrCreate(array $dataFind, array $data): Model
    {
        return $this->model::updateOrCreate($dataFind, $data);
    }

    public function findByOrNull(string $attribute, $value): ?Model
    {
        return $this->model::where($attribute, $value)->first();
    }

    public function findByLastOrNull(string $attribute, $value): ?Model
    {
        return $this->model::where($attribute, $value)->latest()->first();
    }

    public function firstOrCreate(array $array1, array $array2): Model
    {
        return $this->model->firstOrCreate($array1, $array2);
    }

    public function getAll(): Collection
    {
        return $this->model::all();
    }
}
