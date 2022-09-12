<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shed extends Model
{
    
     /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'sheds';

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
                  'livestock_id',
                  'shed_no',
                  'purchase_date',
                  'age',
                  'quantity',
                  'created_by',
                  'status',
                  'comments'
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
     * Get the creator for this model.
     */
    public function creator()
    {
        return $this->belongsTo('App\Models\User','created_by');
    }

     public function liveStock()
     {
         return $this->belongsTo('App\Models\LiveStock','livestock_id');
     }

    /**
     * Set the date.
     *
     * @param  string  $value
     * @return void
     */
    public function setPurchaseDateAttribute($value)
    {
        $this->attributes['purchase_date'] = !empty($value) ? date($this->getDateFormat(), strtotime($value)) : null;
    }

    /**
     * Get date in array format
     *
     * @param  string  $value
     * @return array
     */
    public function getPurchaseDateAttribute($value)
    {
        return date(config('constants.DISPLAY_DATE_FORMAT'), strtotime($value));
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
