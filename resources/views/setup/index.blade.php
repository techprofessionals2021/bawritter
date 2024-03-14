@extends('layouts.app')
@section('content')
<div class="container page-container">
   <div class="row">
      <div class="col-md-3 card-body shadow br-20">
         <h4 class="sky font-weight-bold">Settings</h4>
         <small class="mb-2 form-text text-muted text-grey">Version : {{ settings('prowriters_version') }}</small>
         <div class="sticky-top">@include('setup.partials.menu')</div>
      </div>
      <div class="col-md-9">
         <div id="settings">
            @yield('setting_page')
         </div>
      </div>
   </div>
</div>
@endsection
@push('scripts')
@yield('innerPageJS')
@endpush
