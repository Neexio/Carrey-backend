# Carrey Backend

Backend API for Carrey applikasjonen.

## Funksjonalitet

- Brukerhåndtering
- Oppgavehåndtering
- RESTful API

## API Endepunkter

### Brukere
- `GET /api/users` - Hent alle brukere
- `POST /api/users` - Opprett ny bruker

### Oppgaver
- `GET /api/tasks` - Hent alle oppgaver
- `POST /api/tasks` - Opprett ny oppgave

## Installasjon

1. Klon repositoriet
2. Installer avhengigheter:
   ```bash
   npm install
   ```
3. Start serveren:
   ```bash
   npm run dev
   ```

## Teknologier

- Node.js
- Express.js
- CORS 