import './bootstrap';

// Importa estilos si usas Tailwind/SCSS (ya gestionados por Laravel Breeze)
// import '../css/app.css';

// Importar Firebase para habilitar el botón de Google en login/registro
import './firebase';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();
