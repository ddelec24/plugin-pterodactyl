# Bienvenue sur le plugin Pterodactyl

Ce plugin permet de faire la liaison entre votre panel [Pterodactyl](https://pterodactyl.io/) et Jeedom que vous soyez un simple utilisateur ou un administrateur.

Pterodactyl est un manager de serveurs de jeux supportant actuellement:  

- Minecraft — including Spigot, Sponge, Bungeecord, Waterfall, and more
- Rust
- Terraria
- Teamspeak
- Mumble
- Team Fortress 2
- Counter Strike: Global Offensive
- Garry's Mod
- ARK: Survival Evolved

# Comment ça marche

Nous utilisons l'API de Pterodactyl afin de récupérer les informations.   
Jeedom va faire une demande pour lister la totalité des serveurs auxquels vous avez accès.  
Ensuite une création automatique des équipements aura lieu sur la page des équipements.  


# Les informations requises

Pour faire cela nous aurons besoin de deux informations.  
> 1 - La clef API a récupérer sur le panel (Voir étape suivante).

> 2 - Si vous êtes admin ou pas du panel (Accès à tous les serveurs ou uniquement les vôtres)


# La clef API

Pour accéder à la clef API utilisateur vous devez aller dans votre **profil** Pterodactyl 

Onglet **API Credentials**  
Mettre une description (ici Jeedom)  
Ensuite si vous le souhaitez, autorisez seulement votre Jeedom, (uniquement si IP FIXE et pas d'alias comme XXXX.eu.jeedom.link) dans notre cas nous ne l'utiliserons pas car pas utile.  

![image](https://user-images.githubusercontent.com/16257583/172942846-02cc6611-b3d8-4ece-83ed-ff1c921da322.png)

Une fois la clef créée il suffit de la coller dans la configuration du plugin sur Jeedom  

![image](https://user-images.githubusercontent.com/3704897/183262385-9b204aba-51b0-416b-b0a7-894f9f544c9e.png)


Si vous êtes administrateur du panel, vous pouvez cocher **Je suis admin** afin de récupérer tous les serveurs et pas uniquement ceux qui vous appartiennent.  
  
  
# Synchronisation  

Pour la suite c'est comme tout les autres plugins, il faut aller dans **Plugins/Monitoring/Pterodactyl**  
Il suffit de cliquer sur **Synchronisation automatique**, les serveurs sont alors récupérés et ajoutés automatiquement dans jeedom.  

![image](https://user-images.githubusercontent.com/3704897/183262457-c0948ba2-6c72-4bab-ac45-231431663f4a.png)


# Affichage côté admin

Choisissez les paramètres classiques de catégorisation, affichage et objet.  

![image](https://user-images.githubusercontent.com/3704897/183262692-c33e37ed-9067-4618-9e2c-4952d3cc3d5e.png)   

Le choix du jeu est utile uniquement si c'est minecraft actuellement. Cela permet de récupérer le nombre de joueurs en ligne / max via une requête au site https://minecraft-api.com/  
N'hésitez pas à me faire remonter l'information sur le community jeedom si vous connaissez un site identique pour un autre jeu supporté par pterodactyl.  
L'affichage de la console sur le dashboard vous donnera accès à la console temps réel comme dans pterodactyl.  
  
  
# Affichage côté dashboard 

![image](https://user-images.githubusercontent.com/3704897/183262859-6e14caa5-9c54-4091-9497-52939ef94baa.png)

![image](https://user-images.githubusercontent.com/3704897/183263089-bc3446c0-5322-48ef-9d5e-f1a172ab068d.png)


Remarques:  
- La valeur d'utilisation du processeur est ramenée à 100%. En effet, les valeurs sont définies à l'origine par rapport au nombre de coeurs (6 coeurs = 600), donc si vous utilisez 60 / 600, le plugin affichera 10% d'utilisation CPU. Si aucune restriction CPU n'est définie, il a été enregistré un seuil automatique à 1000 pour avoir une gauge ni trop grande, ni trop limitée.  
- L'API ne renvoi jamais le retour de votre commande, comme le fait la console du panel, vérifiez bien la syntaxe avant envoi. Si vous activez la console vous aurez ce retour dans l'autre tuile.  
- Sur la console, lorsque vous voyez un message comme quoi la connexion est rétablie, c'est normal, toutes les 10 minutes environ jeedom doit redemander un accès à la console.



# FAQ  
  
- Je n'ai pas de retour console, que se passe t'il?
> La cause la plus probable est un refus de la part de Wings. En effet par défaut aucun accès n'est autorisé. Voici la marche à suivre:
> > Ouvrez /etc/pterodactyl/config.yml et cherchez allowed_origins tout en bas  
> > ![image](https://user-images.githubusercontent.com/3704897/183263298-9c4d8d7d-8dee-4b7c-8b6e-926fcd2c6afe.png)  
> > J'ai mis une étoile pour les tests, ce qui autorise tout. il est préférable de n'autoriser que votre domaine jeedom. ça peut etre votre domain \*.eu.jeedom.link ou tout autre domaine que vous auriez mis en place pour votre jeedom. Se référer à la documentation pterodactyl/wings pour plus d'infos.  
> > Relancez wings avec la commande **sudo systemctl restart wings**
  
    
    
- J'ai bien mis *minecraft* en jeu dans la configuration pour avoir le nombre de joueurs, mais ça ne fonctionne pas. Pourquoi?  
> Il faut mettre votre plugin en debug dans la configuration. Vous aurez plus de détails sur ce qui se passe.  
> > Comme on utilise un site tiers pour récupérer les infos, il est vivement conseillé de mettre l'adresse que les joueurs rentrent dans le jeu en IP alias pour que ce site puisse lui aussi s'y connecter.  
> > ![image](https://user-images.githubusercontent.com/3704897/183263511-49b6ae52-81c7-4cf7-ae7c-f5919318bf37.png)  
> > Dans l'administration, menu management => nodes. Cliquez sur le node concerné et Allocation, vous tomberez sur le menu ci-dessus.  
  
  
- Pour toutes autres questions, merci de vérifier si la question n'a pas été posée sur le community => [https://community.jeedom.com/tag/plugin-pterodactyl](https://community.jeedom.com/tag/plugin-pterodactyl). Si ce n'est pas le cas, n'hésitez pas à demander de l'aide.  

