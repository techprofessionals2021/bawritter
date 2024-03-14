<div class="row">
   <div class="col-md-8">
      <div class="card order-box shadow bg-white br-10">
        <div class="row justify-content-center">
            <div class="col-10">
         <h5 class="sky "><b>{{ $order->title }}</b></h5>
         <span class="text-grey"> {{ $order->number }}</span>
            </div>
         <div class="col-2">
            <span class="badge badge-brilliant-rose ">
                {{ $order->service->price_type->name }}
            </span>
        </div>
        </div>
         <hr>
         <div class="row">
            {{-- <div class="col-md-6">
               {{ $order->number }}
            </div> --}}
            <div class="col-md-6 text-right">
               {{-- <div>
                  @if($order->archived)
                     <span class="badge badge-secondary">Archived</span>
                  @endif
                  <span class="badge {{ $order->status->badge }}">{{ $order->status->name }}</span>
               </div> --}}
            </div>
         </div>
         <div class="row mt-4">
            @role('admin')
            <div class="col-md-8 sky">
               Client : <a  class="sky" href="{{ route('user_profile', $order->customer_id) }}">{{ @$order->customer->full_name }}</a>
            </div>
            <div class="col-md-4 text-right bold sky">
               Total : {{ format_money($order->total) }}
            </div>
            @endrole
            @role('staff')
            <div class="col-md-4 sky">
               Payout Budget: {{ format_money($order->staff_payment_amount) }}
            </div>
            @endrole
         </div>
         <p class="order-instruction ">
            <?php echo $order->instruction; ?>
         </p>
         <hr>
         <div class="row order-overview">
            <div class="col-md-4"><span class="font-weight-bold sky">Service Type</span>
               <br>
                <span class="text-grey">{{ $order->service->name }}</span>
               @if($order->service->price_type_id == PriceType::PerPage)
                  <div class="font-12 text-grey "><i>* ({{ $order->spacing_type}} spacing)</i></div>
               @endif
            </div>
            <div class="col-md-4"><span class="font-weight-bold sky ">Assigned To</span>
               @if(isset($order->assignee))
               <br>
               <span class="text-success">
                  @if(auth()->user()->hasRole('admin'))
                     <a class="text-grey" href="{{ route('user_profile', $order->staff_id) }}">
                       <span class="text-grey"> {{ @$order->assignee->full_name }}</span>
                     </a>
                  @else
                  <span class="text-grey">  {{ @$order->assignee->full_name }}</span>
                  @endif
               </span>
               @else
                 <br>
                @if(auth()->user()->hasRole('admin'))

                <span class="text-grey"> None</span>
               @else
                  {{ @$order->assignee_from_client->full_name }}
               @endif
               @endif
            </div>

            @if(auth()->user()->hasRole('admin'))
            <div class="col-md-4"><span class="font-weight-bold sky">Client Assigned To</span>
                <br>
                <span class="text-grey"> {{ @$order->assignee_from_client->full_name }}</span>
            </div>
            @endif


         </div>
         <div class="row order-overview">
            <div class="col-md-4"><span class="font-weight-bold sky">Posted</span>
               <br>
               <span class="text-grey"> {{ convertToLocalTime($order->created_at)}}</span>
            </div>
            <div class="col-md-4"><span class="font-weight-bold sky">Deadline</span>
               <br>
               @if($order->order_status_id != ORDER_STATUS_PENDING_PAYMENT)
                  <span class="text-grey"> {{ convertToLocalTime($order->dead_line) }}</span>
               @else
                  <small class="text-danger">Applicable after payment</small>
               @endif
               <span class="font-12 text-grey"><i>
                  (Urgency: {{ $order->urgency->value }} {{ $order->urgency->type }})
                  </i>
               </span>
            </div>
            <div class="col-md-4">
                <span class="font-weight-bold sky">Additional Services</span>
                <br>
                <ol class="pl-4">
                   @foreach($order->added_services()->get() as $service)
                   <li class="text-grey">{{ $service->name }}</li>
                   @endforeach
                </ol>
             </div>
         </div>
         <div class="row order-overview">
            <div class="col-md-4">
               <span class="font-weight-bold sky">Additional Services</span>
               <br>
               <ol class="pl-4">
                  @foreach($order->added_services()->get() as $service)
                  <li class="text-grey">{{ $service->name }}</li>
                  @endforeach
               </ol>
            </div>
            <div class="col-md-4">
               <div class="font-weight-bold sky">Quantity</div>
               <span class="text-grey"> {{ $order->quantity }} {{ $order->unit_name }}</span>
            </div>
            <div class="col-md-4">
                <span class="font-weight-bold sky">Revision Requested</span>
                <br>
                <span class="text-grey"> {{  $order->revisionUsed() }}</span>
             </div>
         </div>

         <div class="row order-overview">
            {{-- <div class="col-md-6">
               <span class="font-weight-bold sky">Revision Requested</span>
               <br>
               <span class="text-grey"> {{  $order->revisionUsed() }}</span>
            </div> --}}
            <div class="col-md-6">
               <div class="font-weight-bold sky">Attachments</div>
               <ol class="pl-4">
                  @foreach($order->attachments as $attachment)
                  <li><a class="text-grey" target="_blank" href="{{ route('download_attachment', 'file=' .  $attachment->name) }}">{{ $attachment->display_name }}</a></li>
                  @endforeach
               </ol>
            </div>
         </div>
      </div>
   </div>
   <div class="col-md-4">
      @if($order->customer_id == auth()->user()->id && $order->order_status_id == ORDER_STATUS_PENDING_PAYMENT)
      <a class="btn btn-sm btn-warning mb-4" href="{{ route('pay_for_existing_order',$order->id) }}">
         <i class="fas fa-money-bill-wave"></i> Pay Now</a>
      @endif

      @if(auth()->user()->hasRole('admin') && $order->order_status_id == ORDER_STATUS_PENDING_PAYMENT)
      <div>
      <a id="delete_order" class="btn btn-sm btn-danger" href="{{ route('orders_destroy', $order->id) }}">
         <i class="fas fa-trash"></i> Delete</a>
      </div>
      @endif

      @if(!paymentIsPending($order->order_status_id))

            @if($order->order_status_id == ORDER_STATUS_COMPLETE)
               @include('order.partials.rating')
               <br>
            @endif
            @if($order->customer_id == auth()->user()->id)
               @include('order.partials.deliverables')
            @endif
            @if($order->staff_id == auth()->user()->id)
               @include('order.partials.submit_work')
               <br>
            @endif
            @if(auth()->user()->hasRole('admin'))
               @include('order.partials.assignee')
               <br>
            @endif
            @role('admin')
               @include('order.partials.manage_status')
            @endrole
            @if(auth()->user()->hasRole('staff') && settings('enable_browsing_work') == 'yes' && $order->order_status_id == ORDER_STATUS_NEW && empty($order->staff_id))
               @include('order.partials.choose_work')
            @endif

            @role('admin')
            <div class="mt-4 row">
                <div class="col-5">
               @if($order->isAFollower(auth()->user()->id))
            {{-- <a href="{{ route('orders_unfollow', $order->id) }}">Unfollow</a> --}}
               {{-- <button  href="{{ route('orders_unfollow', $order->id) }}" type="submit" class="btn bg-sky text-white btn-sm btn-block mt-4 ml-15 br-20">Unfollow</button> --}}
               @else
               {{-- <button  href="{{ route('orders_follow', $order->id) }}" type="submit" class="btn bg-sky text-white btn-sm btn-block mt-4 ml-15 br-20">Follow</button> --}}
                {{-- </div> --}}
                  {{-- <a href="{{ route('orders_follow', $order->id) }}">Follow</a> --}}
               @endif
            </div>


                <div class="col-5">
                  @if($order->archived)
                  {{-- <button  href="{{ route('orders_follow', $order->id) }}" type="submit" class="btn sky btn-sm btn-block mt-4">Unarchive</button> --}}
                     {{-- <a href="{{ route('orders_unarchive', $order->id) }}">Unarchive</a> --}}
                  @else

                  {{-- <button  href="{{ route('orders_follow', $order->id) }}" type="submit" class="btn sky  btn-sm btn-block mt-4">Archive</button> --}}
                     {{-- <a href="{{ route('orders_archive', $order->id) }}">Archive</a> --}}
                  @endif

                </div>
            </div>
            @endrole
      @endif
   </div>
</div>
