<?php
/**
 * Created by PhpStorm.
 * User: after8
 * Date: 3/22/19
 * Time: 7:25 PM
 */

namespace App\Models\Base;

use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{

    /**
     * @param Model[] $models
     */
    public static function massInsert($models)
    {
        foreach ($models as $model) {
            $model->save();
        }
    }

}