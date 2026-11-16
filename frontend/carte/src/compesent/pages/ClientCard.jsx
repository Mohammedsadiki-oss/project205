
import React, { useEffect, useState } from "react";
import { useParams } from "react-router-dom";
import "./ClientCard.css";

import {
  getClientBySlug,
  downloadContact
} from "../../services/api";

function ClientCard() {
  const { slug } = useParams();
  console.log("SLUG:", slug);

  const [client, setClient] = useState(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState("");

  // Recuperer le client depuis MySQL
  useEffect(() => {
    const fetchClient = async () => {
      try {
        setLoading(true);
        setError("");

        const data = await getClientBySlug(slug);
        setClient(data);

      } catch (err) {
        setError(err.message);
      } finally {
        setLoading(false);
      }
    };

    if (slug) {
      fetchClient();
    } else {
      setError("Slug introuvable");
      setLoading(false);
    }
  }, [slug]);

  // Télécharger le contact
  const handleSaveContact = () => {
    if (client?.slug) {
      downloadContact(client.slug);
    }
  };

  if (loading) {
    return <main className="card-page">Chargement...</main>;
  }

  if (error || !client) {
    return <main className="card-page">{error || "Client introuvable"}</main>;
  }

  const fullName = `${client.nom || ""} ${client.prenom || ""}`.trim();

  return (
    <main className="card-page">
      <div className="business-card">

        {/* Header */}
        <div className="card-cover">
          <span className="card-logo">NFC.</span>
          <span className="cover-label">DIGITAL IDENTITY</span>
          <div className="cover-decoration"></div>
        </div>

        {/* Profile */}
        <section className="profile-section">

          <div className="profile-image">
            {client.photo ? (
              <img src={client.photo} alt={fullName} />
            ) : (
              <span>
                {(client.nom?.[0] || "") + (client.prenom?.[0] || "")}
              </span>
            )}
          </div>

          <h1>{fullName}</h1>
          <p className="job-title">{client.profession || ""}</p>
          <p className="company">{client.entreprise || ""}</p>

          <div className="profile-line"></div>
        </section>

        {/* Contact */}
        <section className="contact-section">

          {client.telephone && (
            <a href={`tel:${client.telephone}`} className="contact-link">
              <span className="contact-icon">☎</span>
              <span>Call Me</span>
              <span className="link-arrow">↗</span>
            </a>
          )}

          {client.email && (
            <a href={`mailto:${client.email}`} className="contact-link">
              <span className="contact-icon">✉</span>
              <span>Send Email</span>
              <span className="link-arrow">↗</span>
            </a>
          )}

          {client.whatsapp && (
            <a
              href={`https://wa.me/${client.whatsapp.replace(/\D/g, "")}`}
              target="_blank"
              rel="noreferrer"
              className="contact-link"
            >
              <span className="contact-icon">◉</span>
              <span>WhatsApp</span>
              <span className="link-arrow">↗</span>
            </a>
          )}

        </section>

        {/* Social Media */}
        <section className="social-section">
          <p className="section-label">CONNECT WITH ME</p>

          <div className="social-links">

            {client.instagram && (
              <a href={client.instagram} target="_blank" rel="noreferrer">
                Instagram
              </a>
            )}

            {client.linkedin && (
              <a href={client.linkedin} target="_blank" rel="noreferrer">
                LinkedIn
              </a>
            )}

            {client.facebook && (
              <a href={client.facebook} target="_blank" rel="noreferrer">
                Facebook
              </a>
            )}

          </div>
        </section>

        {/* Save Contact */}
        <div className="save-contact">
          <button type="button" onClick={handleSaveContact}>
            <span>＋</span> Save Contact
          </button>
        </div>

        <footer>
          POWERED BY <span>NFC.</span>
        </footer>

      </div>
    </main>
  );
}

export default ClientCard;
