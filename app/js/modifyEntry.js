async function update(event){
    event.preventDefault();
    //if all the checks are true, submit the form via POST fetch to /api/signup_register.php
    sql = "UPDATE `horoscopos` SET ";
    sql = fill_fields(sql);
    if (sql == "UPDATE `horoscopos` SET "){
        alert("No se ha modificado ningún campo"); //al concatenar nunca se dará este caso
        return;
    }

    id=getId();
    //FALTA QUE RECIBA EL ID CLAVE DEL HOROSCOPO A MODIFICAR
    sql = sql.slice(0,-1).concat(" WHERE id = ", id).concat(";") //, document.getElementById("DNISignup").getAttribute("placeholder")).concat("'");
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
    try{
        replacer=document.signUp.name.value.trim();
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
    let params = new URLSearchParams(document.location.search);
    let id = params.get("id");

    return id;
}