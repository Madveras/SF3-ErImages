# SF3-ErImages
App para el backend de oficinas para gestion de imagenes

 Gestion de banners
====================
 Genera un banner mediante los datos enviados en el formulario y graba un registro en base de datos para recuperarlo/borrarlo
  - gestionar y modificar imagenes con el bundle GregwarImageBundle
  - Forms->type, Resources/config/validation.yml 
                 validación de campos que no estan en la entity => 'mapped' => false
                 constrains para validar los cmapos
  - base de datos -> Entity Banners y Repository
  - Service -> BannerUtils Resources/config/services.yml
                banner.utils:
                  class: ErImageBundle\Services\BannerUtils
                  arguments: ["@request_stack","@image.handling"]
   
 Gestion de imagenes
 ====================
   Modificar y ajustar tamaños de imagenes subidas por plupload mediante tarea, inserción en base de datos, relacion bucket y medias simulando cottage y medias.
   
   - gestionar y modificar imagenes con el bundle GregwarImageBundle
   - gestionar uploads del plupload mediante el bundle oneup_uploader
   - tareas de consola, Commands
   - parametros de configuración en config.yml para nuestro bundle => DependencyInjection
   - Sesiones por redis, se necesita la sesion para el upload "huerfano"
   - Mensajes flash
   - Listeners: eventos postUpload, Eventos de doctrine, preRemove (LifecycleEvent)
   - Paginación resultados de busqueda.

