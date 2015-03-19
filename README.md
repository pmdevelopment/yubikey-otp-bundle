Yubikey OTP Symfony2 Bundle
=====================

## Install

Add to composer:

```js
  composer require "pmdevelopment/yubikey-otp-bundle" "dev-master"
```

Add to kernel:

```php
  new PM\Bundle\YubikeyOtpBundle\PMYubikeyOtpBundle(),
```

Add to config.yml

```yml
    pm_yubikey_otp:
      server:
        host: https://api2.yubico.com/wsapi/2.0/
        client_id: YourClientId
        client_secret: YourApiKey
```

## Usage

Now you can validate any a OTP by using a service

```php
   $this->get("pm_yubikey_otp.validation")->verify("YourOtpToValidate", "OptionalTheIdentityYouExpect");
```
