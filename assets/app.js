/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */
import 'bootstrap';
// any CSS you import will output into a single css file (app.css in this case)
// import './styles/app.css';
import './styles/app.scss';

import { startStimulusApp } from '@symfony/stimulus-bridge';
// import '@symfony/autoimport';

// Importez le contrôleur UX Toggle Password
import '@symfony/ux-toggle-password';

// Démarrez l'application Stimulus
const app = startStimulusApp(require.context(
    '@symfony/stimulus-bridge/lazy-controller-loader!./controllers',
    true,
    /\.[jt]s$/
));

