import { getSelect } from "./fonctions_rest.js";

// MODALE CHANSON -------------------------------------------------------------------------------------------------------------------------------------------------------------------
/**
 * Création de la modale pour ajout de chanson
 *
 * @param {number} nChanson - Le nombre de chansons de la version.
 * @param {string} idChanson - L'ID de la chanson à modifier.
 * @param {string} trackEditChanson - Le track de la chanson à modifier.
 * @param {string} titreEditChanson - Le titre de la chanson à modifier.
 * @param {string} dureeEditChanson - La durée de la chanson à modifier.
 * @param {string} mode - Le mode de la modale ("add" ou "edit").
 * @returns {void}
 */
export function modaleChanson(nChanson = 1, idChanson = "", trackEditChanson = "", titreEditChanson = "", dureeEditChanson = "", mode = "add") {
	// Création du conteneur principal de la modale
	const modale = document.createElement("div");
	modale.classList.add("modale");
	modale.id = "modale-chanson";

	// Bouton de fermeture de la modale
	const closeBtn = document.createElement("div");
	closeBtn.classList.add("close-btn");
	closeBtn.id = "close-modale";
	closeBtn.textContent = "X";
	modale.appendChild(closeBtn);

	// Bouton Action
	const btnAction = document.createElement("div");
	btnAction.classList.add("btn", "btn-primary");

	const table = document.createElement("table");
	table.classList.add("table");

	const entete = document.createElement("thead");
	const ligneEntete = document.createElement("tr");

	// Création des en-têtes du tableau
	["Track Nr", "Titre de la chanson", "Durée"].forEach((label) => {
		const champEntete = document.createElement("th");
		const labelElement = document.createElement("label");
		labelElement.classList.add("form-label");
		labelElement.textContent = label;
		champEntete.appendChild(labelElement);
		ligneEntete.appendChild(champEntete);
	});

	entete.appendChild(ligneEntete);
	table.appendChild(entete);

	// Corps du tableau (tbody)
	const tbody = document.createElement("tbody");
	tbody.id = "modale-liste-chanson";
	for (let i = 0; i < nChanson; i = i + 1) {
		let ligne = document.createElement("tr");
		ligne.className = "newtrack";
		ligne.id = "newtrack" + i;
		let dataLigne = {
			track: trackEditChanson,
			titre: titreEditChanson,
			duree: dureeEditChanson,
		};
		for (let cle in dataLigne) {
			const tdChamp = document.createElement("td");
			const inputChamp = document.createElement("input");
			inputChamp.type = "text";
			inputChamp.classList.add("form-control", cle);
			inputChamp.id = cle + i;
			if (i == 0) {
				inputChamp.value = dataLigne[cle];
			}

			tdChamp.appendChild(inputChamp);
			ligne.appendChild(tdChamp);
		}

		tbody.appendChild(ligne);
	}

	table.appendChild(tbody);
	modale.appendChild(table);

	if (mode != "edit") {
		btnAction.id = "modale-add-chanson";
		btnAction.textContent = "Créer";
		// Section "Nombre de chansons"
		const nombreChansons = document.createElement("div");
		nombreChansons.id = "nombre-chansons";

		const labelNbrChanson = document.createElement("label");
		labelNbrChanson.classList.add("form-label");
		labelNbrChanson.setAttribute("for", "nChanson");
		labelNbrChanson.textContent = "Nr de Chansons";

		const inputNbrChanson = document.createElement("input");
		inputNbrChanson.type = "number";
		inputNbrChanson.classList.add("form-control", "form-control-color");
		inputNbrChanson.id = "nChanson";
		inputNbrChanson.value = nChanson;

		nombreChansons.appendChild(labelNbrChanson);
		nombreChansons.appendChild(inputNbrChanson);

		modale.appendChild(nombreChansons);
	} else {
		btnAction.id = "modale-edit-chanson";
		btnAction.textContent = "Editer";

		// Input caché pour l'ID de la chanson
		const hiddenInput = document.createElement("input");
		hiddenInput.type = "hidden";
		hiddenInput.id = "modale-id-chanson";
		hiddenInput.textContent = idChanson;
		modale.appendChild(hiddenInput);
	}

	// Ajout des éléments au conteneur principal de la modale
	modale.appendChild(btnAction);

	// Ajout de la modale au body
	document.body.appendChild(modale);
}

// Modale de suppression de chanson
/**
 * Création de la modale de suppression de chanson
 *
 * @param {string} idChanson - L'ID de la chanson à supprimer
 * @returns {void}
 */
export function modaleSuppressionChanson(idChanson = "") {
	// Création du conteneur principal de la modale
	const modale = document.createElement("div");
	modale.classList.add("modale", "modal-flex-column");
	modale.id = "modale-chanson";

	const texteModale = document.createElement("div");
	texteModale.classList.add("texte-modale");
	texteModale.textContent = "Voulez-vous vraiment supprimer cette chanson?";

	const modalFlexRow = document.createElement("div");
	modalFlexRow.classList.add("modal-flex-row");

	const btnConfirmDelete = document.createElement("div");
	btnConfirmDelete.classList.add("btn", "btn-danger");
	btnConfirmDelete.id = "modale-confirm-delete-chanson";
	btnConfirmDelete.textContent = "Confirmer";

	const btnCancelDelete = document.createElement("div");
	btnCancelDelete.classList.add("btn", "btn-primary");
	btnCancelDelete.id = "modale-confirm-cancel-chanson";
	btnCancelDelete.textContent = "Annuler";

	modalFlexRow.appendChild(btnConfirmDelete);
	modalFlexRow.appendChild(btnCancelDelete);

	modale.appendChild(texteModale);
	modale.appendChild(modalFlexRow);

	// Input caché pour l'ID de la chanson
	const hiddenInput = document.createElement("input");
	hiddenInput.type = "hidden";
	hiddenInput.id = "modale-id-chanson";
	hiddenInput.textContent = idChanson;
	modale.appendChild(hiddenInput);

	// Ajout de la modale au body
	document.body.appendChild(modale);
}

// Selecteur dynamique
/**
 * Création d'un selecteur dynamique
 *
 * @param {string} table - Le nom de la table contenant les données
 * @param {string} nom - Le nom de l'input
 * @returns {Promise<Element>} Le selecteur créé
 */
export async function selecteur(table, nom, selectedValue = "") {
	try {
		let listeOptions = await getSelect(table);
		if (listeOptions) {
			let selecteur = document.createElement("select");
			selecteur.classList.add("form-select");
			selecteur.id = nom;
			selecteur.name = nom;
			for (const option in listeOptions) {
				let optionElement = document.createElement("option");
				optionElement.value = option;
				optionElement.textContent = listeOptions[option][1];
				if (listeOptions[option][1] == selectedValue) {
					optionElement.selected = true;
				}
				selecteur.appendChild(optionElement);
			}

			return selecteur;
		}
	} catch (error) {}
}
