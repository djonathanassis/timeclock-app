import './bootstrap';

import Alpine from 'alpinejs';
import mask from '@alpinejs/mask';

// Importar o script do formulário de funcionários
import './employee-form';

// Registrar plugins do Alpine.js
Alpine.plugin(mask);

window.Alpine = Alpine;

Alpine.start();
