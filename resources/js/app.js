import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

window.togglePassword = function (inputId, openId, closeId) {
    const pass = document.getElementById(inputId);
    const eyeOpen = document.getElementById(openId);
    const eyeClose = document.getElementById(closeId);

    if (pass.type === 'password') {
        pass.type = 'text';
        eyeClose.classList.add('hidden');
        eyeOpen.classList.remove('hidden');
    } else {
        pass.type = 'password';
        eyeOpen.classList.add('hidden');
        eyeClose.classList.remove('hidden');
    }
};
