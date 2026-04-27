import Choices from 'choices.js';
// import persist from '@alpinejs/persist'; // Only if needed

window.Choices = Choices;

// In Livewire 3, we don't call Livewire.start() manually if auto-injection is on.
// We just register our global dependencies and plugins.
document.addEventListener('livewire:init', () => {
    // Livewire and Alpine are available here
});

