addEventListener('DOMContentLoaded', (event) => {
    document.getElementById("sendButton").addEventListener("click", sendData);
});

async function sendData(event) {
    event.preventDefault();
     
    if (!checkDate()){
        //Si el formato de la fecha es incorrecto, mostrar mensaje error.
        document.getElementById("wrong_date").style.display = "block";
        return;
    }
    else{
        //Si el formato de la fecha es correcto, proceder a crear la entrada en la DB.
        const formData = new FormData(document.getElementById("entryForm"));

        res = await fetch(
            "/api/create_entry.php",
            {
                method: "POST",
                body: formData
            }
        )
        res = await res.text();
        alert(res);
        if (res == "Horoscopo registrado correctamente") {
            document.getElementById("entryForm").reset();
        }
    }
}

function checkDate(){
    var date= document.entry.DOBEntry.value;
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
    }
    return true;
}

function live_checkDate(){
    if (checkDate()){
        //Ocultar mensaje de error
        document.getElementById("wrong_date").style.display = "none";
    }else{
        //mostrar mensaje de error
        document.getElementById("wrong_date").style.display = "block";
    }
}