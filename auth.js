// routes/auth.js
import express from 'express';
import bcrypt from 'bcrypt';
import jwt from 'jsonwebtoken';
import { users } from '../users.js';

const router = express.Router();

router.post('/register', async (req, res) => {
  const { nombre, email, password } = req.body;

  const existe = users.find(u => u.email === email);
  if (existe) return res.status(400).json({ mensaje: 'El email ya está registrado' });

  const hashedPassword = await bcrypt.hash(password, 10);
  users.push({ nombre, email, password: hashedPassword });

  res.status(201).json({ mensaje: 'Usuario registrado con éxito' });
});

router.post('/login', async (req, res) => {
  const { email, password } = req.body;

  const usuario = users.find(u => u.email === email);
  if (!usuario) return res.status(401).json({ mensaje: 'Credenciales inválidas' });

  const esValida = await bcrypt.compare(password, usuario.password);
  if (!esValida) return res.status(401).json({ mensaje: 'Credenciales inválidas' });

  const token = jwt.sign({ email: usuario.email }, 'secreto', { expiresIn: '1h' });
  res.json({ nombre: usuario.nombre, email: usuario.email, token });
});

export default router;
