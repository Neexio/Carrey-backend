const express = require('express');
const cors = require('cors');
const app = express();

// Middleware
app.use(cors());
app.use(express.json());

// Logg alle forespørsler
app.use((req, res, next) => {
  console.log(`${new Date().toISOString()} - ${req.method} ${req.url}`);
  next();
});

// Ruter
app.get('/', (req, res) => {
  console.log('Hovedside besøkt');
  res.json({ message: 'Velkommen til Carrey API' });
});

// SEO Audit API - Forenklet versjon
app.get('/api/seo-audit', (req, res) => {
  console.log('SEO Audit forespørsel mottatt');
  try {
    const { domain } = req.query;
    console.log('Mottatt domene:', domain);
    
    if (!domain) {
      console.log('Ingen domene oppgitt');
      return res.status(400).json({ error: 'Domain er påkrevd' });
    }

    // Returner en simpel test-respons
    const result = {
      domain,
      score: 85,
      issues: ['Test issue 1', 'Test issue 2'],
      details: {
        metaDescription: 'Test meta description',
        title: 'Test title',
        h1Count: 1,
        internalLinks: 10
      }
    };

    console.log('Returnerer resultat:', result);
    res.json(result);
  } catch (error) {
    console.error('SEO Audit feil:', error);
    res.status(500).json({ 
      error: 'Kunne ikke analysere nettstedet',
      message: error.message
    });
  }
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
const PORT = process.env.PORT || 10000; // Endret til 10000 for Render
app.listen(PORT, '0.0.0.0', () => {
  console.log(`Server kjører på port ${PORT}`);
  console.log(`API tilgjengelig på: http://localhost:${PORT}`);
  console.log('Trykk Ctrl+C for å avslutte');
}); 