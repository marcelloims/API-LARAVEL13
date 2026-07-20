<?php

namespace App\Repositories;

class BaseRepository
{
    public function getData($table, $column)
    {
        return $table::select($column)->get();
    }

    public function getDataById($table, $id, $column)
    {
        return $table::where('id', $id)->select($column)->first();
    }

    public function store($table, $data, $getId = false)
    {
        if ($getId) {
            return $table::insertGetId($data);
        }

        return $table::insert($data);
    }

    public function update($table, $id, $data)
    {
        return $table::where('id', $id)->update($data);
    }

    public function delete($table, $id)
    {
        return $table::where('id', $id)->delete();
    }

    public function auditableCreate()
    {
        return [
            'created_by' => auth()->guard()->id(),
            'updated_by' => auth()->guard()->id(),
            'deleted_by' => null,
            'created_at' => now(),
            'updated_at' => now(),
            'deleted_at' => null,
        ];
    }

    public function auditableUpdate()
    {
        return [
            'updated_by' => auth()->guard()->id(),
            'updated_at' => now(),
        ];
    }

    public function auditableDelete()
    {
        return [
            'deleted_by' => auth()->guard()->id(),
            'deleted_at' => now(),
        ];
    }
}
