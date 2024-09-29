let idVersion = document.getElementById("idVersion").value;
let listeChansons = document.getElementById("liste-chansons");
let modale = document.getElementById("modale-chanson");
let closeModale = document.getElementById("close-modale");
let nombreChanson = document.getElementById('nombre-chansons');
let defineNChanson = document.getElementById("nChanson");
let addChansonBtn = document.getElementById("add-chanson-btn");
let modaleActionChanson = document.getElementById("modale-action-chanson");
let modaleDeleteChanson = document.getElementById("modale-delete-chanson");
let modaleidChanson = document.getElementById("modale-id-chanson");

// TODO: Séparer les fonctions REST du code spécifique à la page
function getChansons(idVersion) {
    listeChansons.innerHTML = "";
    let url = "../../includes/router.php?idVersion=" + idVersion;

    fetch(url, { method: 'GET' })
        .then(response => response.json())
        .then(data => {

            for (let $i = 0; $i < data.length; $i = $i + 1) {

                // Récupération des données de L'api
                id = data[$i]['cha_id'];
                titre = data[$i]['cha_titre'];
                duree = data[$i]['cha_duree'];
                track = data[$i]['cha_track'];
                colonneTable = [titre, duree];

                let ligne = document.createElement('tr');
                ligne.id = id;
                ligne.className = "chanson";

                // Entête deligne avec le numéro de track
                let ligneTrack = document.createElement('th');
                ligneTrack.setAttribute('scope', 'row');
                ligneTrack.innerHTML = track;
                ligne.appendChild(ligneTrack);

                // Infos de chaque chanson
                for ($y = 0; $y < colonneTable.length; $y = $y + 1) {

                    let cellule = document.createElement('td');
                    cellule.innerHTML = colonneTable[$y];
                    ligne.appendChild(cellule);
                }

                // Boutons d'action sur chaque chanson

                let boutonEditChanson = document.createElement('td')
                boutonEditChanson.className = "btn-td"
                boutonEditChanson.innerHTML = '<div class="btn btn-primary edit-chanson">Editer la chanson</div>'
                ligne.appendChild(boutonEditChanson);

                let boutonDeleteChanson = document.createElement('td');
                boutonDeleteChanson.className = "btn-td";
                boutonDeleteChanson.innerHTML = '<div class="btn btn-danger delete-chanson">Supprimer la chanson</div>'
                ligne.appendChild(boutonDeleteChanson);

                listeChansons.appendChild(ligne);
            }

            return;
        })
        .catch((error) => {
            if (error) {
                alert('error');
                return;
            }
            return;
        })
}

function deleteChanson(idChanson) {
    let url = "../../includes/router.php?idChanson=" + idChanson;
    fetch(url, { method: 'DELETE' })
        .then(() => {
            getChansons(idVersion);
        })
        .catch((error) => {
            if (error) {
                alert('error');
                return;
            }
            return;
        })
    resetModaleChanson()
    alert("suppression ok");
    return;
}

function addChanson(idVersion) {
    let addedChansons = document.getElementsByClassName("newtrack");
    let url = "../../includes/router.php";
    for (i = 0; i < addedChansons.length; i = i + 1) {
        let nouvelleChanson = addedChansons.item(i);
        let trackNouvelleChanson = nouvelleChanson.firstChild.firstChild.value;
        let titreNouvelleChanson = nouvelleChanson.children[1].firstChild.value;
        let dureeNouvelleChanson = nouvelleChanson.children[2].firstChild.value;

        fetch(url, {
            method: 'POST',
            body: JSON.stringify({
                "idVersion": idVersion,
                "titre": titreNouvelleChanson,
                "duree": dureeNouvelleChanson,
                "track": trackNouvelleChanson
            }),
            headers: {
                "Content-type": "application/json"
            }
        })
            .then(() => {
                getChansons(idVersion);
            })
            .catch((error) => {
                if (error) {
                    alert('error');
                    return;
                }
                return;
            })
    }
    resetModaleChanson()

    return;
}

function editChanson(idChanson) {
    let url = "../../includes/router.php";
    let trackEditChanson = document.getElementById('track0').value;
    let titreEditChanson = document.getElementById('titre0').value;
    let dureeEditChanson = document.getElementById('duree0').value;
    fetch(url, {
        method: 'PUT',
        body: JSON.stringify({
            "idVersion": idVersion,
            "idChanson": idChanson,
            "titre": titreEditChanson,
            "duree": dureeEditChanson,
            "track": trackEditChanson
        }),
        headers: {
            "Content-type": "application/json"
        }
    })
        .then(() => {
            getChansons(idVersion);
        })
        .catch((error) => {
            if (error) {
                alert('error');
                return;
            }
            return;
        })
    resetModaleChanson()

    return;
}

// Fin des fonctions REST et début du code page
function tailleFormulaireModale() {
    let nChanson = defineNChanson.value;
    let modaleListeChanson = document.getElementById("modale-liste-chanson");
    modaleListeChanson.innerHTML = "";

    for (i = 0; i < nChanson; i = i + 1) {
        ligne = document.createElement('tr')
        ligne.className = "newtrack";
        ligne.id = ("newtrack" + i);
        ligne.innerHTML = "<td><input required type='text' class='form-control track' id='track" + i +
            "'></td><td><input required type='text' class='form-control titre'  id='titre" + i +
            "'></td><td><input type='text' class='form-control duree' id='duree" + i +
            "'></td>"
        modaleListeChanson.appendChild(ligne);
    }

    return;
}

function modalEditChanson(idChanson) {
    let editChanson = document.getElementById(idChanson);
    let trackEditChanson = editChanson.children[0].textContent;
    let titreEditChanson = editChanson.children[1].textContent;
    let dureeEditChanson = editChanson.children[2].textContent;
    tailleFormulaireModale();

    modaleidChanson.value = idChanson;
    document.getElementById('newtrack0').id = idChanson
    document.getElementById('track0').value = trackEditChanson;
    document.getElementById('titre0').value = titreEditChanson;
    document.getElementById('duree0').value = dureeEditChanson;

    nombreChanson.classList.add('hidden');
    let editBtn = document.getElementById("modale-add-chanson");
    editBtn.id = "modale-edit-chanson";
    editBtn.textContent = "Editer la Chanson";
    modale.classList.remove("hidden");

    return
}

function modalDeleteChanson(idChanson) {
    modaleidChanson.value = idChanson;

    modaleDeleteChanson.classList.remove("hidden");
    modaleActionChanson.classList.add("hidden");

    modale.classList.remove("hidden");

}

function resetModaleChanson() {
    modale.classList.add("hidden");
    modaleidChanson.value = "";

    modaleDeleteChanson.classList.add("hidden");
    modaleActionChanson.classList.remove("hidden");

    nombreChanson.classList.remove('hidden');
    let editBtn = document.getElementById("modale-edit-chanson");
    if (editBtn != null) {
        editBtn.id = "modale-add-chanson";
        editBtn.textContent = "Créer";
    }

    return
}

document.onload = getChansons(idVersion);

document.addEventListener('click', function (event) {
    let el = event.target;
    if (el.classList.contains("delete-chanson")) {
        let idChanson = el.parentNode.parentNode.id;
        modalDeleteChanson(idChanson);
        // deleteChanson(idChanson);
    }



    if (el.classList.contains("edit-chanson")) {
        let idChanson = el.parentNode.parentNode.id;
        modalEditChanson(idChanson);
    }

})

addChansonBtn.addEventListener('click', function () {
    tailleFormulaireModale();
    modale.classList.remove("hidden");
})

closeModale.addEventListener('click', function () {
    resetModaleChanson()
})

defineNChanson.addEventListener('change', function () { tailleFormulaireModale() });

modale.addEventListener('click', function (event) {
    let el = event.target;

    if (el.id == "modale-add-chanson") {
        addChanson(idVersion);

    }

    if (el.id == "modale-edit-chanson") {
        let idChanson = modaleidChanson.value;
        editChanson(idChanson);
    }

    if (el.id == "modale-confirm-delete-chanson") {
        let idChanson = modaleidChanson.value;
        deleteChanson(idChanson);
    }
    if (el.id == "modale-confirm-cancel-chanson") {
        resetModaleChanson();
    }
})