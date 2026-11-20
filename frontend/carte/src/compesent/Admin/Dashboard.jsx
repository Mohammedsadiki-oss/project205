
import React, { useEffect, useState } from "react";
import "./Dashboard.css";

import {
  logoutAdmin,
  getDashboardStats
} from "../../services/api";

function Dashboard() {

  const [statsData, setStatsData] = useState({
    total_clients: 0,
    total_cards: 0,
    new_clients: 0
  });

  const [loading, setLoading] = useState(true);
  const [error, setError] = useState("");

  // Recuperer les statistiques depuis MySQL
  useEffect(() => {
    const fetchStats = async () => {
      try {
        const data = await getDashboardStats();
        setStatsData(data);
      } catch (err) {
        console.error("Stats error:", err);
        setError(err.message);
      } finally {
        setLoading(false);
      }
    };

    fetchStats();
  }, []);

  // Deconnexion Admin
  const handleLogout = async () => {
    try {
      await logoutAdmin();
      window.location.href = "/admin/login";
    } catch (error) {
      console.error("Logout error:", error);
      alert(error.message || "Erreur lors de la déconnexion");
    }
  };

  const stats = [
    {
      title: "Total Clients",
      value: statsData.total_clients,
      icon: "👥",
      description: "Clients enregistrés",
      color: "blue",
    },
    {
      title: "Cartes Créées",
      value: statsData.total_cards,
      icon: "💳",
      description: "Cartes digitales",
      color: "purple",
    },
    {
      title: "Nouveaux Clients",
      value: statsData.new_clients,
      icon: "📈",
      description: "Derniers 30 jours",
      color: "orange",
    },
  ];

  return (
    <div className="dashboard">

      {/* Header */}
      <div className="dashboard-header">
        <div>
          <h1>Dashboard Overview</h1>
          <p>Bienvenue dans votre espace administration.</p>
        </div>

        <div className="dashboard-actions">
          <span className="dashboard-date">
            Administration
          </span>

          <button
            type="button"
            className="logout-button"
            onClick={handleLogout}
          >
            🚪 Logout
          </button>
        </div>
      </div>

      {/* Loading */}
      {loading && (
        <p>Chargement des statistiques...</p>
      )}

      {/* Error */}
      {!loading && error && (
        <p className="stats-error">{error}</p>
      )}

      {/* Statistics */}
      {!loading && !error && (
        <div className="stats-grid">
          {stats.map((stat, index) => (
            <div className="stat-card" key={index}>
              <div className="stat-top">
                <div>
                  <p className="stat-title">{stat.title}</p>
                  <h2>{stat.value}</h2>
                </div>

                <div className={`stat-icon ${stat.color}`}>
                  {stat.icon}
                </div>
              </div>

              <p className="stat-description">
                {stat.description}
              </p>
            </div>
          ))}
        </div>
      )}

      {/* Welcome */}
      <div className="dashboard-bottom">
        <div className="welcome-card">
          <h2>Bienvenue sur NFC Admin</h2>
          <p>
            Gérez facilement vos clients et leurs cartes
            de visite digitales depuis votre espace
            d'administration.
          </p>
        </div>
      </div>

    </div>
  );
}

export default Dashboard;
