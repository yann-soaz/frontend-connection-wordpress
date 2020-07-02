# frontend-connection-wordpress

  ## plugin de connection front-end pour wordpress
   Ce plugin vous permet de créer facilement une connection front-end sur wordpress ainsi que de bloquer l'accès à wp-admin aux utilisateurs n'étant pas administrateur.
  
  ## shortcode
    ```
     [login]
    ```
    Shortcode affichant le formulaire de connexion/création de compte
  
  ## options
  
    | bloquer l'administration                                                  | case à cocher      | booléen                      |
    | permettre les inscriptions en front-end                                   | case à cocher      | booléen                      |
    | url de redirection (si connecté)                                          | liste de selection | pages du wordpress           |
    | url de la page de connection (en remplacement de wp-login.php)            | liste de selection | pages du wordpress           |
    | roles utilisateur pour lesquels l'administration wordpress est accessible | cases à cocher     | liste des rôles du wordpress |
    | role des nouveaux comptes créés                                           | radios             | liste des rôles du wordpress |
  
  ## fonctions disponnibles
    | ```function user_has_role (string $role)``` | défini si l'utilisateur possède le role fourni en paramètre                          | $role (string) : nom machine du role à vérifier | booléen |
    | ```function allowed_to_see_admin ()```      | retourne vrai si l'utilisateur à l'autorisation d'accès à l'administration wordpress | -                                                  | booléen |
