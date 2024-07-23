
let versionId = document.getElementById("versionId").value;
let listeChansons = document.getElementById("liste-chansons");

function getChansons(versionId) {
    listeChansons.innerHTML = "";
    let url = "http://discotheque.test/src/router.php?id=" + versionId;

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
                ligne.id = track;

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
                boutonEditChanson.innerHTML = '<div class="btn btn-primary">Editer la chanson</div>'
                ligne.appendChild(boutonEditChanson);

                let boutonDeleteChanson = document.createElement('td');
                boutonDeleteChanson.className = "btn-td";
                boutonDeleteChanson.innerHTML = '<div class="btn btn-danger">Supprimer la chanson</div>'
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

document.onload = getChansons(versionId);