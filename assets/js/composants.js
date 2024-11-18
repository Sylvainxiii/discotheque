import { getSelect } from "./fonctions_rest.js";

// MODALES -------------------------------------------------------------------------------------------------------------------------------------------------------------------
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

/**
 * Génération de la modale de création de nouvelle version
 *
 * @returns {void}
 */
export async function modaleVersion() {
	// Création du conteneur principal de la modale
	const modale = document.createElement("div");
	modale.classList.add("modale");
	modale.id = "modale-version";

	// Bouton de fermeture de la modale
	const closeBtn = document.createElement("div");
	closeBtn.classList.add("close-btn");
	closeBtn.id = "close-modale";
	closeBtn.textContent = "X";
	modale.appendChild(closeBtn);
	closeBtn.addEventListener("click", () => {
		document.body.removeChild(modale);
	});

	// Bouton Action
	const btnAction = document.createElement("div");
	btnAction.classList.add("btn", "btn-primary");
	btnAction.textContent = "Créer";
	btnAction.id = "valide-nouvelle-version";

	// Création du titre de la modale
	const titreModale = document.createElement("h1");
	titreModale.classList.add("titre-modale");
	titreModale.textContent = "Création d'un Album";
	modale.appendChild(titreModale);

	// Création du conteneur pour les inputs
	const formContainer = document.createElement("div");
	formContainer.classList.add("form-container");

	// Configuration des champs
	const champs = [
		{
			nom: "version-add-titre",
			label: "Titre de  l'album:",
			type: "select",
			table: "d_album_alb",
		},
		{
			nom: "version-add-reference",
			label: "Référence de la version:",
			type: "text",
		},
		{
			nom: "version-add-format",
			label: "Format:",
			type: "select",
			table: "d_format_for",
		},
		{
			nom: "version-add-label",
			label: "Label:",
			type: "select",
			table: "d_label_lab",
		},
		{
			nom: "version-add-pressage-annee",
			label: "Sortie:",
			type: "text",
		},
		{
			nom: "version-add-pays",
			label: "Pays:",
			type: "text",
		},
		{
			nom: "version-add-type",
			label: "Edition:",
			type: "select",
			table: "d_edition_edi",
		},
		{
			nom: "version-add-image",
			label: "Image:",
			type: "file",
		},
	];

	// Création des champs
	// Création des promesses pour chaque champ cela permet de les créer dans l'ordre voulu
	const champPromises = champs.map(async (champ) => {
		const champContainer = document.createElement("div");
		champContainer.classList.add("champ-container");

		// Création du label
		const label = document.createElement("label");
		label.classList.add("form-label");
		label.setAttribute("for", champ.nom);
		label.textContent = champ.label;
		champContainer.appendChild(label);

		// Création de l'input ou select selon le type
		if (champ.type === "select") {
			const select = await selecteur(champ.table, champ.nom);
			champContainer.appendChild(select);
			if (champ.nom === "version-add-titre") {
				const newAlbumBtn = document.createElement("div");
				newAlbumBtn.classList.add("btn", "btn-primary");
				newAlbumBtn.textContent = "Titre introuvable?";
				champContainer.appendChild(newAlbumBtn);
				newAlbumBtn.addEventListener("click", () => {
					modaleCreationTitre();
				});
			} else if (champ.nom === "version-add-label") {
				const newLabelBtn = document.createElement("div");
				newLabelBtn.classList.add("btn", "btn-primary");
				newLabelBtn.textContent = "Label introuvable?";
				champContainer.appendChild(newLabelBtn);
				newLabelBtn.addEventListener("click", () => {
					modaleCreationLabel();
				});
			}
		} else {
			const input = document.createElement("input");
			input.classList.add("form-control");
			input.type = champ.type;
			input.id = champ.nom;
			if (champ.nom === "versionRef") {
				input.required = true;
			}
			champContainer.appendChild(input);
		}

		return champContainer;
	});

	// Attendre que tous les champs soient créés
	const champElements = await Promise.all(champPromises);

	// Ajouter les champs dans l'ordre
	champElements.forEach((champContainer) => {
		formContainer.appendChild(champContainer);
	});

	// Ajout du conteneur de formulaire à la modale
	modale.appendChild(formContainer);

	// Ajout des éléments au conteneur principal de la modale
	modale.appendChild(btnAction);

	// Ajout de la modale au body
	document.body.appendChild(modale);
}

/**
 * Génération de la modale de création de nouveau titre d'album
 *
 * @returns {void}
 */
export async function modaleCreationTitre() {
	// Création du conteneur principal de la modale
	const modaleVersion = document.getElementById("modale-version");
	const modale = document.createElement("div");
	modale.classList.add("modale");
	modale.id = "modale-creation-titre";

	// Bouton de fermeture de la modale
	const closeBtn = document.createElement("div");
	closeBtn.classList.add("close-btn");
	closeBtn.id = "close-modale";
	closeBtn.textContent = "X";
	modale.appendChild(closeBtn);
	closeBtn.addEventListener("click", () => {
		document.body.removeChild(modale);
		modaleVersion.style.display = "block";
	});

	// Bouton Action
	const btnAction = document.createElement("div");
	btnAction.classList.add("btn", "btn-primary");
	btnAction.textContent = "Créer";
	btnAction.id = "valide-nouveau-titre";

	// Création du titre de la modale
	const titreModale = document.createElement("h1");
	titreModale.classList.add("titre-modale");
	titreModale.textContent = "Création d'un Titre";
	modale.appendChild(titreModale);

	// Création du conteneur pour les inputs
	const formContainer = document.createElement("div");
	formContainer.classList.add("form-container");

	// Configuration des champs
	const champs = [
		{
			nom: "titre-add-nom",
			label: "Titre de l'album:",
			type: "text",
		},
		{
			nom: "titre-add-artiste",
			label: "Artiste:",
			type: "select",
			table: "d_artiste_art",
		},
		{
			nom: "titre-add-annee",
			label: "Année:",
			type: "text",
		},
		{
			nom: "titre-add-genre",
			label: "Genre:",
			type: "select",
			table: "d_genre_gen",
		},
	];

	// Création des champs
	const champPromises = champs.map(async (champ) => {
		const champContainer = document.createElement("div");
		champContainer.classList.add("champ-container");

		// Création du label
		const label = document.createElement("label");
		label.classList.add("form-label");
		label.setAttribute("for", champ.nom);
		label.textContent = champ.label;
		champContainer.appendChild(label);

		// Création de l'input ou select selon le type
		if (champ.type === "select") {
			const select = await selecteur(champ.table, champ.nom);
			champContainer.appendChild(select);
			if (champ.nom === "titre-add-artiste") {
				const newArtisteBtn = document.createElement("div");
				newArtisteBtn.classList.add("btn", "btn-primary");
				newArtisteBtn.textContent = "Artiste introuvable?";
				champContainer.appendChild(newArtisteBtn);
				newArtisteBtn.addEventListener("click", () => {
					modaleCreationArtiste();
				});
			}
		} else {
			const input = document.createElement("input");
			input.classList.add("form-control");
			input.type = champ.type;
			input.id = champ.nom;
			champContainer.appendChild(input);
		}

		return champContainer;
	});

	// Attendre que tous les champs soient créés
	const champElements = await Promise.all(champPromises);

	// Ajouter les champs dans l'ordre
	champElements.forEach((champContainer) => {
		formContainer.appendChild(champContainer);
	});

	// Ajout du conteneur de formulaire à la modale
	modale.appendChild(formContainer);

	// Ajout des éléments au conteneur principal de la modale
	modale.appendChild(btnAction);

	// Ajout de la modale au body
	modaleVersion.style.display = "none";
	document.body.appendChild(modale);
}

/**
 * Génération de la modale de création de nouvel artiste
 *
 * @returns {void}
 */
export function modaleCreationArtiste() {
	// Création du conteneur principal de la modale
	const modaleTitre = document.getElementById("modale-creation-titre");
	const modale = document.createElement("div");
	modale.classList.add("modale");
	modale.id = "modale-creation-artiste";

	// Bouton de fermeture de la modale
	const closeBtn = document.createElement("div");
	closeBtn.classList.add("close-btn");
	closeBtn.id = "close-modale";
	closeBtn.textContent = "X";
	modale.appendChild(closeBtn);
	closeBtn.addEventListener("click", () => {
		document.body.removeChild(modale);
		modaleTitre.style.display = "block";
	});

	// Bouton Action
	const btnAction = document.createElement("div");
	btnAction.classList.add("btn", "btn-primary");
	btnAction.textContent = "Créer";
	btnAction.id = "valide-nouvel-artiste";

	// Création du titre de la modale
	const titreModale = document.createElement("h1");
	titreModale.classList.add("titre-modale");
	titreModale.textContent = "Création d'un Artiste";
	modale.appendChild(titreModale);

	// Création du conteneur pour les inputs
	const formContainer = document.createElement("div");
	formContainer.classList.add("form-container");

	// Configuration des champs
	const champs = [
		{
			nom: "artiste-add-nom",
			label: "Nom de l'artiste:",
			type: "text",
		},
		{
			nom: "artiste-add-pays",
			label: "Pays:",
			type: "text",
		},
	];

	// Création des champs
	champs.forEach((champ) => {
		const champContainer = document.createElement("div");
		champContainer.classList.add("champ-container");

		// Création du label
		const label = document.createElement("label");
		label.classList.add("form-label");
		label.setAttribute("for", champ.nom);
		label.textContent = champ.label;
		champContainer.appendChild(label);

		// Création de l'input
		const input = document.createElement("input");
		input.classList.add("form-control");
		input.type = champ.type;
		input.id = champ.nom;
		champContainer.appendChild(input);

		// Ajout du champ au conteneur de formulaire
		formContainer.appendChild(champContainer);
	});

	// Ajout du conteneur de formulaire à la modale
	modale.appendChild(formContainer);

	// Ajout des éléments au conteneur principal de la modale
	modale.appendChild(btnAction);

	// Ajout de la modale au body
	modaleTitre.style.display = "none";
	document.body.appendChild(modale);
}

/**
 * Génération de la modale de création de nouveau label
 *
 * @returns {void}
 */
export function modaleCreationLabel() {
	// Création du conteneur principal de la modale
	const modaleVersion = document.getElementById("modale-version");
	const modale = document.createElement("div");
	modale.classList.add("modale");
	modale.id = "modale-creation-label";

	// Bouton de fermeture de la modale
	const closeBtn = document.createElement("div");
	closeBtn.classList.add("close-btn");
	closeBtn.id = "close-modale";
	closeBtn.textContent = "X";
	modale.appendChild(closeBtn);
	closeBtn.addEventListener("click", () => {
		document.body.removeChild(modale);
		modaleVersion.style.display = "block";
	});

	// Bouton Action
	const btnAction = document.createElement("div");
	btnAction.classList.add("btn", "btn-primary");
	btnAction.textContent = "Créer";
	btnAction.id = "valide-nouveau-label";

	// Création du titre de la modale
	const titreModale = document.createElement("h1");
	titreModale.classList.add("titre-modale");
	titreModale.textContent = "Création d'un Label";
	modale.appendChild(titreModale);

	// Création du conteneur pour les inputs
	const formContainer = document.createElement("div");
	formContainer.classList.add("form-container");

	// Configuration des champs
	const champs = [
		{
			nom: "label-add-nom",
			label: "Nom du label:",
			type: "text",
		},
	];

	// Création des champs
	champs.forEach((champ) => {
		const champContainer = document.createElement("div");
		champContainer.classList.add("champ-container");

		// Création du label
		const label = document.createElement("label");
		label.classList.add("form-label");
		label.setAttribute("for", champ.nom);
		label.textContent = champ.label;
		champContainer.appendChild(label);

		// Création de l'input
		const input = document.createElement("input");
		input.classList.add("form-control");
		input.type = champ.type;
		input.id = champ.nom;
		champContainer.appendChild(input);

		// Ajout du champ au conteneur de formulaire
		formContainer.appendChild(champContainer);
	});

	// Ajout du conteneur de formulaire à la modale
	modale.appendChild(formContainer);

	// Ajout des éléments au conteneur principal de la modale
	modale.appendChild(btnAction);

	// Ajout de la modale au body
	modaleVersion.style.display = "none";
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
