<div class="sticky-top">
   <div class="card mb-40 outlined br-20">
      <div class="card-header sky">Filter <i class="fa fa-align-center"></i></div>
      <div class="card-body">
         <form id="search-form" autocomplete="off">
            <div class="form-group">
               <label class="sky">Name or Email</label>
               <input type="text" class="form-control text-grey" id="search" name="search">
            </div>
            <div class="form-group">
               <div class="custom-control custom-checkbox">
                  <input type="checkbox" class="custom-control-input" value="1" id="inactive">
                  <label class="custom-control-label text-grey" for="inactive">Inactive Users</label>
                </div>
            </div>
            @if($data['type'] == 'staff')
            <div class="form-group">
               <label class="sky">Area of expertise</label>
               <?php echo form_dropdown("tag_id[]", $data['tag_id_list'], NULL, "class='form-control form-control-sm  multSelectpicker' multiple='multiple' id='tag_id'") ?>
            </div>
            @endif
            <div class="form-group">
               <button type="submit" class="btn bg-sky btn-sm btn-block mt-4 text-white">&nbsp &nbsp &nbsp <i class="fas fa-search"></i> Search &nbsp &nbsp &nbsp</button>
            </div>
         </form>
      </div>
   </div>
</div>
