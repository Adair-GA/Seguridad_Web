/*$(document).ready( function () {
    $('#tablaHoroscopos').DataTable();
} );*/

function editEntry(id){
    // Nos llevará a modifyEntry.php con el parámetro id en el link (inseguro)
    window.location.href = '/modifyEntry.php?id=' + id; 
}

async function deleteEntry(id){

    let confirmation = confirm("¿Deseas eliminar el horóscopo seleccionado?");
    if (confirmation){
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
    else{
        window.alert("ANULADO: no se ha eliminado el elemento seleccionado");
    }
    

}