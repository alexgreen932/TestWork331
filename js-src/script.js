import weatherApi from './weatherApi.js';
import autoLatLong from './autoLatLong.js';

function build() {
    weatherApi();
    autoLatLong(); 
}

function initComponentsOnNewDOMElements() {
    const observer = new MutationObserver((mutationsList) => {
        mutationsList.forEach(mutation => {
            if (mutation.type === 'childList') {
                mutation.addedNodes.forEach(node => {
                    if (node.nodeType === 1) {
                        weatherApi(); // Run weather API on new elements
                    }
                });
            }
        });
    });

    observer.observe(document.body, { childList: true, subtree: true });
}

document.addEventListener('DOMContentLoaded', () => {
    build();
    initComponentsOnNewDOMElements();
});
