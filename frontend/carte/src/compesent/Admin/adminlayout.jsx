
import React, { useEffect, useState } from "react";
import { NavLink, Outlet, Navigate } from "react-router-dom";
import { checkAdminAuth } from "../../services/api";
import "./AdminLayout.css";

function AdminLayout() {

  const [loading, setLoading] = useState(true);
  const [isAuthenticated, setIsAuthenticated] = useState(false);
  const [admin, setAdmin] = useState(null);

  useEffect(() => {

    const verifySession = async () => {
      try {
        const adminData = await checkAdminAuth();

        setAdmin(adminData);
        setIsAuthenticated(true);

      } catch (error) {
        console.error("Session verification:", error);
        setIsAuthenticated(false);

      } finally {
        setLoading(false);
      }
    };

    verifySession();

  }, []);

  // Attendre la verification
  if (loading) {
    return <div className="admin-loading">Vérification...</div>;
  }

  // Redirection si non connecte
  if (!isAuthenticated) {
    return <Navigate to="/admin/login" replace />;
  }

  return (
    <div className="admin-layout">

      {/* Sidebar */}
      <aside className="admin-sidebar">

        <div className="admin-logo">
          NFC<span>.</span>
          <small>ADMIN PANEL</small>
        </div>

        <nav className="admin-nav">

          <NavLink to="/admin/dashboard" className="admin-link">
            <span>▦</span>
            Dashboard
          </NavLink>

          <NavLink to="/admin/clients" className="admin-link">
            <span>♙</span>
            Clients
          </NavLink>

        </nav>

        <div className="sidebar-footer">
          <p>Admin Panel</p>
          <span>Version 1.0</span>
        </div>

      </aside>

      {/* Main Content */}
      <main className="admin-main">

        <header className="admin-navbar">
          <h2>Administration</h2>

          <div className="admin-profile">
            <span className="admin-avatar">A</span>
            <span>{admin?.username || "Administrator"}</span>
          </div>
        </header>

        <section className="admin-content">
          <Outlet />
        </section>

      </main>

    </div>
  );
}

export default AdminLayout;
