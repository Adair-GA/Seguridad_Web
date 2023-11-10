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
        r_text = await res.text();
        if (res.status == 200){
            //Si la entrada se ha creado correctamente, redirigir a la página de inicio.
            alert("Entrada creada correctamente");
            window.location.href = "/index.php";
        }
        else{
            //Si la entrada no se ha creado correctamente, mostrar mensaje de error.
            alert("Error al crear la entrada");
        }
    }
}   

function checkDate(){
    var date= document.entry.DOBSignup.value.trim();
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
            if (day>0 && day<32){
                return true;
            }else{
                return false;
            }
        }else if (month=="02"){
            let limit = 0;
            if (year%400==0 && year%100==0){
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
            if (day>0 && day<31){
                return true;
            }else{
                return false;
            }
        }
    }
    
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