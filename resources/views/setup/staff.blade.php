@extends('setup.index')
@section('title', 'Employee Settings')
@section('setting_page')
<form role="form" class="form-horizontal card-body br-20 shadow" enctype="multipart/form-data" action="{{ route('patch_settings_staff') }}" method="post" autocomplete="off">
   {{ csrf_field() }}
   {{ method_field('PATCH') }}
   @include('setup.partials.action_toolbar', ['title' => 'Employee'])

   <!-- Existing Form -->
   <div class="form-row">
      <div class="form-group col-md-6">
         <label class="sky">Allow staffs to browse work <span class="required">*</span></label>
         <?php echo form_dropdown('enable_browsing_work', $data['enable_browsing_work'], old_set('enable_browsing_work', NULL, $rec) , "class='form-control enable_browsing_work selectPickerWithoutSearch'"); ?>
         <div class="invalid-feedback d-block">{{ showError($errors, 'enable_browsing_work') }}</div>
      </div>
      <div class="form-group col-md-6 staff-payment-inputs">
         <label class="sky">Staff payment type <span class="required">*</span></label>
         <?php echo form_dropdown('staff_payment_type', $data['staff_payment_types'], old_set('staff_payment_type', NULL, $rec) , "class='form-control selectPickerWithoutSearch'"); ?>
         <div class="invalid-feedback d-block">{{ showError($errors, 'staff_payment_type') }}</div>
      </div>
   </div>
   <div class="form-group staff-payment-inputs">
      <label class="sky">Staff payment amount <span class="required">*</span>
      <span data-toggle="tooltip" title="Will be automatically calculated when an order is placed, if Browse Work is enabled"><i class="fas fa-question-circle"></i></span>
      </label>
      <input type="text" class="form-control {{ showErrorClass($errors, 'staff_payment_amount') }}" name="staff_payment_amount" value="{{ old_set('staff_payment_amount', NULL, $rec) }}" placeholder="Enter amount here">
      <div class="invalid-feedback d-block">{{ showError($errors, 'staff_payment_amount') }}</div>
   </div>
</form>

<!-- New Form -->
<form role="form" class="form-horizontal card-body br-20 shadow mt-4" enctype="multipart/form-data" action="{{ route('patch_settings_staff_payout') }}" method="post" autocomplete="off">
   {{ csrf_field() }}
   {{ method_field('PATCH') }}
   @include('setup.partials.action_toolbar', ['title' => 'Employee Charges'])
   <div class="form-row">
      <div class="form-group col-md-12">
         <label class="sky">Employees <span class="required">*</span></label>
         <select name="employee_id" class="form-control employee_select select2">
            <option value="">Select Employee</option>
            @foreach($employees as $employee)
               <option value="{{ $employee->id }}">{{ $employee->first_name . ' ' . $employee->last_name }}</option>
            @endforeach
         </select>
         <div class="invalid-feedback d-block">{{ showError($errors, 'employee_id') }}</div>
      </div>
   </div>
   <div class="form-row">
      <div class="form-group col-md-6">
         <label class="sky">Single Spaced Price <span class="required">*</span></label>
         <input type="number" step="0.01" class="form-control {{ showErrorClass($errors, 'single_spaced_price') }}" name="single_spaced_price" value="{{ old('single_spaced_price') }}" placeholder="Enter single spaced price">
         <div class="invalid-feedback d-block">{{ showError($errors, 'single_spaced_price') }}</div>
      </div>
      <div class="form-group col-md-6">
         <label class="sky">Double Spaced Price <span class="required">*</span></label>
         <input type="number" step="0.01" class="form-control {{ showErrorClass($errors, 'double_spaced_price') }}" name="double_spaced_price" value="{{ old('double_spaced_price') }}" placeholder="Enter double spaced price">
         <div class="invalid-feedback d-block">{{ showError($errors, 'double_spaced_price') }}</div>
      </div>
   </div>
</form>
@endsection

@section('innerPageJS')
<script>
   $(function() {
      <?php if(old_set('enable_browsing_work', NULL, $rec) == 'yes') { ?>
         showStaffPaymentInputs();
      <?php } else { ?>
         hideStaffPaymentInputs();
      <?php } ?>

      $('.select2').select2({
          theme: 'bootstrap4',
      });

      $('.selectPickerWithoutSearch').select2({
          theme: 'bootstrap4',
         minimumResultsForSearch: -1
      });

      $('.enable_browsing_work').change(function(e) {
         if($(this).val() == 'yes') {
            showStaffPaymentInputs();
         } else {
            hideStaffPaymentInputs();
         }
      });

      $('.employee_select').change(function() {
         var employee_id = $(this).val();
         if (employee_id) {
            $.ajax({
               url: '{{ route('get_staff_prices') }}',
               type: 'GET',
               data: { employee_id: employee_id },
               success: function(data) {console.log(data);
                  if (data) {
                     $('input[name="single_spaced_price"]').val(data.single_space_price);
                     $('input[name="double_spaced_price"]').val(data.double_space_price);
                  } else {
                     $('input[name="single_spaced_price"]').val('');
                     $('input[name="double_spaced_price"]').val('');
                  }
               }
            });
         } else {
            $('input[name="single_spaced_price"]').val('');
            $('input[name="double_spaced_price"]').val('');
         }
      });
   });

   function hideStaffPaymentInputs() {
      $('.staff-payment-inputs').hide();
   }

   function showStaffPaymentInputs() {
      $('.staff-payment-inputs').show();
   }
</script>
@endsection
