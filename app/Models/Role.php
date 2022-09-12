<?php

namespace App\Models;

use Illuminate\Cache\TaggableStore;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;

use Zizaco\Entrust\EntrustRole;

class Role extends EntrustRole
{
    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
                  'name',
                  'display_name',
                  'description'
              ];

    public function savePermissionsWithModule($permissions, $moduleId)
    {
        if (!empty($permissions)) {
            $this->perms()->wherePivot('module_id', $moduleId)->sync($permissions);
        } else {
            $this->perms()->wherePivot('module_id', $moduleId)->detach();
        }

        if (Cache::getStore() instanceof TaggableStore) {
            Cache::tags(Config::get('entrust.permission_role_table'))->flush();
        }
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
