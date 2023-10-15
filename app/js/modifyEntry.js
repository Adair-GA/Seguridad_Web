async function update(event){
    event.preventDefault();
    sql = "UPDATE `horoscopos` SET ";
    // Creamos una sentencia SQL a medida para acceder y modificar aquellos datos que se hayan especificado.
    sql = fill_fields(sql);
    if (sql == "UPDATE `horoscopos` SET "){
        alert("No se ha modificado ningún campo"); //al concatenar nunca se dará este caso, se mantiene por seguridad.
        return;
    }

    id=getId();
    sql = sql.slice(0,-1).concat(" WHERE id = ", id).concat(";") // Con slice quitamos el último carácter, que es una coma
    res = await fetch('/api/update_entry.php', {
        method: 'POST',
        body: sql
    })
    res = await res.text();
    if (res != 'success') {
        alert(res);
    }else{
        alert("Actualización realizada con éxito");
        window.location.reload();
    }
}

function fill_fields(sql){
    /*Se genera una sentencia SQL a medida con sólo aquellos datos que se desean cambiar,
    los datos cuyas casillas se hayan dejado en blanco no se sustituyen, menos accesos a
    la DB*/
    try{
        replacer=document.signUp.name.value.trim(); //.trim() elimina los espacios en blanco en ambos extremos dle string
        if (replacer!=""){
            sql = sql.concat(" nombre = '", replacer).concat("',");
        }
        
        replacer=document.signUp.dob.value.trim();
        if (replacer!=""){
            sql = sql.concat(" fecha_nacimiento = '", replacer).concat("',");
        }
        
        replacer=document.signUp.signosolar.value.trim();
        if (replacer!=""){
            sql = sql.concat(" signo_solar = '", replacer).concat("',");
        }
        
        replacer=document.signUp.signolunar.value.trim();
        if (replacer!=""){
            sql = sql.concat(" signo_lunar = '", replacer).concat("',");
        }
        
        replacer=document.signUp.retrogrado.value.trim();
        if (document.signUp.retrogrado.value.trim() == 'Si'){result='1';}
        else{result='0';}
        sql = sql.concat(" mercurio_retrogrado = '", result).concat("',");
    }
    catch(err){
        console.log(err);
    }
    return sql;
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
        document.getElementById("wrong_date").style.display = "none";
    }else{
        document.getElementById("wrong_date").style.display = "block";
    }
}


document.addEventListener('DOMContentLoaded', (event) => {
    document.getElementById("sendButton").addEventListener("click", update);
})

function getId(){
    //Obtenemos el id de la entrada a modificar a través de la URL
    let params = new URLSearchParams(document.location.search);
    let id = params.get("id");

    return id;
}