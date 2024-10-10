document.addEventListener('alpine:init', () => {
    /**
     * Componentes de Full Bridge
     */
    Alpine.data('full-bridge-component', (receivedData) => {
        return {
            loading: false,
            data: {},
        };
    });
});