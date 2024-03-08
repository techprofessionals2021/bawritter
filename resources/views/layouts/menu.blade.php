
<nav class="navbar navbar-expand-md shadow-sm navbar-background">
   <div class="container">
      <a class="navbar-brand" href="{{ url('/') }}">
      {{-- <img class="logo" src="{{ get_company_logo() }}" alt="{{ config('app.name', 'ProWriter') }}"> --}}
      <img class="logo" src="{{asset('images/img-logo.png')}}" alt="logo">
      </a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
      <i class="fas fa-bars"></i>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
         <ul class="navbar-nav mr-auto ">
            @role('admin')

            <li class=" nav-item ml-3">
                {{-- <img src="images/dashboard-icon.png" alt="" class="mr-1"> --}}
                 <a class="text-grey" href="{{ route('dashboard') }}">Dashboard</a>
            </li>
            <li class="nav-item ml-3">
                {{-- <img src="images/Vector.png" alt="" class="mr-1"> --}}
               <a class="text-grey" href="{{ route('orders_list') }}">Orders</a>
            </li>
            <li class="nav-item ml-3">
                {{-- <img src="images/customer-icon.png" alt="" class="mr-1"> --}}
               <a class="text-grey" href="{{ route('users_list', ['type' => 'customer']) }}">Customers</a>
            </li>
            <li class="nav-item ml-3">
                {{-- <img src="images/writer-icon.png" alt="" class="mr-1"> --}}
               <a class="text-grey" href="{{ route('users_list', ['type' => 'staff']) }}">
               Writers
               </a>
            </li>
            <li class="nav-item dropdown ml-3">
                {{-- <img src="images/payment.png" alt="" class="mr-1"> --}}
               <a id="payments" class="dropdown-toggle text-grey" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
               Payments <span class="caret"></span>
               </a>
               <div class="dropdown-menu " aria-labelledby="payments">
                  <a class="dropdown-item" href="{{ route('pending_payment_approvals') }}">Pending Approval</a>
                  <a class="dropdown-item" href="{{ route('payments_list') }}">Payments List</a>
                  <a class="dropdown-item" href="{{ route('wallet_transactions') }}">Wallet Transactions</a>
               </div>
            </li>
            <li class="nav-item dropdown ml-3">
                {{-- <img src="images/manage.png" alt="" class="mr-1"> --}}
               <a id="managerial" class="text-grey dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
               Manage <span class="caret"></span>
               </a>
               <div class="dropdown-menu" aria-labelledby="managerial">
                  <a class="dropdown-item" href="{{ route('bills_list') }}">Bills from Writers</a>
                  <a class="dropdown-item" href="{{ route('settings_main_page') }}">Settings</a>
                  <a class="dropdown-item" href="{{ route('users_list', ['type' => 'admin']) }}">Admin Users</a>
                  <a class="dropdown-item" href="{{ route('job_applicants') }}">Job Applicants</a>
               </div>
            </li>
            <li class="nav-item dropdown ml-3">
                {{-- <img src="images/report.png" alt="" class="mr-1"> --}}
               <a id="managerial" class=" dropdown-toggle text-grey" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
               Reports <span class="caret"></span>
               </a>
               <div class="dropdown-menu" aria-labelledby="managerial">
                  <a class="dropdown-item" href="{{ route('income_statement') }}">Income Statement</a>
                  <a class="dropdown-item" href="{{ route('total_wallet_balance') }}">Total Wallet Balance</a>

               </div>
            </li>

            @endrole
            @role('staff')
            {{-- @if(strtolower(settings('enable_browsing_work')) == 'yes')
            <li class="nav-item">
               <a class="nav-link" href="{{ route('browse_work') }}">Browse Work</a>
            </li>
            @endif --}}
            <li class="nav-item">
               <a class="nav-link" href="{{ route('tasks_list') }}">My Tasks</a>
            </li>
            <li class="nav-item dropdown">
               <a id="payment_request" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
               Payment Request <span class="caret"></span>
               </a>
               <div class="dropdown-menu" aria-labelledby="payment_request">
                  <a class="dropdown-item" href="{{ route('request_for_payment') }}">
                  Request for payment
                  </a>
                  <a class="dropdown-item" href="{{ route('my_requests_for_payment') }}">
                  List of payment requests
                  </a>
               </div>
            </li>
            @endrole
            @auth
            @unlessrole('staff|admin')
            <li class="nav-item">
               <a class="nav-link" href="{{ route('my_orders') }}">My Orders</a>
            </li>
            <li class="nav-item">
               <a class="nav-link" href="{{ route('order_page') }}">New Order</a>
            </li>
            <li class="nav-item">
               <a class="nav-link" href="{{ route('my_account',['group' => 'wallet']) }}">My Wallet</a>
            </li>
            @endunlessrole
            @endauth
         </ul>

         <ul class="navbar-nav ">
            @guest
            @if(!settings('disable_writer_application'))
            <li class="nav-item">
               <a class="nav-link" href="{{ route('writer_application_page') }}">
                  {{ settings('writer_application_page_link_title') }}
               </a>
            </li>
            @endif
            <li class="nav-item">
               <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
            </li>
            @if (Route::has('register'))
            <li class="nav-item">
               <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
            </li>
            @endif
            @else
            @hasanyrole('staff|admin')
            <li class="nav-item dropdown" style="z-index: 2000 !important;">
               @include('layouts.notification_bell')
            </li>
            @endhasanyrole
             <li class="nav-item dropdown" style="z-index: 2000 !important;">
                {{-- <img src="{{Auth::user()->photo }}" class="card-profile-image avatar rounded-circle shadow hover-shadow-lg user-avatar" alt="..."> --}}

                {{-- <img alt="Image "  src="{{asset('images/user-placeholder.jpg')}}"  class="mr-3  avatar rounded-circle "> --}}

                @auth
                @php
                    $user = auth()->user();
                    $profilePic = $user->photo ? asset(Storage::url($user->photo)) : asset('images/user-placeholder.jpg');
                @endphp

                <img
                  alt="profile"
                  src="{{ $profilePic }}"
                  class="mr-3 avatar rounded-circle"
                >
              @endauth



                <!-- Assuming $user is the variable containing the User model instance -->

               {{-- <a id="navbarDropdown" class=" dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
               {{ Auth::user()->first_name }}
               <p class="role"> {{ Auth::user()->roles->first()->name }} </p>
               <span class="caret"></span>
               </a> --}}

               <a id="navbarDropdown" class="dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                {{ Auth::user()->first_name }}

                {{-- <span class="role"> {{ Auth::user()->roles->first()->name }}</span> --}}
                <span class="caret"></span>

               </a>
                {{-- <span class="role"> {{ Auth::user()->roles->first()->name }}</span> --}}
               <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">

                  <a class="dropdown-item" href="{{ route('my_account') }}">
                  My Account
                  </a>
                  @hasanyrole('staff|admin')
                  <a class="dropdown-item" href="{{ route('my_account',['group' => 'wallet']) }}">My Wallet</a>
                  <a class="dropdown-item" href="{{ route('my_orders') }}">My Orders</a>
                  <a class="dropdown-item" href="{{ route('order_page') }}">New Order</a>

                  <div class="dropdown-divider"></div>
                  @endhasanyrole
                  <a class="dropdown-item" href="{{ route('logout') }}"
                     onclick="event.preventDefault();
                     document.getElementById('logout-form').submit();">
                  {{ __('Logout') }}
                  </a>
                  <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                     @csrf
                  </form>
               </div>
            </li>
            @endguest
         </ul>
      </div>
   </div>
</nav>
<style>
    .nav-item {
    display: flex;
    align-items: center;
}

.icon {
    margin-right: 5px;  /* Adjust the margin as needed for spacing between icon and text */
}




    </style>
