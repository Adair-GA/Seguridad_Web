<?php session_start();
$_SESSION['token'] = bin2hex(random_bytes(24));?> <!--Permite empezar o reanudar una sesión (login) -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Security-Policy" content=" default-src 'self' 'unsafe-inline' cdn.jsdelivr.net *.cdn.jsdelivr.net">
    <title>Modificar entrada - Euskoroscopo</title> <!--Título de la pestaña--->  

    <!--Importar estilo CSS, antes se empleaba DataTable de JQuery, actualmente una tabla propia-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="css/format_messages.css" />

    <!--Importar código JavaScript-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script type="text/javascript" src="js/modifyEntry.js"></script>

    <!--Se usa una plantilla de Bootstrap para el estilo CSS del sistema-->
    <!--También se implementa CSS propio para un propósitos muy determinados y específicos-->
</head>
<body>
    <div class="container-sm">
        <header class="d-flex flex-wrap justify-content-center py-3 mb-4 border-bottom">
        <a href="/" class="d-flex align-items-center me-md-auto link-body-emphasis text-decoration-none">
            <span class="fs-5">Euskor&oacute;scopo<?php if(isset($_SESSION['email'])){echo ": Bienvenido, $_SESSION[usuario]";}?></span>
        </a>
        <ul class="nav nav-pills">
            <li class="nav-item"><a href="/" class="nav-link">Home</a></li>
            <?php if(isset($_SESSION['email'])){
                echo '<li class="nav-item"><a href="logout.php" class="btn btn-danger">Logout</a></li>';
                }?>
        </ul>
    
        </a>
        </header>
    </div>
    <div class="container" style="max-width: 50%;">
        <div class = "container__signup">
            <h1>Modifica los datos del horoscopo de alguien con id:
                <?php 
                    // Se utiliza $_GET porque se pasa por el URL desde index.php, index.js (editEntry(id))
                    echo $_GET['id'];
                    include "dbconn.php";
                    $query = mysqli_query($conn, "SELECT * FROM horoscopos WHERE id = ?")
                        or die (mysqli_error($conn));
                    $stmt = mysqli_prepare($conn, $query) or die (mysqli_error($conn));
                    mysqli_stmt_bind_param($stmt, "i", $_GET['id']);
                    mysqli_stmt_execute($stmt);
                    $result = mysqli_stmt_get_result($stmt);
                    $row = mysqli_fetch_array($query);
                    
                ?>
            </h1>
            <form name="signUp" id="signUpForm">
                    <label for="EntryName" class="form-label">Nombre:</label>
                    <input type="text" class="form-control mb-3" id="EntryName" name="name" placeholder="<?php echo $row['nombre'];?>">

                    <label for="EntryDOB" class="form-label">Fecha de nacimiento (aaaa-mm-dd)</label>
                    <input type="text" class="form-control mb-3" id="DOBSignup" placeholder=<?php echo $row['fecha_nacimiento'];?> name="dob" onkeyup="live_checkDate()"> 
                    <p class="wrong_input" id="wrong_date">El formato de la fecha no es correcto</p>

                    <label for="signosolar" class="form-label">Elige el signo solar:</label>
                    <select class="form-select mb-3" name="signosolar" id="signosolar">
                        <option value="Aries" <?php if($row['signo_solar'] == 'Aries') echo 'selected'; ?>>Aries</option>
                        <option value="Tauro" <?php if($row['signo_solar'] == 'Tauro') echo 'selected'; ?>>Tauro</option>
                        <option value="Geminis" <?php if($row['signo_solar'] == 'Geminis') echo 'selected'; ?>>Geminis</option>
                        <option value="Cancer" <?php if($row['signo_solar'] == 'Cancer') echo 'selected'; ?>>Cancer</option>
                        <option value="Leo" <?php if($row['signo_solar'] == 'Leo') echo 'selected'; ?>>Leo</option>
                        <option value="Virgo" <?php if($row['signo_solar'] == 'Virgo') echo 'selected'; ?>>Virgo</option>
                        <option value="Libra" <?php if($row['signo_solar'] == 'Libra') echo 'selected'; ?>>Libra</option>
                        <option value="Escorpio" <?php if($row['signo_solar'] == 'Escorpio') echo 'selected'; ?>>Escorpio</option>
                        <option value="Sagitario" <?php if($row['signo_solar'] == 'Sagitario') echo 'selected'; ?>>Sagitario</option>
                        <option value="Capricornio" <?php if($row['signo_solar'] == 'Capricornio') echo 'selected'; ?>>Capricornio</option>
                        <option value="Acuario" <?php if($row['signo_solar'] == 'Acuario') echo 'selected'; ?>>Acuario</option>
                        <option value="Piscis" <?php if($row['signo_solar'] == 'Piscis') echo 'selected'; ?>>Piscis</option>
                    </select>

                    <label for="signolunar" class="form-label">Elige el signo lunar:</label>
                    <select class="form-select mb-3" name="signolunar" id="signolunar">
                        <option value="Aries" <?php if($row['signo_lunar'] == 'Aries') echo 'selected'; ?>>Aries</option>
                        <option value="Tauro" <?php if($row['signo_lunar'] == 'Tauro') echo 'selected'; ?>>Tauro</option>
                        <option value="Geminis" <?php if($row['signo_lunar'] == 'Geminis') echo 'selected'; ?>>Geminis</option>
                        <option value="Cancer" <?php if($row['signo_lunar'] == 'Cancer') echo 'selected'; ?>>Cancer</option>
                        <option value="Leo" <?php if($row['signo_lunar'] == 'Leo') echo 'selected'; ?>>Leo</option>
                        <option value="Virgo" <?php if($row['signo_lunar'] == 'Virgo') echo 'selected'; ?>>Virgo</option>
                        <option value="Libra" <?php if($row['signo_lunar'] == 'Libra') echo 'selected'; ?>>Libra</option>
                        <option value="Escorpio" <?php if($row['signo_lunar'] == 'Escorpio') echo 'selected'; ?>>Escorpio</option>
                        <option value="Sagitario" <?php if($row['signo_lunar'] == 'Sagitario') echo 'selected'; ?>>Sagitario</option>
                        <option value="Capricornio" <?php if($row['signo_lunar'] == 'Capricornio') echo 'selected'; ?>>Capricornio</option>
                        <option value="Acuario" <?php if($row['signo_lunar'] == 'Acuario') echo 'selected'; ?>>Acuario</option>
                        <option value="Piscis" <?php if($row['signo_lunar'] == 'Piscis') echo 'selected'; ?>>Piscis</option>
                    </select>

                    <label for="retrogrado" class="form-label">¿Esta persona nació en mercurio retrogrado?</label>
                    <select class="form-select mb-3" name="retrogrado" id="retrogrado">
                        <option value=No>No</option>
                        <option value=Si <?php if($row['mercurio_retrogrado'] == 1) echo 'selected'; ?>>Si</option>
                    </select>

                    <input type="hidden" id="InputToken" name="token" value="<?php echo $_SESSION['token']?>">
                    <button type="submit" id="sendButton" class="btn btn-primary">Modificar</button>
            </form>
            <br>
        </div>
        </div>
</body>
</html>