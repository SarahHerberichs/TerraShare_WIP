Réalisation Perso réalisé en Symfony et JS. 

Site de mise en relation, petites annonces.
La relation entre les utilisateur tourne autour d’un LIEU. 

Il peut s’agir d’un lieu que les personnes souhaitent Acheter ou Louer ou Partager et ce pour une durée déterminée ou indéterminée.
Il peut donc s’agir de vacances, d’hébergement contre services, de lieu pour poser une tente, d'un bout de terrain à aménager ou déjà habitable...
Le lieu peut avoir besoin de travaux , auquel cas la mise en relation se fera entre une personne qui cherche a intervenir contre hébergement ou salaire.
Il peut s’agir d’organiser des visites de lieux (Ferme par exemple). 
Il permettra également d’organiser des événements (Mise en relation d’artistes et habitants du lieu).
J’ai également ajouté catégorie « matériel » pour permettre l’échange de matériel pouvant servir à un terrain (Tracteur, pèle, compost, ou même poules etc etc).


L’utilisateur peut Consulter des annonces et faire un don via STRIPE.

L’utilisateur connecté à son compte peut Consulter des annonces, y répondre et accéder aux conversations avec son interlocuteur.Il peut modifier son profil.

Le dashboard de l’administrateur lui permet l’administration de l’ensemble des données (Annonces, Utilisateurs , photos…).

La liste des villes et départements ont été injecté dans la BDD à partir d’un fichier JSON (ImportDepartmentsCommand).
Les images uploadés sont immédiatement compressées (ImageCompressionService) afin d’alléger au maximum la BDD.

