<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <title>Login | Coaching Center System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="{{ asset('dist/css/adminlte.css') }}" />
  </head>
  <body class="login-page bg-body-secondary">
    <div class="login-box">
      <div class="card card-outline card-primary">
        <div class="card-header text-center">
          <h1 class="mb-0"><b>Coaching</b> Center</h1>
        </div>
        <div class="card-body login-card-body">
          <p class="login-box-msg">Sign in to start your session</p>

          @if($errors->any())
            <div class="alert alert-danger">
              <ul class="mb-0 ps-3">
                @foreach($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif

          <form action="{{ route('login.post') }}" method="post">
            @csrf
            <div class="input-group mb-3">
              <input type="text" name="username" class="form-control" placeholder="Username" required />
            </div>
            <div class="input-group mb-3">
              <input type="password" name="password" class="form-control" placeholder="Password" required />
            </div>
            <div class="row">
              <div class="col-12">
                <button type="submit" class="btn btn-primary btn-block w-100">Sign In</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </body>
</html>