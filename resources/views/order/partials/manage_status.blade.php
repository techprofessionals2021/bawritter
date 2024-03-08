<div class="card outlined br-20">
   <div class="card-header sky">
      Status
   </div>
   <div class="card-body">
      <form action="{{ route('order_change_status', $order->id) }}" method="POST" autocomplete="off">
            {{ csrf_field()  }}
         <div class="form-group">
            <label class="sky">Name</label>
            <?php echo form_dropdown("order_status_id", $data['order_status_list'], old('order_status_id', $order->order_status_id), "class='form-control form-control-sm  selectpicker'") ?>
            <div class="invalid-feedback d-block text-grey">{{ showError($errors, 'order_status_id') }}</div>
         </div>
         <button type="submit" class="btn bg-sky btn-sm btn-block text-white ">Change</button>
      </form>
   </div>
</div>
