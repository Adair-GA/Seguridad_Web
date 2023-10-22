function editEntry(id){
    // Nos llevará a modifyEntry.php con el parámetro id en el link (inseguro)
    window.location.href = '/modifyEntry.php?id=' + id; 
}

async function deleteEntry(id,name,signo){

    // Pedimos confirmación del borrado, indicando los datos del elemento a borrar
    let confirmation = confirm("¿Deseas eliminar el horóscopo de "+name+ " con signo solar "+signo.toLowerCase()+"?"
    + " Puedes revisar o modificar toda la información del horóscopo cancelando y clickando en el botón 'Editar' de la tabla.");
    if (confirmation){ // Si se confirma, se borra
        sql = "DELETE FROM horoscopos WHERE id=" + id + ";"; 
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
    else{ // Si se anula el borrado, se avisa de la situación
        window.alert("ANULADO: no se ha eliminado el horóscopo de "+name+ " con signo solar "+signo.toLowerCase()+".");
    }
    

}