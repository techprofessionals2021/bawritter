<div class="card shadow br-20">
   <div class="card-body">
      <div class="card-title sky font-weight-bold">Change password</div>
      <hr>
      <form autocomplete="off" class="form-horizontal" method="post" action="{{ route('change_password') }}">
         {{ csrf_field()  }}
         {{ method_field('PATCH') }}
         <div class="form-group">
            <label class="sky">Current Password <span class="required">*</span></label>
            <input type="password" class="form-control form-control-sm {{ showErrorClass($errors, 'current_password') }}" name="current_password" value="">
            <div class="invalid-feedback d-block">
               {{ showError($errors, 'current_password') }}
            </div>
         </div>
         <div class="form-group">
            <label class="sky">New password <span class="required">*</span></label>
            <input type="password" class="form-control form-control-sm {{ showErrorClass($errors, 'password') }}" name="password" value="">
            <div class="invalid-feedback d-block">{{ showError($errors, 'password') }}</div>
         </div>
         <div class="form-group">
            <label class="sky">Retype Password <span class="required">*</span></label>
            <input type="password" class="form-control form-control-sm {{ showErrorClass($errors, 'password_confirmation') }}" name="password_confirmation" value="">
            <div class="invalid-feedback d-block">{{ showError($errors, 'password_confirmation') }}</div>
         </div>
         <button type="submit" class="btn bg-sky text-white">Submit</button>
      </form>
   </div>
</div>
