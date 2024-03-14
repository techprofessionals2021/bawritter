<form id="search-form" autocomplete="off">
   <div class="form-row mb-10">
      <div class="form-group col-md-2">
         <label class="sky ">Bill Number</label>
         <input type="text" class="form-control" id="number" name="number">
      </div>
      {{-- <div class=" form-group col-md-2">
         <label class="sky">From</label>
         <?php echo form_dropdown("from", $data['staff_list'], NULL, " class='form-control selectpicker'") ?>
      </div>

      <div class="form-group col-md-2">
         <label class="sky">Status</label>
         <?php echo form_dropdown("bill_status_list", $data['bill_status_list'], NULL, "class='form-control selectpicker'") ?>
      </div> --}}
      <div class="form-group col-md-2 pt-30">
         <button type="submit" class="btn bg-sky text-white">&nbsp &nbsp &nbsp <i class="fas fa-search"></i> Search &nbsp &nbsp &nbsp</button>
      </div>
   </div>
</form>
