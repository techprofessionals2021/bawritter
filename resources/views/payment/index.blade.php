@extends('layouts.app')
@section('title', 'Payments')
@section('content')
<div class="container page-container card br-20 shadow">
    <div class="row card-body">
        <div class="col-md-6">
            <h4 class="sky font-weight-bold ">Payments</h4>
        </div>
        <div class="col-md-6 text-right">

        </div>
        <div class="col-md-12">
            <table id="orders_table" class="table nowrap w-100">
                <thead>
                    <tr class="sky font-weight-bold mt-5">
                        <th scope="col">Date</th>
                        <th scope="col">Number</th>
                        <th scope="col">From</th>
                        <th scope="col">Method</th>
                        <th scope="col">Reference</th>
                        <th scope="col">Amount</th>
                        <th scope="col">Attachment</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script>
        $(function () {

            $('select').select2({
                theme: 'bootstrap4',
            });

            var oTable = $('#orders_table').DataTable({
                responsive: true,
                "bLengthChange": false,
                "bSort": false,
                searching: true,
                processing: true,
                serverSide: true,
                "ajax": {
                    "url": "{!! route('datatable_payments') !!}",
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
                        data: 'from',
                        name: 'from'
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
                        name: 'attachment',
                        className: "text-right"
                    }

                ]
            }).on('page.dt', function () {
                $('html, body').animate({
                    scrollTop: $(".dataTables_wrapper").offset().top
                }, 'slow');
            });


            $('#search-form').on('submit', function (e) {
                oTable.draw();
                e.preventDefault();
            });

        });

    </script>
@endpush
