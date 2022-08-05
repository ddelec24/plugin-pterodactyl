# Changelog BETA plugin Pterodactyl  

> **IMPORTANT**  
>  
> S'il n'y a pas d'information sur la mise à jour, c'est que celle-ci concerne uniquement de la mise à jour de documentation, de traduction ou de texte.  

# 05/08/2022 - BETA

- Colorisation de la console.  
- Correction de la gestion des websocket multiples.  

# 04/08/2022 - BETA

- Passage d'un message d'erreur en debug au lieu de error si problème pour récupérer les joueurs en ligne.  
- Bouton pour désactiver la console sans avoir à rafraichir la page.  

# 03/08/2022 - BETA

- Amélioration de la récupération des joueurs en priorisant l'alias ip (l'ip retournée par l'API étant souvent l'ip locale du serv, impossible de récupérer le nombre de joueurs depuis l'extérieur) 

# 02/08/2022 - BETA

- Implementation du nombre de joueurs et joueurs max (uniquement minecraft est implanté actuellement).  
- Affichage de la console en temps réel sur le dashboard.  
- Correction d'un bug sur les envois des commandes.  
- Correction de la gauge qui était à 100% quand on avait des valeurs en dessous de 1.  
- TODO: refaire la doc avec les nouvelles captures et nouvelles fonctionnalités.  

# 18/07/2022 - BETA  

- Correction de l'affichage pour les limites de données de disque et ram si celles-ci sont inférieures à 1Go.  
- Forçage du rafraichissement du widget lors d'une mise à jour.  
- Amélioration du texte de l'uptime (cacher 0 jours par exemple) et gestion de la pluralisation des mots.  

# 15/07/2022 - BETA

- Correction de l'unité des remontées de données RX et TX en **Mo**.  
- Si le serveur renvoi un statut vide ou **0**, on précise si c'est car il est suspendu ou cause inconnue.  
- Retrait de l'ajout manuel de serveur car inutile.  
- Ajout de la commande **uptime**.  
- Correction de l'affichage pour les valeurs cpu/ram/disk sans limites.
- En cours: Il semblerait que la clé application (ptla_xxxx) ne soit pas utilisable, seule la clé utilisateur en admin/utilisateur. Investigations en cours...  


# 15/06/2022 - Stable

- Publication du plugin en version stable
- Fix de bugs d'affichages si pas de limite de CPU allouée, 2 décimales pour l'espace disque et la RAM
- Mise à jour documentation


# 10/06/2022 (ALPHA)

- Ajout des commandes pour démarrer/redémarrer/arrêter/kill un serveur, avec confirmation  
- Passage des valeurs mémoire et disque en Go  
- Affichage dashboard remanié pour afficher la valeur actuelle/valeur limite sur une même ligne 


# 09/06/2022 (ALPHA)

- Correction de bug API Admin/utilisateur


# 07/06/2022 (ALPHA)

- Creation des commandes informations
- Creation du plugins