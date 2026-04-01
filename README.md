
## Installation et lancement

### 1. Cloner le dépôt

```bash
git clone git@github.com:ucefidel/ETSGlobalTestTechnique.git
cd ETSGlobalTestTechnique
```

### 2. Lancer le projet

```bash
make install
```

Cette commande construit les images Docker, démarre les services et génère les clés JWT automatiquement.

Les services démarrent sur :

 - Frontend: http://localhost:3000
 - Backend: http://localhost:8080

### 3. Arrêter le projet

```bash
make stop
```
## Compte de test                                                                                                                                                                        
                                                                                                                                                                                         
Un utilisateur et des sessions sont automatiquement créés au lancement via `make install`.                                                                                               
                                                                                                                                                                                         
                                                                                                                                                                     
Email: `test@example.com`                                                                                                                                                              
Mot de passe: `password`                                                                                                                                                             

————————————————————————
## Tests

### Backend — PHPUnit

```bash
make test-backend
```

### Frontend — Jest

```bash
make test-frontend
```
