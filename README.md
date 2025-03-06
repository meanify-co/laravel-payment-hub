<p align="center">
  <a href="https://www.meanify.co?from=github&lib=laravel-payment-hub">
    <img src="https://meanify.co/assets/img/logo/png/meanify_color_dark_horizontal_02.png" width="200" alt="Meanify Logo" />
  </a>
</p>


# Laravel Payment Hub
A PHP library for centralizing integrations with multiple payment gateways, providing a unified API for seamless transactions across platforms.

## Installation:

Install this package with composer:

~~~
composer require meanify-co/laravel-payment-hub
~~~

### Feature comparison:

| Gateway      | Status      | Available versions |
|--------------|-------------|--------------------|
| Mercado Pago | Developing  | v1                 |
| Pagar.me     | Coming soon | v5                 |
| Stripe       | Coming soon | -                  |
| PayPal       | Coming soon | -                  |
| PagSeguro    | Coming soon | -                  |
| Vindi        | Coming soon | -                  |


### Methods by gateway:


| Methods                                      | Mercado Pago  (v1)  | Pagar.me (v5) | 
|----------------------------------------------|---------------------|---------------|
| `card()->get()`                              | ❌                   | ❌             |
| `card()->create()`                           | ❌                   | ❌             |
| `card()->delete()`                           | ❌                   | ❌             |
| `customer()->get()`                          | ❌                   | ❌             |
| `customer()->create()`                       | ❌                   | ❌             |
| `customer()->update()`                       | ❌                   | ❌             |
| `payment()->get()`                           | ❌                   | ❌             |
| `payment()->createCreditCardTransaction()`   | ❌                   | ❌             |
| `payment()->createDebitCardTransaction()`    | ❌                   | ❌             |
| `payment()->createBankSlipTransaction()`     | ❌                   | ❌             |
| `payment()->createPixTransaction()`          | ❌                   | ❌             |
| `payment()->refundCreditCardTransaction()`   | ❌                   | ❌             |
| `payment()->getPayableFromPaidTransaction()` | ❌                   | ❌             |
| `payment()->getPixInfoFromPixTransaction()`  | ❌                   | ❌             |
| `postback()->handle()`                       | ❌                   | ❌             |
| `postback()->getRefuseReasonByCode()`        | ❌                   | ❌             |



### Basic usage: 

1. Instantiate the factory class by providing parameters such as the preferred gateway, gateway version, account environment and additional data (such as access token or secret key).


~~~
# Example instance for MercadoPago

$handler = new \Meanify\LaravelPaymentHub\Factory('mercado-pago','v5','sandbox', ['secret_key' => 'MY_SCRET_KEY']);

//or

$handler = meanifyPaymentHub('mercado-pago','v5','sandbox', ['secret_key' => 'MY_SCRET_KEY']);


~~~

2. Call the method according to the feature comparison table.

~~~
# Example for create customer

$data = (object) [
    'first_name'      => 'Fulano',
    'last_name'       => 'de Tal',
    'document_type'   => 'cpf',
    'document_number' => '00011122233',
    'email'           => 'customer@example.com',
    'birth_date'      => '1990-01-01',
    'address' => (object) [
        'line_1'        => 'Av XYZ',
        'line_2'        => '100',
        'neighborhood'  => 'Centro',
        'postal_code'   => '01100000',
        'city'          => 'São Paulo',
        'state_code'    => 'SP',
        'country_code'  => 'BR',
    ],
    'phone' => (object) [
        'country_code'  => '+55',
        'area_code'     => '11',
        'number'        => '999999999',
    ],
];

$handler->customer()
  ->create($data)
  ->send(); #execute this method to send data to Gateway API
  
~~~

Other examples is availables in [Tests](https://github.com/meanify-co/laravel-payment-hub/tree/master/tests)
