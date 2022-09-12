<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FoodHistory extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'food_history';

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
                  'food_id',
                  'livestock_id' ,
                  'shed_id' ,
                  'inventory_id',
                  'consume_quantity',
                  'duration',
                  'date',
                  'comments',
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
     * Get the livestock for this model.
     */

     public function liveStock()
     {
         return $this->belongsTo('App\Models\LiveStock','livestock_id');
     }

    public function shed()
    {
        return $this->belongsTo('App\Models\Shed','shed_id');
    }
    public function inventory()
    {
        return $this->belongsTo('App\Models\InventoryType','inventory_id');
    }


   /**
     * Set the date.
     *
     * @param  string  $value
     * @return void
     */
    public function setDateAttribute($value)
    {
        $this->attributes['date'] = !empty($value) ? date($this->getDateFormat(), strtotime($value)) : null;
    }

    /**
     * Get date in array format
     *
     * @param  string  $value
     * @return array
     */
    public function getDateAttribute($value)
    {
        return date(config('constants.DISPLAY_DATE_FORMAT'), strtotime($value));
    }
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
