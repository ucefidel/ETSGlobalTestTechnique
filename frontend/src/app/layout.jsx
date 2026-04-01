import Navbar from "../components/Navbar";

export default function RootLayout({ children }) {
  return (
      <html lang="fr">
          <body>
              <Navbar/>
              {children}
          </body>
      </html>
  );
}