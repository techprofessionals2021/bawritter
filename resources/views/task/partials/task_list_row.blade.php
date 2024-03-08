<div class="card order-box shadow bg-white br-10 ">
   <a href="{{ route('orders_show', $order->id) }}">
      <h5  class="sky ">{{ $order->title }}</h5>
   </a>
   <div class="row">
      <div class="col-md-8 text-grey">
         {{ $order->number }}
      </div>
      <div class="col-md-2  text-right ">
         <div><span class="badge {{ $order->status->badge }}">{{ $order->status->name }}</span></div>
      </div>
   </div>
   <div class="row mt-4">
      <div class="col-md-8 sky">
         Client :
         @if(auth()->user()->hasRole('admin'))
            <a href="{{ route('user_profile', $order->customer_id) }}">
              <span class="text-grey">  {{ $order->customer->full_name }}</span>
            </a>
         @else
         <span class="text-grey" > {{ $order->customer->full_name }}</span>
         @endif
      </div>
      <div class="col-md-2 text-right sky">
         Budget
         <span class="text-grey" >  {{ format_money($order->staff_payment_amount) }}</span>
      </div>
   </div>
   <p class="order-instruction text-grey">
      <?php echo Str::words($order->instruction, 20, ' ...'); ?>
   </p>
   <div class="row order-overview">
      <div class="col-md-6"><span class="font-weight-bold sky">Service Type</span>
         <br>
         <span class="text-grey" >   {{ $order->service->name }}</span>
      </div>
      <div class="col-md-6"><span class="font-weight-bold sky">Assigned To</span>
         <br>
            @if(isset($order->assignee))
            <span class="text-grey" > {{ $order->assignee->full_name }}</span>
            @else
               None
            @endif
      </div>
   </div>
   <div class="row order-overview">
      <div class="col-md-6"><span class="font-weight-bold sky">Posted</span>
         <br>
         <span class="text-grey" > {{ $order->created_at->format('d-M-Y')}}</span>
      </div>
      <div class="col-md-6"><span class="font-weight-bold sky">Deadline</span>
         <br>
         <span class="text-grey" >  {{ $order->dead_line->format('d-M-Y')}}</span>
      </div>
   </div>
</div>
