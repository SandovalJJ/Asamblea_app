<!doctype html>
<html lang="en">
  <head>
  	<title>Asamblea Coopserp - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    @extends('layouts.head')
  </head>
  <body>
		<div class="wrapper d-flex align-items-stretch">
			@extends('layouts.sidebar')
            @section('content')
            <button type="button" id="sidebarCollapse" class="btn btn-primary">
              <i class="fa fa-bars"></i>
            </button>
      <div id="content" class="p-4 p-md-5">
        @auth
        @if(Auth::user()->rol == 'admin')
        <h2 class="mb-4">¡Bienvenid@ al software de asamblea, {{ Auth::user()->name }}!</h2>
        <div id="content" class="">
          <nav>
            <div class="nav nav-tabs" id="nav-tab" role="tablist">
              <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#nav-home" type="button" role="tab" aria-controls="nav-home" aria-selected="true">Listar</button>
              <button class="nav-link" id="nav-profile-tab" data-bs-toggle="tab" data-bs-target="#nav-profile" type="button" role="tab" aria-controls="nav-profile" aria-selected="false">Agregar usuario</button>
            </div>
          </nav>
          <div class="tab-content" id="nav-tabContent">
            <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab" tabindex="0"> <br> Tabla de usuarios
                  <table id="tabla-usuarios" class="table">
                    <thead>
                      <tr>
                        <th scope="col">Id</th>
                        <th scope="col">Nombre</th>
                        <th scope="col">Email</th>
                        <th scope="col">Rol</th>
                        <th scope="col">acciones</th>
                      </tr>
                    </thead>
                    <tbody>
                    </tbody>
                  </table>
            </div>
            <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab" tabindex="0">
 
              <div class="registration-form">
                <form id="registro-usuario" class="user-form">
                  @csrf
                    <div class="form-column">
                        <div class="form-group">
                          <label for="nombreU">Nombre</label>
                          <input type="text" class="form-control item" name="nombreU" id="nombreU">
                        </div>
                        <div class="form-group">
                          <label for="correo">Email</label>
                          <input type="email" class="form-control item" name="email" id="correo">
                        </div>
                        <div class="form-group">
                          <label for="cedula">Cédula</label>
                          <input type="text" class="form-control item" name="cedula" id="cedula">
                        </div>
                    </div>
                    <div class="form-column">
                        <div class="form-group">
                          <label for="agencia">Agencia</label>
                          <input type="text" class="form-control item" name="agencia" id="agencia">
                        </div>
                        <div class="form-group">
                          <label for="cuenta">Cuenta</label>
                          <input type="text" class="form-control item" name="cuenta" id="cuenta">
                        </div>
                        <div class="form-group">
                          <label for="telefono">Teléfono</label>
                          <input type="text" class="form-control item" name="telefono" id="telefono">
                        </div>
                        <div class="form-group">
                          <p>Selecciona un rol</p>
                          <select class="form-control" name="rol" id="rol">
                              <option value="DELEGADO">DELEGADO</option>
                              <option value="SUPLENTE">SUPLENTE</option>
                          </select>
                      </div>
                    </div>
                    <div class="form-group">
                      <button type="submit" class="btn btn-primary create-account">Crear usuario</button>
                    </div>
                </form>
            </div>
            </div>
          </div>
        </div>
      </div>
<div class="modal fade" id="confirmodal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Confirmación</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        ¿Desea eliminar el registro seleccionado?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        <button type="button" id="btnEliminar" name="btnEliminar" class="btn btn-danger">Eliminar</button>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="editarUsuarioModal" tabindex="-1" aria-labelledby="editarUsuarioModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editarUsuarioModalLabel">Editar Usuario</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="editar-usuario-form">
          <input type="hidden" name="id" id="usuario_id">
          <div class="form-group">
            <label for="edit_nombreU">Nombre</label>
            <input type="text" class="form-control" id="edit_nombreU" name="nombreU">
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            <button type="submit" class="btn btn-primary">Actualizar Usuario</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

@endif
@endauth
		</div>
    @extends('layouts.footer')
    <script>
      $(document).ready(function(){
    var user = $('#tabla-usuarios').DataTable({
        responsive: true,
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('usuario.index') }}",
            method: 'GET'
        },
        columns: [
            { data: 'id' },
            { data: 'name' },
            { data: 'email' },
            { data: 'rol' },
            { data: 'action', orderable: false }
        ],
        language: {
            url: "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json"
        }
    });
      })
      </script>
    <script>
      $('#registro-usuario').submit(function(e){
    e.preventDefault();

    // Recolectar los valores de los campos del formulario
    var nombre = $('#nombreU').val();
    var email = $('#correo').val();
    var cedula = $('#cedula').val();
    var agencia = $('#agencia').val();
    var cuenta = $('#cuenta').val();
    var telefono = $('#telefono').val();
    var rol = $('#rol').val();
    var _token = $("input[name=_token]").val();

    // Crear un objeto con los datos del formulario
    var formData = {
        nombreU: nombre,
        email: email,
        cedula: cedula,
        agencia: agencia,
        cuenta: cuenta,
        telefono: telefono,
        rol: rol,
        _token: _token
    };


    // Enviar los datos al servidor usando AJAX
    $.ajax({
        url: "{{ route('usuario.registrar') }}",
        type: "POST",
        data: formData,
        success: function(response) {
            if(response) {
                $('#registro-usuario')[0].reset();
                toastr.success('El registro se ingresó correctamente', 'Nuevo Registro', {timeOut: 3000});
                $('#tabla-usuarios').DataTable().ajax.reload();
            }
        },
        error: function(response) {
            toastr.error('Hubo un error al registrar el usuario', 'Error', {timeOut: 3000});
        }
    });
});
    </script>
    <script>
      var user_id;
      $(document).on('click', '.delete', function(){
    user_id = $(this).attr('id');
    $('#confirmodal').modal('show');
});
$('#btnEliminar').click(function(){
    $.ajax({
        url: "usuario/eliminar/" + user_id,

        success: function(data){
            $('#confirmodal').modal('hide');
            toastr.warning('El registro se ha eliminado correctamente', 'Eliminar Registro', {timeOut: 3000});
            $('#tabla-usuarios').DataTable().ajax.reload();
        },
        error: function(error){
            toastr.error('Hubo un error al eliminar', 'Error', {timeOut: 3000});
            $('#btnEliminar').text('Eliminar'); // Restablece el texto si hay un error
        }
    });
});

$('#confirmodal').on('hide.bs.modal', function (e) {
    $('#btnEliminar').text('Eliminar');
});
$(document).on('click', '.edit', function(){
    var id = $(this).data('id');
    $.ajax({
        url: '/usuario/' + id + '/editar',
        type: 'GET',
        success: function(resp) {
            $('#usuario_id').val(resp.id);
            $('#edit_nombreU').val(resp.name);
            // Continúa estableciendo los valores para los demás campos...
            $('#editarUsuarioModal').modal('show');
        }
    });
});



    </script>
    @endsection
  </body>
</html>