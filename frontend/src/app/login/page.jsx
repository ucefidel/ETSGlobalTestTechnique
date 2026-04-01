'use client';

import {useState} from 'react';
import {useRouter} from 'next/navigation';
import styles from './login.module.css';
import {Box, Button, TextField, Typography, Paper, Alert, Form} from '@mui/material';
import { user} from "../../httpClient/userHttpClient";

export default function LoginPage() {
    const router = useRouter();
    const [email, setEmail] = useState('');
    const [password, setPassword] = useState('');
    const [error, setError] = useState(null);

    const handleSubmit = async (e) => {
        e.preventDefault();

        try {
            await user.login(email, password);
            router.push('/bookings');
        } catch (error) {
            setError(error.message);

        }
    }

    return (
        <Box className={styles.container}>
            <Paper elevation={3} className={styles.paper}>
                <Typography variant="h5" fontWeight="bold" mb={3} textAlign="center">
                    ETS Global — Connexion
                </Typography>

                {error && <Alert severity="error" sx={{ mb: 2 }}>{error}</Alert>}
                <Box component="form" onSubmit={handleSubmit} className={styles.form}>
                    <TextField
                        label="Email"
                        type="email"
                        value={email}
                        onChange={(e) => setEmail(e.target.value)}
                        required
                        fullWidth
                    />
                    <TextField
                        label="Mot de passe"
                        type="password"
                        value={password}
                        onChange={(e) => setPassword(e.target.value)}
                        required
                        fullWidth
                    />
                    <Button type="submit" variant="contained" fullWidth>
                        Se connecter
                    </Button>
                </Box>
            </Paper>
        </Box>
    )
}