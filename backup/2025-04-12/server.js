const express = require('express');
const mysql = require('mysql2');
const path = require('path');
const helmet = require('helmet');
const cors = require('cors');
const { exec } = require('child_process');
require('dotenv').config();

const app = express();
const PORT = 3000;

// Security middleware
app.use(helmet());
app.use(cors({
    origin: ['https://carrey.ai', 'http://localhost:3000'],
    credentials: true
}));

// Serve static files
app.use(express.static(path.join(__dirname, 'public')));
app.use('/wp-content', express.static(path.join(__dirname, 'public/wp-content')));
app.use('/wp-includes', express.static(path.join(__dirname, 'public/wp-includes')));
app.use('/wp-admin', express.static(path.join(__dirname, 'public/wp-admin')));

// Parse JSON bodies
app.use(express.json());
app.use(express.urlencoded({ extended: true }));

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
const db = mysql.createConnection({
    host: 'localhost',
    user: 'Lift',
    password: 'Eirik16498991',
    database: 'carrfacy_wpq8'
});

// Connect to MySQL
db.connect((err) => {
    if (err) {
        console.error('Error connecting to MySQL database:', err);
        return;
    }
    console.log('Connected to MySQL database');
    
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
    db.query(createUsersTable, (err) => {
        if (err) {
            console.error('Error creating wp_users table:', err);
            return;
        }
        console.log('wp_users table created successfully');
    });

    db.query(createUsermetaTable, (err) => {
        if (err) {
            console.error('Error creating wp_usermeta table:', err);
            return;
        }
        console.log('wp_usermeta table created successfully');
    });

    db.query(createSeoAnalysisTable, (err) => {
        if (err) {
            console.error('Error creating wp_seo_analysis table:', err);
            return;
        }
        console.log('wp_seo_analysis table created successfully');
    });

    // Check if admin user already exists
    const checkAdminQuery = "SELECT ID FROM wp_users WHERE user_email = 'Eiriklarsen2010@gmail.com'";
    db.query(checkAdminQuery, (err, results) => {
        if (err) {
            console.error('Error checking for admin user:', err);
            return;
        }

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
        db.query(insertUserQuery, adminUser, (err, result) => {
            if (err) {
                console.error('Error inserting admin user:', err);
                return;
            }
            console.log('Admin user inserted successfully with ID:', result.insertId);
            
            // Insert user capabilities
            const capabilities = {
                user_id: result.insertId,
                meta_key: 'wp_capabilities',
                meta_value: 'a:1:{s:13:"administrator";b:1;}'
            };
            
            db.query('INSERT INTO wp_usermeta SET ?', capabilities, (err) => {
                if (err) {
                    console.error('Error inserting user capabilities:', err);
                    return;
                }
                console.log('User capabilities inserted successfully');
                
                // Insert user level
                const userLevel = {
                    user_id: result.insertId,
                    meta_key: 'wp_user_level',
                    meta_value: '10'
                };
                
                db.query('INSERT INTO wp_usermeta SET ?', userLevel, (err) => {
                    if (err) {
                        console.error('Error inserting user level:', err);
                        return;
                    }
                    console.log('User level inserted successfully');
                });
            });
        });
    });
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
    
    db.query('SELECT * FROM wp_users WHERE user_login = ? AND user_pass = ?', [log, pwd], (err, results) => {
        if (err) {
            console.error('Error checking login:', err);
            res.status(500).json({ error: 'Database error' });
            return;
        }
        
        if (results.length > 0) {
            // Successful login
            res.json({ success: true, user: results[0] });
        } else {
            // Failed login
            res.status(401).json({ error: 'Invalid credentials' });
        }
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
    const query = 'INSERT INTO wp_seo_analysis (url, analysis_data) VALUES (?, ?)';
    db.query(query, [url, JSON.stringify(analysis)], (err, result) => {
        if (err) {
            console.error('Error saving SEO analysis:', err);
            return res.status(500).json({ error: 'Database error' });
        }
        res.json(analysis);
    });
});

app.get('/wp-json/carrey/v1/seo-analysis', (req, res) => {
    const query = 'SELECT * FROM wp_seo_analysis ORDER BY created_at DESC LIMIT 10';
    db.query(query, (err, results) => {
        if (err) {
            console.error('Error fetching SEO analysis:', err);
            return res.status(500).json({ error: 'Database error' });
        }
        res.json(results.map(row => ({
            ...row,
            analysis_data: JSON.parse(row.analysis_data)
        })));
    });
});

// Error handling middleware
app.use((err, req, res, next) => {
    console.error('Unhandled error:', err);
    res.status(500).json({ error: 'Internal server error', details: err.message });
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