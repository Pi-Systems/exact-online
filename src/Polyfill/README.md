# Polyfill

This contains a collection of simple classes that implement the PSR interfaces.

These can be used as is, but are recommended to be superseded by more functional implementations.


 - [ExactEventDispatcher](ExactEventDispatcher.php)  
   Acts as the primary event layer for all events within the project.  
   One may add anything that implements [Psr\EventDispatcher\EventDispatcher](https://www.php-fig.org/psr/psr-14/)
   The library will simply wrap around it and use it as-is.
 - [FormStream](FormStream.php) Is a simple wrapper around [Psr7\Messages](https://www.php-fig.org/psr/psr-7/), it's only goal is to facilitate the occasianal form entries the library has to deal with.  
   This class is used internally by `loadAdministration`.
 - [JsonDataStream](JsonDataStream.php), similar to the above `FormStream`, except the body is not just a simple json encoded string.
 - [SimpleArrayLogger](SimpleArrayLogger.php) Used as the logging interface if no logger is present.  
  Ensures log calls do not fail, and one can always still see what happened.
 - [SimpleClosureLogger](SimpleArrayLogger.php) Used during (re)-building to (poorly) attach a console printer.  
  Feel free to use it use it, but it is highly recommended to use [monolog](https://github.com/Seldaek/monolog) instead.
 - [SimpleFileCache](SimpleFileCache.php) A simple caching implementation.  
   Note: The pages downloaded from [exact](https://docs.exactonline.nl) are stored locally in this format.
   Extract [ExactDocumentationCache.zip](../Resources/ExactDocumentationCache.zip) and use its `ExactDocumentationCache` folder as the folder.  
   Note: DO NOT ATTACH TO THE `default` FOLDER, that would be the pool, not the cache.
 - [Validation::is_guid](Validation.php) Honestly, I could really find a way to make this make sense using globals/psr.  
  If the method `is_guid` exists in global scope, it will use that, else it will perform a regex check.  
