import mysql from 'mysql2/promise';

export const db = mysql.createPool({
    host: 'sql208.infinityfree.com',
    user: 'if0_39450468',
    password: 'Njoy2025',
    database: 'if0_39450468_XXX'
});
