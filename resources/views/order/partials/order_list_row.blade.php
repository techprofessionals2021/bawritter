<div class="card order-box shadow bg-white br-10 ">

   <div class="flex">
   <div class="col-6">
    <a href="{{ route('orders_show', $order->id) }}">
        <h5 class="sky font-weight-bold"><b>{{ $order->title }}</b></h5>
        <span class="text-grey ">{{ $order->number }}</span>
     </a>
  </div>
      <div class="">
         @if($order->archived)
            <span class="badge badge-secondary">Archived</span>
         @endif
         <span class="badge bg-sky text-white">
            {{ $order->service->price_type->name }}
            </span>
         <span class="badge {{ $order->status->badge }}">{{ $order->status->name }}</span>
         <div class="sky font-weight-bold text-align-end mt-3">
            Total : {{ format_money($order->total) }}
        </div>
      </div>
   </div>

   {{-- <div class="mt-4 row">
      <div class="col-md-12 sky">
         Client : <a  class="text-grey" href="{{ route('user_profile', $order->customer_id) }}">{{ $order->customer->full_name }}</a>
      </div>
   </div> --}}

   <div class="flex order-overview">
      <div class="col-md-6"><span class="font-weight-bold sky">Service Type</span>
         <br>
       <span class="text-grey"> {{ $order->service->name }}</span>
      </div>
      <div class="col-md-6"><span class="font-weight-bold sky">Admin Assigned To</span>
         <br>
         <?php
            if(isset($order->assignee))
            {
               echo '<a  class="text-grey"  href="'.route('user_profile', $order->staff_id).'">'.$order->assignee->full_name.'</a>';
            }
            ?>
      </div>
   </div>
   <div class="flex order-overview">
      <div class="col-md-6"><span class="font-weight-bold sky">Posted</span>
         <br>
         <span class="text-grey"> {{ convertToLocalTime($order->created_at) }}</span>
      </div>
      <div class="col-md-6"><span class="font-weight-bold sky">Deadline</span>
         <br>
         @if($order->order_status_id != ORDER_STATUS_PENDING_PAYMENT)
         <span class="text-grey">  {{ convertToLocalTime($order->dead_line) }}</span>
         @else
            <small class="text-danger ">Applicable after payment</small>
         @endif
         <span class="font-12 text-info"><i>
            (Urgency: {{ $order->urgency->value }} {{ $order->urgency->type }})
            </i>
         </span>
      </div>
   </div>

   <div class="flex order-overview">
      {{-- client assigned to --}}
      <div class="col-md-6"><span class="font-weight-bold sky">Client Assigned To</span>
        <br>
        <?php
           if(isset($order->assignee_from_client))
           {
              echo '<a class="text-grey" href="'.route('user_profile', $order->staff_id_from_client).'">'.$order->assignee_from_client->full_name.'</a>';
           }
           ?>
     </div>
     <div class="col-md-6  font-weight-bold sky">
        Client : <br/>
        <a  class="text-grey" href="{{ route('user_profile', $order->customer_id) }}">{{ $order->customer->full_name }}</a>
     </div>

   </div>
</div>
