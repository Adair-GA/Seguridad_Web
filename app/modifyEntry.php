<?php session_start();
if(!isset($_SESSION['email'])){
    header("Location: /login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modificar entrada - Euskoroscopo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script type="text/javascript" src="js/modifyEntry.js"></script>
    <style>
        .wrong_input{
            display: none; 
            color: red; 
            opacity: 70%; 
            font-size: small;
        }
    </style>
</head>
<body>
    <div class="container-sm">
        <header class="d-flex flex-wrap justify-content-center py-3 mb-4 border-bottom">
        <a href="/" class="d-flex align-items-center me-md-auto link-body-emphasis text-decoration-none">
        <span class="fs-5">Euskor&oacute;scopo</span>
        
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

                <?php ?>



            </h1>
            <form name="signUp" id="signUpForm">
                    <label for="EntryName" class="form-label">Nombre:</label>
                    <input type="text" class="form-control mb-3" id="EntryName" name="name">

                    <label for="EntrDOB" class="form-label">Fecha de nacimiento (aaaa-mm-dd) </label>
                    <input type="date" class="form-control mb-3" id="DOBSignup" name="dob" onkeyup="live_checkDate()">
                    <p class="wrong_input" id="wrong_date">El formato del numero de la fecha no es correcto</p>

                    <label for="signosolar" class="form-label">Elige el signo solar:</label>
                    <select class="form-select mb-3" name="signosolar" id="signosolar">
                        <option value=Aries>Aries</option>
                        <option value=Tauro>Tauro</option>
                        <option value=Geminis>Geminis</option>
                        <option value=Cancer>Cancer</option>
                        <option value=Leo>Leo</option>
                        <option value=Virgo>Virgo</option>
                        <option value=Libra>Libra</option>
                        <option value=Escorpio>Escorpio</option>
                        <option value=Sagitario>Sagitario</option>
                        <option value=Capricornio>Capricornio</option>
                        <option value=Acuario>Acuario</option>
                        <option value=Piscis>Piscis</option>
                    </select>

                    <label for="signolunar" class="form-label">Elige el signo lunar:</label>
                    <select class="form-select mb-3" name="signolunar" id="signolunar">
                        <option value=Aries>Aries</option>
                        <option value=Tauro>Tauro</option>
                        <option value=Geminis>Geminis</option>
                        <option value=Cancer>Cancer</option>
                        <option value=Leo>Leo</option>
                        <option value=Virgo>Virgo</option>
                        <option value=Libra>Libra</option>
                        <option value=Escorpio>Escorpio</option>
                        <option value=Sagitario>Sagitario</option>
                        <option value=Capricornio>Capricornio</option>
                        <option value=Acuario>Acuario</option>
                        <option value=Piscis>Piscis</option>
                    </select>

                    <label for="retrogrado" class="form-label">Esta persona nació en mercurio retrogrado?</label>
                    <select class="form-select mb-3" name="retrogrado" id="retrogrado">
                        <option value=No>No</option>
                        <option value=Si>Si</option>
                    </select>

                    <button type="submit" id="sendButton" class="btn btn-primary">Modificar</button>
            </form>
            <br>
        </div>
        </div>
</body>
</html>