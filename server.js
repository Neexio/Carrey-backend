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