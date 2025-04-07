const express = require('express');
const cors = require('cors');
const app = express();

// Middleware
app.use(cors());
app.use(express.json());

// Ruter
app.get('/', (req, res) => {
  res.json({ message: 'Velkommen til Carrey API' });
});

// SEO Audit API
app.get('/api/seo-audit', (req, res) => {
  const { domain } = req.query;
  if (!domain) {
    return res.status(400).json({ error: 'Domain er påkrevd' });
  }

  // Simulert SEO-audit resultat
  const result = {
    domain,
    score: Math.floor(Math.random() * 100),
    issues: [
      'Manglende meta-beskrivelse',
      'For få interne lenker',
      'Bilder mangler alt-tekst',
      'Siden er for treg'
    ]
  };

  res.json(result);
});

// Clara AI Assistent API
app.get('/api/clara-response', (req, res) => {
  const { q } = req.query;
  if (!q) {
    return res.status(400).json({ error: 'Spørsmål er påkrevd' });
  }

  // Simulert svar fra Clara
  const response = {
    response: `Jeg har analysert spørsmålet ditt om "${q}". Her er mine anbefalinger for å forbedre SEO-en på nettstedet ditt...`
  };

  res.json(response);
});

// Bruker-ruter
app.get('/api/users', (req, res) => {
  res.json({ message: 'Liste over brukere' });
});

app.post('/api/users', (req, res) => {
  res.json({ message: 'Ny bruker opprettet' });
});

// Oppgave-ruter
app.get('/api/tasks', (req, res) => {
  res.json({ message: 'Liste over oppgaver' });
});

app.post('/api/tasks', (req, res) => {
  res.json({ message: 'Ny oppgave opprettet' });
});

// Feilhåndtering
app.use((err, req, res, next) => {
  console.error(err.stack);
  res.status(500).json({ message: 'Noe gikk galt!' });
});

// Start server
const PORT = process.env.PORT || 3000;
app.listen(PORT, () => {
  console.log(`Server kjører på port ${PORT}`);
}); 