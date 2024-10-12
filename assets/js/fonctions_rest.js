// SELECT---------------------------------------------------------------------------------------------------------------------------------------

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

// CHANSONS---------------------------------------------------------------------------------------------------------------------------------------

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

// VERSION---------------------------------------------------------------------------------------------------------------------------------------
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

// export async function deleteVersion(bodyContent) {
//     let url = "../../includes/router.php";
// }

// export async function addVersion(bodyContent) {
//     let url = "../../includes/router.php";
// }

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
		console.error("Erreur lors de la modification de la chanson :", error);
		throw error;
	}
}

// LABEL---------------------------------------------------------------------------------------------------------------------------------------
// export async function getLabels() {
//     let url = "../../includes/router.php?action=getLabels";

//     try {
//         const response = await fetch(url, { method: 'GET' });
//         const data = await response.json();
//         return data;
//     } catch (error) {
//         console.error("Erreur lors de la récupération des labels :", error);
//         throw error;
//     }
// }

// ALBUM---------------------------------------------------------------------------------------------------------------------------------------
// export async function getAlbums() {
//     let url = "../../includes/router.php?action=getAlbums";
// }

// export async function deleteAlbum(bodyContent) {
//     let url = "../../includes/router.php";
// }

// export async function addAlbum(bodyContent) {
//     let url = "../../includes/router.php";
// }

// export async function editAlbum(bodyContent) {
//     let url = "../../includes/router.php";
// }

// ARTISTE---------------------------------------------------------------------------------------------------------------------------------------

// export async function getArtistes() {
//     let url = "../../includes/router.php?action=getArtistes";
// }

// export async function deleteArtiste(bodyContent) {
//     let url = "../../includes/router.php";
// }

// export async function addArtiste(bodyContent) {
//     let url = "../../includes/router.php";
// }

// export async function editArtiste(bodyContent) {
//     let url = "../../includes/router.php";
// }
