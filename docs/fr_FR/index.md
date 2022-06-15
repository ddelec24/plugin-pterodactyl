# Bienvenue sur le plugin Pterodactyl

Ce plugin permet de faire la liaison entre votre panel [Pterodactyl](https://pterodactyl.io/) et Jeedom que vous soyez un simple utilisateur ou un administrateur.


# Comment ça marche

Nous utilisons l'API de Pterodactyl afin de récupérer les informations.   
Jeedom va faire une demande pour lister la totalité des serveurs auxquels vous avez accès. Ensuite une création automatique des équipements aura lieu sur la page des équipements.


# Les informations requises

Pour faire cela nous aurons besoin de deux informations.  
> 1 - La clef API a récupérer sur le panel (Voir étape suivante).

> 2 - Si vous êtes admin ou pas du panel (Accès à tous les serveurs ou uniquement les vôtres)


# La clef API

 **=> La clef API Admin**  

  Pour récupérer la clef API admin il suffira de créer une clef API Application : 
  
 ![image](https://user-images.githubusercontent.com/16257583/172942388-15d4b6ee-ffec-465a-82e9-a716e49cf9c7.png)
  
  Ensuite, créez la clef avec les autorisations voulues.  
  (Actuellement nous n'aurons besoin que de **Server** et mode **read & write**)
  
  ![image](https://user-images.githubusercontent.com/16257583/172942517-41b60775-d201-4a7d-94bb-a11804a52403.png)
  ![image](https://user-images.githubusercontent.com/16257583/172942611-48c15962-4584-4533-96cd-a896d630e0e9.png)

  
  Une fois la clef créée, il suffit de la coller dans la configuration du plugin sur Jeedom et de cocher la case **Je suis admin**
  
  ![image](https://user-images.githubusercontent.com/3704897/173872052-eb589a3a-a6eb-4d15-9136-d77bfcfce20f.png)


 **=> La clef API Utilisateur**  

Pour accéder à la clef API utilisateur vous devez aller dans votre **profil** Pterodactyl 

Onglet **API Credentials**
Mettre une description (ici Jeedom)
Ensuite si vous le souhaitez, autorisez seulement votre Jeedom, (uniquement si IP FIXE et pas d'alias comme XXXX.eu.jeedom.link) dans notre cas nous ne l'utiliserons pas car pas utile.

![image](https://user-images.githubusercontent.com/16257583/172942846-02cc6611-b3d8-4ece-83ed-ff1c921da322.png)

Une fois la clef créée il suffit de la coller dans la configuration du plugin sur Jeedom et de **NE PAS** cocher la case **Je suis admin**  

![image](https://user-images.githubusercontent.com/3704897/173872708-717fa40c-a3bc-40f8-9484-2e9a02a9846f.png)


# Synchronisation  

Pour la suite c'est comme tout les autres plugins, il faut aller dans **Plugins/Monitoring/Pterodactyl**
Il suffit de cliquer sur **Synchronisation automatique**, les serveurs sont alors récupérés et ajoutés automatiquement dans jeedom.  

![image](https://user-images.githubusercontent.com/3704897/173873237-f1c595e0-f2c4-402f-915b-9bdbb0756abd.png)


# Affichage Admin

Choisissez les paramètres classiques de catégorisation, affichage et objet.  

![image](https://user-images.githubusercontent.com/3704897/173875412-7d3c6e3a-cae0-4fda-9999-cbb09c4d545e.png)


# Affichage Dashboard 

![image](https://user-images.githubusercontent.com/3704897/173874678-bd99c131-df4b-46ae-bb52-477c6d508780.png)

