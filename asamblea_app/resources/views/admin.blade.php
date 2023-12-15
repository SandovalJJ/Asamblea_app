<!doctype html>
<html lang="en">
  <head>
    <title>Guía de Usuario - Sistema de Gestión de Formularios de Votación</title>
    <!-- Estilos y scripts omitidos para brevedad -->
    @extends('layouts.head')
  </head>
  <body>
    <div class="wrapper d-flex align-items-stretch">
      @extends('layouts.sidebar')
      @section('content')
        <!-- Page Content  -->
        <button type="button" id="sidebarCollapse" class="btn btn-primary">
          <i class="fa fa-bars"></i>
          <span class="sr-only">Toggle Menu</span>
        </button>
        
        <div id="content" class="p-4 p-md-5">
          @if (session('error'))
            <div class="alert alert-warning">
                {{ session('error') }}
            </div>
          @endif
          <div class="container">
            <h2>Bienvenido/a a la Guía de Usuario</h2>
            <p>Este software ha sido diseñado para facilitar la creación, distribución y recopilación de respuestas de formularios utilizados en votaciones y asambleas. A continuación, encontrarás una guía detallada sobre cómo navegar y utilizar las diversas funciones del sistema según tu rol.</p>
    
            <h3>Acceso al Sistema:</h3>
            <ul>
                <li>Inicia sesión con tus credenciales proporcionadas por el administrador.</li>
                <li>Dependiendo de tu rol (Administrador, Delegado, Suplente), tendrás acceso a diferentes funcionalidades.</li>
            </ul>
    
            <h3>Navegación:</h3>
            <p>Utiliza la barra lateral para navegar entre las diferentes secciones del sistema. Cada sección está diseñada para ser intuitiva y fácil de usar.</p>

            @auth
            @if(Auth::user()->rol == 'admin')
            <div class="info">
                <h3>Para Administradores:</h3>
                <ul>
                    <li>Creación y gestión de formularios: Como administrador, puedes crear nuevos formularios de votación, editarlos y gestionar su disponibilidad.</li>
                    <li>Activación y desactivación de preguntas: Tienes el control para activar o desactivar preguntas individuales en los formularios, lo que permite una gestión flexible de las votaciones.</li>
                    <li>Visualización de respuestas y análisis de resultados: Accede a un panel de resultados para ver las respuestas de los usuarios y realizar análisis detallados.</li>
                </ul>
                <h4>Asignar y Desasignar Usuarios a Formularios:</h4>
                <p>Como administrador, tienes la capacidad de controlar quién puede acceder a cada formulario. Utiliza las funciones "Asignar Usuarios" y "Desasignar Usuarios" para gestionar eficientemente la participación en los formularios.</p>

                <h5>Asignar Usuarios:</h5>
                <p>Selecciona "Asignar Usuarios" para abrir un modal con una lista de todos los usuarios disponibles. Marca las casillas de los usuarios que deseas que tengan acceso al formulario y confirma la selección. Los usuarios asignados podrán acceder y responder al formulario.</p>
                
                <h5>Desasignar Usuarios:</h5>
                <p>Utiliza "Desasignar Usuarios" para abrir un modal con los usuarios actualmente asignados al formulario. Desmarca las casillas de los usuarios a los que deseas remover el acceso y confirma la acción. Esto revocará el acceso al formulario para los usuarios deseleccionados.</p>
                
                <p><strong>Nota:</strong> Las modificaciones en la asignación de usuarios son efectivas inmediatamente y reflejan el acceso actual y futuro al formulario. Asegúrate de revisar tu selección antes de confirmar los cambios.</p>
            </div>
            @endif
            @endauth

            <div class="info">
                <h3>Para Delegados y Suplentes:</h3>
                <p>Como Delegado o Suplente, tu rol principal es participar en las votaciones a través de los formularios asignados.</p>
                <ul>
                    <li><strong>Responder Formularios:</strong> Accede a los formularios asignados desde tu panel de usuario. Podrás responder a las preguntas presentadas y enviar tus respuestas.</li>
                    <li><strong>Navegación entre Preguntas:</strong> Utiliza los controles proporcionados para moverte entre las diferentes preguntas del formulario. Asegúrate de enviar tus respuestas antes de finalizar.</li>
                </ul>
                <p><strong>Importante:</strong> Tus respuestas son confidenciales y solo serán utilizadas para los fines de la votación o asamblea. Asegúrate de completar todas las preguntas antes de enviar tus respuestas.</p>
            </div>

            <h3>Seguridad y Privacidad:</h3>
            <p>Tu información y respuestas son manejadas con la máxima confidencialidad. Solo el personal autorizado tiene acceso a los datos detallados. Si tienes preguntas o inquietudes sobre la seguridad de tus datos, no dudes en contactar al equipo de soporte.</p>
    
            <h3>Soporte:</h3>
            <p>Si tienes preguntas o necesitas asistencia sobre el uso del sistema, no dudes en contactar al equipo de soporte técnico. Estamos aquí para ayudarte a tener una experiencia fluida y eficiente con el sistema.</p>
        </div>
        <div class="footer">
          <p>&copy; 2023 Sistema de Gestión de Formularios de Votación. Todos los derechos reservados.</p>
        </div>
      </div>
      @extends('layouts.footer')
      @endsection
    </div>
  </body>
</html>
