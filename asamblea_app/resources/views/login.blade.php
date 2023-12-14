<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Iniciar sesión - Asamblea</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <link rel="stylesheet" href="css/styles.css">

       
    </head>
<div class="container-fluid ps-md-0">
  <div class="row g-0">
    <div class="d-none d-md-flex col-md-4 col-lg-6 bg-image"></div>
    <div class="col-md-8 col-lg-6">
      <div class="login d-flex align-items-center py-5">
        <div class="container">
          <div class="row">
            <div class="col-md-9 col-lg-8 mx-auto">
                @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
                @endif

              <h3 class="login-heading mb-4">¡Bienvenido al software de asamblea!</h3>
              <form action="{{route('iniciar')}}" method="POST">
                @csrf
                <div class="form-floating mb-3">
                    <input name="loginname" type="email" id="examplename"  placeholder="Correo electrónico" class="form-control">
                    @error('email') <span class="error">{{ $message }}</span> @enderror
                  <label for="floatingInput">Correo electrónico</label>
                </div>
                <div class="form-floating mb-3"> 
                    <input name="loginpassword" type="password" placeholder="Contraseña" class="form-control">
                    @error('password') <span class="error">{{ $message }}</span> @enderror
                  <label for="floatingPassword">Contraseña</label>
                </div>
                <div class="d-grid">
                  <button type="submit" class="btn btn-outline-primary btn-lg" type="submit">Iniciar sesión</button>
                  <div class="text-center">
                  </div>
                </div>

                </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

    </body>
</html>


