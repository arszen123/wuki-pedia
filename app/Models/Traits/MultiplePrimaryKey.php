<?php
/**
 * Created by PhpStorm.
 * User: after8
 * Date: 3/10/19
 * Time: 10:02 AM
 */

namespace App\Models\Traits;


trait MultiplePrimaryKey
{

    public abstract function getKeys();

    protected function setKeysForSaveQuery(\Illuminate\Database\Eloquent\Builder $query)
    {
        $keys = $this->getKeys();
        foreach ($keys as $key) {
            $query->where($key, '=', $this->getAttribute($key));
        }

        return $query;
    }
}