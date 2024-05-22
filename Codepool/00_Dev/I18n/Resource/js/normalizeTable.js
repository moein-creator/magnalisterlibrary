const mysql = require('mysql');
const crypto = require('crypto');
const fs = require('fs');
const path = require('path');

// Database connection credentials
const dbConfig = {
    host: '127.0.0.1',
    user: 'root',
    password: 'root',
    database: 'sw_5'
};

// Function to create a MySQL connection
function createConnection() {
    return mysql.createConnection(dbConfig);
}

// Function to replace marketplace names
function replaceMarketplaceNames(sqlContent) {
    const marketplaceNames = [
        "OTTO Market",
        "OTTO",
        "Amazon",
        "eBay",
        "hood.de",
        "hood",
        "METRO Markets",
        "METRO",
        "Etsy",
        "CDiscount",
        "Rakuten FR",
        "Rakuten",
        "PriceMinister",
        "Kaufland.de",
        "Kaufland",
        "idealo.de",
        "idealo",
        "Google Shopping",
        "Ricardo",
        "Check24",
        "MercadoLivre"
    ];
    marketplaceNames.forEach(name => {
        const regex = new RegExp(name, 'gi');
        sqlContent = sqlContent.replace(regex, "{#setting:currentMarketplaceName#}");
    });
    return sqlContent;
}


// Function to generate a SHA-256 hash
function sha256Hash(text) {
    return crypto.createHash('sha256').update(text).digest('hex').substring(0, 255);
}

// Function to generate a unique key
function generateUniqueKey(text, existingKeys) {
    let key = '';
    if (text !== null && text !== '') {
        key = text.toLowerCase()
            .replace(/[^a-zA-Z0-9]/g, '_')
            .replace(/__/g, '0').substring(0, 255);
        while (existingKeys.has(key)) {
            key = sha256Hash(key + Math.random());
        }
    }
    return key;
}

// Function to convert text to a set of unique words
function textToWordSet(text) {
    return new Set(text.replace(/\s+/g, ' ').trim().toLowerCase().split(' '));
}

// Function to calculate similarity between two sets of words
function calculateSimilarity(set1, set2) {
    const intersection = new Set([...set1].filter(word => set2.has(word)));
    return intersection.size / Math.min(set1.size, set2.size);
}

// Function to process the data from the database
function processData(results, outputPath) {
    const existingKeys = new Set();
    const textMap = new Map();
    const originalTextMap = new Map();

    // Store word sets only for DE values longer than 255 characters
    results.forEach(row => {
        if (row.DE !== null && row.DE.length > 255) {
            const originalText = replaceMarketplaceNames(row.DE).replace(/ +/g, ' ');
            const wordSet = textToWordSet(originalText);
            textMap.set(row.TranslationKey, wordSet);
            originalTextMap.set(row.TranslationKey, originalText);
        }
    });

    let sqlCommands = '';

    // Compare each text with all others to find similarities
    textMap.forEach((wordSet1, key1) => {
        textMap.forEach((wordSet2, key2) => {
            if (key1 !== key2) {
                const similarity = calculateSimilarity(wordSet1, wordSet2);
                if (similarity >= 0.95) { // 80% similarity
                    let combinedWords = new Set([...wordSet1, ...wordSet2]);
                    let uniqueKey = generateUniqueKey([...combinedWords].join(' '), existingKeys);
                    // if (uniqueKey.length > 255) {
                    uniqueKey = sha256Hash(uniqueKey);
                    // }
                    existingKeys.add(uniqueKey);
                    const originalText = originalTextMap.get(key1).replace(/\n+/g, ' ');
                    const originalText2 = originalTextMap.get(key2).replace(/\n+/g, ' ');

                    sqlCommands += `-- '${originalText}';\n`;
                    sqlCommands += `-- '${originalText2}';\n`;
                    sqlCommands += `-- similarity : '${similarity}';\n`;
                    sqlCommands += `INSERT INTO magnalister_translation
                                    SET TranslationKey='${uniqueKey}', DE='${originalText}';    `;
                    sqlCommands += `UPDATE magnalister_translation
                                    SET DE='{#i18n:${uniqueKey}#}'
                                    WHERE TranslationKey IN ('${key1}', '${key2}');  `;
                }
            }
        });
    });

    fs.writeFileSync(outputPath, sqlCommands, {encoding: 'utf8'});
}


// Main function to read data from the database and process it
function main() {
    const connection = createConnection();
    const outputPath = path.join(__dirname, 'output_sql_commands.sql');

    connection.connect(err => {
        if (err) {
            console.error('Error connecting to the database:', err);
            return;
        }
        console.log('Connected to the database.');

        const query = 'SELECT TranslationKey, DE FROM magnalister_translation';
        connection.query(query, (err, results) => {
            if (err) {
                console.error('Error querying the database:', err);
                connection.end();
                return;
            }

            processData(results, outputPath);
            console.log(`SQL commands written to: ${outputPath}`);
            connection.end();
        });
    });
}

main();
