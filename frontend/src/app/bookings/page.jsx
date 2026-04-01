'use client';

import { useEffect, useState } from 'react';
import { useRouter } from 'next/navigation';
import { Box, Typography, Button, Alert } from '@mui/material';
import {bookingHttpClient} from "../../httpClient/bookingHttpClient";
import { user} from "../../httpClient/userHttpClient";

export default function BookingsPage() {
    const router = useRouter();
    const [bookings, setBookings] = useState([]);
    const [error, setError] = useState(null);
    const [success, setSuccess] = useState(null);

    useEffect(() => {
        if (!user.isAuthenticated()) {
            router.push('/login');
            return;
        }
        fetchBookings();
    }, []);

    const fetchBookings = async () => {
        try {
            const data = await bookingHttpClient.getAllBooking();
            setBookings(data);
        } catch (err) {
            setError('Erreur lors du chargement des réservations');
        }
    };

    const handleCancel = async (id) => {
        setError(null);
        setSuccess(null);
        try {
            await bookingHttpClient.cancel(id);
            setSuccess('Réservation annulée');
            await fetchBookings();
        } catch (err) {
            setError(err.response?.data?.error);
        }
    };

    return (
        <Box p={4} maxWidth={900} mx="auto">
            <Typography variant="h5" mb={3}>Mes réservations</Typography>

            {error && <Alert severity="error" sx={{ mb: 2 }}>{error}</Alert>}
            {success && <Alert severity="success" sx={{ mb: 2 }}>{success}</Alert>}

            <table width="100%" style={{ borderCollapse: 'collapse' }}>
                <thead>
                <tr style={{ borderBottom: '2px solid #ddd', textAlign: 'left' }}>
                    <th style={{ padding: '8px' }}>Langue</th>
                    <th style={{ padding: '8px' }}>Date</th>
                    <th style={{ padding: '8px' }}>Heure</th>
                    <th style={{ padding: '8px' }}>Lieu</th>
                    <th style={{ padding: '8px' }}>Réservé le</th>
                    <th style={{ padding: '8px' }}></th>
                </tr>
                </thead>
                <tbody>

                {bookings.map((booking) => (
                    <tr key={booking.id} style={{ borderBottom: '1px solid #eee' }}>
                        <td style={{ padding: '8px' }}>{booking.session.language}</td>
                        <td style={{ padding: '8px' }}>{booking.session.dateAt}</td>
                        <td style={{ padding: '8px' }}>{booking.session.hourAt}</td>
                        <td style={{ padding: '8px' }}>{booking.session.location}</td>
                        <td style={{ padding: '8px' }}>{booking.bookedAt}</td>
                        <td style={{ padding: '8px' }}>
                            <Button variant="outlined" color="error" onClick={() => handleCancel(booking.id)}>
                                Annuler
                            </Button>
                        </td>
                    </tr>
                ))}

                </tbody>
            </table>
        </Box>
    );
}