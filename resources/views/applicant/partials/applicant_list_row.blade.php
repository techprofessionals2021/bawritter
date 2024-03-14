<div class="card mb-3 bg-white w-100 shadow br-20">
    <div class="row no-gutters">
        <div class="col-md-8">
            <div class="card-body ">
                <a href="{{ route('job_applicant_profile', $applicant->id) }}">
                    <h5>{{ $applicant->fullname }}</h5>
                </a>
                <i class="font-12 text-yellow-accent-4">Status: {{ $applicant->status->name }}</i>
                <br>
                <div class="text-grey">Email: {{ $applicant->email }}</div>
            </div>
        </div>
        <div class="col-md-4 text-right">
            <div class="card-body mt-2 font-14">
                <div class="sky" >Applicant# :
                    <span class="text-grey"> {{ $applicant->number }}</div></span>
                <div class="sky">Referrer:
                    <span class="text-grey"> {{ $applicant->referral_source->name }}</span></div>
            </div>
        </div>
    </div>
</div>
