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
            <h2 style="font-weight: bold">Manual de Usuario para la Gestión de Votaciones y Formularios</h2>
            <p>Bienvenido al manual de usuario diseñado para facilitar la comprensión y el uso de nuestro sistema de gestión de votaciones y formularios. Hemos desarrollado este manual pensando en la claridad y facilidad de uso. A continuación, encontrará paso a paso cómo utilizar las funciones clave del sistema.</p>
    
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
                <h3 style="font-weight: bold">Para Administradores:</h3>
                
                <h4>Crear Usuario:</h4>
                <p>Para agregar un nuevo usuario al sistema, diríjase a la opción "Usuarios" en el menú principal. Haga clic en "Agregar Usuario" y complete la información requerida. Recuerde que la contraseña inicial será el mismo número de cédula del usuario para facilitar el acceso inicial.</p>

                <h4>Editar Usuario:</h4>
                <p>Si necesita actualizar la información de un usuario, busque al usuario deseado en la lista y seleccione "Editar". Realice los cambios necesarios y guarde la información.</p>

                <h4>Borrar Usuario:</h4>
                <p>Para eliminar un usuario, seleccione "Eliminar" junto al nombre del usuario en la lista. Tenga en cuenta que esta acción es definitiva y debe usarse con precaución.</p>
                
                <h4>Creación de Formularios:</h4>
               <ul>
                <li>Acceda a la opción "Formularios" en el menú.</li>
                <li>Haga clic en "Crear Formulario" y escriba el nombre y la descripción del formulario.
                </li>
                <li>Añada las preguntas que desee incluir en el formulario.
                </li>
                <li>Una vez añadidas todas las preguntas, guarde el formulario.
                </li>
               </ul>
                
               <h4>Vista de Formularios y Asignación de Participantes:</h4>
               <ul>
                <li>En la sección "Formularios", podrá ver todos los formularios creados.</li>
                <li>Cada formulario tiene dos botones importantes:</li>
                
                  <li><strong>Detalles de Respuestas:</strong> Permite ver las respuestas que han dado los participantes a cada formulario.</li>
                  <li><strong>Asignar Participantes:</strong> Aquí podrá agregar a los usuarios que desea que participen en la votación de un formulario específico. Seleccionando "Asignar Usuarios", aparecerá una lista para elegir a quién agregar. Es crucial solo añadir a quienes deben votar.</li>
                
                <li>Si un participante no realiza su votación, puede usar la opción "Desasignar Usuarios" para retirarlo del formulario.</li>
               </ul>

               <h4>Opción de Formulario Actual para Administradores:</h4>
               <ul>
                <li>En la sección "Formulario Actual", el administrador tiene la capacidad de activar o desactivar preguntas individualmente.
                </li>
                <li>Esta función permite controlar el flujo de la votación, asegurando que solo las preguntas activadas estén disponibles para los participantes.
                </li>
                <li>Para activar una pregunta, simplemente haga clic en "Activar" al lado de la pregunta deseada. Si necesita desactivarla, haga clic en "Desactivar".
                </li>
               </ul>

                <p><strong>Nota:</strong> Las modificaciones en la asignación de usuarios son efectivas inmediatamente y reflejan el acceso actual y futuro al formulario. Asegúrate de revisar tu selección antes de confirmar los cambios.</p>
            </div>
            @endif
            @endauth

            <div class="info">
                <h3 style="font-weight: bold">Para Delegados y Suplentes:</h3>
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
            <p>Si tienes preguntas o necesitas asistencia sobre el uso del sistema, no dudes en contactar al equipo de soporte técnico. Estamos aquí para ayudarte a tener una experiencia fluida y eficiente con el sistema. Llama a: 602 889 3390 ext: 1901</p>
        </div>
        <br>
        <div class="footer">
          <p style="color: black">&copy; 2023 Sistema de Gestión de Formularios de Votación. Todos los derechos reservados.</p>
        </div>
      </div>
      @extends('layouts.footer')
      @endsection
    </div>
  </body>
</html>
