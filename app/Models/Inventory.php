<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{


    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'inventories';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
                  'name',
                  'inventory_image',
                  'inventory_type_id',
                  'inventory_unit_id',
                  'source',
                  'warranty',
                  'description',
                  'instruction',
                  'created_by'
              ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [];

    /**
     * Get the inventoryType for this model.
     */
    public function inventoryType()
    {
        return $this->belongsTo('App\Models\InventoryType','inventory_type_id');
    }

    /**
     * Get the inventoryUnit for this model.
     */
    public function inventoryUnit()
    {
        return $this->belongsTo('App\Models\InventoryUnit','inventory_unit_id');
    }

    /**
     * Get the creator for this model.
     */
    public function creator()
    {
        return $this->belongsTo('App\Models\User','created_by');
    }

    /**
     * Get created_at in array format
     *
     * @param  string  $value
     * @return array
     */
    public function getCreatedAtAttribute($value)
    {
        return date(config('constants.DISPLAY_DATE_FORMAT') . ' g:i A', strtotime($value));
    }

    /**
     * Get updated_at in array format
     *
     * @param  string  $value
     * @return array
     */
    public function getUpdatedAtAttribute($value)
    {
        return date(config('constants.DISPLAY_DATE_FORMAT') . ' g:i A', strtotime($value));
    }
}
