<div class="card outlined br-20 ">
   <div class="card-header sky">
      Assignee
   </div>
   <div class="card-body">
      <form action="{{ route('assign_task', $order->id) }}" method="POST" autocomplete="off">
            {{ csrf_field()  }}
         <div class="form-group">
            <label class="sky">Name</label>
            <?php echo form_dropdown("staff_id", $data['staff_list'], old('staff_id', $order->staff_id), "class='form-control form-control-sm  selectpicker'") ?>

            <div class="invalid-feedback d-block text-grey">{{ showError($errors, 'staff_id') }}</div>
         </div>
         <div class="form-group">
            <label class="sky">Payment Amount</label>
            <input type="text" class="form-control form-control-sm" name="staff_payment_amount" value="{{ old('staff_payment_amount', $order->staff_payment_amount) }}">
            <div class="invalid-feedback d-block text-grey">{{ showError($errors, 'staff_payment_amount') }}</div>
         </div>
         <button type="submit" class="btn bg-sky btn-sm btn-block text-white">Submit</button>
      </form>
   </div>
</div>
