# REPARTICIÓN DEL PROYECTO
Aqui se muestra las partes que tendrán que hacer cada uno
Aviso: se agregaron 3 tablas a la base de datos, y a empresa y postulante les sale la nueva sección notificaciones en el header,
el administrador ya tenía su apartado de este tipo llamado ATENCIÓN
## 1. Haro:
  Arreglar los PDF
  Actualizar foto
  Actualizar contraseña

## 2. Alvarado:
  Página "ATENCIÓN"
  Hasta ahora la página que pusiste solo es un listado de las empresas o usuarios a los cuales les puedes cambiar el estado
  Necesitamos que ese apartado en específico sea una página en la cual puedas ver los reportes que envían tanto los postulantes
  como las empresas.
  Entonces necesitamos hacer los siguientes cambios:
  (Ya se ha enviado la base de datos actualizada, existe una nueva tabla llamada mensaje, esta tabla puede tener 3 tipos, una de ellas es "queja")
  lo que tienes que hacer es la interfaz que pueda ver los mensajes de tipo queja que envían los demás usuarios.
  una interfaz inicial donde salga la lista de todos los reportes, cuando abras alguno, aparte de poder leer el mensaje podrás
  cambiar el estado del usuario que ha sido reportado

## 3. Quiñones:
  Arreglar "Eliminar" esas propuestas (Cambiar estado a eliminado, cuando se cambia el estado no se puede ver).
  Las propuestas nunca se borran, de hecho añade un estado a estado_propuesta que sea expirado, cuando se venza la fecha los
  postulantes no podrán ver la propuesta, pero las empresas van a poder seguir viéndolo hasta que ellos mismos decidan eliminar su propuesta
  una columna más en ver postulantes en la cual va a haber un botón que diga contactar, al presionar ese botón debe de llevarte a una interfaz 
  que te permita enviarle un mensaje. (El ingeniero mencionó que las empresas cuando reciban las postulaciones con los cv podían 
  contactarlos para reunirse), el botón debe de guardar el mensaje, la fecha, el id del emisor y del receptor, este mensaje es del tipo "mensaje"

## Villavicencio:
  Al momento de que una empresa contacta con un postulante, al postulante en la nueva sección de la cabecera "notificaiones" le debe de
  aparecer todos los mensajes que le ha llegado de la tabla mensaje, el solo podrá leer, y cuando el lo lea, el estado cambiará a leído
  cuando digo que lo lea, me refiero a que la página de notificaciones tiene que haber una lista con todos los mensajes donde el destinatario
  es el, y cuando presione un boton que le muestre el mensaje, así se contará como leído y automáticamente se cambiará el estado a leído
  esto lo vas a trabajar para postulante, solo se tienen que mostrar los mensajes de tipo "mensaje"

## Gomez:
  Tienes que ponerle las validaciones al apartado de registro, segun los datos que deben de incluirse, por ejemplo al registrar el ruc, este
  no debe de poder ingresar más ni menos de 11 dígitos, igualmente con dni y demás campos como para número de teléfono no debe de poder ingresar
  texto. El diseño de las interfaces no tienen que cambiar, solamente trabaja con restriccionees a nivel de codigo para los registros.
  Actualizar datos de empresa y postulante, en el proyecto ya hay un archivo editar_datos_personales.php, tienes que darle función de editar,
  igualmente con las restricciones que pidió el ingeniero en el meet.


Otro aviso, en caso de que quieran usar las imagenes de las empresas o los postulantes en sus interfaces, hay 2 php que son p_imagen_empresa y 
p_imagen_postulante, estos 2 tienen una funcion declarada, osea pueden llamarlos como si fueran métodos de java, un ejemplo en ver_postulante.php:
<?php
                            $ruta_imagen_usuario = obtenerImagenPostulante($row['id_postulante']);
                            ?>
                            <img src="<?php echo htmlspecialchars($ruta_imagen_usuario); ?>?<?php echo time(); ?>"
                                alt="Foto de perfil" class="profile-image">
solo le ponen el id del postulante o la empresa que y les trae la imagen, igual tienen que ponerle su include arriba como con conexion: include('control/p_imagen_postulante.php');
si lo quieren usar en su html.
