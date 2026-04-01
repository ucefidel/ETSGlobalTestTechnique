'use client';

import {useRouter} from "next/navigation";
import {user} from "../httpClient/userHttpClient";
import {AppBar, Button, Toolbar, Typography, Box} from "@mui/material";

export default function Navbar() {
    const router = useRouter();

    const handleLogout = () => {
        user.logout();
        router.push('/login');
    }

    return (
      <AppBar position="static" color={"default"} elevation={1}>
          <Toolbar>
              <Typography variant="h6" component="div" sx={{ flexGrow: 1 }}>
                  ETS Global
              </Typography>
              <Box display={"fle"} gap={2}>
                  <Button onClick={() => router.push('/sessions')}>Sessions</Button>
                  <Button onClick={() => router.push('/bookings')}>Réservations</Button>
                  <Button onClick={() => router.push('/account')}>Mon compte</Button>
                  <Button variant="text" onClick={handleLogout}>Déconnexion</Button>
              </Box>

          </Toolbar>
      </AppBar>
    );
}