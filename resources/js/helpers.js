async function addToCart(gate) {
    await requestJson('/gated/cart/add', {gate});
}

async function removeFromCart(gate) {
    await requestJson('/gated/cart/remove', {gate});
}

let meta = (key) => document.querySelector(`meta[name=${key}]`).content;

async function requestJson(url, body = {}, method = 'POST') {
    return await fetch(url, {
        method: method,
        headers: {'Content-Type': 'application/json', 'Accept': 'application/json'},
        body: JSON.stringify({
            _token: meta('csrf_token'), ...body
        }),
    }).then(r => r.json())
}

export {addToCart, meta, requestJson, removeFromCart}