@extends('layouts.app')
@section('title', 'Pay with PayPal')
@section('content')
<div class="container page-container">
    <div class="row">
        <div class="col-md-12">
            <h3 class="font-weight-bold sky">Pay with PayPal</h3>
            {{-- <hr> --}}
        </div>
        {{-- <div class="col-md-6 d-none d-lg-block ">
            <div class="checkout-image-cover">
                <img src="{{ asset('images/payment.svg') }}" class="img-fluid">
            </div>
        </div> --}}
        <div class="col-md-12">
            {{-- @include('checkout.back_to_payment_options') --}}

            <div class="card shadow br-20 mt-5">
                <div class="card-body">
                    <div class="d-flex justify-content-between ">
                        <h4 class="h4 sky font-weight-bold">Total</h4>
                        <div class="h4 sky font-weight-bold">{{ format_money($data['total']) }}</div>
                    </div>
                    <hr>
                    <form action="{{ route('paypalPayment') }}" method="post">
                        @csrf
                        <input type="hidden" name="amount" value="{{ $data['total'] }}">
                        <button type="submit" class="btn bg-sky text-white btn-block confirm-button">
                            <i class="fab fa-paypal"></i> Pay with PayPal
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
