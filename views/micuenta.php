<?php 
session_start();
  if ($_SESSION['tipo_usuario'] == 'cliente')
    {

?>

<!DOCTYPE html>

<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Studio de Tatuajes - Clientes</title>
  <link rel="stylesheet" href="../assets/css/style.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

</head>

<body class="fondo">
  <div class="container">
    <nav class="navbar navbar-expand-lg navbar-light">
      <a class="navbar-brand" href="#"><img class="logo" src="../assets/img/logo.png" alt=""></a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon "></span>
      </button>

      <div class="collapse navbar-collapse justify-content-center" id="navbarSupportedContent">
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link btn" href="#">Nosotros</a>
          </li>
          <li class="nav-item">
            <a class="nav-link btn" href="tatuadores_view.php">Tatuadores</a>
          </li>
          <li class="nav-item">
            <a class="nav-link btn" href="#">Perforadores</a>
          </li>
          <li class="nav-item">
            <a class="nav-link btn" href="micuenta.php">Mis Reservas</a>
          </li>
          <li class="nav-item">
            <a class="nav-link btn" href="../index.php">Cerrar Sesion</a>
          </li>
        </ul>
      </div>
    </nav>

    <?php 

    if ($_SESSION['tipo_usuario'] == 'cliente') {
        // Incluir el archivo de conexión a la base de datos
        include '../conexion.php'; 
        
        // Obtener el ID del cliente de la sesión
        $cliente_id = $_SESSION['usuario_id'];
        
        // Consulta SQL para obtener las citas del cliente
        $sql_citas = "SELECT citas.*, 
                            tatuadores.nombre AS nombre_tatuador, 
                            DATE_FORMAT(horarios_disponibles.fecha, '%d/%m/%Y') AS fecha_formato,
                            CASE 
                                WHEN horarios_disponibles.turno = 'am' THEN '09:00 a 14:00'
                                WHEN horarios_disponibles.turno = 'pm' THEN '16:00 a 21:00'
                            END AS hora_formato,
                            horarios_disponibles.estado AS estado_horario
                        FROM citas
                        INNER JOIN tatuadores ON citas.usuario_id = tatuadores.usuario_id
                        INNER JOIN horarios_disponibles ON citas.hora_disponible_id = horarios_disponibles.id
                        WHERE citas.cliente_id = $cliente_id";
                        
        $resultado_citas = $conexion->query($sql_citas);
        
        if ($resultado_citas->num_rows > 0) {
            // Mostrar la información de las citas del cliente
            echo '<div class="row justify-content-center">';
            echo '<div class="card m-4 col-7">';
            echo '<h1 class="fw-bold fs-3 text-center">Mis Reservas</h1>';
            echo '<div class="table-responsive">';
            echo '<table class="table table-striped">';
            echo '<thead>';
            echo '<tr>';
            echo '<th scope="col">Tatuador</th>';
            echo '<th scope="col">Fecha</th>';
            echo '<th scope="col">Hora</th>';
            echo '<th scope="col">Precio Total</th>'; // Cambié el título de la columna
            echo '<th scope="col">Estado</th>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';
            
            while ($fila_cita = $resultado_citas->fetch_assoc()) {
                // Formatear el precio total a pesos chilenos
                $precio_total_clp = number_format($fila_cita['precio_total'], 0, ',', '.');
                
                // Mostrar los detalles de la cita en una fila de la tabla
                echo '<tr>';
                echo '<td>' . $fila_cita['nombre_tatuador'] . '</td>';
                echo '<td>' . $fila_cita['fecha_formato'] . '</td>';
                echo '<td>' . $fila_cita['hora_formato'] . '</td>';
                echo '<td>$' . $precio_total_clp . '</td>'; // Mostrar el precio total en formato CLP
                echo '<td>' . $fila_cita['estado_horario'] . '</td>';
                echo '</tr>';
            }
            
            echo '</tbody>';
            echo '</table>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
        } else {
            // No hay citas registradas
            echo '<div class="row justify-content-center">';
            echo '<div class="card m-4 col-6 text-center">';
            echo '<p class="fw-bold fs-5">No tienes citas registradas.</p>';
            echo '</div>';
            echo '</div>';
        }
        
        // Cerrar la conexión a la base de datos
        $conexion->close();
    } else {
        header("Location:../index.php");
        exit(); // Asegúrate de salir del script después de redirigir
    }
    ?>



  </div>
</body>

<footer class="bg-dark text-white  fixed-bottom">
  <div class="text-center py-3 fondo">
    <p class="mb-0">&copy; 2024 TattoStudioINK</p>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
  integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
  integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>

</html>

<?php }
  else{
    header("Location:../index.php");
  }?>