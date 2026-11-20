
import React from "react";
import "./Dashboard.css";

function Dashboard() {
  const stats = [
    {
      title: "Total Clients",
      value: "120",
      icon: "👥",
      description: "Clients enregistrés",
      color: "blue",
    },
    {
      title: "Clients Actifs",
      value: "95",
      icon: "🟢",
      description: "Clients actifs",
      color: "green",
    },
    {
      title: "Cartes Créées",
      value: "120",
      icon: "💳",
      description: "Cartes digitales",
      color: "purple",
    },
    {
      title: "Nouveaux Clients",
      value: "12",
      icon: "📈",
      description: "Derniers ajouts",
      color: "orange",
    },
  ];

  return (
    <div className="dashboard">

      <div className="dashboard-header">
        <div>
          <h1>Dashboard Overview</h1>
          <p>Bienvenue dans votre espace administration.</p>
        </div>

        <span className="dashboard-date">
          Administration
        </span>
      </div>

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
