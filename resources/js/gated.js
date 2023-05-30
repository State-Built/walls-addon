// A reference to Stripe.js initialized with a fake API key.
const stripe = Stripe(meta('stripe_publishable_key'));

let elements;


let paymentForm = document
    .querySelector('#payment-form');

if(paymentForm) {
    initialize();
    checkStatus();

    paymentForm.addEventListener('submit', handleSubmit);
}


// Fetches a payment intent and captures the client secret
async function initialize() {
    const {clientSecret} = await requestJson('gated/payment-intent');

    elements = stripe.elements({clientSecret});

    const paymentElement = elements.create('payment');
    paymentElement.mount('#payment-element');
}

async function handleSubmit(e) {
    e.preventDefault();
    setLoading(true);

    const {error} = await stripe.confirmPayment({
        elements,
        confirmParams: {
            return_url: document.querySelector('[name=stripe_return_url]').value,
        },
    });

    // This point will only be reached if there is an immediate error when
    // confirming the payment. Otherwise, your customer will be redirected to
    // your `return_url`. For some payment methods like iDEAL, your customer will
    // be redirected to an intermediate site first to authorize the payment, then
    // redirected to the `return_url`.
    if(error.type === 'card_error' || error.type === 'validation_error') {
        showMessage(error.message);
    }
    else {
        showMessage('An unexpected error occured.');
    }

    setLoading(false);
}

// Fetches the payment intent status after payment submission
async function checkStatus() {
    const clientSecret = new URLSearchParams(window.location.search).get(
        'payment_intent_client_secret',
    );

    if(!clientSecret) {
        return;
    }

    const {paymentIntent} = await stripe.retrievePaymentIntent(clientSecret);

    switch(paymentIntent.status) {
        case 'succeeded':
            showMessage('Payment succeeded!');
            break;
        case 'processing':
            showMessage('Your payment is processing.');
            break;
        case 'requires_payment_method':
            showMessage('Your payment was not successful, please try again.');
            break;
        default:
            showMessage('Something went wrong.');
            break;
    }
}

// ------- UI helpers -------

function showMessage(messageText) {
    const messageContainer = document.querySelector('#payment-message');

    messageContainer.classList.remove('hidden');
    messageContainer.textContent = messageText;

    setTimeout(function() {
        messageContainer.classList.add('hidden');
        messageText.textContent = '';
    }, 4000);
}

// Show a spinner on payment submission
function setLoading(isLoading) {
    if(isLoading) {
        // Disable the button and show a spinner
        document.querySelector('#submit').disabled = true;
        document.querySelector('#spinner').classList.remove('hidden');
        document.querySelector('#button-text').classList.add('hidden');
    }
    else {
        document.querySelector('#submit').disabled = false;
        document.querySelector('#spinner').classList.add('hidden');
        document.querySelector('#button-text').classList.remove('hidden');
    }
}


function meta(key) {
    return document.querySelector(`meta[name=${key}]`).content;
}

async function requestJson(url, body = {}, method = 'POST') {
    return await fetch(url, {
        method: method,
        headers: {'Content-Type': 'application/json', 'Accept': 'application/json'},
        body: JSON.stringify({
            _token: meta('csrf_token'), ...body
        }),
    }).then(r => r.json())
}