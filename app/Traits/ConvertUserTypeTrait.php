<?php

namespace App\Traits;

trait ConvertUserTypeTrait
{
    // Accessor: Convert numeric value to string representation
    public function getTypeAttribute($value)
    {
        switch ($value) {
            case 0:
                return 'guest';
            case 1:
                return 'sender';
            case 2:
                return 'angel';
            default:
                return 'sender';
        }
    }

    // Mutator: Convert string representation to numeric value
    public function setTypeAttribute($value)
    {
        $headerType = request()->header('type');

        if ($headerType == 'sender') {
            $this->attributes['type'] = 1;
        } elseif ($headerType == 'angel') {
            $this->attributes['type'] = 2;
        } else {
            if ($value === 'sender') {
                $this->attributes['type'] = 1;
            } elseif ($value === 'angel') {
                $this->attributes['type'] = 2;
            } else {
                $this->attributes['type'] = 0;
            }
        }
    }

    public function scopeWhereType($query, $type)
    {

        switch ($type) {
            case 'sender':
                return $query->where('type', 1);
            case 'angel':
                return $query->where('type', 2);
            default:
                return $query->where('type', 0);
        }

    }
}
