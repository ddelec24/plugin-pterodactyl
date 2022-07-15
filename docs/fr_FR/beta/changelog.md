# Changelog plugin Pterodactyl  

> **IMPORTANT**  
>  
> S'il n'y a pas d'information sur la mise à jour, c'est que celle-ci concerne uniquement de la mise à jour de documentation, de traduction ou de texte.  

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