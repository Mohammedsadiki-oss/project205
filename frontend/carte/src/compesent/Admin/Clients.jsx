
import React, { useEffect, useState } from "react";
import "./Clients.css";

function Clients() {
  const [clients, setClients] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState("");
  const [search, setSearch] = useState("");

  const fetchClients = async () => {
    try {
      setLoading(true);
      setError("");

      const response = await fetch(
        "http://localhost/nfc_project/backend/API/clients/Get.php"
      );

      if (!response.ok) {
        throw new Error("Erreur lors de la récupération des clients");
      }

      const result = await response.json();

      if (result.success) {
        setClients(result.data);
      } else {
        throw new Error(result.message || "Erreur API");
      }

    } catch (err) {
      setError(err.message);
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    fetchClients();
  }, []);

  const filteredClients = clients.filter((client) => {
    const fullName = `${client.nom || ""} ${client.prenom || ""}`;

    return (
      fullName.toLowerCase().includes(search.toLowerCase()) ||
      (client.email || "").toLowerCase().includes(search.toLowerCase()) ||
      (client.telephone || "").includes(search)
    );
  });

  return (
    <div className="clients-page">

      <div className="clients-header">
        <div>
          <h1>Gestion des Clients</h1>
          <p>Consultez et gérez vos clients NFC.</p>
        </div>

        <button className="add-client-btn">
          + Ajouter un client
        </button>
      </div>

      <div className="clients-stats">
        <div className="clients-stat-card">
          <span>Total Clients</span>
          <h2>{clients.length}</h2>
        </div>

        <div className="clients-stat-card">
          <span>Clients affichés</span>
          <h2>{filteredClients.length}</h2>
        </div>
      </div>

      <div className="clients-table-container">

        <div className="clients-table-header">
          <h2>Liste des Clients</h2>

          <input
            type="text"
            placeholder="Rechercher un client..."
            value={search}
            onChange={(e) => setSearch(e.target.value)}
          />
        </div>

        {loading ? (
          <p className="clients-message">Chargement des clients...</p>
        ) : error ? (
          <div className="clients-message error">
            {error}
            <button onClick={fetchClients}>Réessayer</button>
          </div>
        ) : filteredClients.length === 0 ? (
          <p className="clients-message">
            Aucun client trouvé.
          </p>
        ) : (
          <div className="table-responsive">
            <table className="clients-table">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Nom complet</th>
                  <th>Téléphone</th>
                  <th>Email</th>
                  <th>Profession</th>
                  <th>Carte NFC</th>
                </tr>
              </thead>

              <tbody>
                {filteredClients.map((client) => (
                  <tr key={client.id}>
                    <td>#{client.id}</td>

                    <td className="client-name">
                      {client.nom} {client.prenom}
                    </td>

                    <td>{client.telephone || "-"}</td>

                    <td>{client.email || "-"}</td>

                    <td>{client.profession || "-"}</td>

                    <td>
                      {client.slug ? (
                        <a
                          href={`/client/${client.slug}`}
                          target="_blank"
                          rel="noreferrer"
                          className="view-card-link"
                        >
                          Voir la carte
                        </a>
                      ) : (
                        "-"
                      )}
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        )}

      </div>
    </div>
  );
}

export default Clients;
