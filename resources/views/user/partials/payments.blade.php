<h4 class="sky">My Payments</h4>
<table id="table" class="table nowrap w-100">
    <thead class="sky">
        <tr>
            <th scope="col" data-priority="1">Date</th>
            <th scope="col">Number</th>
            <th scope="col" data-priority="2">Method</th>
            <th scope="col">Reference</th>
            <th scope="col" data-priority="3">Amount</th>
            <th scope="col">Attachment</th>
        </tr>
    </thead>
</table>
@section('innerJs')
<script>
    $(function () {
        var oTable = $('#table').DataTable({
            responsive: true,
            "bLengthChange": false,
            "bSort": false,
            searching: false,
            processing: true,
            serverSide: true,
            "ajax": {
                "url": "{!! route('datatable_payments', ['user_id' => $user->id]) !!}",
                "type": "POST",
                'headers': {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            },

            columns: [{
                    data: 'date',
                    name: 'date'
                },
                {
                    data: 'number',
                    name: 'number'
                },
                {
                    data: 'method',
                    name: 'method'
                },
                {
                    data: 'reference',
                    name: 'reference'
                },

                {
                    data: 'amount',
                    name: 'amount',
                    className: "text-right"
                },
                {
                    data: 'attachment',
                    name: 'attachment'
                },

            ]
        }).on('page.dt', function () {
            $('html, body').animate({
                scrollTop: $(".dataTables_wrapper").offset().top
            }, 'slow');
        });
    });
</script>
@endsection
