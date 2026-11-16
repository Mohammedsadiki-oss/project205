
import React from "react";
import { BrowserRouter, Routes, Route, Navigate } from "react-router-dom";

import ClientCard from "./compesent/pages/ClientCard";

function App() {
  return (
    <BrowserRouter>
      <Routes>

        {/* Redirection vers la carte NFC */}
        <Route
          path="/"
          element={
            <Navigate
              to="/client/a7a560c0035b82df"
              replace
            />
          }
        />

        {/* Route dynamique de chaque client */}
        <Route
          path="/client/:slug"
          element={<ClientCard />}
        />

      </Routes>
    </BrowserRouter>
  );
}

export default App;
