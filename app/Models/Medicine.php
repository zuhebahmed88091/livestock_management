<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Medicine extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'medicines';

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
                  'doctor_id',
                  'shed_id' ,
                  'livestock_id',
                  'identify_date',
                  'start_date',
                  'end_date',
                  'next_follow_up_date',
                  'special_dose',
                  'regular_dose',
                  'comments',
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

    /**
     * Get the doctor for this model.
     */
    public function doctor()
    {
        return $this->belongsTo('App\Models\User','doctor_id');
    }

    /**
     * Get the creator for this model.
     */
    public function creator()
    {
        return $this->belongsTo('App\Models\User','created_by');
    }


    /**
     * Set the identify_date.
     *
     * @param  string  $value
     * @return void
     */
    public function setIdentifyDateAttribute($value)
    {
        $this->attributes['identify_date'] = !empty($value) ? date($this->getDateFormat(), strtotime($value)) : null;
    }

    /**
     * Set the start_date.
     *
     * @param  string  $value
     * @return void
     */
    public function setStartDateAttribute($value)
    {
        $this->attributes['start_date'] = !empty($value) ? date($this->getDateFormat(), strtotime($value)) : null;
    }

    /**
     * Set the end_date.
     *
     * @param  string  $value
     * @return void
     */
    public function setEndDateAttribute($value)
    {
        $this->attributes['end_date'] = !empty($value) ? date($this->getDateFormat(), strtotime($value)) : null;
    }

    /**
     * Set the next_follow_up_date.
     *
     * @param  string  $value
     * @return void
     */
    public function setNextFollowUpDateAttribute($value)
    {
        $this->attributes['next_follow_up_date'] = !empty($value) ? date($this->getDateFormat(), strtotime($value)) : null;
    }

    /**
     * Get identify_date in array format
     *
     * @param  string  $value
     * @return array
     */
    public function getIdentifyDateAttribute($value)
    {
        return date(config('constants.DISPLAY_DATE_FORMAT'), strtotime($value));
    }

    /**
     * Get start_date in array format
     *
     * @param  string  $value
     * @return array
     */
    public function getStartDateAttribute($value)
    {
        return date(config('constants.DISPLAY_DATE_FORMAT'), strtotime($value));
    }

    /**
     * Get end_date in array format
     *
     * @param  string  $value
     * @return array
     */
    public function getEndDateAttribute($value)
    {
        return date(config('constants.DISPLAY_DATE_FORMAT'), strtotime($value));
    }

    /**
     * Get next_follow_up_date in array format
     *
     * @param  string  $value
     * @return array
     */
    public function getNextFollowUpDateAttribute($value)
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
