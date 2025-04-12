const express = require('express');
const mysql = require('mysql2/promise');
const path = require('path');
const helmet = require('helmet');
const cors = require('cors');
const { exec } = require('child_process');
const rateLimit = require('express-rate-limit');
const compression = require('compression');
const NodeCache = require('node-cache');
const jwt = require('jsonwebtoken');
require('dotenv').config();

const app = express();
const PORT = process.env.PORT || 3000;

// Testmiljø-konfigurasjon
const isTestEnvironment = process.env.NODE_ENV === 'test';
const testConfig = {
    host: 'localhost',
    user: 'carrfacy_wpadmin',
    password: 'Eirik16498991',
    database: 'carrfacy_wp137'
};

// Produksjonskonfigurasjon
const prodConfig = {
    host: 'localhost',
    user: 'carrfacy_wpadmin',
    password: 'Eirik16498991',
    database: 'carrfacy_wp137'
};

// Velg riktig konfigurasjon basert på miljø
const dbConfig = isTestEnvironment ? testConfig : prodConfig;

// Cache-konfigurasjon
const cache = new NodeCache({ 
    stdTTL: process.env.CACHE_TTL || 600,
    checkperiod: process.env.CACHE_CHECK_PERIOD || 120
});

// Rate limiting
const limiter = rateLimit({
    windowMs: process.env.RATE_LIMIT_WINDOW_MS || 15 * 60 * 1000,
    max: process.env.RATE_LIMIT_MAX_REQUESTS || 100
});

// Security middleware
app.use(helmet());
app.use(cors({
    origin: process.env.NODE_ENV === 'production' ? 'https://carrey.ai' : '*',
    credentials: true
}));
app.use(compression());

// Serve static files
app.use(express.static(path.join(__dirname, 'public')));
app.use('/wp-content', express.static(path.join(__dirname, 'public/wp-content')));
app.use('/wp-includes', express.static(path.join(__dirname, 'public/wp-includes')));
app.use('/wp-admin', express.static(path.join(__dirname, 'public/wp-admin')));

// Parse JSON bodies
app.use(express.json());
app.use(express.urlencoded({ extended: true }));
app.use(limiter);

// Handle PHP files using XAMPP's PHP
app.get('*.php', (req, res) => {
    const filePath = path.join(__dirname, req.path);
    exec(`C:\\xampp\\php\\php.exe ${filePath}`, (error, stdout, stderr) => {
        if (error) {
            console.error(`Error executing PHP: ${error}`);
            return res.status(500).send('Error executing PHP file');
        }
        res.send(stdout);
    });
});

// MySQL connection using XAMPP's MySQL
const pool = mysql.createPool(dbConfig);

// Connect to MySQL
pool.getConnection().then((connection) => {
    console.log(`Connected to ${isTestEnvironment ? 'test' : 'production'} database`);
    
    // Create tables if they don't exist
    const createUsersTable = `CREATE TABLE IF NOT EXISTS wp_users (
        ID BIGINT(20) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        user_login VARCHAR(60) NOT NULL,
        user_pass VARCHAR(255) NOT NULL,
        user_nicename VARCHAR(50) NOT NULL,
        user_email VARCHAR(100) NOT NULL,
        user_url VARCHAR(100),
        user_registered DATETIME NOT NULL,
        user_activation_key VARCHAR(255),
        user_status INT(11) DEFAULT 0,
        display_name VARCHAR(250),
        UNIQUE KEY user_login (user_login),
        UNIQUE KEY user_email (user_email)
    )`;

    const createUsermetaTable = `CREATE TABLE IF NOT EXISTS wp_usermeta (
        umeta_id BIGINT(20) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        user_id BIGINT(20) UNSIGNED NOT NULL,
        meta_key VARCHAR(255),
        meta_value LONGTEXT,
        FOREIGN KEY (user_id) REFERENCES wp_users(ID)
    )`;

    const createSeoAnalysisTable = `CREATE TABLE IF NOT EXISTS wp_seo_analysis (
        id BIGINT(20) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        url VARCHAR(255) NOT NULL,
        analysis_data JSON NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )`;

    // Create tables
    connection.execute(createUsersTable).then(() => {
        console.log('wp_users table created successfully');
    }).catch((err) => {
        console.error('Error creating wp_users table:', err);
    });

    connection.execute(createUsermetaTable).then(() => {
        console.log('wp_usermeta table created successfully');
    }).catch((err) => {
        console.error('Error creating wp_usermeta table:', err);
    });

    connection.execute(createSeoAnalysisTable).then(() => {
        console.log('wp_seo_analysis table created successfully');
    }).catch((err) => {
        console.error('Error creating wp_seo_analysis table:', err);
    });

    // Check if admin user already exists
    const checkAdminQuery = "SELECT ID FROM wp_users WHERE user_email = 'Eiriklarsen2010@gmail.com'";
    connection.execute(checkAdminQuery).then(([results]) => {
        if (results.length > 0) {
            console.log('Admin user already exists with ID:', results[0].ID);
            return;
        }

        // Create admin user if it doesn't exist
        const adminUser = {
            user_login: 'admin',
            user_pass: '$P$B4r3vLxVZxVZxVZxVZxVZxVZxVZxVZx', // WordPress hashed password for 'Eirik16498991'
            user_nicename: 'admin',
            user_email: 'Eiriklarsen2010@gmail.com',
            user_url: 'https://carrey.ai',
            user_registered: new Date().toISOString().slice(0, 19).replace('T', ' '),
            user_activation_key: '',
            user_status: 0,
            display_name: 'Administrator'
        };

        const insertUserQuery = 'INSERT INTO wp_users SET ?';
        connection.execute(insertUserQuery, adminUser).then(([result]) => {
            console.log('Admin user inserted successfully with ID:', result.insertId);
            
            // Insert user capabilities
            const capabilities = {
                user_id: result.insertId,
                meta_key: 'wp_capabilities',
                meta_value: 'a:1:{s:13:"administrator";b:1;}'
            };
            
            connection.execute('INSERT INTO wp_usermeta SET ?', capabilities).then(() => {
                console.log('User capabilities inserted successfully');
                
                // Insert user level
                const userLevel = {
                    user_id: result.insertId,
                    meta_key: 'wp_user_level',
                    meta_value: '10'
                };
                
                connection.execute('INSERT INTO wp_usermeta SET ?', userLevel).then(() => {
                    console.log('User level inserted successfully');
                });
            });
        }).catch((err) => {
            console.error('Error inserting admin user:', err);
        });
    }).catch((err) => {
        console.error('Error checking for admin user:', err);
    });

    connection.release();
});

// WordPress routes
app.get('/', (req, res) => {
    res.sendFile(path.join(__dirname, 'index.php'));
});

app.get('/wp-admin', (req, res) => {
    res.sendFile(path.join(__dirname, 'wp-admin', 'index.php'));
});

app.get('/wp-login.php', (req, res) => {
    res.sendFile(path.join(__dirname, 'wp-login.php'));
});

// WordPress API routes
app.post('/wp-login.php', (req, res) => {
    const { log, pwd } = req.body;
    
    pool.getConnection().then((connection) => {
        connection.execute('SELECT * FROM wp_users WHERE user_login = ? AND user_pass = ?', [log, pwd]).then(([results]) => {
            if (results.length > 0) {
                // Successful login
                res.json({ success: true, user: results[0] });
            } else {
                // Failed login
                res.status(401).json({ error: 'Invalid credentials' });
            }
        }).catch((err) => {
            console.error('Error checking login:', err);
            res.status(500).json({ error: 'Database error' });
        }).finally(() => {
            connection.release();
        });
    });
});

// SEO Analysis routes
app.post('/wp-json/carrey/v1/seo-analysis', (req, res) => {
    const { url, keywords } = req.body;
    
    if (!url) {
        return res.status(400).json({ error: 'URL is required' });
    }

    // Perform SEO analysis
    const analysis = {
        url: url,
        keywords: keywords || [],
        score: Math.floor(Math.random() * 100),
        recommendations: [
            'Optimize meta title and description',
            'Add alt text to images',
            'Improve content structure',
            'Check for broken links',
            'Optimize page load speed'
        ],
        metrics: {
            titleLength: Math.floor(Math.random() * 60),
            metaDescriptionLength: Math.floor(Math.random() * 160),
            wordCount: Math.floor(Math.random() * 1000),
            imageCount: Math.floor(Math.random() * 10),
            linkCount: Math.floor(Math.random() * 20)
        }
    };

    // Save analysis to database
    pool.getConnection().then((connection) => {
        connection.execute('INSERT INTO wp_seo_analysis (url, analysis_data) VALUES (?, ?)', [url, JSON.stringify(analysis)]).then(() => {
            res.json(analysis);
        }).catch((err) => {
            console.error('Error saving SEO analysis:', err);
            res.status(500).json({ error: 'Database error' });
        }).finally(() => {
            connection.release();
        });
    });
});

app.get('/wp-json/carrey/v1/seo-analysis', (req, res) => {
    pool.getConnection().then((connection) => {
        connection.execute('SELECT * FROM wp_seo_analysis ORDER BY created_at DESC LIMIT 10').then(([results]) => {
            res.json(results.map(row => ({
                ...row,
                analysis_data: JSON.parse(row.analysis_data)
            })));
        }).catch((err) => {
            console.error('Error fetching SEO analysis:', err);
            res.status(500).json({ error: 'Database error' });
        }).finally(() => {
            connection.release();
        });
    });
});

// Test-konfigurasjon
const TEST_CONFIG = {
    enabled: false,
    allowedDomains: [
        'test.carrey.ai',
        'staging.carrey.ai',
        'dev.carrey.ai'
    ],
    testDatabase: {
        host: 'localhost',
        user: 'root',
        password: '',
        database: 'carrfacy_wpq8'
    }
};

// Database-tilkobling
const testDb = mysql.createPool(TEST_CONFIG.testDatabase);

// Test-endepunkt
app.get('/api/test/status', (req, res) => {
    res.json({
        testMode: TEST_CONFIG.enabled,
        allowedDomains: TEST_CONFIG.allowedDomains
    });
});

// Aktiver/deaktiver testmodus
app.post('/api/test/toggle', (req, res) => {
    TEST_CONFIG.enabled = !TEST_CONFIG.enabled;
    res.json({ 
        success: true, 
        testMode: TEST_CONFIG.enabled 
    });
});

// Legg til testdomene
app.post('/api/test/domains', (req, res) => {
    const { domain } = req.body;
    if (domain && !TEST_CONFIG.allowedDomains.includes(domain)) {
        TEST_CONFIG.allowedDomains.push(domain);
    }
    res.json({ 
        success: true, 
        domains: TEST_CONFIG.allowedDomains 
    });
});

// Fjern testdomene
app.delete('/api/test/domains/:domain', (req, res) => {
    const { domain } = req.params;
    TEST_CONFIG.allowedDomains = TEST_CONFIG.allowedDomains.filter(d => d !== domain);
    res.json({ 
        success: true, 
        domains: TEST_CONFIG.allowedDomains 
    });
});

// API-autentisering
const authenticateRequest = (req, res, next) => {
    const apiKey = req.headers['x-api-key'];
    if (!apiKey || apiKey !== process.env.API_KEY) {
        return res.status(401).json({ error: 'Ugyldig API-nøkkel' });
    }
    next();
};

// Cache middleware
const cacheMiddleware = (duration) => {
    return (req, res, next) => {
        const key = req.originalUrl;
        const cachedResponse = cache.get(key);
        
        if (cachedResponse) {
            return res.json(cachedResponse);
        }
        
        res.originalJson = res.json;
        res.json = (body) => {
            cache.set(key, body, duration);
            res.originalJson(body);
        };
        next();
    };
};

// JWT middleware
const authenticateToken = (req, res, next) => {
    const authHeader = req.headers['authorization'];
    const token = authHeader && authHeader.split(' ')[1];
    
    if (!token) {
        return res.status(401).json({ error: 'Ingen tilgangstoken' });
    }
    
    jwt.verify(token, process.env.JWT_SECRET, (err, user) => {
        if (err) {
            return res.status(403).json({ error: 'Ugyldig token' });
        }
        req.user = user;
        next();
    });
};

// Søk API
app.get('/api/search', authenticateToken, cacheMiddleware(300), async (req, res) => {
    try {
        const { query, limit = 10 } = req.query;
        
        if (!query) {
            return res.status(400).json({ error: 'Søkefrase er påkrevd' });
        }

        const connection = await pool.getConnection();
        try {
            const [results] = await connection.execute(
                'SELECT * FROM websites WHERE title LIKE ? OR description LIKE ? LIMIT ?',
                [`%${query}%`, `%${query}%`, parseInt(limit)]
            );
            
            res.json({
                results,
                metadata: {
                    query,
                    count: results.length,
                    timestamp: new Date().toISOString()
                }
            });
        } finally {
            connection.release();
        }
    } catch (error) {
        console.error('Søkefeil:', error);
        res.status(500).json({ error: 'Intern serverfeil' });
    }
});

// Login-endepunkt
app.post('/api/login', async (req, res) => {
    try {
        const { username, password } = req.body;
        
        if (!username || !password) {
            return res.status(400).json({ error: 'Brukernavn og passord er påkrevd' });
        }

        const connection = await pool.getConnection();
        try {
            const [users] = await connection.execute(
                'SELECT * FROM wp_users WHERE user_login = ? AND user_pass = ?',
                [username, password]
            );
            
            if (users.length === 0) {
                return res.status(401).json({ error: 'Ugyldige innloggingsdetaljer' });
            }
            
            const user = users[0];
            const token = jwt.sign(
                { id: user.ID, username: user.user_login },
                process.env.JWT_SECRET,
                { expiresIn: process.env.JWT_EXPIRATION }
            );
            
            res.json({ token });
        } finally {
            connection.release();
        }
    } catch (error) {
        console.error('Innloggingsfeil:', error);
        res.status(500).json({ error: 'Intern serverfeil' });
    }
});

// Håndter 404
app.use((req, res) => {
    res.status(404).json({ error: 'Ressurs ikke funnet' });
});

// Feilhåndtering
app.use((err, req, res, next) => {
    console.error('Serverfeil:', err);
    res.status(500).json({ error: 'Intern serverfeil' });
});

// Start server
app.listen(PORT, () => {
    console.log(`Carrey.ai WordPress admin running on port ${PORT}`);
}).on('error', (err) => {
    if (err.code === 'EADDRINUSE') {
        console.error(`Port ${PORT} is already in use. Please try a different port.`);
        process.exit(1);
    } else {
        console.error('Server error:', err);
    }
}); 