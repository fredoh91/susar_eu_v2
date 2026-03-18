 Bonjour,

  J'ai une application Symfony nommée susar_eu_v2 qui est hébergée sur le même serveur qu'une autre application
  Symfony. Pour éviter des conflits de session et corriger un bug de connexion, je souhaite appliquer les
  modifications suivantes :

   1. Donner un nom unique au cookie de session.
   2. Harmoniser le nom du champ de connexion (email) sur l'ensemble du processus d'authentification.

  Veuillez effectuer les modifications suivantes :

  1. Configurer un nom de session unique

  Dans le fichier config/packages/framework.yaml, remplacez la configuration de session actuelle par une
  configuration plus détaillée pour nommer le cookie de session SUSARSESSID.

  Code à remplacer :
   1     session: true

  Nouveau code :

   1     session:
   2         handler_id: null
   3         cookie_secure: auto
   4         cookie_samesite: lax
   5         storage_factory_id: session.storage.factory.native
   6         name: 'SUSAREUSESSID'

  2. Mettre à jour le formulaire de connexion

  Trouvez le formulaire de connexion de l'application (il se trouve probablement dans src/Form/ et son nom
  pourrait être LoginFormType.php, TogglePasswordForm.php ou similaire). Dans ce fichier, renommez le champ de
  formulaire _username en _email.

  Code à remplacer :
   1 ->add('_username', EmailType::class, [
   2     'label' => 'Email',
   3     // ... autres options
   4 ])

  Nouveau code :
   1 ->add('_email', EmailType::class, [
   2     'label' => 'Email',
   3     // ... autres options
   4 ])

  3. Mettre à jour la configuration de sécurité

  Dans le fichier config/packages/security.yaml, sous la clé form_login de votre pare-feu principal, mettez à
  jour le username_parameter pour qu'il corresponde au nouveau nom du champ.

  Code à remplacer :
   1                 username_parameter: _username

  Nouveau code :
   1                 username_parameter: _email

  4. Mettre à jour l'authentificateur

  Trouvez la classe de l'authentificateur (probablement dans src/Security/ et nommée UsersAuthenticator.php ou
  similaire). Dans la méthode authenticate, assurez-vous que l'email est bien récupéré depuis le champ _email
  de la requête.

  Code à remplacer (exemple basé sur le bug précédent) :

   1     public function authenticate(Request $request): Passport
   2     {
   3         $formData = $request->request->all('nom_du_formulaire');
   4         $email = $formData['email'] ?? '';
   5         // ...
   6     }
  ou

   1     public function authenticate(Request $request): Passport
   2     {
   3         $email = $request->request->get('_username', '');
   4         // ...
   5     }

  Nouveau code :

    1     public function authenticate(Request $request): Passport
    2     {
    3         $email = $request->request->get('_email', '');
    4         $password = $request->request->get('_password', '');
    5         $csrfToken = $request->request->get('_token', '');
    6
    7         $request->getSession()->set(SecurityRequestAttributes::LAST_USERNAME, $email);
    8
    9         return new Passport(
   10             new UserBadge($email, function ($userIdentifier) {
   11                 // Assurez-vous que cette ligne est correcte pour votre application
   12                 return $this->userRepository->findOneBy(['email' => $userIdentifier]);
   13             }),
   14             new PasswordCredentials($password),
   15             [
   16                 new CsrfTokenBadge('authenticate', $csrfToken),
   17                 new RememberMeBadge(),
   18             ]
   19         );
   20     }

  Merci d'appliquer ces quatre modifications.
