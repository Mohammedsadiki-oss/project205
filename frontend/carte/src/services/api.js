
const API_BASE_URL =
  "http://localhost/nfc_project/backend/API/clients";

// Recuperer un client par son slug
export const getClientBySlug = async (slug) => {
  const url = `${API_BASE_URL}/get_clients_by_slug.php?slug=${encodeURIComponent(slug)}`;

  try {
    const response = await fetch(url);

    const result = await response.json();

    if (!response.ok || !result.success) {
      throw new Error(result.message || "Erreur API");
    }

    return result.data;

  } catch (error) {
    console.error("Erreur API:", error);
    throw error;
  }
};

// Télécharger le contact VCF
export const downloadContact = (slug) => {
  const url = `${API_BASE_URL}/Contact.php?slug=${encodeURIComponent(slug)}`;
  window.location.href = url;
};
