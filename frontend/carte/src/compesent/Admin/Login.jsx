
import React, { useState } from "react";
import { useNavigate } from "react-router-dom";
import "./Login.css";

function Login() {
  const navigate = useNavigate();

  const [username, setUsername] = useState("");
  const [password, setPassword] = useState("");
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState("");

  const handleLogin = async (e) => {
    e.preventDefault();

    setLoading(true);
    setError("");

    try {
      const response = await fetch(
        "http://localhost/nfc_project/backend/API/admin/login.php",
        {
          method: "POST",
          credentials: "include",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify({
            username,
            password,
          }),
        }
      );

      const result = await response.json();

      if (!response.ok || !result.success) {
        throw new Error(result.message || "Erreur de connexion");
      }

      navigate("/admin/dashboard");

    } catch (err) {
      setError(err.message || "Impossible de contacter le serveur");
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="login-page">
      <div className="login-card">

        <div className="login-logo">
          NFC<span>.</span>
          <small>ADMIN PANEL</small>
        </div>

        <h1>Bienvenue !</h1>
        <p className="login-subtitle">
          Connectez-vous à votre espace administrateur.
        </p>

        <form onSubmit={handleLogin}>

          <div className="login-field">
            <label>Username</label>
            <input
              type="text"
              placeholder="Votre username"
              value={username}
              onChange={(e) => setUsername(e.target.value)}
              required
            />
          </div>

          <div className="login-field">
            <label>Password</label>
            <input
              type="password"
              placeholder="Votre password"
              value={password}
              onChange={(e) => setPassword(e.target.value)}
              required
            />
          </div>

          {error && (
            <div className="login-error">
              {error}
            </div>
          )}

          <button
            type="submit"
            disabled={loading}
            className="login-button"
          >
            {loading ? "Connexion..." : "Se connecter"}
          </button>

        </form>

        <p className="login-footer">
          NFC Digital Business Card © 2026
        </p>

      </div>
    </div>
  );
}

export default Login;
