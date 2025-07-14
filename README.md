# Exact Online, PHP8.4+


## Goals

1) A functional PHP8.4 library that facilitates communication between Exact Online and the implementing service.
2) PSR Above all, with the solo exception of `Doctrine\Collections` (We did not want to re-invent the wheel)
3) Uncoupled data layer from communication layer.  
   No need to add `$connection` into the data layer.
4) Extendable on things that matter.

This project uses [Doctrine Collections (Criteria)](https://github.com/doctrine/collections) as it's only forced
production library.  
If in the future, a PSR version of Selection Criteria were to be made available, this project will switch to that.  
For now, as everything we need it to do is pretty much fully cover.

> Note: Due to PISystems own requirements, the minimum version is set to `>=2.2.2`.

# Setup

## Construct [ExactConnectionManager](src/ExactConnectionManager.php)

Constructing the manager takes quite a few objects to be present before it can be done.

```php
class ExactConnectionManager {
   //...
   public function __construct(
      public readonly ExactAppConfigurationInterface     $appConfiguration,
      public readonly CacheItemPoolInterface             $cache,
      public readonly RequestFactoryInterface            $requestFactory,
      public readonly UriFactoryInterface                $uriFactory,
      public readonly ClientInterface                    $client,
      public readonly LoggerInterface                    $logger,
      null|EventDispatcherInterface|ExactEventDispatcher $dispatcher = null,
      public readonly SeededUuidProviderInterface $uuidProvider = new SeededUuidProvider()
    ) {}
   //...
}
```

### [ExactAppConfigurationInterface](src/Model/ExactAppConfigurationInterface.php)

Contains the information obtained by registering your application within Exact.  
Both the `test-app` and `production` app follow the same schema, there are no functional differences for this package.

Implementations available through:

- [DirectExactAppConfiguration](src/Util/DirectExactAppConfiguration.php) Simplest use-case (But requires the
  credentials to always be loaded/required.)
- [OnDemandAppConfiguration](src/Util/OnDemandAppConfigurationLoader.php) Better use-case (Only load the credentials
  when they are actually needed.)

```php
   // Example
   use PISystems\ExactOnline\Util\DirectExactAppConfiguration;  
   use PISystems\ExactOnline\Util\OnDemandAppConfigurationLoader;
   
   $loader = new OnDemandAppConfigurationLoader(
        new DirectExactAppConfiguration(
            clientId: $_ENV['EXACT_CLIENT_ID'] ?? throw new \RuntimeException("Cannot load EXACT_CLIENT_ID from \$_END, is \DotEnv loaded?"),
            redirectUri: $_ENV['EXACT_REDIRECT_URI'] ?? throw new \RuntimeException("Cannot load EXACT_REDIRECT_URI from \$_END, is \DotEnv loaded?"),
            clientSecret: $_ENV['EXACT_CLIENT_SECRET'] ?? throw new \RuntimeException("Cannot load EXACT_CLIENT_SECRET from \$_END, is \DotEnv loaded?"),
            webhookSecret: $_ENV['EXACT_WEBHOOK_SECRET'] ?? throw new \RuntimeException("Cannot load EXACT_WEBHOOK_SECRET from \$_END, is \DotEnv loaded?"),
        )
   );
`````

### [CacheItemPoolInterface](https://www.php-fig.org/psr/psr-6/)

Contains copies of called urls following specified time-out rules.  
This is to prevent one spamming Exact when calling the same list again within a certain time-span.

Implementation is available through:

- [SimpleFileCache](src/Polyfill/SimpleFileCache.php)

Suggest implementation:

- [`symfony/cache`](https://github.com/symfony/cache)

```php
    // Example
    use PISystems\ExactOnline\Polyfill\SimpleFileCache;
    
    $cache = new SimpleFileCache(sys_get_temp_dir());
```

### [RequestFactoryInterface, UriFactoryInterface](https://www.php-fig.org/psr/psr-17/)

Suggested Implementation:

- [`guzzle/guzzle`](https://github.com/guzzle/guzzle)

### [ClientInterface](https://www.php-fig.org/psr/psr-18/)

Suggest Implementation:  
- [`guzzle/guzzle`](https://github.com/guzzle/guzzle)

### [LoggerInterface](https://www.php-fig.org/psr/psr-3)

Available Implementations:

- [SimpleArrayLogger](src/Polyfill/SimpleAbstractLogger.php)
- [SimpleClosureLogger](src/Polyfill/SimpleClosureLogger.php)

Suggested Implementation:

- [`seldaek/monolog`](https://github.com/Seldaek/monolog)

### [EventDispatchInterface|ExactEventDispatcher](https://www.php-fig.org/psr/psr-14)

Available Implementation:

- [`ExactEventDispatcher`](src/Polyfill/ExactEventDispatcher.php)

Suggest Implementation

- [`symfony/event-dispatcher`](https://github.com/symfony/event-dispatcher)

### [SeededUuidProviderInterface](src/Model/SeededUuidProviderInterface.php)

We suggest to leave this alone, there is little point in doing this unless you need complete control over the generated
UUIDs.

Available Implementation:

- [`SeededUuidProvider`](src/Util/SeededUuidProvider.php)

## Implement the oAuth loop

Once the [ConnectionManager](src/ExactConnectionManager.php) has been constructed, several methods become available:

- [`->createRunTimeConfiguration`](src/ExactConnectionManager.php#generateOAuthUri)
- [`->generateOAuthUri`](src/ExactConnectionManager.php#generateOAuthUri)
- [`->create`](src/ExactConnectionManager.php#create)

There are several ways to implement the oAuth loop.  
The most straight forward would be saving the data to a local persistent storage and listening to changes.  
Then checking if we need to start from the oAuth URL, or we can go ahead and just resolve it all in the background.

```php
    // Example
    use PISystems\ExactOnline\ExactConnectionManager;
    use PISystems\ExactOnline\Events\CredentialsChange;
    use \PISystems\ExactOnline\Events\DivisionChange;
    
    $hasLocalConfiguration = false;
    // Best to load this from a container/use dependency injection.
    $manager = $container->get(ExactConnectionManager::class);
    
    // All entries may be null, nothing wrong with this.
    $data = [
        //'organizationAuthorizationCode' => null
        'organizationAccessToken' => null,
        'organizationAccessTokenExpires' => null,
        'organizationRefreshToken' => null,
        'division' => null
    ];
    
    if ($hasLocalConfiguration) {
        $data = $localConfigurationLoader();
    }

    // Whenever the AccessCode, TokenExpires or RefreshToken changes, this is called.
    $manager->dispatcher->addEventListener(
        CredentialsChange::class, 
        function(CredentialsChange $change) {
            // Handle persistence     
            // Do not persist the Authorization code, utterly pointless, It's valid for only a minute.
        }
    );

    // Whenever the division changes, this is called.
    $manager->dispatcher->addEventListener(
        DivisionChange::class,
        function(DivisionChange $change) {
            // Handle persistence
        }
    );

    $config = $manager->createRunTimeConfiguration(
        null, // AuthorizationCode (This should NOT be persisted!)
        ... array_values($data)
    );

    $exact = $manager->create($config);

    // This package cannot help you with this, the user must implement this part of the loop.
    // https://my.site/exact/webhook?source=<THIS IS THE CODE>&this_is_not=part_of_the_code
    $authCode = null; 

    if (!($authed = $exact->isAuthorized()) && !$authCode) {
        // How the redirection of the user and/or handling of the return message are handles is left to the user.  
        return $exact->oAuthUri();
    } else {
        if ($authed) {
            throw new \LogicException("Already authorized, this call makes no sense.");
        }
        $exact->setOrganizationAuthorizationCode(urldecode($authCode));
    }

    // Call *ANY* endpoint to ensure it works (As the first call, `$exact->getDivision()` is recommended).  
    // If nothing is called, the authorization code will expire!
    print "Division: ".$exact->getDivision();

```

# Calling simple lists

> A more complete example can be found
> [here](src/Command/ListDocumentTypeCategoriesExactCommand.php) and
> [here](src/Command/ListDocumentTypesExactCommand.php)

Lists are incredibly simple to retrieve, requiring nothing more than a simple `matching()` call.

The simplest structure would be something like:

> Warning: By default, the generator WILL NOT STOP unless it no longer has anything to yield, or it is told to stop.  
> It will continue giving results until exact stops giving results.  
> Ensure you set either `maxResults` in the criteria before calling, or aborting the generator when enough has
> been iterated.
>
> The loader will not load the next page until all entries in the page have been iterated over.
>
> Note: Using 'read all' mechanics should be avoided, even when using `bulk/sync`.
> Keep your own applications memory limit in mind!

```php

$exact = $container->get(\PISystems\ExactOnline\Exact::class);

$generator = $exact->matching(
    \PISystems\ExactOnline\Entity\Documents\DocumentTypeCategories::class
);

/** @var \PISystems\ExactOnline\Entity\Documents\DocumentTypeCategories $category */
foreach ($generator as $category) {
    //...
}

```

# Posting simple booking lines

> A more complete example can be found [here](src/Command/GenerateBookingExampleExactCommand.php)

```php
$exact = $container->get(\PISystems\ExactOnline\Exact::class);
$meta = \PISystems\ExactOnline\Entity\SalesEntry\SalesEntries::meta();
$entry = new PISystems\ExactOnline\Entity\SalesEntry\SalesEntries();

$entry->EntryID = $exact->uuid();
$entry->date = new \DateTimeImmutable();
//...
$entry->SalesEntryLines = [];

foreach ($lines as $line) {
    $entry->SalesEntryLines[] = $eLine = new \PISystems\ExactOnline\Entity\SalesEntry\SalesEntryLines();
    $eLine->EntryID = $entry->EntryID;
    //...
}

$bookingId = $exact->create($entry);
print $bookingId ? "Entry created" : "Entry not created"

```

# Creating an attachment to a booking

> A more complete example can be found [here](src/Command/UploadPdfFileToSalesEntryExactCommand.php)

```php
$exact = $container->get(\PISystems\ExactOnline\Exact::class);

// Are we even allowed to upload (Check before bothering exact)
$denyReason = null;
if (!$exact->fileUploadAllowed(new \SplFileInfo($myFile), $denyReason)) {
    throw new \RuntimeException("Upload denied due to: {$denyReason}");
}

$document = new \PISystems\ExactOnline\Entity\Documents\Documents();
$document->Type = 10;
$document->Subject = "Random upload";
$document->Account = 1; // The 'customer/ crm/account to attach to (Required) 

if (!($id = $exact->create($document)) {
    throw new \RuntimeException("Could not create document");
}

$attachment = new  PISystems\ExactOnline\Entity\Documents\DocumentAttachments();
$attachment->Document = $document
$attachment->Attachment = base64_encode(/*data*/);
$attachment->FileName = 'GloriousFile.png';

if (!($aid = $exact->create($attachment))) {
    throw new \RuntimeException("Could not create attachment / Upload file")
}

// Load this any way you want, its the result from the posting simple booking lines in this example
$entry -> Document = $aid;

$updated = $this->exact->update(
    $entry,
    ['Document']
)

```