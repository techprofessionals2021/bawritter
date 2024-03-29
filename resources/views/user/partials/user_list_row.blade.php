<div class="card order-box shadow bg-white br-10">
   <div class="row no-gutters">
      <div class="col-md-2">
         <?php $photo_url = ($user->photo) ? asset(Storage::url($user->photo)) : asset('images/user-placeholder.jpg') ; ?>
         <div class=" text-center"><img src="{{ $photo_url }}" class="avatar rounded-circle"></div>
      </div>
      <div class="col-md-7">
         <div class="">
            <a href="{{ route('user_profile', $user->id) }}" ><h5 class="sky">{{ $user->full_name }}</h5></a>
            <p class="card-text text-sm text-muted mb-0 ">Email: {{ $user->email }}</p>
         </div>
      </div>
      <div class="col-3 text-align-end">
        <a href="{{ route('user_profile', $user->id) }}"  type="submit" class="bg-sky text-white btn-sm mt-4 br-5 "><i class="fa fa-eye"></i> View Profile</a>
      </div>

    </div>
</div>
