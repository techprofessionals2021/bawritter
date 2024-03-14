<div class="sticky-top ">
   <div class="card mb-40 outlined br-20">
      <div class="card-header sky">Filter <i class="fa fa-align-center"></i></div>
      <div class="card-body">
         <form id="search-form" autocomplete="off">
            <div class="form-group">
               <label class="sky">Name/Email/Applicant Number</label>
               <input type="text" class=" text-grey form-control" name="general_text_search" >
            </div>
            <div class="form-group">
               <label class="sky">Status</label>
               <?php echo form_dropdown("applicant_status_id", $data['statuses'], NULL, "class='form-control form-control-sm  selectpicker'") ?>
            </div>
            <div class="form-group">
               <label class="sky">Referrer</label>
               <?php echo form_dropdown("referral_source_id", $data['referral_sources'], NULL, "class='form-control form-control-sm  selectpicker'") ?>
            </div>
            <div class="form-group">
               {{-- <button type="submit" class="btn bg-sky">&nbsp &nbsp &nbsp <i class="fas fa-search"></i> Search &nbsp &nbsp &nbsp</button> --}}
               <button type="submit" class="btn bg-sky btn-sm btn-block mt-4 text-white">&nbsp &nbsp &nbsp <i class="fas fa-search"></i> Search &nbsp &nbsp &nbsp</button>
            </div>
         </form>
      </div>
   </div>
</div>
