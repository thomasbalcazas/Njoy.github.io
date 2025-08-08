import express from 'express';
import cors from 'cors';
import path from 'path';
import { fileURLToPath } from 'url';
import userRoutes from './routes/users.js'; // Importa las rutas de usuarios

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

const app = express();
const PORT = 3306;

// Middlewares
app.use(cors()); // Permite peticiones desde el frontend
app.use(express.json()); // Para parsear JSON en las peticiones

// Sirve archivos estáticos (frontend)
app.use(express.static(path.join(__dirname, '../front')));

// Rutas de la API (ej: /api/login, /api/registro)
app.use('/api', userRoutes);

// Inicia el servidor
app.listen(PORT, () => {
    console.log(`Servidor corriendo en http://localhost:${PORT}`);

});
