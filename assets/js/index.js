import { getList, editEtat, deleteToList } from "./fonctions_rest.js";
import { modaleSuppression, selecteur } from "./composants.js";

const tableBody = document.querySelector("tbody");

// GESTION DES EVENEMENTS DU DOM------------------------------------------------------------------------------------------------------------------------------
// Récupère la collection de l'utilisateur

window.onload = () => {
	afficheListe();
};

document.addEventListener("change", function (event) {
	const el = event.target;
	if (el.id.includes("etat-media")) {
		changeEtat(el, "media");
	} else if (el.id.includes("etat-pochette")) {
		changeEtat(el, "pochette");
	}
});

document.addEventListener("click", function (event) {
	const el = event.target;
	if (el.id.includes("delete-")) {
		let id = el.id.replace("delete-", "");
		modaleSuppression(id);
	}

	//Confirmation de la suppression de la chanson
	if (el.id == "modale-confirm-supression") {
		const modale = el.closest(".modale");
		if (modale) {
			supprimerLigne(modale);
		}
	}
});

/**
 * Affiche la liste des versions de l'utilisateur de manière asynchronne
 *
 * @returns {void}
 */
async function afficheListe() {
	const parametres = Object.entries({
		id: sessionStorage.getItem("userId"),
	})
		.filter(([key, value]) => value.trim() !== "") // Permet de filtrer les valeurs vides dans un tableau de tableaux
		.reduce((acc, [key, value]) => {
			// Permet de transformer le tableau de tableaux en un objet
			acc[key] = value;
			return acc;
		}, {});

	try {
		const liste = await getList(parametres);
		if (liste) {
			const baseSelect = await selecteur("d_etat_eta", "etat-media-base");

			const lignesPromises = liste.map(async (item, i) => {
				const ligne = document.createElement("tr");
				ligne.id = item.lis_id;

				// numéro de ligne
				const numeroLigne = document.createElement("th");
				numeroLigne.scope = "row";
				numeroLigne.classList.add("th-center", "th-row");
				numeroLigne.textContent = i + 1;
				ligne.appendChild(numeroLigne);

				// Image
				const imageTd = document.createElement("td");
				imageTd.classList.add("hide");
				const img = document.createElement("img");
				img.src = item.ver_image;
				img.alt = "pochette de l'album";
				img.classList.add("img-liste");
				imageTd.appendChild(img);
				ligne.appendChild(imageTd);

				// Reference
				const refTd = document.createElement("td");
				refTd.textContent = item.ver_ref;
				ligne.appendChild(refTd);

				// Titre Album
				const albumTd = document.createElement("td");
				const albumLink = document.createElement("a");
				albumLink.href = `vue/version_album.php?id=${item.ver_id}`;
				albumLink.textContent = item.alb_titre;
				albumTd.appendChild(albumLink);
				ligne.appendChild(albumTd);

				// Artiste
				const artisteTd = document.createElement("td");
				artisteTd.textContent = item.art_nom;
				ligne.appendChild(artisteTd);

				// Format
				const formatTd = document.createElement("td");
				formatTd.classList.add("hide");
				formatTd.textContent = item.for_nom;
				ligne.appendChild(formatTd);

				// Genre
				const genreTd = document.createElement("td");
				genreTd.classList.add("hide");
				genreTd.textContent = item.gen_nom;
				ligne.appendChild(genreTd);

				// Etats
				const etatTd = document.createElement("td");
				etatTd.classList.add("etat-td", "form-etat-pc");

				// Etat media select
				const mediaStateDiv = document.createElement("div");
				mediaStateDiv.classList.add("select-etat");
				const mediaStateLabel = document.createElement("label");
				mediaStateLabel.classList.add("form-label");
				mediaStateLabel.textContent = "Média:";
				// Cloner le sélecteur de base et personnaliser
				const mediaStateSelect = baseSelect.cloneNode(true);
				mediaStateSelect.id = mediaStateSelect.name = "etat-media-" + item.lis_id;
				mediaStateSelect.value = item.lis_fk_media_eta_id;
				mediaStateLabel.setAttribute("for", mediaStateSelect.id);
				mediaStateDiv.appendChild(mediaStateLabel);
				mediaStateDiv.appendChild(mediaStateSelect);
				etatTd.appendChild(mediaStateDiv);

				// Etat pochette select
				const pochetteStateDiv = document.createElement("div");
				pochetteStateDiv.classList.add("select-etat");
				const pochetteStateLabel = document.createElement("label");
				pochetteStateLabel.classList.add("form-label");
				pochetteStateLabel.textContent = "Pochette:";
				// Cloner le sélecteur de base et personnaliser
				const pochetteStateSelect = mediaStateSelect.cloneNode(true);
				pochetteStateSelect.id = pochetteStateSelect.name = "etat-pochette-" + item.lis_id;
				pochetteStateSelect.value = item.lis_fk_pochette_eta_id;
				pochetteStateLabel.setAttribute("for", pochetteStateSelect.id);
				pochetteStateDiv.appendChild(pochetteStateLabel);
				pochetteStateDiv.appendChild(pochetteStateSelect);
				etatTd.appendChild(pochetteStateDiv);
				ligne.appendChild(etatTd);

				// Delete button
				const deleteTd = document.createElement("td");
				deleteTd.classList.add("btn-td", "form-etat-pc");
				const deleteDiv = document.createElement("div");
				deleteDiv.classList.add("btn", "btn-danger");
				deleteDiv.id = "delete-" + item.lis_id;
				deleteDiv.textContent = "Supprimer";
				deleteTd.appendChild(deleteDiv);
				ligne.appendChild(deleteTd);

				return ligne;
			});
			// Attends que chaque ligne soit créée avant de l'ajouter au tableau
			const lignes = await Promise.all(lignesPromises);

			// Ajoute les lignes au tableau
			lignes.forEach((ligne) => tableBody.appendChild(ligne));
		}
	} catch (error) {
		console.error("Erreur lors de l'ajout de la version à la liste :", error);
	}
}

/**
 * Fonction pour modifier l'état d'un élément
 *
 *  @param {HTMLElement} el - L'élément HTML à modifier
 * @param {string} type - Le type d'état à modifier (media ou pochette)
 * @returns {Promise<void>} Une promesse qui se résout lorsque l'état est modifié avec succès
 */
async function changeEtat(el, type) {
	let id = el.id.replace("etat-" + type + "-", "");
	let etat = el.value;

	let bodyContent = {
		id: id,
		type: type,
		etat: etat,
	};

	try {
		let response = await editEtat(bodyContent);
		if (response) {
			el.value = etat;
		} else {
			alert("L'edition de l'état de " + type + " a échoué.");
		}
	} catch (error) {
		alert("Erreur lors de l'edition de l'état de " + type + " : " + error);
	}
}

async function supprimerLigne(modale) {
	let id = document.getElementById("modale-id").textContent;
	let bodyContent = {
		id: id,
	};

	try {
		const suppression = await deleteToList(bodyContent);
		if (suppression) {
			const ligne = document.getElementById(id);
			ligne.remove();
			modale.remove();
		} else {
			alert("La suppression de la ligne a échoué.");
		}
	} catch (error) {
		alert("Erreur lors de la suppression de la ligne : " + error);
	}
}
