<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;
use App\models\Country;
use App\models\ReferralSource;

class Applicant extends Model
{
    protected $fillable = [
        'number',
        'first_name',
        'last_name',
        'email',
        'about',
        'note',
        'applicant_status_id',
        'country_id',
        'referral_source_id',
        'attachment',
    ];

    public function getFullNameAttribute()
    {
        return ucfirst($this->first_name) . ' ' . ucfirst($this->last_name);
    }

    public function status()
    {
        return $this->belongsTo('App\Models\ApplicantStatus', 'applicant_status_id');
    }

    public function referral_source()
    {
        return $this->belongsTo('App\Models\ReferralSource');
    }

    public function country()
    {
        return $this->belongsTo('App\Models\Country');
    }

    static function applyAsCandidateDropdown()
    {
        $data['countries'] = Country::orderBy('id', 'ASC')->pluck('name', 'id')->toArray();
        $data['referral_sources'] = ReferralSource::orderBy('display_order', 'ASC')->pluck('name', 'id')->toArray();

        return $data;
    }

    static function adminSearchDropdown()
    {
        $data['statuses'] =  ['' => 'All'] + ApplicantStatus::orderBy('id', 'ASC')->pluck('name', 'id')->toArray();
        $data['referral_sources'] = ['' => 'All'] + ReferralSource::orderBy('display_order', 'ASC')->pluck('name', 'id')->toArray();

        return $data;
    }
}
