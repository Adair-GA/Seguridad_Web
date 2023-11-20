function checkName(){
    var name= document.signUp.name.value.trim();

    for (let i = 0; i < name.length; i++){
        var ascii = name.charCodeAt(i); //UTF-16
        if ((ascii < 65 || ascii > 122) && (ascii!=32) && (ascii!=209 && ascii!=241) && (ascii!=225 && ascii!=193) && (ascii!=201 && ascii!=233) && (ascii!=205 && ascii!=237) && (ascii!=211 && ascii!=243) && (ascii!=218 && ascii!=250)){
            //209: Ñ, 241: ñ, y vocales con tílde
            return false;
        }
        
    }
    return true;
}

function checkSurname(){
    var surname= document.signUp.surname.value.trim();
    for (let i = 0; i < surname.length; i++){
        var ascii = surname.charCodeAt(i); //UTF-16
        if ((ascii < 65 || ascii > 122) && (ascii!=32) && (ascii!=209 && ascii!=241) && (ascii!=225 && ascii!=193) && (ascii!=201 && ascii!=233) && (ascii!=205 && ascii!=237) && (ascii!=211 && ascii!=243) && (ascii!=218 && ascii!=250)){
            //209: Ñ, 241: ñ, y vocales con tílde
            return false;
        }
        
    }
    return true;
}

function checkDNI(){
    
    var letras = ['T','R','W','A','G','M','Y','F','P','D','X','B','N','J','Z','S','Q','V','H','L','C','K','E','T'];
    var dni=document.signUp.dni.value.trim();

    if (dni.length!=10){
        return false;
    }else{
        try {
            var letraInput=dni[9].toString().toUpperCase();
        } catch (error) {
            return false;
        }
        var dniSinLetra=dni.slice(0,8);
    
        dniSinLetra=dniSinLetra%23;
        
        if (letras[dniSinLetra]!=letraInput){
            return false;
        }
        return true;
    }
}

function checkTel(){
    var tel= document.signUp.phone.value.trim();

    for (let i = 0; i < tel.length; i++) {
        var ascii = tel.charCodeAt(i);
        if (tel.length<9 || ascii < 48 || ascii > 57) {
           return false;
        }
     }

     return true;
}

function checkEmail(){
    var email= document.signUp.email.value.trim();
    
    // Regex que permite caracteres (incluyendo ñ y .), @, servicio (gmail, outlook...) y extensión de entre 2 y 4 caracteres (.com, .es, .eus...)
    var reg = RegExp('^[\\w-\\.\\ñ\\Ñ]+@([\\w-]+\\.)+[\\w-]{2,4}$');

    if (!reg.test(email)){
        return false;
    }
    return true;
}

function checkDate(){
    var date= document.signUp.DOBSignup.value.trim();
    /*Validamos la fecha con la siguiente expresión Regex, que indica
    que será un formato de:
        4 números del 0 al 9 cada uno
        2 números del 0 al 9 cada uno
        2 números del 0 al 9 cada uno
    
    Esta expresión permite números erróneos para los meses y días (87, por ejemplo),
    pero esto se soluciona en HTML ya que no es una casilla textbox, si no que hay
    que elegir la fecha en un desplegable.

    Por tanto, la expresión Regex nos permite para comprobar si se ha dejado alguna 
    parte de la fecha en blanco.
    */
    var reg = RegExp('^[0-9]{4}-[0-9]{2}-[0-9]{2}$');

    if (!reg.test(date)){
        return false;
    }else{
        let year=date.slice(0,4);
        let month=date.slice(5,7);
        let day=parseInt(date.slice(8,10));

        if (month=="01" || month== "03" || month=="05" || month=="07" || month=="08" || month=="10" || month=="12"){
        //Meses con 31 días    
            if (day>0 && day<32){
                return true;
            }else{
                return false;
            }
        }else if (month=="02"){ // Caso especial Febrero
            let limit = 0;
            if (year%400==0 && year%100==0){ // Año bisiesto
                limit=30;
            }else{
                limit=29;
            }

            if (day>0 && day<limit){
                return true;
            }else{
                return false;
            }
        }else{
        // Meses con 30 días
            if (day>0 && day<31){
                return true;
            }else{
                return false;
            }
        }
    }
    
}

// Función que se ejecuta al pulsar en Registrar.
async function register(event){
    event.preventDefault();
    var captcha = grecaptcha.getResponse(1);
    //if all the checks are true, submit the form via POST fetch to /api/signup_register.php
    try {
        if (!checkName() || !checkSurname() || !checkDNI() || !checkTel() || !checkDate()) {
            return;
        }
    } catch (error) {
        alert("Algo ha ido mal")
        console.log(error);
        return;
    }
    if (captcha == '') {
        alert ("Por favor, rellene el captcha");
        return;
    }
    fd = new FormData(document.getElementById('signUpForm'))
    fd.append('g-recaptcha-response', captcha)
    res = await fetch('/api/signup_register.php', {
        method: 'POST',
        body: fd
    })
    res = await res.text();
    console.log(res)
    if (res == 'ERROR' || res == 'Error al registrar el usuario') {
        alert("Ha ocurrido un error, recarga la página y vuelve a intentarlo. En caso de que el error persista, póngase en contacto con los administradores.");
        grecaptcha.reset();
    }else if (res != 'Usuario registrado correctamente'){
        alert(res);
        grecaptcha.reset();
    }else {
        alert(res);
        window.location.reload()
    }
}

// Funciones cuyo nombre empizan con "live_" sirven para checkear la correción de los datos introducidos en el momento y no cuando se pulse el botón.
// Si hay algo mal, se muestra un mensaje.
function live_checkName(){
    if (checkName()){
        document.getElementById("wrong_name").style.display = "none";
    }else{
        document.getElementById("wrong_name").style.display = "block";
    }
}

function live_checkSurname(){
    if (checkSurname()){
        document.getElementById("wrong_surname").style.display = "none";
    }else{
        document.getElementById("wrong_surname").style.display = "block";
    }
}

function live_checkDNI(){
    if (checkDNI()){
        document.getElementById("wrong_dni").style.display = "none";
    }else{
        document.getElementById("wrong_dni").style.display = "block";
    }
}

function live_checkTel(){
    if (checkTel()){
        document.getElementById("wrong_tel").style.display = "none";
    }else{
        document.getElementById("wrong_tel").style.display = "block";
    }
}

function live_checkEmail(){
    if (checkEmail()){
        document.getElementById("wrong_email").style.display = "none";
    }else{
        document.getElementById("wrong_email").style.display = "block";
    }
}

function live_checkDate(){
    if (checkDate()){
        document.getElementById("wrong_date").style.display = "none";
    }else{
        document.getElementById("wrong_date").style.display = "block";
    }
}

// Función que se ejecuta al pulsar en Login.
async function login(event){
    event.preventDefault();
    //if all the checks are true, submit the form via POST fetch to /api/login.php
    fd = new FormData()
    var captcha = grecaptcha.getResponse(0);
    if (captcha == '') {
        alert ("Por favor, rellene el captcha");
        return;
    }
    fd.append('g-recaptcha-response', captcha)
    fd.append('email', document.getElementById('InputEmail').value)
    fd.append('password', document.getElementById('InputPassword').value)
    fd.append('token', document.getElementById('InputToken').value)
    res = await fetch('/api/login.php', {
        method: 'POST',
        body: fd
    })
    console.log(res)
    res = await res.text();
    console.log(res)
    if (res == 'ERROR'){
        alert("Ha ocurrido un error, recarga la página y vuelve a intentarlo. En caso de que el error persista, póngase en contacto con los administradores.");
        grecaptcha.reset();
    }else {
        alert(res);
        window.location.reload();
    }
}

// EventListener de los dos botones disponibles en la página, para evitar el uso de onClicked en HTML.
document.addEventListener('DOMContentLoaded', (event) => {
    document.getElementById("NameSignup").addEventListener("keyup", live_checkName);
    document.getElementById("ApellidosSignup").addEventListener("keyup", live_checkSurname);
    document.getElementById("InputEmailSignup").addEventListener("keyup", live_checkEmail);
    document.getElementById("PhoneSignup").addEventListener("keyup", live_checkTel);
    document.getElementById("DOBSignup").addEventListener("keyup", live_checkDate);
    document.getElementById("DNISignup").addEventListener("keyup", live_checkDNI);
    
    document.getElementById("SignUpButton").addEventListener("click", register);
    document.getElementById("LogInButton").addEventListener("click", login);
})