<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use App\Traits\TagOperation;
use App\Traits\Wallet\HasWallet;
use App\Traits\Wallet\Transactionable;
use Spatie\Activitylog\Traits\CausesActivity;
use Spatie\Permission\Models\Role;
use App\Models\Setting;
use Laravel\Sanctum\HasApiTokens;


class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens;
    use SoftDeletes;
    use Notifiable, HasRoles, TagOperation, HasWallet;
    use CausesActivity;
    use Transactionable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime'
    ];
    
    public function getAuthPassword()
{
    return $this->password;
}

    public function getFullNameAttribute()
    {
        return ucfirst($this->first_name) . ' ' . ucfirst($this->last_name);
    }

    public function my_orders()
    {
        return $this->hasMany('App\Models\Order', 'customer_id', 'id');
    }

    public function tasks()
    {
        return $this->hasMany('App\Models\Order', 'staff_id', 'id');
    }

    public function staff_price()
    {
        return $this->hasOne('App\Models\StaffPrice');
    }

    public function ratings_received()
    {
        return $this->hasManyThrough('App\Models\Rating', 'App\Models\Order', 'staff_id');
    }

    public function unbilled_tasks()
    {
        return $this->tasks()
            ->where('order_status_id', ORDER_STATUS_COMPLETE)
            ->whereNull('billed');
    }

    public function bills()
    {
        return $this->hasMany('App\Models\Bill');
    }

    public function pushNotification()
    {
        return $this->hasOne('App\Models\PushNotification');
    }


    public function uncleared_payment_requests()
    {
        return $this->bills()->whereNull('paid');
    }

    public function uncleared_payment_total()
    {
        return $this->uncleared_payment_requests()
            ->get()
            ->sum('total');
    }

    public function records()
    {
        return $this->hasMany('App\Models\UserRecord');
    }

    public function setMetaData()
    {
        $records = $this->records()->get();

        $this->meta = new \StdClass();

        if ($records->count() > 0) {
            foreach ($records as $row) {
                $this->meta->{$row->option_key} = $row->option_value;
            }
        }
    }

    public function meta($option_key)
    {
        $records = $this->records()
            ->where('option_key', $option_key)
            ->get();

        if ($records->count() > 0) {
            return optional($records->first())->option_value;
        }
    }

    public static function adminDropdown()
    {
        $data['tag_id_list'] = Tag::orderBy('name', 'ASC')->pluck('name', 'id')->toArray();

        $data['role_id_list'] = Role::orderBy('name', 'ASC')->pluck('name', 'name')->toArray();

        $data['countries'] = ['' => 'Select'] + Country::orderBy('id', 'ASC')->pluck('name', 'name')->toArray();

        $data['referral_sources'] = ['' => 'Select'] + ReferralSource::orderBy('display_order', 'ASC')->pluck('name', 'name')->toArray();

        $data['timezones'] = Setting::get_list_of_time_zone();
        return $data;
    }
}
