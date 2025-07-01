import express from 'express';
import bcrypt from 'bcrypt';
import { db } from '../db.js';

const router = express.Router();

// REGISTRO
router.post('/registro', async (req, res) => {
    const { nombre, email, password } = req.body;

    try {
        const [usuarios] = await db.query(
            'SELECT * FROM usuario WHERE correo_electronico = ?',
            [email]
        );

        if (usuarios.length > 0) {
            return res.json({ success: false, message: 'El correo ya está registrado' });
        }

        const hashedPassword = await bcrypt.hash(password, 10);
        const fecha = new Date().toISOString().split('T')[0]; // formato YYYY-MM-DD
        const rol = 'usuario';

        await db.query(
            `INSERT INTO usuario (Nombre_Apellido, correo_electronico, contrasena, fecha_registro, rol)
             VALUES (?, ?, ?, ?, ?)`,
            [nombre, email, hashedPassword, fecha, rol]
        );

        res.json({ success: true, message: 'Usuario registrado con éxito' });

    } catch (error) {
        console.error(error);
        res.json({ success: false, message: 'Error al registrar usuario' });
    }
});

// LOGIN
router.post('/login', async (req, res) => {
    const { email, password } = req.body;

    try {
        const [result] = await db.query(
            'SELECT * FROM usuario WHERE correo_electronico = ?',
            [email]
        );

        if (result.length === 0) {
            return res.json({ success: false, message: 'Correo no registrado' });
        }

        const usuario = result[0];
        const coincide = await bcrypt.compare(password, usuario.contrasena);

        if (!coincide) {
            return res.json({ success: false, message: 'Contraseña incorrecta' });
        }

        res.json({
            success: true,
            usuario: {
                nombre: usuario.Nombre_Apellido,
                email: usuario.correo_electronico
            }
        });

    } catch (error) {
        console.error(error);
        res.json({ success: false, message: 'Error al iniciar sesión' });
    }
});

export default router;
