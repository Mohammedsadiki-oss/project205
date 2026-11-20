
const API_BASE_URL =
  "http://localhost/nfc_project/backend/API/clients";

const ADMIN_API_URL =
  "http://localhost/nfc_project/backend/API/admin";

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

// Déconnexion Admin
export const logoutAdmin = async () => {
  const response = await fetch(
    `${ADMIN_API_URL}/logout.php`,
    {
      method: "POST",
      credentials: "include",
      headers: {
        "Content-Type": "application/json",
      },
    }
  );

  const result = await response.json();

  if (!response.ok || !result.success) {
    throw new Error(result.message || "Erreur lors de la déconnexion");
  }

  return result;
};

export const checkAdminAuth = async () => {
  const response = await fetch(
    "http://localhost/nfc_project/backend/API/admin/check_autho.php",
    {
      method: "GET",
      credentials: "include",
    }
  );

  const result = await response.json();

  if (!response.ok || !result.success) {
    throw new Error(result.message || "Session invalide");
  }

  return result.admin;
};

export const getDashboardStats = async () => {
  const response = await fetch(
    "http://localhost/nfc_project/backend/API/admin/dashboard_stats.php",
    {
      method: "GET",
      credentials: "include",
    }
  );

  const result = await response.json();

  if (!response.ok || !result.success) {
    throw new Error(result.message || "Erreur lors du chargement des statistiques");
  }

  return result.data;
};


