import { getVersions, addVersionToList, addVersion, addAlbum, addArtiste, addLabel } from "./fonctions_rest.js";
import { modaleVersion, selecteur } from "./composants.js";

const chercherReference = document.getElementById("chercher-reference");
const chercherTitre = document.getElementById("chercher-titre");
const chercherArtiste = document.getElementById("chercher-artiste");
const tableListe = document.getElementById("table-liste");

// GESTION DES EVENEMENTS DU DOM------------------------------------------------------------------------------------------------------------------------------

// Recherche les versions en fonction des champs de recherche
document.addEventListener("change", function (event) {
	const el = event.target;
	if (el.id == "chercher-reference" || el.id == "chercher-titre" || el.id == "chercher-artiste") {
		const parametres = Object.entries({
			chercherreference: chercherReference.value,
			cherchertitre: chercherTitre.value,
			chercherartiste: chercherArtiste.value,
		})
			.filter(([key, value]) => value.trim() !== "") // Permet de filtrer les valeurs vides dans un tableau de tableaux
			.reduce((acc, [key, value]) => {
				// Permet de transformer le tableau de tableaux en un objet
				acc[key] = value;
				return acc;
			}, {});

		tableListe.innerHTML = "";
		afficherRechercheVersions(parametres);
	}
});

// Ajoute une version à la liste
document.addEventListener("click", function (event) {
	const el = event.target;
	if (el.classList.contains("ajout-liste")) {
		const idVersion = el.parentNode.parentNode.id;
		ajoutListe(idVersion);
	}

	if (el.id == "ajout-version") {
		modaleVersion();
	}

	// ajout d'une version
	if (event.target.id == "valide-nouvelle-version") {
		const nouvellesDonnees = donneesFormulaire();
		ajouterVersion(nouvellesDonnees);
	}

	// ajout d'un titre d'album
	if (event.target.id == "valide-nouveau-titre") {
		ajouterTitre();
	}

	// ajout d'un artiste
	if (event.target.id == "valide-nouvel-artiste") {
		ajouterArtiste();
	}

	// ajout d'un label
	if (event.target.id == "valide-nouveau-label") {
		ajouterLabel();
	}
});

// FONCTIONS de MANIPULATION DU DOM suite au retour API REST-----------------------------------------------------------------------------------------------------------------

/**
 * Affiche les versions en fonction des champs de recherche
 *
 * @param {Object} parametres - Les paramètres de recherche
 * @returns {Promise<void>}
 */
async function afficherRechercheVersions(parametres) {
	try {
		const listeVersions = await getVersions(parametres);
		if (listeVersions) {
			let index = 1;
			for (let version in listeVersions) {
				const ligneVersion = document.createElement("tr");
				ligneVersion.id = listeVersions[version]["ver_id"];
				const thLigne = document.createElement("th");
				thLigne.scope = "row";
				thLigne.textContent = index;
				ligneVersion.appendChild(thLigne);

				const thRef = document.createElement("th");
				const thRefLien = document.createElement("a");
				thRefLien.href = "../../vue/version_album.php?id=" + listeVersions[version]["ver_id"];
				thRefLien.textContent = listeVersions[version]["ver_ref"];
				thRef.appendChild(thRefLien);
				ligneVersion.appendChild(thRef);

				const thTitre = document.createElement("td");
				thTitre.textContent = listeVersions[version]["alb_titre"];
				ligneVersion.appendChild(thTitre);

				const thArtiste = document.createElement("td");
				thArtiste.textContent = listeVersions[version]["art_nom"];
				ligneVersion.appendChild(thArtiste);

				const thSortie = document.createElement("td");
				thSortie.textContent = listeVersions[version]["ver_press_annee"];
				ligneVersion.appendChild(thSortie);

				const thPays = document.createElement("td");
				thPays.textContent = listeVersions[version]["ver_press_pays"];
				ligneVersion.appendChild(thPays);

				const thBtn = document.createElement("td");
				const btnAjout = document.createElement("div");
				btnAjout.classList.add("btn", "btn-primary", "ajout-liste");
				btnAjout.textContent = "Ajouter";
				thBtn.appendChild(btnAjout);
				ligneVersion.appendChild(thBtn);

				tableListe.appendChild(ligneVersion);
				index++;
			}
		}
	} catch (error) {
		console.error("Erreur lors de l'affichage de la liste des versions :", error);
	}
}

/**
 * Ajoute une version à la liste de l'utilisateur
 *
 * @param {string} idVersion - L'identifiant de la version à ajouter
 * @returns {Promise<void>}
 */
async function ajoutListe(idVersion) {
	let bodyContent = {
		user: sessionStorage.getItem("userId"),
		version: idVersion,
	};

	try {
		const ajout = await addVersionToList(bodyContent);
		if (ajout) {
			window.location.href = "../../index.php";
		}
	} catch (error) {
		console.error("Erreur lors de l'ajout de la version à la liste :", error);
	}
}

/**
 * Récupère les le contenu de la modale
 *
 * @param {string} elementId - L'identifiant de l'élément à lire
 * @returns {Object} - Les données du formulaire
 */
function valeurElement(elementId) {
	const element = document.getElementById(elementId);
	if (!element) {
		console.warn(`Élément avec l'ID ${elementId} non trouvé`);
		return "";
	}

	// Pour les éléments select
	if (element.tagName.toLowerCase() === "select") {
		return element.options[element.selectedIndex].textContent;
	}

	// Pour les éléments input
	if (element.tagName.toLowerCase() === "input") {
		return element.value;
	}

	// Pour les éléments image
	if (element.tagName.toLowerCase() === "img") {
		return element.src;
	}

	// Pour les div et autres éléments contenant du texte
	return element.textContent.trim();
}

/**
 * Récupère et stocke les données du formulaire
 *
 * @returns {Object} - Les données du formulaire
 */
function donneesFormulaire() {
	return {
		titre: valeurElement("version-add-titre"),

		label: valeurElement("version-add-label"),

		reference: valeurElement("version-add-reference"),

		format: valeurElement("version-add-format"),

		pays: valeurElement("version-add-pays"),

		pressageAnnee: valeurElement("version-add-pressage-annee"),

		type: valeurElement("version-add-type"),

		image: valeurElement("version-add-image"),
	};
}

/**
 * Ajoute une nouvelle version à la base de données
 *
 * @param {Object} donneesFormulaire - Les données du formulaire
 * @returns {Promise<void>}
 */
async function ajouterVersion(donneesFormulaire) {
	const imageInput = document.getElementById("version-add-image");

	const formData = new FormData();
	formData.append("titre", donneesFormulaire["titre"]);
	formData.append("format", donneesFormulaire["format"]);
	formData.append("label", donneesFormulaire["label"]);
	formData.append("pressageAnnee", donneesFormulaire["pressageAnnee"]);
	formData.append("pays", donneesFormulaire["pays"]);
	formData.append("reference", donneesFormulaire["reference"]);
	formData.append("type", donneesFormulaire["type"]);

	// Ajout de l'image si elle existe
	if (imageInput && imageInput.files[0]) {
		formData.append("image", imageInput.files[0]);
	}

	try {
		let success = await addVersion(formData);
		if (success) {
			const modale = document.getElementById("modale-version");
			if (modale) {
				modale.remove();
			}
		} else {
			alert("L'ajout de la version a échoué.");
		}
	} catch (error) {
		alert("Erreur lors de l'ajout' de la version.");
	}
}

/**
 * Ajoute un titre à la base de données
 *
 * @param {Object} donneesFormulaire - Les données du formulaire
 * @returns {Promise<void>}
 */
async function ajouterTitre() {
	const modale = document.getElementById("modale-creation-titre");
	const modaleVersion = document.getElementById("modale-version");
	const selectAlbum = document.getElementById("version-add-titre");
	let titre = document.getElementById("titre-add-nom").value;
	let artiste = document.getElementById("titre-add-artiste").value;
	let annee = document.getElementById("titre-add-annee").value;
	let genre = document.getElementById("titre-add-genre").value;

	let bodyContent = {
		titre: titre,
		artiste: artiste,
		annee: annee,
		genre: genre,
	};

	try {
		let id = await addAlbum(bodyContent);
		if (id) {
			const newSelectAlbum = await selecteur("d_album_alb", "version-add-titre");
			selectAlbum.replaceWith(newSelectAlbum);
			modale.remove();
			modaleVersion.style.display = "block";
		} else {
			alert("L'ajout du titre a échoué.");
		}
	} catch (error) {
		alert("Erreur lors de l'ajout du titre.");
	}
}

/**
 * Ajoute un artiste à la base de données
 *
 * @param {Object} donneesFormulaire - Les données du formulaire
 * @returns {Promise<void>}
 */
async function ajouterArtiste() {
	const modale = document.getElementById("modale-creation-artiste");
	const modaleTitre = document.getElementById("modale-creation-titre");
	const selectArtiste = document.getElementById("titre-add-artiste");
	let nomArtiste = document.getElementById("artiste-add-nom").value;
	let paysArtiste = document.getElementById("artiste-add-pays").value;

	let bodyContent = {
		nom: nomArtiste,
		pays: paysArtiste,
	};

	try {
		let id = await addArtiste(bodyContent);
		if (id) {
			const newSelectArtiste = await selecteur("d_artiste_art", "titre-add-artiste");
			selectArtiste.replaceWith(newSelectArtiste);
			modale.remove();
			modaleTitre.style.display = "block";
		} else {
			alert("L'ajout de l'artiste' a échoué.");
		}
	} catch (error) {
		alert("Erreur lors de l'ajout de l'artiste'.");
	}
}

/**
 * Ajoute un label à la base de données
 *
 * @param {Object} donneesFormulaire - Les données du formulaire
 * @returns {Promise<void>}
 */
async function ajouterLabel() {
	const modale = document.getElementById("modale-creation-label");
	const modaleVersion = document.getElementById("modale-version");
	const selectLabel = document.getElementById("version-add-label");
	let nom = document.getElementById("label-add-nom").value;

	let bodyContent = {
		nom: nom,
	};

	try {
		let id = await addLabel(bodyContent);
		if (id) {
			const newSelectLabel = await selecteur("d_label_lab", "version-add-label");
			selectLabel.replaceWith(newSelectLabel);
			modale.remove();
			modaleVersion.style.display = "block";
		} else {
			alert("L'ajout du label a échoué.");
		}
	} catch (error) {
		alert("Erreur lors de l'ajout du label.");
	}
}
