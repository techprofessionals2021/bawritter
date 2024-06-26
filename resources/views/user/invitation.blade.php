@extends('layouts.app')
@section('title', 'Send Invitation')
@section('content')
<div class="container page-container">
   <div class="row justify-content-center">
      <div class="col-md-12">
         <h5 class="sky font-weight-bold">Send an Invitation</h5>

      </div>
      <div class="col-md-8 card-body shadow br-20 mt-10">

         <form autocomplete="off" method="POST" action="{{ route('send_invitation') }}">
            {{ csrf_field()  }}
            <div class="form-group">
               <label class="sky">Email Address</label>
               <input type="text" class="text-grey form-control" value="{{ old('email')}}" name="email" placeholder="Type email address">
               <div class="invalid-feedback d-block">@php if($errors->has('email')) { echo $errors->first('email') ; } @endphp</div>
            </div>
            <div class="form-group">
               <label class="sky">Request to join as : </label>
               <div class="font-14">
                  <div class="form-check">
                     <input class="text-grey form-check-input" type="radio" name="role_type" id="admin" value="admin"
                     {{ ($data['type'] == 'admin') ? 'checked' : '' }}>
                     <label class="form-check-label" for="admin">
                     Admin / Manager
                     </label>
                  </div>
                  <div class="form-check">
                     <input class="form-check-input" type="radio" name="role_type" id="staff" value="staff" {{ ($data['type'] == 'staff') ? 'checked' : '' }}>
                     <label class="form-check-label" for="staff">
                     Staff / Writer
                     </label>
                     <div class="invalid-feedback d-block">@php if($errors->has('role_type')) { echo $errors->first('role_type') ; } @endphp</div>
                  </div>
               </div>
            </div>
            <button type="submit" class="btn bg-sky text-white"><i class="fas fa-paper-plane"></i> Send Invitation</button>
         </form>
      </div>
   </div>
</div>
@endsection
