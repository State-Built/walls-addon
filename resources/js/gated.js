import './checkout';

import {addToCart, removeFromCart} from './helpers';

document.onreadystatechange = function () {
    if (document.readyState === "interactive") {
        init();
    }
}

function init() {
    let addToCartButtons = document.querySelectorAll('[data-gated]');
    let removeFromCartButtons = document.querySelectorAll('[data-gated-remove]');

    addToCartButtons.forEach(btn => {
        btn.addEventListener('click', async () => {
            await addToCart(btn.dataset.gate);

            btn.dispatchEvent(new CustomEvent('gated:addedToCart', {
                detail: {
                    gate: btn.dataset.gate
                }
            }));
        });
    });

    removeFromCartButtons.forEach(btn => {
        btn.addEventListener('click', async() => {
            await removeFromCart(btn.dataset.gate);

            btn.dispatchEvent(new CustomEvent('gated:removedFromCart', {
                detail: {
                    gate: btn.dataset.gate
                }
            }));
        });
    })

}
