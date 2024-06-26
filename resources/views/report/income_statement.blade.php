@extends('layouts.app')
@section('title', 'Income Statement')
@section('content')
<div class="container page-container">
   <div class="row">
      <div class="col-md-12">
         <h4 class="sky font-weight-bold">Income Statement</h4>
         <hr>
         <form class=" mt-10 form-inline" action="" method="GET" autocomplete="off">
            <label class="my-1 mr-2 sky" for="date">Date Range</label>
            <input type="text" class="form-control" id="reportrange" name="date" >
            &nbsp &nbsp
            <button class="btn bg-sky text-white" type="submit" name="type" value="requested">&nbsp &nbsp &nbsp &nbsp Get Report &nbsp &nbsp &nbsp &nbsp</button>
         </form>
      </div>
   </div>
   <br><br>
   @if(isset($data['record']) && $data['record'])
   <small class="form-text text-muted text-center">
    Report generated based on all the orders that are marked with status "Complete".
   </small>
   <div class="row mt-2">
      <div class="offset-md-2 col-md-8" style="background-color:#94c3ec; padding: 10px;">
         <div style="background-color: #fff; padding: 10px;">
            <h3 class="text-center sky font-weight-bold">Income Statement</h3>
            <div class="text-center text-grey">From {{ $data['from_date'] }} to {{ $data['to_date'] }}</div>
            <table id="data" class="table table-sm">
               <thead>
                  <tr>
                     <th scope="col" style="width: 40%;" class="sky">Sales Revenue</th>
                     <th scope="col" class="text-right"></th>
                  </tr>
               </thead>
               <tbody>
                  @foreach($data['record'] as $row)
                  <tr>
                     <td>{{ $row->service->name }}</td>
                     <td class="text-right">{{ format_money($row->amount) }}</td>
                  </tr>
                  @endforeach
                  <tr>
                     <th class="sky">Total</th>
                     <th class="text-right sky ">{{ format_money($data['total_revenue']) }}</th>
                  </tr>
               </tbody>
            </table>
            <table id="data" class="table table-sm">
               <thead>
                  <tr>
                     <th scope="col" class="sky" style="width: 40%;">(-) Expenses</th>
                     <th scope="col" class="text-right"></th>
                  </tr>
               </thead>
               <tbody>
                  <tr>
                     <td >Payments to staffs</th>
                     <td class="text-right">
                        {{ format_money($data['total_expense']) }}
                     </td>
                  </tr>
                  <tr>
                     <th class="sky font-weight-bold">Total</th>
                     <th class="text-right sky font-weight-bold">{{ format_money($data['total_expense']) }}</th>
                  </tr>
               </tbody>
            </table>
            <table id="data" class="table table-sm">
               <thead>
                  <tr>
                     <th scope="col" class="sky font-weight-bold" style="width: 40%;">Net Income</th>
                     <th scope="col" class="text-right font-weight-bold sky">{{ format_money($data['income']) }}</th>
                  </tr>
               </thead>
            </table>
         </div>
      </div>
   </div>
   @endif
   @if((!isset($data['record'])) && request('type') == 'requested')
   <div class="text-center">No record found</div>
   @endif
</div>
@endsection

@push('scripts')

<script>

  $(function() {

    <?php if($data['from_date']) { ?>

    var start = moment("{{ $data['from_date'] }}");
    var end = moment("{{ $data['to_date'] }}");

    <?php } else { ?>
    var start = moment().startOf('month');
    var end = moment().endOf('month');

    <?php } ?>


    function cb(start, end) {
        $('#reportrange span').html(start.format('YYYY-MM-DD') + ' - ' + end.format('YYYY-MM-DD'));
    }

    $('#reportrange').daterangepicker({
        startDate: start,
        endDate: end,
        ranges: dateRanges,// globaly defined
        locale: {
            format: 'YYYY-MM-DD'
        }
    }, cb);

    cb(start, end);
});

</script>
@endpush
