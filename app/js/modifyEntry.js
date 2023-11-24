async function update(event){
    event.preventDefault();
    id = getId();

    if (!checkDate()){
        alert("Fecha incorrecta");
        return;
    }
    var captcha = grecaptcha.getResponse(0);
    if (captcha == '') {
        alert ("Por favor, rellene el captcha");
        return;
    }
    
    data = new FormData();
    data.append("name", document.signUp.name.value.trim() || document.signUp.name.getAttribute("placeholder"));
    data.append("dob", document.signUp.dob.value.trim() || document.signUp.dob.getAttribute("placeholder"));
    data.append("signosolar", document.signUp.signosolar.value.trim() );
    data.append("signolunar", document.signUp.signolunar.value.trim());
    data.append("retrogrado", document.signUp.retrogrado.value.trim());
    data.append("token", document.signUp.InputToken.value.trim());
    data.append('g-recaptcha-response', captcha)

    res = await fetch(
        "/api/update_entry.php?id=" + id,
        {
            method: "POST",
            body: data
        }
    );
    r_text = await res.text();
    //alert(r_text);
    if (r_text == "OK"){
        alert("Actualización realizada con éxito");
        window.location.href = "/index.php";
    }else{
        alert("Ha ocurrido un error, recarga la página y vuelve a intentarlo. En caso de que el error persista, póngase en contacto con los administradores.");
    }
}

function checkDate(){
    var date= document.signUp.DOBSignup.value.trim();
    if (date==""){
        return true;
    }
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
            if (day>0 && day<32){ // Caso especial Febrero
                return true;
            }else{
                return false;
            }
        }else if (month=="02"){
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

// Checkear la correción de los datos introducidos en el momento y no cuando se pulse el botón.
function live_checkDate(){
    if (checkDate()){
        document.getElementById("wrong_date").style.display = "none";
    }else{
        document.getElementById("wrong_date").style.display = "block";
    }
}

// EventListener del botón disponible en la página, para evitar el uso de onClicked en HTML.
document.addEventListener('DOMContentLoaded', (event) => {
    document.getElementById("DOBSignup").addEventListener("keyup", live_checkDate);
    
    document.getElementById("sendButton").addEventListener("click", update);
})

function getId(){
    //Obtenemos el id de la entrada a modificar a través de la URL
    let params = new URLSearchParams(document.location.search);
    let id = params.get("id");

    return id;
}