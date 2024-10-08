import { getChansons, deleteChanson, addChanson, editChanson } from "./fonctions_rest.js";
import { modaleChanson, modaleSuppressionChanson } from "./composants.js";

// Récupération de divers éléments du DOM dans des variables
let idVersion = document.getElementById("idVersion").value;
let listeChansons = document.getElementById("liste-chansons");
let addChansonBtn = document.getElementById("add-chanson-btn");

// GESTION DES EVENEMENTS DU DOM------------------------------------------------------------------------------------------------------------------------------
// Récupère la liste des chansons au chargement
document.onload = afficherChansons(idVersion);

// ouvre la modale pour ajout de chanson
addChansonBtn.addEventListener('click', function () {
    modaleChanson();
})

listeChansons.addEventListener('click', function (event) {
    let el = event.target;

    // ouvre la modale pour editer une chanson
    if (el.classList.contains("edit-chanson-btn")) {
        let idChanson = el.parentNode.parentNode.id;
        let trackEditChanson = el.parentNode.parentNode.querySelector('.track').textContent;
        let titreEditChanson = el.parentNode.parentNode.querySelector('.titre').textContent;
        let dureeEditChanson = el.parentNode.parentNode.querySelector('.duree').textContent;
        modaleChanson(1, idChanson, trackEditChanson, titreEditChanson, dureeEditChanson, 'edit');
    }

    // ouvre la modale pour supprimer une chanson
    if (el.classList.contains("delete-chanson-btn")) {
        let idChanson = el.parentNode.parentNode.id;
        modaleSuppressionChanson(idChanson);
    }

})

// Action des boutons de la modale
document.addEventListener('click', function (event) {
    //fermeture de la modale
    if (event.target.id == 'close-modale' || event.target.id == 'modale-confirm-cancel-chanson') {
        const modale = event.target.closest('.modale');
        if (modale) {
            modale.remove();
        }
    }

    //Confirmation de l'ajout de chansons
    if (event.target.id == 'modale-add-chanson') {
        const modale = event.target.closest('.modale');
        if (modale) {
            let addedChansons = modale.querySelectorAll('.newtrack')
            ajoutChanson(idVersion, addedChansons, modale);

        }
    }

    //Confirmation de l'edition de la chanson
    if (event.target.id == 'modale-edit-chanson') {
        const modale = event.target.closest('.modale');
        if (modale) {
            editerChanson(idVersion, modale);
        }
    }

    //Confirmation de la suppression de la chanson
    if (event.target.id == 'modale-confirm-delete-chanson') {
        const modale = event.target.closest('.modale');
        if (modale) {
            supprimerChanson(modale);
        }
    }
});

//Modifie le nombre de formulaire d'ajout de chanson
document.addEventListener('change', function (event) {
    let el = event.target;
    const modale = event.target.closest('.modale');
    if (el.id == 'nChanson') {
        let nChanson = el.value;
        modale.remove();
        modaleChanson(nChanson);
    }
})

// FONCTIONS de MANIPULATION DU DOM suite au retour API REST------------------------------------------------------------------------------------------------------------------------------
async function afficherChansons(idVersion) {
    listeChansons.innerHTML = "";

    try {
        const chansons = await getChansons(idVersion);  // Appel de la fonction de récupération des chansons

        if (chansons) {
            chansons.forEach(chanson => {
                const { cha_id: id, cha_titre: titre, cha_duree: duree, cha_track: track } = chanson;
                const colonneTable = { "track": track, "titre": titre, "duree": duree };

                const ligne = creationLigneChanson(id, colonneTable);

                // Ajout de la ligne au tableau
                listeChansons.appendChild(ligne);
            })
        } else {
            alert("L'affichage des chansons a échoué.");
        }
    } catch (error) {
        alert("Erreur lors de l'affichage des chansons");
    }
}

async function supprimerChanson(modale) {
    let idDeleteChanson = document.getElementById('modale-id-chanson').textContent;
    let bodyContent = {
        "idChanson": idDeleteChanson
    };

    try {
        let suppression = await deleteChanson(bodyContent);
        if (suppression) {
            let ligne = document.getElementById(idDeleteChanson);
            ligne.remove();
            modale.remove();
            alert("Suppression réussie.");
        } else {
            alert("La suppression de la chanson a échoué.");
        }
    } catch (error) {
        alert("Erreur lors de la suppression de la chanson.");
    }

    return;
}

async function ajoutChanson(idVersion, addedChansons, modale) {
    // let addedChansons = document.getElementsByClassName("newtrack");

    for (let i = 0; i < addedChansons.length; i++) {
        let nouvelleChanson = addedChansons.item(i);
        let trackNouvelleChanson = nouvelleChanson.firstChild.firstChild.value;
        let titreNouvelleChanson = nouvelleChanson.children[1].firstChild.value;
        if (trackNouvelleChanson == "" && titreNouvelleChanson == "") { continue; }
        let dureeNouvelleChanson = nouvelleChanson.children[2].firstChild.value;
        let colonneTable = { "track": trackNouvelleChanson, "titre": titreNouvelleChanson, "duree": dureeNouvelleChanson };

        let bodyContent = {
            "idVersion": idVersion,
            "titre": titreNouvelleChanson,
            "duree": dureeNouvelleChanson,
            "track": trackNouvelleChanson
        };
        try {
            let id = await addChanson(bodyContent);
            if (id) {
                const ligne = creationLigneChanson(id, colonneTable);

                // Ajout de la ligne au tableau
                listeChansons.appendChild(ligne);
                modale.remove();
            } else {
                alert("L'ajout de la chanson a échoué.");
            }
        } catch (error) {
            alert("Erreur lors de l'ajout de la chanson.");
        }
    }
}

async function editerChanson(idVersion, modale) {
    let idEditChanson = document.getElementById('modale-id-chanson').textContent;
    let trackEditChanson = document.getElementById('track0').value;
    let titreEditChanson = document.getElementById('titre0').value;
    let dureeEditChanson = document.getElementById('duree0').value;
    let bodyContent = {
        "idVersion": idVersion,
        "idChanson": idEditChanson,
        "titre": titreEditChanson,
        "duree": dureeEditChanson,
        "track": trackEditChanson
    };

    try {
        let success = await editChanson(bodyContent);
        if (success) {
            let ligne = document.getElementById(idEditChanson);
            ligne.getElementsByClassName('track')[0].textContent = trackEditChanson;
            ligne.getElementsByClassName('titre')[0].textContent = titreEditChanson;
            ligne.getElementsByClassName('duree')[0].textContent = dureeEditChanson;
            modale.remove();
        } else {
            alert("La modification de la chanson a échoué.");
        }
    } catch (error) {
        alert("Erreur lors de la modification de la chanson.");
    }
}

function creationLigneChanson(id, colonneTable) {

    // Création de la ligne
    let ligne = document.createElement('tr');
    ligne.id = id;
    ligne.className = "chanson";

    // Ajout du numéro de piste
    let ligneTrack = document.createElement('th');
    ligneTrack.className = "track";
    ligneTrack.setAttribute('scope', 'row');
    ligneTrack.innerHTML = colonneTable['track'];
    ligne.appendChild(ligneTrack);

    // Ajout des colonnes titre et durée
    for (let cle in colonneTable) {
        if (cle != 'track') {
            let cellule = document.createElement('td');
            cellule.className = cle;
            cellule.textContent = colonneTable[cle];
            ligne.appendChild(cellule);
        }
    }

    // Ajout des boutons d'action
    let boutonEditChanson = document.createElement('td');
    boutonEditChanson.className = "btn-td";
    boutonEditChanson.innerHTML = '<div class="btn btn-primary edit-chanson-btn">Editer la chanson</div>';
    ligne.appendChild(boutonEditChanson);

    let boutonDeleteChanson = document.createElement('td');
    boutonDeleteChanson.className = "btn-td";
    boutonDeleteChanson.innerHTML = '<div class="btn btn-danger delete-chanson-btn">Supprimer la chanson</div>';
    ligne.appendChild(boutonDeleteChanson);

    return ligne;
}