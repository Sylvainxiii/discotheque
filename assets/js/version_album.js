import { getChansons, deleteChanson, addChanson, editChanson, editVersion, getVersion } from "./fonctions_rest.js";
import { modaleChanson, modaleSuppression, selecteur } from "./composants.js";

// Récupération de divers éléments du DOM dans des variables
let idVersion = document.getElementById("idVersion").value;
let listeChansons = document.getElementById("liste-chansons");
let addChansonBtn = document.getElementById("add-chanson-btn");
let editVersionBtn = document.getElementById("edit-version-btn");
let ligneCommandes = document.getElementById("edit-commandes");

// GESTION DES EVENEMENTS DU DOM------------------------------------------------------------------------------------------------------------------------------
// Récupère la liste des chansons au chargement

window.onload = () => {
	afficheDetailVersion(idVersion);
	afficherChansons(idVersion);
};

// ouvre la modale pour ajout de chanson
addChansonBtn.addEventListener("click", function () {
	modaleChanson();
});

// Transforme l'affichage des données de la version en formulaire d'édition
editVersionBtn.addEventListener("click", async function () {
	try {
		await activeEditVersionForm(ligneCommandes);
		afficheDetailVersion(idVersion);
	} catch (error) {
		console.error("Une erreur est survenue:", error);
	}
});

listeChansons.addEventListener("click", function (event) {
	let el = event.target;

	// ouvre la modale pour editer une chanson
	if (el.classList.contains("edit-chanson-btn")) {
		let idChanson = el.parentNode.parentNode.id;
		let trackEditChanson = el.parentNode.parentNode.querySelector(".track").textContent;
		let titreEditChanson = el.parentNode.parentNode.querySelector(".titre").textContent;
		let dureeEditChanson = el.parentNode.parentNode.querySelector(".duree").textContent;
		modaleChanson(1, idChanson, trackEditChanson, titreEditChanson, dureeEditChanson, "edit");
	}

	// ouvre la modale pour supprimer une chanson
	if (el.classList.contains("delete-chanson-btn")) {
		let idChanson = el.parentNode.parentNode.id;
		modaleSuppression(idChanson);
	}
});

// Action des boutons créés dynamiquement
document.addEventListener("click", function (event) {
	//Confirmation de l'ajout de chansons
	if (event.target.id == "modale-add-chanson") {
		const modale = event.target.closest(".modale");
		if (modale) {
			let addedChansons = modale.querySelectorAll(".newtrack");
			ajoutChanson(idVersion, addedChansons, modale);
		}
	}

	//Confirmation de l'edition de la chanson
	if (event.target.id == "modale-edit-chanson") {
		const modale = event.target.closest(".modale");
		if (modale) {
			editerChanson(idVersion, modale);
		}
	}

	//Confirmation de la suppression de la chanson
	if (event.target.id == "modale-confirm-supression") {
		const modale = event.target.closest(".modale");
		if (modale) {
			supprimerChanson(modale);
		}
	}

	//Confirmation de l'edition de la version
	if (event.target.id == "confirm-edit-version") {
		const nouvellesDonnees = donneesFormulaire();
		editerVersion(idVersion, nouvellesDonnees);
	}

	//Annulation de l'edition de la chanson
	if (event.target.id == "cancel-edit-version") {
		(async function (ligneCommandes) {
			try {
				await desactiveEditVersionForm(ligneCommandes);
				afficheDetailVersion(idVersion);
			} catch (error) {
				console.error("Une erreur est survenue:", error);
			}
		})(ligneCommandes);
	}
});

//Modifie le nombre de formulaire d'ajout de chanson
document.addEventListener("change", function (event) {
	let el = event.target;
	const modale = event.target.closest(".modale");
	if (el.id == "nChanson") {
		let nChanson = el.value;
		modale.remove();
		modaleChanson(nChanson);
	}
});

// FONCTIONS de MANIPULATION DU DOM suite au retour API REST-----------------------------------------------------------------------------------------------------------------
// FONCTION VERSION ---------------------------------------------------------------------------------------------------------------------------------------------------------

/**
 * Récupère la valeur d'un élément HTML en fonction de son ID et de son type.
 *
 * @param {string} elementId - L'ID de l'élément HTML à récupérer.
 * @returns {string} La valeur de l'élément HTML.
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
 * Permet de récupérer les données du formulaire
 *
 * @returns {Object} Un objet contenant les données du formulaire.
 */
function donneesFormulaire() {
	return {
		label: valeurElement("version-edit-label"),

		reference: valeurElement("version-edit-reference"),

		format: valeurElement("version-edit-format"),

		pays: valeurElement("version-edit-pays"),

		pressageAnnee: valeurElement("version-edit-pressage-annee"),

		type: valeurElement("version-edit-type"),

		image: valeurElement("version-edit-image"),
	};
}

/**
 * Permet de sélectionner tous les champs nécessaires au formulaire ainsi que le type de transformation et la table de transformation
 *
 * @returns {Object} Un objet contenant les éléments du formulaire et le type de transformation.
 */
function elementsFormulaire() {
	return {
		label: {
			element: document.getElementById("version-edit-label"),
			transformType: "select",
			table: "d_label_lab",
			cleSecondaire: "ver_fk_lab_id",
		},
		reference: {
			element: document.getElementById("version-edit-reference"),
			transformType: "text",
		},
		format: {
			element: document.getElementById("version-edit-format"),
			transformType: "select",
			table: "d_format_for",
			cleSecondaire: "ver_fk_for_id",
		},
		pays: {
			element: document.getElementById("version-edit-pays"),
			transformType: "text",
		},
		pressageAnnee: {
			element: document.getElementById("version-edit-pressage-annee"),
			transformType: "text",
		},
		type: {
			element: document.getElementById("version-edit-type"),
			transformType: "select",
			table: "d_edition_edi",
			cleSecondaire: "ver_fk_edi_id",
		},
		image: {
			element: document.getElementById("version-edit-image"),
			transformType: "image",
		},
	};
}

/**
 * Permet d'afficher les données d'une version
 *
 * @param {string} idVersion
 * @returns {Promise<void>}
 */
async function afficheDetailVersion(idVersion) {
	try {
		const details = await getVersion(idVersion);

		if (details) {
			console.log(details);
			document.querySelector("h1").textContent = details["alb_titre"] + " par " + details["art_nom"];
			document.querySelector("#version-edit-sortie-annee").textContent = details["alb_sortie_annee"];
			document.querySelector("#version-edit-genre").textContent = details["gen_nom"];

			const elements = elementsFormulaire();
			for (const champs in elements) {
				const contenu = details[champs];
				const element = elements[champs]["element"];
				const typeElement = element.tagName;
				if (typeElement === "IMG") {
					element.src = "../" + contenu;
				} else if (typeElement === "DIV") {
					element.textContent = contenu;
				} else if (typeElement === "SELECT") {
					element.value = contenu;
					const options = element.querySelectorAll("option");
					const cleSecondaire = elements[champs]["cleSecondaire"];
					for (const option of options) {
						if (option.value == details[cleSecondaire]) {
							option.selected = true;
						}
					}
				} else if (typeElement === "INPUT") {
					if (element.type != "file") {
						element.value = contenu;
					}
				}
			}
		}
	} catch (error) {
		console.error("Erreur lors de l'affichage des details de la version :", error);
	}
}
/**
 * Transforme les cellules contenant les données de la version en input de manière asynchrone (pour le contenu des select)
 *
 * @param {Object} ligneCommandes - L'objet correspondant à la ligne contenant les boutons de commande.
 * @returns {Promise<void>} Une promesse qui se résout lorsque la transformation est terminée.
 */
async function activeEditVersionForm(ligneCommandes) {
	const elements = elementsFormulaire();
	for (const champs in elements) {
		const element = elements[champs]["element"];
		const type = elements[champs]["transformType"];
		if (type === "select") {
			// const contenu = dataVersion[champs];
			const table = elements[champs]["table"];
			const nom = element.id;
			try {
				const inputSelecteur = await selecteur(table, nom);
				element.parentNode.replaceChild(inputSelecteur, element);
			} catch (error) {}
		} else if (type === "image") {
			const input = document.createElement("input");
			// const contenu = dataVersion[champs];
			input.type = "file";
			input.className = "version-edit-input";
			input.id = element.id;
			// input.src = contenu;
			element.parentNode.replaceChild(input, element);
		} else {
			const input = document.createElement("input");
			// const contenu = dataVersion[champs];
			input.type = "text";
			input.className = "version-edit-input";
			input.id = element.id;
			// input.value = contenu;
			element.parentNode.replaceChild(input, element);
		}
	}

	const btnConfirmEdit = document.createElement("div");
	btnConfirmEdit.classList.add("btn", "btn-danger");
	btnConfirmEdit.id = "confirm-edit-version";
	btnConfirmEdit.textContent = "Confirmer";

	const btnCancelEdit = document.createElement("div");
	btnCancelEdit.classList.add("btn", "btn-primary");
	btnCancelEdit.id = "cancel-edit-version";
	btnCancelEdit.textContent = "Annuler";

	ligneCommandes.appendChild(btnConfirmEdit);
	ligneCommandes.appendChild(btnCancelEdit);
	editVersionBtn.classList.add("hidden");
}

/**
 * Permet de désactiver le formulaire d'édition de la version
 *
 * @param {Object} ligneCommandes Un objet contenant les éléments du formulaire et le type de transformation.
 * @returns {Object} Un objet contenant les éléments du formulaire et le type de transformation.
 */
function desactiveEditVersionForm(ligneCommandes) {
	let btnConfirmEdit = document.getElementById("confirm-edit-version");
	let btnCancelEdit = document.getElementById("cancel-edit-version");
	const elements = elementsFormulaire();

	for (let champs in elements) {
		const element = elements[champs]["element"];
		let nouvelElement;

		if (champs === "image") {
			nouvelElement = document.createElement("img");
			nouvelElement.className = "img-detail";
		} else {
			nouvelElement = document.createElement("div");
			nouvelElement.className = "version-edit-input";
		}

		nouvelElement.id = element.id;
		element.parentNode.replaceChild(nouvelElement, element);
	}

	ligneCommandes.removeChild(btnConfirmEdit);
	ligneCommandes.removeChild(btnCancelEdit);
	editVersionBtn.classList.remove("hidden");
}

/**
 * Permet de modifier la version et de mettre à jour les champs après confirmation de la modification dans la base de données
 *
 * @param {number} idVersion L'identifiant de la version à modifier.
 * @param {Object} donneesFormulaire Les données du formulaire.
 * @returns {Promise<void>} Une promesse qui se résout lorsque la modification est terminée.
 */
async function editerVersion(idVersion, donneesFormulaire) {
	const imageInput = document.getElementById("version-edit-image");

	const formData = new FormData();
	formData.append("versionId", idVersion);
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
		let success = await editVersion(formData);
		if (success) {
			(async function (ligneCommandes) {
				try {
					await desactiveEditVersionForm(ligneCommandes);
					afficheDetailVersion(idVersion);
				} catch (error) {
					console.error("Une erreur est survenue:", error);
				}
			})(ligneCommandes);
		} else {
			alert("La modification de la version a échoué.");
		}
	} catch (error) {
		alert("Erreur lors de la modification de la version.");
	}
}

// FONCTIONS CHANSONS -------------------------------------------------------------------------------------------------------------------------------------------------------------

/**
 * Affiche les chansons d'une version dans le DOM de façon asynchrone.
 *
 * @param {number} idVersion - L'identifiant de la version pour laquelle les chansons doivent être affichées.
 * @returns {void}
 */
async function afficherChansons(idVersion) {
	listeChansons.innerHTML = "";

	try {
		const chansons = await getChansons(idVersion); // Appel de la fonction de récupération des chansons

		if (chansons) {
			chansons.forEach((chanson) => {
				const { cha_id: id, cha_titre: titre, cha_duree: duree, cha_track: track } = chanson;
				const colonneTable = { track: track, titre: titre, duree: duree };

				const ligne = creationLigneChanson(id, colonneTable);

				// Ajout de la ligne au tableau
				listeChansons.appendChild(ligne);
			});
		} else {
			alert("L'affichage des chansons a échoué.");
		}
	} catch (error) {
		alert("Erreur lors de l'affichage des chansons");
	}
}

/**
 * Supprime une chanson de la base de données de façon asynchrone.
 *
 * @param {Object} modale - L'objet modale contenant les informations de la chanson à supprimer.
 * @returns {void}
 */
async function supprimerChanson(modale) {
	let idDeleteChanson = document.getElementById("modale-id").textContent;
	let bodyContent = {
		idChanson: idDeleteChanson,
	};

	try {
		let suppression = await deleteChanson(bodyContent);
		if (suppression) {
			let ligne = document.getElementById(idDeleteChanson);
			ligne.remove();
			modale.remove();
		} else {
			alert("La suppression de la chanson a échoué.");
		}
	} catch (error) {
		alert("Erreur lors de la suppression de la chanson.");
	}

	return;
}

/**
 * Ajoute une ou plusieurs chansons à la version spécifiée de façon asynchrone.
 *
 * @param {number} idVersion - L'ID de la version à laquelle ajouter les chansons.
 * @param {HTMLElement[]} addedChansons - Un tableau d'éléments HTML représentant les nouvelles chansons à ajouter.
 * @param {HTMLElement} modale - L'élément modal contenant les nouvelles chansons à ajouter.
 * @returns {void}
 */
async function ajoutChanson(idVersion, addedChansons, modale) {
	for (let i = 0; i < addedChansons.length; i++) {
		let nouvelleChanson = addedChansons.item(i);
		let trackNouvelleChanson = nouvelleChanson.firstChild.firstChild.value;
		let titreNouvelleChanson = nouvelleChanson.children[1].firstChild.value;
		if (trackNouvelleChanson == "" && titreNouvelleChanson == "") {
			continue;
		}
		let dureeNouvelleChanson = nouvelleChanson.children[2].firstChild.value;
		let colonneTable = {
			track: trackNouvelleChanson,
			titre: titreNouvelleChanson,
			duree: dureeNouvelleChanson,
		};

		let bodyContent = {
			idVersion: idVersion,
			titre: titreNouvelleChanson,
			duree: dureeNouvelleChanson,
			track: trackNouvelleChanson,
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

/**
 * Edite une chanson de la base de données de façon asynchrone.
 *
 * @param {number} idVersion - L'ID de la version pour laquelle la chanson doit être éditée.
 * @param {HTMLElement} modale - L'élément modal contenant les informations de la chanson à éditer.
 * @returns {void}
 */
async function editerChanson(idVersion, modale) {
	let idEditChanson = document.getElementById("modale-id-chanson").textContent;
	let trackEditChanson = document.getElementById("track0").value;
	let titreEditChanson = document.getElementById("titre0").value;
	let dureeEditChanson = document.getElementById("duree0").value;
	let bodyContent = {
		idVersion: idVersion,
		idChanson: idEditChanson,
		titre: titreEditChanson,
		duree: dureeEditChanson,
		track: trackEditChanson,
	};

	try {
		let success = await editChanson(bodyContent);
		if (success) {
			let ligne = document.getElementById(idEditChanson);
			ligne.getElementsByClassName("track")[0].textContent = trackEditChanson;
			ligne.getElementsByClassName("titre")[0].textContent = titreEditChanson;
			ligne.getElementsByClassName("duree")[0].textContent = dureeEditChanson;
			modale.remove();
		} else {
			alert("La modification de la chanson a échoué.");
		}
	} catch (error) {
		alert("Erreur lors de la modification de la chanson.");
	}
}

/**
 * Crée une ligne de tableau pour une chanson.
 *
 * @param {number} id - L'identifiant de la chanson.
 * @param {Object} colonneTable - Un objet contenant les colonnes de la ligne.
 * @returns {HTMLElement} - La ligne de tableau créée.
 */
function creationLigneChanson(id, colonneTable) {
	// Création de la ligne
	let ligne = document.createElement("tr");
	ligne.id = id;
	ligne.className = "chanson";

	// Ajout du numéro de piste
	let ligneTrack = document.createElement("th");
	ligneTrack.className = "track";
	ligneTrack.setAttribute("scope", "row");
	ligneTrack.innerHTML = colonneTable["track"];
	ligne.appendChild(ligneTrack);

	// Ajout des colonnes titre et durée
	for (let cle in colonneTable) {
		if (cle != "track") {
			let cellule = document.createElement("td");
			cellule.className = cle;
			cellule.textContent = colonneTable[cle];
			ligne.appendChild(cellule);
		}
	}

	// Ajout des boutons d'action
	let boutonEditChanson = document.createElement("td");
	boutonEditChanson.className = "btn-td";
	boutonEditChanson.innerHTML = '<div class="btn btn-primary edit-chanson-btn">Editer la chanson</div>';
	ligne.appendChild(boutonEditChanson);

	let boutonDeleteChanson = document.createElement("td");
	boutonDeleteChanson.className = "btn-td";
	boutonDeleteChanson.innerHTML = '<div class="btn btn-danger delete-chanson-btn">Supprimer la chanson</div>';
	ligne.appendChild(boutonDeleteChanson);

	return ligne;
}
