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


document.addEventListener('DOMContentLoaded', (event) => {
    document.getElementById("sendButton").addEventListener("click", update);
})

function getId(){
    //Obtenemos el id de la entrada a modificar a través de la URL
    let params = new URLSearchParams(document.location.search);
    let id = params.get("id");

    return id;
}