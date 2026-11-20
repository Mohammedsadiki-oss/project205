
import React from "react";
import { NavLink, Outlet } from "react-router-dom";
import "./AdminLayout.css";

function AdminLayout() {
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
            <span>Administrator</span>
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
