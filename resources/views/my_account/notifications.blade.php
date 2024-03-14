@extends('layouts.app')
@section('title', 'Notifications')
@section('content')
<div class="container page-container">
   <div class="row card-body shadow br-20 mt-5 ">
      <div class="col-md-6">
         <h4 class="sky font-weight-bold">Your Notifications</h4>
         <br>
      </div>
      <div class="col-md-6 ">

      </div>

      <div class="col-md-12 mt-5">
         <table id="table" class="table">
            <thead>
               <tr class="font-weight-bold sky">
                  <th>Description</th>
                <th>Status</th>
                  <th>Time</th>

               </tr>
            </thead>
         </table>
      </div>
   </div>
</div>
@endsection

@push('scripts')
<script>
    $(function(){


      var oTable = $('#table').DataTable({
        "bLengthChange": false,
          searching: false,
          processing: true,
          serverSide: true,
          "ordering": false,
          "ajax": {
                  "url": "{!! route('datatable_notifications') !!}",
                  "type": "POST",
                  'headers': {
                      'X-CSRF-TOKEN': '{{ csrf_token() }}'
                  },

          },
          columns: [
              {data: 'description', name: 'description'},
              {data: 'status', name: 'status'},
              {data: 'created_at', name: 'created_at'},
          ]
      }).
      on('page.dt', function() {
          $('html, body').animate({
            scrollTop: $(".dataTables_wrapper").offset().top
          }, 'slow');
        });



    });
</script>
@endpush

