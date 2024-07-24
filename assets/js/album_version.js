let idVersion = document.getElementById("idVersion").value;
let listeChansons = document.getElementById("liste-chansons");
let modale = document.getElementById("modale-chanson");
let closeModale = document.getElementById("close-modale");
let defineNChanson = document.getElementById("nChanson");

function getChansons(idVersion) {
    listeChansons.innerHTML = "";
    let url = "http://discotheque.test/src/router.php?idVersion=" + idVersion;

    fetch(url, { method: 'GET' })
        .then(response => response.json())
        .then(data => {

            for (let $i = 0; $i < data.length; $i = $i + 1) {

                //Récupération des données de L'api
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
    let url = "http://discotheque.test/src/router.php?idChanson=" + idChanson;
    fetch(url, { method: 'DELETE' })
        .then(() => {
            alert("suppression ok");
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

function addChanson(idVersion) {



}

function tailleFormulaireModale() {
    let nChanson = defineNChanson.value;
    let modaleListeChanson = document.getElementById("modale-liste-chanson");
    modaleListeChanson.innerHTML = "";

    for (i = 0; i < nChanson; i = i + 1) {
        ligne = document.createElement('tr')
        ligne.id = ("newtrack" + i);
        ligne.innerHTML = "<td><input required type='text' class='form-control' id='track" + i +
            "'></td><td><input required type='text' class='form-control'  id='titre" + i +
            "'></td><td><input type='text' class='form-control' id='duree" + i +
            "'></td>"
        modaleListeChanson.appendChild(ligne);
    }
}

document.onload = getChansons(idVersion);

document.addEventListener('click', function (event) {
    let el = event.target;
    if (el.classList.contains("delete-chanson")) {
        idChanson = el.parentNode.parentNode.id;
        deleteChanson(idChanson);
    }

    if (el.classList.contains("add-chanson")) {
        tailleFormulaireModale();
        modale.classList.remove("hidden");
    }
})

closeModale.addEventListener('click', function () {
    modale.classList.add("hidden");
})

defineNChanson.addEventListener('change', function () { tailleFormulaireModale() });