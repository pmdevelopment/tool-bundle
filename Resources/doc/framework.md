# Framework

Some core features.

## PMController

The methods "saved" and "denied" add given messages to the flashbag and redirect to given route. When using [JMS Object-Routing](http://jmsyst.com/libs/object-routing) 
use "savedObject" and "deniedObject" to redirect by object routing.

## Repositories

### SoftdeleteRepository
Provides methods for softdeleteable entities, using "deleted" as boolean.

## Utilities

### CryptUtility

Static methods to encrypt and decrypt using AES256.

### SeoUtility

Basic static method to slug strings, e.g. convert "Hello, world" to "hello-world" for usage as path.

### SepaUtility

A new and very simple class, at the moment only providing a "slug" function to convert characters not supported by SEPA, like "&".

### SoftdeleteUtility

Used for X-To-Many relations to get all relations where deleted is false

```php

    /**
     * @var Dummy[]|Collection
     *
     * @ORM\OneToMany(targetEntity="Acme\DummyBundle\Entity\Dummy", mappedBy="foobar")
     */
    private $dummies;
    
     /**
      * @return Collection|Dummy[]
      */
     public function getDummies()
     {
        return SoftdeleteUtility::getNotDeleted($this->dummies);
     }
```