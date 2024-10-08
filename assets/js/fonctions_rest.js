
// CHANSONS---------------------------------------------------------------------------------------------------------------------------------------
export async function getChansons(idVersion) {
    let url = "../../includes/router.php?idVersion=" + idVersion;

    try {
        const response = await fetch(url, { method: 'GET' });
        const data = await response.json();
        return data;
    } catch (error) {
        console.error("Erreur lors de la récupération des chansons :", error);
        throw error;
    }
}

export async function deleteChanson(bodyContent) {
    let url = "../../includes/router.php";

    try {
        const response = await fetch(url, {
            method: 'DELETE',
            body: JSON.stringify(bodyContent),
            headers: {
                "Content-type": "application/json"
            }
        });
        return response.ok;  // Renvoie true si la suppression s'est bien passée
    } catch (error) {
        console.error("Erreur lors de la suppression de la chanson :", error);
        throw error;
    }
}

export async function addChanson(bodyContent) {
    let url = "../../includes/router.php";

    try {
        const response = await fetch(url, {
            method: 'POST',
            body: JSON.stringify(bodyContent),
            headers: {
                "Content-type": "application/json"
            }
        });
        const data = await response.json();
        return data.id;
    } catch (error) {
        console.error("Erreur lors de l'ajout de la chanson :", error);
        throw error;
    }
}

export async function editChanson(bodyContent) {
    let url = "../../includes/router.php";

    try {
        const response = await fetch(url, {
            method: 'PUT',
            body: JSON.stringify(bodyContent),
            headers: {
                "Content-type": "application/json"
            }
        });
        return response.ok;  // Renvoie true si la modification s'est bien passée
    } catch (error) {
        console.error("Erreur lors de la modification de la chanson :", error);
        throw error;
    }
}