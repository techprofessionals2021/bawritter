<div class=" card order-box shadow bg-white br-10 ">
     <a href="{{ route('orders_show', $order->id) }}">
        <h5 class="sky">{{ $order->title }}</h5>
     </a>
    <div class="row">
        <div class="col-md-8">
          <span class="text-grey"> {{ $order->number }}</span>
        </div>
        <div class="col-md-2  text-right">
            @if ($order->archived)
                <span class="badge badge-secondary ">Archived</span>
            @endif
            <span class="badge bg-sky text-white">
                {{ $order->service->price_type->name }}
            </span>
            <span class="badge {{ $order->status->badge }}">{{ $order->status->name }}</span>
        </div>
    </div>

    <p class="order-instruction text-grey">

        <?php echo Str::words($order->instruction, 20, ' ...'); ?>
    </p>
    <div class="row order-overview">
        <div class="col-md-5"><span class="font-weight-bold sky">Service Type</span>
            <br>
            <span class="text-grey">  {{ @$order->service->name }}</span>
        </div>
        <div class="col-md-5"><span class="font-weight-bold sky">Assigned To</span>
            <br>
            <?php
            // if (isset($order->assignee)) {
            //     echo '<a href="' . route('user_profile', $order->staff_id) . '">' . $order->assignee->full_name . '</a>';
            // }
            ?>
            @if (isset($order->assignee))
                <span class="text-success">
                    @if (auth()->user()->hasRole('admin'))
                        <a href="{{ route('user_profile', @$order->staff_id) }}">
                            <span class="text-grey">{{ @$order->assignee->full_name }}</span>
                        </a>
                    @else
                    <span class="text-grey">{{ @$order->assignee->full_name }}</span>
                    @endif
                </span>
            @else

                @if (auth()->user()->hasRole('admin'))
                    None
                @else
                       <span class="text-grey">{{ @$order->assignee_from_client->full_name }}</span>
                @endif
            @endif
        </div>
    </div>
    <div class="row order-overview">
        <div class="col-md-5"><span class="font-weight-bold sky">Posted</span>
            <br>
            <span class="text-grey">{{ convertToLocalTime($order->created_at) }}</span>
        </div>
        <div class="col-md-5"><span class="font-weight-bold sky">Deadline</span>
            <br>
            @if ($order->order_status_id != ORDER_STATUS_PENDING_PAYMENT)
            <span class="text-grey">{{ convertToLocalTime($order->dead_line) }}</span>
            @else
                <small class="sky">Applicable after payment</small>
            @endif
            <span class="font-12 sky"><i>
                    (Urgency:
                    <span class="text-grey">{{ $order->urgency->value }} {{ $order->urgency->type }}</span>
                    )
                </i>
            </span>
        </div>
    </div>
</div>
