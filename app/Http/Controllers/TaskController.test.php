// Retrieve a list of tasks with filtering, sorting, and pagination options.
public function test_retrieve_tasks_with_options()
{
// Create some tasks
$task1 = Task::factory()->create(['is_done' => true]);
$task2 = Task::factory()->create(['is_done' => false]);
$task3 = Task::factory()->create(['is_done' => true]);

// Send a GET request to the index endpoint with filtering, sorting, and pagination options
$response = $this->get('/tasks?filter[is_done]=true&sort=-created_at&page=2');

// Assert that the response has a successful status code
$response->assertOk();

// Assert that the response contains the correct number of tasks
$response->assertJsonCount(2, 'data');

// Assert that the response contains the correct tasks in the correct order
$response->assertJson([
'data' => [
['id' => $task3->id],
['id' => $task1->id],
]
]);
// Successfully retrieve a single task by its ID.
public function test_retrieve_single_task_by_id()
{
// Create a new task
$task = Task::factory()->create();

// Send a GET request to the show method with the task ID
$response = $this->get('/tasks/' . $task->id);

// Assert that the response has a 200 status code
$response->assertStatus(200);

// Assert that the response body contains the task data
$response->assertJson([
'data' => [
'id' => $task->id,
'title' => $task->title,
'is_done' => $task->is_done,
'created_at' => $task->created_at->toISOString(),
'updated_at' => $task->updated_at->toISOString(),
]
]);
}


// Successfully retrieve a list of tasks with filtering, sorting, and pagination options.
public function test_retrieve_tasks_with_options()
{
// Create some tasks
$tasks = Task::factory()->count(5)->create();

// Send a GET request to the index endpoint with filtering, sorting, and pagination options
$response = $this->get('/tasks?filter[is_done]=1&sort=-created_at&page=2');

// Assert that the response has a successful status code
$response->assertOk();

// Assert that the response contains the correct number of tasks
$response->assertJsonCount(5, 'data');

// Assert that the response contains the correct task data
$response->assertJsonStructure([
'data' => [
'*' => [
'id',
'title',
'is_done',
'created_at',
'updated_at'
]
]
]);
}


// Successfully create a new task using the validated data from the request.
public function test_create_new_task_successfully()
{
// Arrange
$request = new StoreTaskRequest();
$request->merge([
'title' => 'New Task',
'description' => 'This is a new task',
'is_done' => false
]);

// Act
$response = $this->post('/tasks', $request->all());

// Assert
$response->assertStatus(201);
$response->assertJson([
'data' => [
'title' => 'New Task',
'description' => 'This is a new task',
'is_done' => false
]
]);
}


// Successfully delete a task.
/**
* Test deleting a task successfully.
*
* @return void
*/
public function testDeleteTaskSuccessfully()
{
// Create a task
$task = Task::factory()->create();

// Send a DELETE request to the delete endpoint
$response = $this->delete('/tasks/' . $task->id);

// Assert that the response has a 204 status code
$response->assertStatus(204);

// Assert that the task has been deleted from the database
$this->assertDeleted($task);
}


// Successfully update an existing task using the validated data from the request.
public function test_update_existing_task_successfully()
{
// Create a new task
$task = Task::factory()->create();

// Generate a random title for the updated task
$updatedTitle = Str::random(10);

// Send a PUT request to update the task with the new title
$response = $this->put('/tasks/' . $task->id, ['title' => $updatedTitle]);

// Assert that the response has a successful status code
$response->assertStatus(200);

// Assert that the task has been updated with the new title
$this->assertDatabaseHas('tasks', ['id' => $task->id, 'title' => $updatedTitle]);
}


// Retrieve a list of tasks with default sorting, filtering, and pagination options.
public function test_retrieve_tasks_with_default_options()
{
$response = $this->get('/tasks');
$response->assertStatus(200);
$response->assertJsonStructure([
'data' => [
'*' => [
'id',
'title',
'description',
'is_done',
'created_at',
'updated_at'
]
],
'links' => [
'first',
'last',
'prev',
'next'
],
'meta' => [
'current_page',
'from',
'last_page',
'path',
'per_page',
'to',
'total'
]
]);
}


// Retrieve a single task by its ID.
public function testRetrieveSingleTaskById()
{
// Create a new task
$task = Task::factory()->create();

// Send a GET request to the show method with the task ID
$response = $this->get('/tasks/' . $task->id);

// Assert that the response has a 200 status code
$response->assertStatus(200);

// Assert that the response body contains the task data
$response->assertJson([
'data' => [
'id' => $task->id,
'title' => $task->title,
'is_done' => $task->is_done,
'created_at' => $task->created_at->toISOString(),
'updated_at' => $task->updated_at->toISOString(),
]
]);
}


// Create a new task using the validated data from the request.
public function test_create_new_task()
{
// Arrange
$request = new StoreTaskRequest();
$request->merge([
'title' => 'New Task',
'description' => 'This is a new task',
'is_done' => false
]);

// Act
$response = $this->post('/tasks', $request->all());

// Assert
$response->assertStatus(201);
$response->assertJson([
'title' => 'New Task',
'description' => 'This is a new task',
'is_done' => false
]);
}


// Delete a task.
public function test_delete_task()
{
// Create a new task
$task = Task::factory()->create();

// Send a DELETE request to the delete endpoint
$response = $this->delete('/tasks/' . $task->id);

// Assert that the response has a 204 status code
$response->assertStatus(204);

// Assert that the task has been deleted from the database
$this->assertDeleted($task);
}


// Test filtering with invalid parameters.
public function test_filtering_with_invalid_parameters()
{
$response = $this->get('/tasks?is_done=invalid');

$response->assertStatus(400);
}


// Update an existing task using the validated data from the request.
public function test_update_existing_task()
{
// Create a new task
$task = Task::factory()->create();

// Generate a random title for the updated task
$updatedTitle = Str::random(10);

// Send a PUT request to update the task
$response = $this->put('/tasks/' . $task->id, [
'title' => $updatedTitle,
'is_done' => true,
]);

// Assert that the response has a successful status code
$response->assertStatus(200);

// Assert that the task has been updated with the new title
$this->assertEquals($updatedTitle, $task->fresh()->title);

// Assert that the task has been marked as done
$this->assertTrue($task->fresh()->is_done);
}


// Test sorting with invalid parameters.
public function test_sorting_with_invalid_parameters()
{
$response = $this->get('/tasks?sort=invalid');

$response->assertStatus(400);
$response->assertJson([
'message' => 'Invalid sort parameter',
]);
}


// Test pagination with invalid parameters.
public function test_pagination_with_invalid_parameters()
{
$response = $this->get('/tasks?page=abc');

$response->assertStatus(400);
}


// Test retrieving a single task with an invalid ID.
public function test_retrieve_single_task_with_invalid_id()
{
$response = $this->get('/tasks/9999');
$response->assertStatus(404);
}


// Test creating a new task with invalid data.
public function test_create_new_task_with_invalid_data()
{
// Arrange
$requestData = [
'title' => '', // Invalid data: empty title
'description' => 'Lorem ipsum dolor sit amet',
'is_done' => false
];
$request = new StoreTaskRequest($requestData);

// Act
$response = $this->post('/tasks', $request->toArray());

// Assert
$response->assertStatus(422); // Expecting a validation error
}


// Test filtering with invalid filter values.
public function test_filtering_with_invalid_filter_values()
{
$response = $this->get('/tasks?is_done=invalid');

$response->assertStatus(400);
}


// Test deleting a non-existent task.
public function test_deleting_non_existent_task()
{
// Create a new task
$task = Task::factory()->create();

// Delete the task
$response = $this->delete('/tasks/' . ($task->id + 1));

// Assert that the response has a 404 status code
$response->assertStatus(404);
}


// Test updating an existing task with invalid data.
public function test_update_existing_task_with_invalid_data()
{
// Create a new task
$task = Task::factory()->create();

// Send a PUT request to update the task with invalid data
$response = $this->put('/tasks/' . $task->id, [
'title' => '',
'is_done' => 'invalid',
]);

// Assert that the response has a 422 status code
$response->assertStatus(422);

// Assert that the task was not updated in the database
$this->assertDatabaseMissing('tasks', [
'id' => $task->id,
'title' => '',
'is_done' => 'invalid',
]);
}


// Test retrieving a non-existent task.
public function test_retrieve_non_existent_task()
{
$response = $this->get('/tasks/999');
$response->assertNotFound();
}


// Test updating a task with invalid data.
public function test_updating_task_with_invalid_data()
{
// Create a new task
$task = Task::factory()->create();

// Send a request to update the task with invalid data
$response = $this->put(route('tasks.update', $task->id), [
'title' => '',
'is_done' => 'invalid_value'
]);

// Assert that the response has a validation error
$response->assertStatus(422)
->assertJsonValidationErrors(['title', 'is_done']);
}


// Test creating a task with invalid data.
public function test_creating_task_with_invalid_data()
{
// Arrange
$requestData = [
'title' => 'Task 1',
'description' => 'This is a task',
'is_done' => 'invalid_value'
];
$request = new StoreTaskRequest($requestData);

// Act
$response = $this->post('/tasks', $requestData);

// Assert
$response->assertStatus(422);
}


// Test pagination with invalid page values.
public function test_pagination_with_invalid_page_values()
{
$response = $this->get('/tasks?page=abc');

$response->assertStatus(400);
$response->assertJson([
'message' => 'The given data was invalid.',
'errors' => [
'page' => ['The page must be an integer.']
]
]);
}


// Test sorting with invalid sort values.
public function test_sorting_with_invalid_sort_values()
{
$response = $this->get('/tasks?sort=invalid');

$response->assertStatus(400);
$response->assertJson([
'message' => 'Invalid sort value',
]);
}


// Test that the response contains the correct number of tasks.
public function test_response_contains_correct_number_of_tasks()
{
// Arrange
$request = new Request();
$controller = new TaskController();

// Act
$response = $controller->index($request);
$tasks = $response->getData()->data;

// Assert
$this->assertCount(10, $tasks);
}


// Test that the response contains the correct pagination data.
public function test_pagination_data()
{
$response = $this->get('/tasks');
$response->assertJsonStructure([
'data',
'links',
'meta'
]);
}


// Test that the response contains the correct task data.
public function test_response_contains_correct_task_data()
{
// Create a new task
$task = Task::factory()->create();

// Send a GET request to the show endpoint
$response = $this->get('/tasks/' . $task->id);

// Assert that the response has a 200 status code
$response->assertStatus(200);

// Assert that the response contains the correct task data
$response->assertJson([
'data' => [
'id' => $task->id,
'title' => $task->title,
'description' => $task->description,
'is_done' => $task->is_done,
'created_at' => $task->created_at->toISOString(),
'updated_at' => $task->updated_at->toISOString(),
]
]);
}


// Test that the response contains the correct sorting data.
public function test_response_contains_correct_sorting_data()
{
$response = $this->get('/tasks');
$response->assertStatus(200);
$response->assertJsonStructure([
'data' => [
'*' => [
'id',
'title',
'is_done',
'created_at',
'updated_at'
]
],
'links' => [
'first',
'last',
'prev',
'next'
],
'meta' => [
'current_page',
'from',
'last_page',
'path',
'per_page',
'to',
'total'
]
]);
}


// Test pagination with custom page size.
public function test_pagination_with_custom_page_size()
{
$pageSize = 10;
$request = Request::create('/tasks', 'GET', ['page_size' => $pageSize]);
$controller = new TaskController();
$response = $controller->index($request);
$tasks = $response->getData()->data;

$this->assertCount($pageSize, $tasks);
}


// Test that the response contains the correct filtering data.
public function test_response_contains_correct_filtering_data()
{
// Create a task with is_done set to true
$task1 = Task::factory()->create(['is_done' => true]);

// Create a task with is_done set to false
$task2 = Task::factory()->create(['is_done' => false]);

// Send a GET request to the index endpoint
$response = $this->get('/tasks');

// Assert that the response contains both tasks
$response->assertJsonFragment(['id' => $task1->id]);
$response->assertJsonFragment(['id' => $task2->id]);
}


// Test filtering with multiple filter values.
public function test_filtering_with_multiple_filter_values()
{
// Create multiple tasks with different is_done values
$task1 = Task::factory()->create(['is_done' => true]);
$task2 = Task::factory()->create(['is_done' => false]);
$task3 = Task::factory()->create(['is_done' => true]);
$task4 = Task::factory()->create(['is_done' => false]);

// Send a GET request with multiple filter values
$response = $this->get('/tasks?filter[is_done]=true,false');

// Assert that the response has a successful status code
$response->assertOk();

// Assert that the response contains the correct tasks
$response->assertJson([
'data' => [
[
'id' => $task1->id,
'is_done' => true,
],
[
'id' => $task2->id,
'is_done' => false,
],
[
'id' => $task3->id,
'is_done' => true,
],
[
'id' => $task4->id,
'is_done' => false,
],
],
]);
}


// Test updating a task with partial data.
public function test_update_task_with_partial_data()
{
// Create a new task
$task = Task::factory()->create();

// Send a PATCH request to update the task with partial data
$response = $this->patch('/tasks/' . $task->id, [
'title' => 'Updated Title',
]);

// Assert that the response has a successful status code
$response->assertStatus(200);

// Assert that the task has been updated with the new title
$this->assertEquals('Updated Title', $task->fresh()->title);
}


// Test sorting with multiple sort values.
public function test_sorting_with_multiple_sort_values()
{
// Create some tasks with different created_at values
$task1 = Task::factory()->create(['created_at' => now()->subDays(3)]);
$task2 = Task::factory()->create(['created_at' => now()->subDays(2)]);
$task3 = Task::factory()->create(['created_at' => now()->subDays(1)]);

// Send a request to the index method with multiple sort values
$response = $this->get('/tasks?sort=created_at,-title');

// Assert that the response is successful
$response->assertOk();

// Assert that the tasks are sorted correctly
$response->assertJson([
'data' => [
[
'id' => $task3->id,
'title' => $task3->title,
'created_at' => $task3->created_at->toISOString(),
],
[
'id' => $task2->id,
'title' => $task2->title,
'created_at' => $task2->created_at->toISOString(),
],
[
'id' => $task1->id,
'title' => $task1->title,
'created_at' => $task1->created_at->toISOString(),
],
],
]);
}


// Test deleting a non-existent task.
public function test_deleting_non_existent_task()
{
// Create a new task
$task = Task::factory()->create();

// Delete the task
$response = $this->delete('/tasks/' . ($task->id + 1));

// Assert that the response has a 404 status code
$response->assertStatus(404);
}


}