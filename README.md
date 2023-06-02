# 
## Installation

```bash
composer install state/walls-addon
```


## Wall types

#### NullWall
The NullWall type is an adhoc type for walling off content. This can be manually added to users for testing without doing a full integration.

### PaymentWall
The payment wall for Stripe is for one time payment purchases. Optionally you can specify an `expires_after` property to limit the time a user can access the walled content.



## Protecting Content
Walls uses statamic's built in [protector pattern](https://statamic.dev/protecting-content), to configure the protectors open `config/statamic/protect.php`
For each content type you wish to protect add new one with a unique key, set the 'driver' to 'walls' and set the 'allow' array to the wall keys that can access that content, and the redirect to where you'd like to redirect users without access to.

```php
...
'schemes' => [  
      
    ...  
      
    'basic' => [
        'driver' => 'walls',  
        'allowed' => ['basic', 'plus'],  
        'redirect_url' => '/join',  
    ],

    'plus' => [
        'driver' => 'walls',
        'allow' => ['plus'],
        'redirect' => '/join'
    ],
],
```

In this example we have a `basic` and a `plus` protector specified, the basic allows both basic and plus users to view whatever content is protected by the `basic` scheme. The `plus` only allows users with the `plus` wall on their user.

To protect a collection open the collection's yaml file and add your protector:
```yaml
title: Plus Only Posts
inject:  
  protect: plus
```
To learn more about protectors I recommend reviewing the statamic docs.

## Tags


#### Head tag
For convenience there is the `{{ walls:head }}` tag that should be placed within your template head. This will include a CSRF token and your Stripe publishable key as meta tags, along with a script tag importing stripe-js.
```antlers
<head>
...
    {{ walls:head }}
</head>
```
Output:
```html
<head>
...
    <meta name="csrf_token" content="****">  
    <meta name="stripe_publishable_key" content="****">  
    <script src="https://js.stripe.com/v3/"></script>
</head>
```

### Cart

#### Add to cart
The add-to-cart tag will render an html form with a single button that will add the product specified to the cart.
```antlers
{{
    walls:add-to-cart
    class="btn"
    redirect="/checkout"
    wall="ecourse"
    text="Buy now"
}}
```

#### Remove from cart
```antlers
{{
    walls:remove-from-cart
    class="btn"
    redirect="/join"
    wall="ecourse"
    text="Remove"
}}
```


##### Parameters
- wall (required)
    - the handle within the walls collection
- class
    - classes that are applied to the html button
- redirect
    - the url to redirect after successful request
    - default behavior: redirects back to the previous page
- text
    - changes the text displayed in the button.
    - default: "Add to cart" / "Remove from cart"


### Cart
The cart tag gives you access to the cart data. It returns a total along with each item in your cart containing entries from the walls collection.
```antlers
<h3>Cart</h3>  
{{ walls:cart }}  
    {{ items }}  
        {{ title }}
        {{ price }}
    {{ /items }}  
    <p>total: ${{ total }}</p>  
{{ /walls:cart }}  
```

### Owned
The owned tag returns an array of walls on the user, including the entry from the walls collection.
```antlers
{{ walls:owned }}  
    {{ entry }}  
        <a href="{{ wall_home:url }}">  
            {{ title }}  
        </a>  
    {{ /entry }}  
{{ /walls:owned }}
```


## Manage cart via Ajax

```js

// Add to cart.
fetch('/walls/cart/add', {
    headers: {'Content-Type': 'application/json', 'Accept': 'application/json'},
    body: JSON.stringify({
        _token: document.querySelector(`meta[name=csrf_token]`).content,
        wall: 'ecourse',
    }),
});

// Remove from cart
fetch('/walls/cart/remove', {
    headers: {'Content-Type': 'application/json', 'Accept': 'application/json'},
    body: JSON.stringify({
        _token: document.querySelector(`meta[name=csrf_token]`).content,
        wall: 'ecourse',
    }),
});
```

