# Carrey.ai Backend

Backend API for søk og analyse av nettsteder på Carrey.ai.

## Funksjonalitet

- Søk etter nettsteder
- SEO-analyse
- Brukerautentisering
- Caching av resultater
- Rate limiting
- Sikkerhetsfunksjoner

## Teknisk Stack

- Node.js
- Express.js
- MySQL
- JWT for autentisering
- Node-cache for caching
- Express-rate-limit for rate limiting

## Installasjon

1. Klon repositoriet:
```bash
git clone https://github.com/Neexio/Carrey-backend.git
cd Carrey-backend
```

2. Installer avhengigheter:
```bash
npm install
```

3. Konfigurer miljøvariabler:
```bash
cp .env.example .env
# Rediger .env med dine innstillinger
```

4. Start utviklingsserveren:
```bash
npm run dev
```

## API-endepunkter

### Autentisering
- `POST /api/login` - Innlogging og JWT-token generering

### Søk
- `GET /api/search` - Søk etter nettsteder
  - Query parametere:
    - `query`: Søkefrase (påkrevd)
    - `limit`: Maks antall resultater (standard: 10)

## Sikkerhet

- JWT-basert autentisering
- Rate limiting
- CORS-konfigurasjon
- Helmet for sikkerhetsheaders
- Input-validering

## Ytelse

- Caching av søkeresultater
- Database-pooling
- Komprimering av responser
- Optimalisert database-spørringer

## Utvikling

```bash
# Kjøre tester
npm test

# Lint kode
npm run lint

# Formater kode
npm run format
```

## Lisens

MIT 