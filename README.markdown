Simple implementation of Data Access Object pattern for symfony 2 projects.

 * Has unit tests: yes
 * Vendors: doctrine-common


Advantages
-----------------

 * allows you to quickly switch between the ORM and ODM doctrine managers
 * promote clean and tested code. all of the business application logic should be in the services


Installation
-----------------

If you use a deps file, you could add:

 <pre>
 [dao]
     git=https://github.com/fightmaster/dao.git
 </pre>

Or if you want to clone the repos:

 <pre>
 git clone https://github.com/fightmaster/dao.git vendor/dao
 </pre>

If you use Composer, you could add:

```
{"require": {"fightmaster/dao": "1.x"}}
```

Add the namespace to your autoloader

```php
<?php
 $loader->registerNamespaces(array(
     ............
     'Fightmaster'   => __DIR__.'/../vendor/dao/src',
     ...........
 ));

```

Examples
-----------------

Example service layer

```php
<?php
.....
Class ProductService extends Service
{
    public function __construct(ManagerInterface $manager)
    {
        $this->manager = $manager;
    }
    ....
    public function saveProduct(Product $product)
    {
        $prePersistEvent = new PrePersistEvent($product);
        $this->dispatcher->dispatch('product_pre_persist', $prePersistEvent);
        if (!$prePersistEvent->isAborted()) {
            $this->manager->save($product);
        }
        $postPersistEvent = new PostPersistEvent($product);
        $this->dispatcher->dispatch('product_post_persist', $postPersistEvent);
    }

    public function changeProductName(Product $product, $newName)
    {
        ....
        $product->setName($newName);
        $this->saveProduct($product);
    }
}

```
