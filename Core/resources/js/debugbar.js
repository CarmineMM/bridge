document.addEventListener('DOMContentLoaded', () => {
    const debugbar = document.querySelector('#debug-bar');

    if (!debugbar) {
        return;
    }

    // Colocar espaciado al body
    document.body.style.paddingBottom = '40px';
});

// Alpine.js
document.addEventListener('alpine:init', () => {
    Alpine.data('debugbar', () => ({
        bodyOpen: false,

        options: {
            query: {
                text: 'Queries',
            },
        },

        selectedOption: {},

        toggleBody() {
            this.bodyOpen = !this.bodyOpen
        },

        selectOption(option) {
            this.selectedOption = this.options[option];
            this.bodyOpen = true;
        }
    }))
});