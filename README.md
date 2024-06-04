https://terrashare.fr
[Réalisation Perso - Réalisé en Symfony et JS + CSS & Bootstrap.

Site de mise en relation, petites annonces.
La relation entre les utilisateurs tourne autour d’un LIEU. 

Il peut s’agir d’un lieu que les personnes souhaitent Acheter ou Louer ou Partager et ce pour une durée déterminée ou indéterminée.
Il peut donc s’agir de vacances, d’hébergement contre services, de lieu pour poser une tente, d'un bout de terrain à aménager ou déjà habitable...
Le lieu peut avoir besoin de travaux , auquel cas la mise en relation se fera entre une personne qui cherche a intervenir contre hébergement ou salaire.
Il peut s’agir d’organiser des visites de lieux (Ferme par exemple). 
Il permettra également d’organiser des événements (Mise en relation d’artistes et habitants du lieu).
J’ai également ajouté catégorie « matériel » pour permettre l’échange de matériel pouvant servir à un terrain (Tracteur, pèle, compost, ou même poules etc etc).


L’utilisateur peut Consulter des annonces et faire un don via STRIPE.

L’utilisateur connecté à son compte peut Consulter des annonces, y répondre et accéder aux conversations avec son interlocuteur, marquer comme lu ses messages et répondre à l'expéditeur.Il peut poster des annonces avec des photos et modifier ses annonces.Il peut aussi modifier son profil.

Le dashboard de l’administrateur lui permet l’administration de l’ensemble des données (Annonces, Utilisateurs , photos…).

Les passwords sont hashés et les formulaires sont protégés par des tokens.

La liste des villes et départements ont été injectés dans la BDD à partir d’un fichier JSON (ImportDepartmentsCommand).
Les images uploadées sont immédiatement compressées (ImageCompressionService) afin d’alléger au maximum la BDD.

L'optimisation du code HTML en terme de référencement et de choix de balises adaptées n'a pas encore été géré.


](https://terrashare.fr

Réalisation Perso - Réalisé en Symfony et JS + CSS & Bootstrap.

Site de mise en relation, petites annonces. La relation entre les utilisateurs tourne autour d’un LIEU.

Il peut s’agir d’un lieu que les personnes souhaitent Acheter ou Louer ou Partager et ce pour une durée déterminée ou indéterminée. Il peut donc s’agir de vacances, d’hébergement contre services, de lieu pour poser une tente, d'un bout de terrain à aménager ou déjà habitable... Le lieu peut avoir besoin de travaux , auquel cas la mise en relation se fera entre une personne qui cherche a intervenir contre hébergement ou salaire. Il peut s’agir d’organiser des visites de lieux (Ferme par exemple). Il permettra également d’organiser des événements (Mise en relation d’artistes et habitants du lieu). J’ai également ajouté catégorie « matériel » pour permettre l’échange de matériel pouvant servir à un terrain (Tracteur, pèle, compost, ou même poules etc etc).

L’utilisateur peut Consulter des annonces et faire un don via STRIPE.

L’utilisateur connecté à son compte peut Consulter des annonces, y répondre et accéder aux conversations avec son interlocuteur, marquer comme lu ses messages et répondre à l'expéditeur.Il peut poster des annonces avec des photos et modifier ses annonces.Il peut aussi modifier son profil.

Le dashboard de l’administrateur lui permet l’administration de l’ensemble des données (Annonces, Utilisateurs , photos…).

Les passwords sont hashés et les formulaires sont protégés par des tokens.

La liste des villes et départements ont été injectés dans la BDD à partir d’un fichier JSON (ImportDepartmentsCommand). Les images uploadées sont immédiatement compressées (ImageCompressionService) afin d’alléger au maximum la BDD.

L'optimisation du code HTML en terme de référencement et de choix de balises adaptées n'a pas encore été géré.
)
