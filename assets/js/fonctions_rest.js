// 1-SELECT
// 2-CHANSONS
// 3-VERSION
// 4-LISTE UTILISATEUR
// 5-LABEL
// 6-ALBUM
// 7-ARTISTE

// 1-SELECT---------------------------------------------------------------------------------------------------------------------------------------

/**
 * Permet de récupérer de manière asynchrone les optionss d'un select
 *
 * @param {string} table
 * @returns {Promise<Object>}
 */
export async function getSelect(table) {
	let url = "../../includes/router.php?entite=select&table=" + table;

	try {
		const response = await fetch(url, { method: "GET" });
		const data = await response.json();
		return data;
	} catch (error) {
		console.error("Erreur lors de la récupération des données :", error);
		throw error;
	}
}

// 2-CHANSONS---------------------------------------------------------------------------------------------------------------------------------------

/**
 * Permet de récupérer les données d'une chanson de manière asynchrone
 *
 * @param {string} idVersion
 * @returns {Promise<Object>}
 */
export async function getChansons(idVersion) {
	let url = "../../includes/router.php?entite=chanson&id=" + idVersion;

	try {
		const response = await fetch(url, { method: "GET" });
		const data = await response.json();
		return data;
	} catch (error) {
		console.error("Erreur lors de la récupération des chansons :", error);
		throw error;
	}
}

/**
 * Permet de supprimer de manière asynchrone une chanson
 *
 * @param {Object} bodyContent
 * @returns {Promise<boolean>}
 */
export async function deleteChanson(bodyContent) {
	let url = "../../includes/router.php?entite=chanson";

	try {
		const response = await fetch(url, {
			method: "DELETE",
			body: JSON.stringify(bodyContent),
			headers: {
				"Content-type": "application/json",
			},
		});
		return response.ok; // Renvoie true si la suppression s'est bien passée
	} catch (error) {
		console.error("Erreur lors de la suppression de la chanson :", error);
		throw error;
	}
}

/**
 * Permet d'ajouter de manière asynchrone une ou plusieurs chanson
 *
 * @param {Object} bodyContent
 * @returns {Promise<number>}
 */
export async function addChanson(bodyContent) {
	let url = "../../includes/router.php?entite=chanson";

	try {
		const response = await fetch(url, {
			method: "POST",
			body: JSON.stringify(bodyContent),
			headers: {
				"Content-type": "application/json",
			},
		});
		const data = await response.json();
		return data.id;
	} catch (error) {
		console.error("Erreur lors de l'ajout de la chanson :", error);
		throw error;
	}
}

/**
 * Permet de modifier de manière asynchrone une chanson
 *
 * @param {Object} bodyContent
 * @returns {Promise<boolean>}
 */
export async function editChanson(bodyContent) {
	let url = "../../includes/router.php?entite=chanson";

	try {
		const response = await fetch(url, {
			method: "PUT",
			body: JSON.stringify(bodyContent),
			headers: {
				"Content-type": "application/json",
			},
		});
		return response.ok; // Renvoie true si la modification s'est bien passée
	} catch (error) {
		console.error("Erreur lors de la modification de la chanson :", error);
		throw error;
	}
}

// 3-VERSION---------------------------------------------------------------------------------------------------------------------------------------

/**
 * Permet de récupérer de manière asynchrone les versions cherchées
 *
 * @param {Object} parametres
 * @returns {Promise<Object>}
 */
export async function getVersions(parametres) {
	let parametreGet = "";
	for (let parametre in parametres) {
		parametreGet += "&" + parametre + "=" + parametres[parametre];
	}

	let url = "../../includes/router.php?entite=listeVersion" + parametreGet;

	if (parametreGet) {
		try {
			const response = await fetch(url, { method: "GET" });
			const data = await response.json();
			return data;
		} catch (error) {
			console.error("Erreur lors de la récupération de la version :", error);
			throw error;
		}
	}
}

/**
 * Permet de récupérer de manière asynchrone les données d'une version
 *
 * @param {string} idVersion
 * @returns {Promise<Object>}
 */
export async function getVersion(idVersion) {
	let url = "../../includes/router.php?entite=version&id=" + idVersion;

	try {
		const response = await fetch(url, { method: "GET" });
		const data = await response.json();
		return data;
	} catch (error) {
		console.error("Erreur lors de la récupération de la version :", error);
		throw error;
	}
}

/**
 * Permet d'ajouter de manière asynchrone une version
 *
 * @param {Object} formData
 * @returns {Promise<boolean>}
 */
export async function addVersion(formData) {
	let url = "../../includes/router.php?entite=version";
	try {
		const response = await fetch(url, {
			method: "POST",
			body: formData,
		});
		return response.ok; // Renvoie true si l'ajout' s'est bien passé
	} catch (error) {
		console.error("Erreur lors de l'ajout de la version :", error);
		throw error;
	}
}

/**
 * Permet de modifier de manière asynchrone une version
 *
 * @param {Object} formData
 * @returns {Promise<boolean>}
 */
export async function editVersion(formData) {
	let url = "../../includes/router.php?entite=version";

	try {
		const response = await fetch(url, {
			method: "PUT",
			body: formData,
		});
		return response.ok; // Renvoie true si la modification s'est bien passée
	} catch (error) {
		console.error("Erreur lors de la modification de la cersion :", error);
		throw error;
	}
}

// 4-LISTE UTILISATEUR-------------------------------------------------------------------------------------------------------------------------

/**
 * Permet de récupérer de manière asynchrone les listes cherchées
 *
 * @param {Object} parametres (id de l'utilisateur)
 * @returns {Promise<Object>}
 */
export async function getList(parametres) {
	let parametreGet = "";
	for (let parametre in parametres) {
		parametreGet += "&" + parametre + "=" + parametres[parametre];
	}

	let url = "../../includes/router.php?entite=liste" + parametreGet;

	if (parametreGet) {
		try {
			const response = await fetch(url, { method: "GET" });
			const data = await response.json();
			return data;
		} catch (error) {
			console.error("Erreur lors de la récupération de la version :", error);
			throw error;
		}
	}
}

/**
 * Permet d'éditer de manière asynchrone l'état d'une pochette ou d'un media
 *
 * @param {Object} bodyContent (id de l'album dans la collection, type de l'état, etat)
 * @returns {Promise<boolean>}
 */
export async function editEtat(bodyContent) {
	let url = "../../includes/router.php?entite=liste";

	try {
		const response = await fetch(url, {
			method: "PUT",
			body: JSON.stringify(bodyContent),
			headers: {
				"Content-type": "application/json",
			},
		});
		return response.ok; // Renvoie true si la modification s'est bien passée
	} catch (error) {
		console.error("Erreur lors de la modification de la chanson :", error);
		throw error;
	}
}

/**
 * Permet d'ajouter de manière asynchrone une version à une liste
 *
 * @param {Object} bodyContent - objet contenant l'id de l'utilisateur et l'id de la version
 * @returns {Promise<boolean>}
 */
export async function addVersionToList(bodyContent) {
	let url = "../../includes/router.php?entite=liste";

	try {
		const response = await fetch(url, {
			method: "POST",
			body: JSON.stringify(bodyContent),
			headers: {
				"Content-type": "application/json",
			},
		});
		const data = await response.json();
		return data.ajout;
	} catch (error) {
		console.error("Erreur lors de l'ajout de la version à la collection :", error);
		throw error;
	}
}

export async function deleteToList(bodyContent) {
	let url = "../../includes/router.php?entite=liste";

	try {
		const response = await fetch(url, {
			method: "DELETE",
			body: JSON.stringify(bodyContent),
			headers: {
				"Content-type": "application/json",
			},
		});
		return response.ok; // Renvoie true si la suppression s'est bien passée
	} catch (error) {
		console.error("Erreur lors de la suppression de l'item de la liste :", error);
		throw error;
	}
}

// 5-LABEL---------------------------------------------------------------------------------------------------------------------------------------

/**
 * Permet d'ajouter de manière asynchrone un label
 *
 * @param {Object} bodyContent
 * @returns {Promise<boolean>}
 */
export async function addLabel(bodyContent) {
	let url = "../../includes/router.php?entite=label";

	try {
		const response = await fetch(url, {
			method: "POST",
			body: JSON.stringify(bodyContent),
		});
		return response.ok; // Renvoie true si l'ajout' s'est bien passé
	} catch (error) {
		console.error("Erreur lors de l'ajout du label' :", error);
		throw error;
	}
}

// 6-ALBUM---------------------------------------------------------------------------------------------------------------------------------------

/**
 * Permet d'ajouter de manière asynchrone un album
 *
 * @param {Object} bodyContent
 * @returns {Promise<boolean>}
 */
export async function addAlbum(bodyContent) {
	let url = "../../includes/router.php?entite=album";

	try {
		const response = await fetch(url, {
			method: "POST",
			body: JSON.stringify(bodyContent),
		});
		return response.ok; // Renvoie true si l'ajout' s'est bien passé
	} catch (error) {
		console.error("Erreur lors de l'ajout de l'album' :", error);
		throw error;
	}
}

// 7-ARTISTE---------------------------------------------------------------------------------------------------------------------------------------

/**
 * Permet d'ajouter de manière asynchrone un artiste
 *
 * @param {Object} bodyContent
 * @returns {Promise<boolean>}
 */
export async function addArtiste(bodyContent) {
	let url = "../../includes/router.php?entite=artiste";

	try {
		const response = await fetch(url, {
			method: "POST",
			body: JSON.stringify(bodyContent),
		});
		return response.ok; // Renvoie true si l'ajout' s'est bien passé
	} catch (error) {
		console.error("Erreur lors de l'ajout de l'album' :", error);
		throw error;
	}
}
