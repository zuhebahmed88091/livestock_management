<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyWage extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'daily_wages';

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
                  'user_id',
                  'wages',
                  'paying_status',
                  'work_date',
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
     * Get the user for this model.
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User','user_id');
    }

    /**
     * Set the work_date.
     *
     * @param  string  $value
     * @return void
     */
    public function setWorkDateAttribute($value)
    {
        $this->attributes['work_date'] = !empty($value) ? date($this->getDateFormat(), strtotime($value)) : null;
    }

    /**
     * Get work_date in array format
     *
     * @param  string  $value
     * @return array
     */
    public function getWorkDateAttribute($value)
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
