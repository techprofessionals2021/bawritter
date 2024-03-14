<form role="form" class="form-horizontal" enctype="multipart/form-data" action="{{ route('patch_settings_paypal_checkout') }}" method="post" autocomplete="off" >
   {{ csrf_field()  }}
   {{ method_field('PATCH') }}
   <div class="form-group">
      <label class="sky">Environment <span class="required">*</span></label>
      <?php echo form_dropdown("environment", $options['env_list'], old('environment', optional($rec->keys)->environment), "class='form-control selectPickerWithoutSearch'") ?>
      <div class="invalid-feedback d-block ">{{ showError($errors,'environment') }}</div>
   </div>
   <div class="form-group">
     <label class="sky">Display Name <span class="required">*</span></label>
      <input type="text" class="text-grey form-control {{ showErrorClass($errors,'name') }}" name="name" value="{{ old('name', optional($rec)->name) }}">
      <div class="invalid-feedback">{{ showError($errors,'name') }}</div>
   </div>
   <div class="form-group">
      <label class="sky">Client Id <span class="required">*</span></label>
      <input type="text" class="text-grey form-control {{ showErrorClass($errors,'client_id') }}" name="client_id" value="{{ old('client_id', optional($rec->keys)->client_id) }}">
      <div class="invalid-feedback">{{ showError($errors,'client_id') }}</div>
   </div>
   <div class="form-group">
      <label class="sky">Client Secret <span class="required">*</span></label>
      <input type="text" class=" text-grey form-control {{ showErrorClass($errors,'client_secret') }}" name="client_secret" value="{{ old('client_secret', optional($rec->keys)->client_secret) }}">
      <div class="invalid-feedback">{{ showError($errors,'client_secret') }}</div>
   </div>
    <?php
      $inactive = (old('inactive', (optional($rec)->inactive))) ? 'checked' : '';
       ?>
   <div class="form-group">
      <div class="custom-control custom-checkbox">
         <input type="checkbox" class="text-grey custom-control-input text-grey" id="inactive"  name="inactive" value="1"
         {{ $inactive }}
         >
         <label class="custom-control-label text-grey" for="inactive">Inactive</label>
      </div>
   </div>
   <input type="submit" name="submit" class="btn bg-sky text-white" value="Submit"/>
</form>
