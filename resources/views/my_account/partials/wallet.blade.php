<div class="card shadow br-20">
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <h5 class="card-title sky font-weight-bold">Wallet Topup</h5>
            </div>
            <div class="col-md-6">
                <h5 class="card-title text-right sky">Current Balance:
                    <span class="text-grey">{{ format_money(auth()->user()->wallet()->balance()) }}</span></h5>
            </div>
        </div>
        <form action="{{ route('my_wallet_topup') }}" method="POST" autocomplete="off">
            @csrf
            <div class="form-group">
                <label class="sky">Amount</label>
                <input type="text" class="form-control {{ showErrorClass($errors, 'amount') }}" name="amount" value="{{ old('amount') }}">
                <div class="invalid-feedback d-block">{{ showError($errors, 'amount') }}</div>
            </div>
            <button type="submit" class="btn bg-sky text-white">Choose payment option</button>
        </form>

    </div>
</div>
