'use client';

import {useEffect, useState} from 'react';
import {useRouter} from 'next/navigation';
import {Box, Typography, Button, Card, CardActions, Pagination, Alert} from '@mui/material';
import {sessionHttpClient} from "../../httpClient/sessionHttpClient";
import {bookingHttpClient} from "../../httpClient/bookingHttpClient";
import {connect, user} from "../../httpClient/userHttpClient";


export default function SessionsPage() {
    const router = useRouter();
    const [sessions, setSessions] = useState([]);
    const [page, setPage] = useState(1);
    const [totalPages, setTotalPages] = useState(1);
    const [availableOnly, setAvailableOnly] = useState(false);
    const [error, setError] = useState(null);
    const [success, setSuccess] = useState(null);

    useEffect(() => {
        if (!user.isAuthenticated()) {
            router.push('/login');
            return;
        }

        fetchSessions();
    }, [page, availableOnly]);

    const fetchSessions = async () => {
        try {
            const data = await sessionHttpClient.getSessions(page, 10, availableOnly);

            setSessions(data.data);
            setTotalPages(data.pages);
        } catch (err) {
            setError('Erreur lors du chargement des sessions');
        }
    };

    const handleBook = async (sessionId) => {
        setError(null);
        setSuccess(null);
        try {
            await bookingHttpClient.book(sessionId);
            setSuccess('Réservation effectuée avec succès');

            await fetchSessions();
        } catch (err) {
            setError(err.response?.data?.error);
        }
    };

    return (
        <Box p={4} maxWidth={900} mx="auto">
            <Typography variant="h5" mb={3}>Sessions de tests de langues</Typography>

            {error && <Alert severity="error" sx={{mb: 2}}>{error}</Alert>}
            {success && <Alert severity="success" sx={{mb: 2}}>{success}</Alert>}

            <table width="100%" style={{borderCollapse: 'collapse'}}>
                <thead>
                <tr style={{borderBottom: '2px solid #ddd', textAlign: 'left'}}>
                    <th style={{padding: '8px'}}>Langue</th>
                    <th style={{padding: '8px'}}>Date</th>
                    <th style={{padding: '8px'}}>Heure</th>
                    <th style={{padding: '8px'}}>Lieu</th>
                    <th style={{padding: '8px'}}>Places</th>
                    <th style={{padding: '8px'}}></th>
                </tr>
                </thead>
                <tbody>

                {sessions.map((session) => (
                    <tr key={session.id} style={{borderBottom: '1px solid #eee'}}>
                        <td style={{padding: '8px'}}>{session.language}</td>
                        <td style={{padding: '8px'}}>{session.dateAt}</td>
                        <td style={{padding: '8px'}}>{session.hourAt}</td>
                        <td style={{padding: '8px'}}>{session.location}</td>
                        <td style={{padding: '8px'}}>{session.availableSeats}</td>
                        <td style={{padding: '8px'}}>
                            <Button size="small" disabled={session.availableSeats === 0} onClick={() => handleBook(session.id)}>
                                Réserver
                            </Button>
                        </td>
                    </tr>
                ))}

                </tbody>
            </table>

            <Box display="flex" justifyContent="center" mt={3}>
                <Pagination count={totalPages} page={page} onChange={(_, val) => setPage(val)}/>
            </Box>
        </Box>
    );

}