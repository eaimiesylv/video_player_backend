<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use App\Models\User;
use App\Models\Organization;
use Illuminate\Http\Request;

trait SetCreatedBy
{
    protected static function bootSetCreatedBy()
    {
        static::creating(function ($model) {
            $request = request(); // Get the current request instance

            // Check if the table has 'created_by' column and set it
            if (Auth::check() && Schema::hasColumn($model->getTable(), 'created_by')) {
                $model->created_by = Auth::id();
            }

            // // Check if the table has 'user_id' column and set it
            // if (Auth::check() && Schema::hasColumn($model->getTable(), 'user_id')) {
            //     $model->user_id = Auth::id();
            // }

        });

        static::updating(function ($model) {
            // Always set 'updated_by' during an update, regardless of current value
            if (Auth::check() && Schema::hasColumn($model->getTable(), 'updated_by')) {
                $model->updated_by = Auth::id();
            }
        });
    }


}
