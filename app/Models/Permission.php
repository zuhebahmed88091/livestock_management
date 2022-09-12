<?php

namespace App\Models;

use Zizaco\Entrust\EntrustPermission;

class Permission extends EntrustPermission
{
    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
                  'module_id',
                  'name',
                  'display_name',
                  'description'
              ];

    /**
     * Get the module for this model.
     */
    public function module()
    {
        return $this->belongsTo('App\Models\Module','module_id');
    }

    /**
     * Get created_at in array format
     *
     * @param  string  $value
     * @return array
     */
    public function getCreatedAtAttribute($value)
    {
        return date(config('settings.DISPLAY_DATE_FORMAT') . ' g:i A', strtotime($value));
    }

    /**
     * Get updated_at in array format
     *
     * @param  string  $value
     * @return array
     */
    public function getUpdatedAtAttribute($value)
    {
        return date(config('settings.DISPLAY_DATE_FORMAT') . ' g:i A', strtotime($value));
    }
}
