
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://fonts.googleapis.com/css?family=Montserrat' rel='stylesheet'>
    <link rel="stylesheet" type="text/css" href="{{ asset('css/authentication.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body class=" align-items-center">

    <div class="">
        <div class="row no-gutters">
            <div class="col-6 rounded-top-right">
                <div class="mb-0 check">
                    <div class="card-body" id="authentication">
                       <div class="mb-5 text-center">
                          <h1 class="heading mt-lg-4">Register </h1>
                          <p class="text-muted mb-0">Create your new account</p>
                       </div>
                       <form method="POST" action="{{ route('register') }}">
                          @csrf
                          <div class="form-group mb-5">

                            <input id="first_name" type="text" class=" no-border form-control @error('first_name') is-invalid @enderror" name="first_name" value="{{ old('first_name') }}" required autocomplete="first_name" placeholder=" First Name">
                            @error('first_name')
                            <span class="invalid-feedback d-block" role="alert">
                            <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                         </div>
                         <div class="form-group mb-5">

                            <input id="last_name" type="text" class="no-border form-control @error('last_name') is-invalid @enderror" name="last_name" value="{{ old('last_name') }}" required autocomplete="last_name" placeholder=" Last Name">
                            @error('last_name')
                            <span class="invalid-feedback d-block" role="alert">
                            <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                         </div>
                         <div class="form-group  mb-5 @error('email') is-invalid @enderror">

                            <div class="input-group input-group-merge">

                               <input  id="email" type="email" class="no-border form-control @error('email') is-invalid @enderror" name="email" value="{{ session()->get( 'email' ) ?? '' }}" {{ session()->get( 'readonly' ) ?? ''}} required autocomplete="email" placeholder=" E-Mail Address">
                               @error('email')
                               <span class="invalid-feedback d-block" role="alert">
                               <strong>{{ $message }}</strong>
                               </span>
                               @enderror
                            </div>
                         </div>
                         <div class="form-group mb-5 @error('password') is-invalid @enderror">

                            <div class="input-group input-group-merge">

                               <input id="password" type="password" class= "no-border form-control" name="password" required autocomplete="new-password" placeholder="Password">
                            </div>
                            @error('password')
                            <span class="invalid-feedback d-block" role="alert">
                            <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                         <div class="form-group mb-5">

                            <div class="input-group input-group-merge">

                            <input id="password-confirm" type="password" class=" no-border form-control" name="password_confirmation" required autocomplete="new-password" placeholder="Confirm Password">
                            </div>
                            @error('password')
                            <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                         </div>

                          @if(isset($data['user_token']))
                          <input id="user_token" type="hidden" class="form-control" name="user_token" value="{{ $data['user_token'] }}">
                          @endif

                          <div class="form-group mb-0 text-center">
                            <button type="submit" class="btn btn-blue btn-lg br-20 p-0 text-white">
                                {{ __('Create my account') }}
                                {{-- <i class="fas fa-long-arrow-alt-right"></i> --}}
                          </button>
                          </div>
                       </form>
                       {{-- <div class="text-center mt-5">Don't have an account? <a href="{{ route('register') }}">Sign up</a></div> --}}
                    </div>
                 </div>
             </div>

            <div class="col-md-6 position-relative">
                <img src="images/login-image-1.png" class="img-fluid cover-image" alt="Your Image">
                <div class="header position-absolute top-50 start-50 translate-middle  bg-transparent">

                    <a class="" href="{{ route('login') }}">{{ __('Login') }}</a>
                    <a class="" href="{{ route('register') }}">{{ __('Register') }}</a>
                </div>
              </div>
        </div>
    </div>

    <style>

        body{
            font-family: 'Montserrat';
            font-size: 16px;
        }

        .cover-image{
            height: 100vh;
        }
        .p-0{
            padding: 3px 45px 5px 40px !important;

        }
        .heading{

             color: #5597D1;
             font-size: 60px;
        }

        .btn-blue{
            background-color: #5597D1;

        }

        .rounded-top-right {
            border-top-right-radius: 120px;
        }
        .br-20{
          border-radius:20px;
        }.check{
        width: 80%;
        margin: 0px auto;
        }

    .header {

      top: 10px;
      left: 390px;
      right: 20px;
      background-color: rgba(255, 255, 255, 0.8);
      padding: 10px;
      border-radius: 5px;
    }

    .header a {
      color: #333;
      margin-right: 10px;
    }

    .no-border {
  border: 0;
  box-shadow: none;
  border-bottom: 1px solid #ccc;
}
    </style>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

{{--  --}}


