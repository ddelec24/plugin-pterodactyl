# Bienvenue sur le plugin Pterodactyl

Ce plugin permet de faire la liaison entre votre panel Pterodactyl et Jeedom que vous soyer un simple utilisateur ou un admin.


# Comment sa marche.

Nous utilisé l'API de Pterodactyl afin de récupérer les information 
Jeedom vas faire une demande pour lister la totalité des serveurs au quelle vous avez accès, ensuite une création automatique des équipements aura lieu sur la page d'équipements.

# Les informations requis

Pour faire cela nous aurons besoin de deux informations.
> 1 - La clef API a récupéré sur le panel (Voir la prochaine étape).

> 2 - Si vous êtes admin ou pas du panel (Accès a tout les serveur ou uniquement les vôtres)


# La clef API
 ## La clef API Admin : 
  
  Pour récupère la clef API admin il suffira de crée une clef API Application : 
  
 ![image](https://user-images.githubusercontent.com/16257583/172942388-15d4b6ee-ffec-465a-82e9-a716e49cf9c7.png)
  
  Ensuite crée la clef avec les informations a lui autorisé 
  (Actuellement nous n'aurons besoin que de **Server** et mode **read & write**)
  
  ![image](https://user-images.githubusercontent.com/16257583/172942517-41b60775-d201-4a7d-94bb-a11804a52403.png)
  ![image](https://user-images.githubusercontent.com/16257583/172942611-48c15962-4584-4533-96cd-a896d630e0e9.png)

  
  Une fois la clef crée il suffit de la coller dans la configuration du plugins sur Jeedom et de cocher la case **Je suis admin**
  
  ![image](https://user-images.githubusercontent.com/16257583/172942746-5d732433-b1dc-4e80-b8ca-93afb19aac02.png)

 ## La clef API Utilisateur : 
Pour accédée a la clef API utilisateur vous devez allée dans votre **profils** Pterodactyl 

Onglet **API Credentials**
Mettre une description (ici Jeedom)
Ensuite i vous le souhaité autorisé ou pas uniquement votre Jeedom (uniquement si IP FIXE et pas l'ip XXXX.eu.jeedom.link) dans notre cas nous ne l'utiliserons pas car pas utile.

![image](https://user-images.githubusercontent.com/16257583/172942846-02cc6611-b3d8-4ece-83ed-ff1c921da322.png)

Une fois la clef crée il suffit de la coller dans la configuration du plugins sur Jeedom et de **NE PAS** cocher la case **Je suis admin**
![image](https://user-images.githubusercontent.com/16257583/172942746-5d732433-b1dc-4e80-b8ca-93afb19aac02.png)


# Synchronisations
Pour la suite c'est comme tout les autre plugins, il faut aller dans **Plugins/Monitoring/Pterodactyl**
Si tout c'est bien passer les serveur sont crée automatiquement, sinon il suffit de cliquer sur **Synchronisation automatique**
![image](https://user-images.githubusercontent.com/16257583/172943041-90a4df24-2391-462e-8b92-74ed700247bd.png)

Et voila il resta a choisir sont objet et les commandes que vous souhaiter utilisé. 

# Index 

![image](https://user-images.githubusercontent.com/16257583/172943090-bb866d34-73ea-473f-b46d-68953921b098.png)
![image](https://user-images.githubusercontent.com/16257583/172943123-b99dbbed-ea2e-44d4-a5aa-cc9ee572f6d7.png)
![image](https://user-images.githubusercontent.com/16257583/172943153-8792cad2-4c47-4622-9b27-479f2fd985ce.png)

