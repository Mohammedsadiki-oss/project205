
import React from "react";

import {
  BrowserRouter,
  Routes,
  Route,
  Navigate
} from "react-router-dom";

import ClientCard from "./compesent/pages/ClientCard";

import AdminLayout from "./compesent/Admin/adminlayout";
import Dashboard from "./compesent/Admin/Dashboard";
import Clients from "./compesent/Admin/Clients";
import Login from "./compesent/Admin/Login";

function App() {
  return (
    <BrowserRouter>
      <Routes>

        {/* Client Card */}
        <Route
          path="/"
          element={<Navigate to="/client/a7a560c0035b82df" replace />}
        />

        <Route
          path="/client/:slug"
          element={<ClientCard />}
        />
        {/* login admin */}

        <Route path="/admin/login" element={<Login />} />

        {/* Admin Dashboard */}
        <Route path="/admin" element={<AdminLayout />}>

          <Route
            index
            element={<Navigate to="dashboard" replace />}
          />

          <Route
            path="dashboard"
            element={<Dashboard />}
          />

          <Route
            path="clients"
            element={<Clients />}
          />

        </Route>

      </Routes>
    </BrowserRouter>
  );
}

export default App;
