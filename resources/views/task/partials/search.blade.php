<div class="sticky-top">
   <div class="card outlined br-20">
      <div class="card-header sky ">Filter</div>
      <div class="card-body">
         <form id="search-form" autocomplete="off">
            <div class="form-group">
                <label class="sky">Order Date</label>
               <input type="text" class="form-control form-control-sm" id="order_number" name="order_number">
            </div>
            <div class="form-group">
               <label class="sky">Status</label>
               <?php echo form_dropdown("order_status_id", $data['order_status_list'], NULL, "class='form-control form-control-sm  selectpicker'") ?>
            </div>
            <div class="form-group">
               <label class="sky">Due Date</label>
            <?php echo form_dropdown("dead_line", $data['dead_line_list'], NULL, "class='form-control form-control-sm  selectpicker'") ?>
            </div>
            <div class="form-group">
               <button type="submit" class="btn btn-sm bg-sky text-white btn-block mt-4">&nbsp &nbsp &nbsp <i class="fas fa-search"></i> Search &nbsp &nbsp &nbsp</button>
            </div>
         </form>
      </div>
   </div>
</div>
{{--  --}}


