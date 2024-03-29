@extends('setup.index')
@section('title', 'Test email')
@section('setting_page')

@include('setup.partials.action_toolbar', [
 'title' => 'Test Email Configuration',
 'hide_save_button' => TRUE,
 'back_link' => ['title' => 'back to Email Configuration', 'url' => route("settings_email_page")],
])

<form autocomplete="off" action="{{ route('post_test_email') }}" method="POST" class="card-body shadow br-20">
{{ csrf_field()  }}
   <div class="form-group ">
      <label class="form-text text-muted sky" >Enter an email address to test the email configutation</label>
      <input type="email" class=" mt-5 form-control {!! $errors->has('test_email_address') ? ' is-invalid' : '' !!}" name="test_email_address" placeholder="Enter you email">
      <div class="invalid-feedback">@php if($errors->has('test_email_address')) { echo $errors->first('test_email_address') ; } @endphp</div>
   </div>
   <button type="submit" class="btn bg-sky text-white">
   <i class="fas fa-envelope-square"></i> Send
   </button>
</form>

@endsection
