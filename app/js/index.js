$(document).ready( function () {
    $('#tablaHoroscopos').DataTable();
} );

function editEntry(id){
    // Nos llevará a modifyEntry.php con el parámetro id en el link (inseguro)
    window.location.href = '/modifyEntry.php?id=' + id; 
}
