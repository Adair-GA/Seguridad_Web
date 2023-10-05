<?php session_start();?> <!--Permite empezar o reanudar una sesión (login) -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Euskor&oacute;scopo</title>    

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.css" />
    <link rel="stylesheet" href="css/tabla_inicio.css" />

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.js"></script>
    <script src="js/index.js"></script>
</head>

<body style="background-image: url('images/background.webp'); background-size: cover; color: white;">
    <div class="container-sm">
        
        <!-- Menú de navegación superior -->
        <header class="d-flex flex-wrap justify-content-center py-3 mb-4 border-bottom">
            <a href="/" class="d-flex align-items-center me-md-auto link-body-emphasis text-decoration-none">
                <span class="fs-5" style="color:white">Euskor&oacute;scopo
                    <!-- Si hay una sesión iniciada, se mostrará el nombre de usuario -->
                    <?php 
                        if(isset($_SESSION['email'])){ //<!-- isset(): determina si una variables está definida y no es null -->
                            echo ": Bienvenido, $_SESSION[usuario]";
                        }?>
                </span>
            </a>
            <ul class="nav nav-pills">
                <!-- Si hay una sesión iniciada, se mostrará su menú correspondiente -->
                <!-- Si no, se mostrarán las opciones que no necesitan identificación -->
                <?php
                if (isset($_SESSION['email'])) {
                    echo '<li class="nav-item"><a href="createEntry.php" class="nav-link" style="color:white">Crear entrada</a></li>';
                    echo '<li class="nav-item"><a href="modifyUserData.php" class="nav-link" style="color:white">Modificar datos</a></li>';
                    echo '<li class="nav-item"><a href="logout.php" class="btn btn-danger">Logout</a></li>';
                } else {
                    echo '<li class="nav-item"><a href="login.php" class="nav-link active">Login</a></li>';
                }
                ?>
            </ul>
        </header>

        <!-- Contenido de la página principal -->
        <main>
            <h1>
                La mejor p&aacute;gina de hor&oacute;scopos de Euskadi <!-- &*acute para tildes -->
            </h1>

            <!-- Para mostrar número de usuarios registrados -->
            <h2>
                Ahora con: 
                <?php
                 // Tendremos que crear una conexión con la base de datos, en dbconn.php
                 // La conexión se almacena en la variable $conn de dbconn.php
                 // Dicha variable se podrá usar porque se ha incluído su script
                 include "dbconn.php";

                 // Realizar la consulta con la DB, se indica la conexión y la instrucción SQL
                 $query = mysqli_query($conn, "SELECT COUNT(dni) FROM usuarios")
                    or die (mysqli_error($conn));
                 // mysqli_error() devuelve una cadena con el último error
                 // die() es un alias de exit()

                $row = mysqli_fetch_array($query); // Obtiene una fila de resultados como array
                echo $row[0]; // Sólo tendrá un elemento
                ?> usuarios
            </h2>
            <br>

            <!-- Tabla o listado de los elementos que pertenecen a la base de datos -->
            <!-- Por defecto la tabla estará vacía, y se llenará mediante una consulta SQL a través de PHP -->
            <div>
                <table id="tablaHoroscopos" class="display">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Signo solar</th>
                            <th>Editar</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query = mysqli_query($conn, "SELECT id, nombre, signo_solar FROM horoscopos")
                            or die (mysqli_error($conn));
                        
                        while ($row = mysqli_fetch_array($query)) { // Mientras haya elementos en el array
                            echo "<tr>";
                            echo "<td>$row[nombre]</td>";
                            echo "<td>$row[signo_solar]</td>";
                            // Al clickar editar, pasamos el id del elemento a editar con el fin de poder modificar la DB (id es la clave de la relación)
                            echo "<td><button class='btn btn-danger' onclick='editEntry($row[id])'>Editar</button></td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                    </thead>
                </table>
            </div>
        </main>
        <footer>
        Zodiac Backgrounds@wallpaperuse.com
        </footer>
    </div>
</body>
</html>
