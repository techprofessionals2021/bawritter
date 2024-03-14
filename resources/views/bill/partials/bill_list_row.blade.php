<div class="media-body mb-3 card-body shadow br-20">
   <div class="">
      <div class="row">
         <div class="col-md-8 font-weight-bold">
            <a href="{{ route('my_requests_bills_show', $bill->id) }}">
              {{ $bill->staff_invoice_number}}
            </a>
         </div>
         <div class="col-md-3 text-right">
            @if($bill->paid)
              <span class="badge badge-success ">Paid</span>
            @else
              <span class="badge badge-warning ">Unpaid</span>
            @endif
         </div>
      </div>
      <div class="row mt-5">
         <div class="col-md-8">
          {{ $bill->created_at->format('d-M-y') }}
         </div>
         <div class="col-md-3 text-right">
            {{ format_money($bill->total) }}
         </div>
      </div>
   </div>
</div>
