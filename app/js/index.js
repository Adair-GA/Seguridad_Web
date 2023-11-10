function editEntry(id){
    // Nos llevará a modifyEntry.php con el parámetro id en el link (inseguro)
    window.location.href = '/modifyEntry.php?id=' + id;
}

async function deleteEntry(id,name,signo){

    // Pedimos confirmación del borrado, indicando los datos del elemento a borrar
    let confirmation = confirm("¿Deseas eliminar el horóscopo de "+name+ " con signo solar "+signo.toLowerCase()+"?"
    + " Puedes revisar o modificar toda la información del horóscopo cancelando y clickando en el botón 'Editar' de la tabla.");
    if (confirmation){
        fd = new FormData();
        fd.append('id', id);
        res = await fetch('/api/delete_entry.php', {
            method: 'POST',
            body: fd
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

document.addEventListener('DOMContentLoaded', (event) => {
    let btns = document.querySelectorAll('button');
    let index = 1;
    /*for (i of btns) {
        if (i.getAttribute("data-type")=="edit"){
            i.addEventListener('click', () => { 
                alert(i.getAttribute("data-id"))});
        }else{
            i.addEventListener('click', deleteEntry2);
        }
        index++;
    }*/

    for (i of btns) {
        (function(i) {
            if (i.getAttribute("data-type")=="edit"){
                i.addEventListener('click', function() {
                editEntry(i.getAttribute("data-id"));
                });
            }else{
                i.addEventListener('click', function() {
                    deleteEntry(i.getAttribute("data-id"),i.getAttribute("data-name"),i.getAttribute("data-signo"));
                    });
            }
        })(i);
      }

    
})



/*document.addEventListener('DOMContentLoaded', (event) => {
    document.getElementById("SignUpButton").addEventListener("click", register);
    document.getElementById("LogInButton").addEventListener("click", login);
})*/