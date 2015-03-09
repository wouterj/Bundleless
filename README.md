# Symfony without a Bundle? Welcome to Bundleless!

This is a very simple proof of concept. This package basically provides a bundle
class which can be used to create "virtual" bundles. Virtual bundles only
exists for the Symfony kernel, but for you it's just a package which uses
bundle conventions (like automatic mapping for `Entity/`).

This can be used to remove the "bundle" from AppBundle. Let me tell you how to
get this working for your application.

## Install

This is pretty simple (if you're using [Composer](http://getcomposer.org/)):

    $ composer require wouterj/bundleless:1.*@dev

## Edit

Use the new `WouterJ\Bundleless\AppFocusedKernel` as parent of your `AppBundle`:

```php
// app/AppKernel.php

use WouterJ\Bundleless\AppFocusedKernel;

// ...
class AppKernel extends AppFocusedKernel
{
}
```

Then, remove that ugly `AppBundle` register line from your `AppKernel`.
Bundeless will take care of it now.

```php
// app/AppKernel.php

// ...
public function registerBundles()
{
    $bundles = array(
        // ...
        // comment or remove
        // new AppBundle\AppBundle();
}
```

## Use

You're already ready! You should move your application code outside of the
`AppKernel` bundle by removing the namespace and putting it in `src/` directly.
For instance:

```php
// src/Controller/StaticController.php
namespace Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class StaticController extends Controller
{
    /**
     * @Route("/")
     */
    public function homepageAction()
    {
        return $this->render('static/homepage.html.twig');
    }
}
```

```yaml
# app/config/routing.yml
app:
    resource: "@App/Controller"
    type: annotation
```

That's it. Apart from the template file, you now have a working homepage!

## Customize

You can customize 2 things in your `AppKernel` by defining properties:

* `$appPath` (default: `%kernel.root_dir%/../src`): The root path for your
  application code. You can use the familair `%kernel.root_dir%` parameter;

* `$appNamespace` (default: `''`): The root namespace of your application code.
  You might want to set this to `App` to avoid naming conflicts.

## License

This project is released under the MIT license, it's just 2 files anyway.
