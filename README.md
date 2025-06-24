# Exact Online, PHP8.4+


## Goals

1) A functional PHP8.4 library that facilitates communication between Exact Online and the implementing service.
2) PSR Above all, no forced additional libraries.  
   If it implements the PSR interfaces properly, it can be used in this project.  
   Don't want to use `guzzlehttp`* but your own/other library? That's your call!  
     
   *Note: Requirements are not enforced in dev, `guzzlehttp` is used in dev.
3) Uncoupled data layer from communication layer.  
   No need to add `$connection` into the data layer.
4) `Easy` rebuild of entity stacks, using custom templates.  