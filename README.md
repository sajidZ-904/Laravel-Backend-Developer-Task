# Laravel-Backend-Developer-Task

**Task 1: Communication:**

The table and chart are given as .xlsx and .png 

**Task 2: Eloquent:**

a. The performance is improved by using Eloquent's ```with()``` for eager loading related data (customer and items), minimizing ```N+1``` query issues. The ```withCount()``` method is used to calculate the number of items directly in the query, and the total amount is computed using the ```sum()``` method on the collection. Sorting is handled in-memory using Laravel's collection methods instead of performing multiple queries inside loops.

b. The refactored code eliminates redundant queries by fetching all necessary data in a single step and avoids looping over items unnecessarily. Calculations are done using collection methods to enhance readability and performance. Further improvements could include caching query results or moving complex logic to database views or stored procedures for even better performance.

The refactored code is in the Eloquent.php file

**Task 3: Testing**

The refactored code is in the SpreadSheetService.php file

**Task 4: Collections**

The complete code is in the Collections.php file

**Task 5: Q&A**

a. The provided code snippet is a scheduling configuration in Laravel using the Schedule facade. It defines a command named 'app:example-command' that is scheduled to run hourly. The withoutOverlapping() method ensures that if the command takes longer than one hour to complete, it will not start a new instance until the previous one has finished, preventing potential conflicts or overlapping executions. The onOneServer() method specifies that the command should only be executed on a single server in a multi-server setup, ensuring that it does not run concurrently on multiple servers. Finally, the runInBackground() method allows the command to run asynchronously, meaning the scheduler will not wait for it to finish before proceeding to the next scheduled task, enhancing overall efficiency and responsiveness of the application.

b. 

**Context Facade**: The Context facade is not a default part of Laravel but can refer to various implementations for managing application state or context. It is often used to encapsulate a set of related functionalities or data, making it easier to manage the application's current state, user context, or other operational parameters.

Suppose you have a Context facade to handle user sessions or application state:

```bash
  <?php
  namespace App\Facades;

  use Illuminate\Support\Facades\Facade;
  
  class Context extends Facade
  {
      protected static function getFacadeAccessor()
      {
          return 'context';
      }
  }
  
  Context::set('user_id', $userId);
  $userId = Context::get('user_id');

```

In the above example, the Context facade allows setting and retrieving the current user ID for the application, encapsulating related session data in a centralized manner.


**Cache Facade**: The Cache facade is a built-in part of Laravel that provides a unified API for various caching systems, allowing you to store, retrieve, and manage cached data efficiently. It is commonly used to improve application performance by reducing database queries and reusing frequently accessed data.

You can use Cache Facade to store and retrieve data:

```bash
  <?php
  use Illuminate\Support\Facades\Cache;

  Cache::put('user_1', $user, 600);
  
  $user = Cache::get('user_1');
  
  if (Cache::has('user_1')) {
    // The key exists
  }

```

In the above example, the Cache Facade is used to store a user object in the cache for 10 minutes and retrieve it later, helping to minimize database access and speed up application responses.

c.

**$query->update()**: This method is called on a query builder instance, and it updates all records that match the query criteria directly in the database.

**When to use**: Use ```$query->update()``` when you need to update multiple records at once based on specific conditions without needing to instantiate model instances. It’s efficient for bulk updates because it performs a single SQL query without loading the models into memory.

**$model->update()**: This method is called on an instance of a model. It updates the record associated with that model instance and triggers Eloquent model events (like  ```updating``` and ```updated```).

**When to use**: Use ```$model->update()``` when you have an instance of a model and want to update its attributes. This method is useful when you want to utilize Eloquent's features, such as event handling or model accessors and mutators.

**$model->updateQuietly()**: Similar to ```$model->update()```, this method updates the model instance but does not trigger any model events (such as ```updating``` and ```updated```).

**When to use**: Use ```$model->updateQuietly()``` when you need to perform an update without firing events. This can be useful in scenarios where you want to avoid event listeners or when performance is critical, and you know that the model's state doesn't require any additional processing.
