'use client';

import { useEffect, useState } from 'react';
import { useRouter } from 'next/navigation';
import { Box, Typography, TextField, Button, Alert } from '@mui/material';
import {user} from "../../httpClient/userHttpClient";
import api from "../../httpClient/api";

export default function AccountPage() {
    const router = useRouter();
    const [name, setName] = useState('');
    const [email, setEmail] = useState('');
    const [error, setError] = useState(null);
    const [success, setSuccess] = useState(null);

    useEffect(() => {
        if (!user.isAuthenticated()) {
            router.push('/login');
            return;
        }
        fetchProfile();
    }, []);

    const fetchProfile = async () => {
        try {
            const data = await user.getUser();
            setName(data.name);
            setEmail(data.email);
        } catch (err) {
            setError('Erreur lors du chargement du profil');
        }
    };

    const handleSubmit = async (e) => {
        e.preventDefault();

        setError(null);
        setSuccess(null);

        try {
            await user.updateUser(name, email);
            await user.logout();
            router.push('/login');
        } catch (err) {
            setError(err.response?.data?.error);
        }
    };

    return (
        <Box p={4} maxWidth={500} mx="auto">
            <Typography variant="h5" mb={3}>Mon compte</Typography>

            {error && <Alert severity="error" sx={{ mb: 2 }}>{error}</Alert>}
            {success && <Alert severity="success" sx={{ mb: 2 }}>{success}</Alert>}

            <Box component="form" onSubmit={handleSubmit} display="flex" flexDirection="column" gap={2}>
                <TextField
                    label="Nom"
                    value={name}
                    onChange={(e) => setName(e.target.value)}
                    required
                    fullWidth
                />
                <TextField
                    label="Email"
                    type="email"
                    value={email}
                    onChange={(e) => setEmail(e.target.value)}
                    required
                    fullWidth
                />
                <Button type="submit" variant="contained">
                    Mettre à jour
                </Button>
            </Box>
        </Box>
    );
}