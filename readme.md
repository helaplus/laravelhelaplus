# Laravelhelaplus

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Total Downloads][ico-downloads]][link-downloads]
[![Build Status][ico-travis]][link-travis]
[![StyleCI][ico-styleci]][link-styleci]

This is where your description should go. Take a look at [contributing.md](contributing.md) to see a to do list.

## Installation

Via Composer

``` bash
$ composer require helaplus/laravelhelaplus
```
Add the following Configs
``` bash
B2B_INITIATOR="{your_initiator}"
B2B_PASSWORD="{your_password}"
B2B_SOURCE={source_paybll}
B2B_RESULT_URL={result_url}
B2B_CALLBACK_URL={callback_url}
B2B_SECURITY_CREDENTIAL={security_credential}
HELAPLUS_API_TOKEN="your_helaplus_api_token"
HELAPLUS_B2B_ENDPOINT={helaplus_endpoint}
```


Publish the config file. This will publish the config file to config/laravelhelaplus


``` bash
$ php artisan vendor:publish 

Then select laravelhelaplus

$ php artisan migrate 

 //This will create a table for helaplus_transactions
```

## Usage

``` bash
use Helaplus\Laravelhelaplus\Http\B2BPaymentController;

$response = B2BPaymentController::sendB2BPayment($amount,$recipient_paybill,$commandId,$reference);
     
```

## Change log

Please see the [changelog](changelog.md) for more information on what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [contributing.md](contributing.md) for details and a todolist.

## Security

If you discover any security related issues, please email author@email.com instead of using the issue tracker.

## Credits

- [Author Name][link-author]
- [All Contributors][link-contributors]

## License

MIT. Please see the [license file](license.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/helaplus/laravelhelaplus.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/helaplus/laravelhelaplus.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/helaplus/laravelhelaplus/master.svg?style=flat-square
[ico-styleci]: https://styleci.io/repos/12345678/shield

[link-packagist]: https://packagist.org/packages/helaplus/laravelhelaplus
[link-downloads]: https://packagist.org/packages/helaplus/laravelhelaplus
[link-travis]: https://travis-ci.org/helaplus/laravelhelaplus
[link-styleci]: https://styleci.io/repos/12345678
[link-author]: https://github.com/helaplus
[link-contributors]: ../../contributors
