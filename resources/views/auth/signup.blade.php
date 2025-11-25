<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>TIXID</title>

  <!-- Font Awesome -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap" rel="stylesheet">

  <!-- MDB -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/9.1.0/mdb.min.css" rel="stylesheet">
</head>
<body>
  <form class="w-50 d-block m-auto my-5" method="POST" action="{{ route('signup.register') }}">
    @csrf <!--generate token-->
    <!-- First & Last Name -->
    <div class="row mb-4">
      <div class="col">
        <div data-mdb-input-init class="form-outline">
          <input type="text" id="formFirstName" class="form-control @error ('first_name') is-invalid @enderror" name="first_name" value="{{ old('first_name') }}">
          <label class="form-label" for="formFirstName">First name</label>
        </div>
        {{-- Validation Error Message --}}
        @error('first_name')
            <small class="text-danger mt-2">{{ $message }}</small>
        @enderror

      </div>
      <div class="col">
        <div data-mdb-input-init class="form-outline">
          <input type="text" id="formLastName" class="form-control @error ('last_name') is-invalid @enderror" name="last_name" value="{{ old('last_name') }}">
          <label class="form-label" for="formLastName">Last name</label>
        </div>
         @error('last_name')
        <small class="text-danger mt-2">{{ $message }}</small>
          @enderror
      </div>
    </div>

    <!-- Email -->
     @error('email')
        <small class="text-danger mt-2">{{ $message }}</small>
          @enderror
    <div data-mdb-input-init class="form-outline mb-4">
      <input type="email" id="formEmail" class="form-control @error ('email') is-invalid @enderror" name="email" value="{{ old('email') }}">
      <label class="form-label" for="formEmail">Email address</label>
    </div>

    <!-- Password -->
     @error('password')
        <small class="text-danger mt-2">{{ $message }}</small>
          @enderror
    <div data-mdb-input-init class="form-outline mb-4">
      <input type="password" id="formPassword" class="form-control @error ('password') is-invalid @enderror" name="password" value="{{ old('password') }}">
      <label class="form-label" for="formPassword">Password</label>
    </div>

    <!-- Submit -->
    <button type="submit" class="btn btn-primary btn-block">Sign Up</button>

    <!-- Social Sign Up -->
    <div class="text-center mt-4">
      <p>or sign up with:</p>
      <button data-mdb-ripple-init type="button" class="btn btn-secondary btn-floating mx-1">
        <i class="fab fa-facebook-f"></i>
      </button>
      <button data-mdb-ripple-init type="button" class="btn btn-secondary btn-floating mx-1">
        <i class="fab fa-google"></i>
      </button>
      <button data-mdb-ripple-init type="button" class="btn btn-secondary btn-floating mx-1">
        <i class="fab fa-twitter"></i>
      </button>
      <button data-mdb-ripple-init type="button" class="btn btn-secondary btn-floating mx-1">
        <i class="fab fa-github"></i>
      </button>
    </div>

    <!-- Back to Home -->
    <div class="text-center mt-3">
      <a href="{{ route('home') }}">Kembali</a>
    </div>
  </form>

  <!-- JS Libraries -->
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.min.js" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/9.1.0/mdb.umd.min.js"></script>
</body>
</html>
