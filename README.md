# Laravel-Backend-Developer-Task

**Task 1: Communication:**

The table and chart are given as .xlsx and .png 

**Task 2: Eloquent:**

a. The performance is improved by using Eloquent's ```with()``` for eager loading related data (customer and items), minimizing ```N+1``` query issues. The ```withCount()``` method is used to calculate the number of items directly in the query, and the total amount is computed using the ```sum()``` method on the collection. Sorting is handled in-memory using Laravel's collection methods instead of performing multiple queries inside loops.

b. The refactored code eliminates redundant queries by fetching all necessary data in a single step and avoids looping over items unnecessarily. Calculations are done using collection methods to enhance readability and performance. Further improvements could include caching query results or moving complex logic to database views or stored procedures for even better performance.

The refactored code is in the Eloquent.php file

**Task 3: Testing**

The refactored code is in the SpreadSheetService.php file
