
# Dust: Laravel Framework Base Package

Welcome to **Dust**, a **modular framework base package** for Laravel. Designed with developers in mind, Dust accelerates your Laravel project development by providing **pre-configured tools**, **enhanced CLI commands**, and a robust structure for **modular architecture**.

[![GitHub stars](https://img.shields.io/github/stars/Cyberbugz/dust.svg?style=for-the-badge&color=blue)](https://github.com/Cyberbugz/dust/stargazers)
[![GitHub issues](https://img.shields.io/github/issues/Cyberbugz/dust.svg?style=for-the-badge&color=yellow)](https://github.com/Cyberbugz/dust/issues)
[![GitHub license](https://img.shields.io/github/license/Cyberbugz/dust.svg?style=for-the-badge&color=green)](https://github.com/Cyberbugz/dust/blob/main/LICENSE)

---

## Table of Contents
1. [What Is Dust](#-what-is-dust)
2. [Why Use Dust](#-why-use-dust)
3. [Installation](#installation)
4. [Usage](#usage)
5. [File Structure](#file-structure)
6. [Key Features](#key-features)
7. [Detailed Description of Components](#detailed-description-of-components)
    - [Config](#config)
    - [Base](#base)
    - [Console](#console)
    - [Http](#http)
    - [Providers](#providers)
    - [Support](#support)
8. [Command Cheat Sheet](#Command-Cheat-Sheet)
9. [Overview](#Overview)

---



## 🚀 What is Dust?

Dust is a **developer-first Laravel package** that brings modularity, reusability, and streamlined workflows to your Laravel projects. It acts as a **base framework** for new Laravel applications, providing pre-built functionalities and a clean starting point for scalable, maintainable, and efficient development.

### 🛠️ Key Benefits:
- **Modular Design**: Organize your application into self-contained modules, making it easier to scale and maintain.
- **Enhanced CLI Commands**: Scaffold models, controllers, policies, and more with a single command.
- **Attribute-Based Routing**: Write cleaner, declarative route definitions using PHP attributes.
- **Centralized Configuration**: Manage settings, guards, and modules in one place.
- **Custom Error Handling**: Standardize responses with built-in error handling utilities.

---

## 🎯 Why Use Dust?

Dust empowers Laravel developers to focus on building features rather than boilerplate code. By adopting Dust, you gain:

- **Time Savings**: Automated scaffolding reduces development time.
- **Flexibility**: Supports both traditional and modern Laravel practices.
- **Scalability**: Designed to grow with your project, enabling large-scale applications.
- **Community Standards**: Fully adheres to Laravel's and PSR-4 standards.

---

> ⚡ **Ready to supercharge your Laravel development?** Dive into Dust and unlock the power of modularity and automation!

---


### Installation


To install the Dust package, use Composer:

```bash
composer require cyberbugz/dust
```

After installation, publish the configuration file:

```bash
php artisan vendor:publish --provider="Dust\Providers\DustServiceProvider" --tag="dust-config"
```
---

### Usage

Start a new Laravel project using Dust:

1. Define your modules and their components in the configuration file.
2. Use Dust's CLI commands to scaffold controllers, models, migrations, and more.
3. Leverage attribute-based routing to simplify route definitions.
4. Integrate custom providers, traits, and helpers for modular development.

---

### File Structure
Here’s a summary of the folder structure:

```
.github/
config/
src/
  ├── Base/
  ├── Console/
  ├── Http/
  ├── Support/
composer.json
README.md
```

---

### Key Features
- Modular architecture for Laravel projects.
- Custom console commands to scaffold various components.
- Attribute-based routing for clean and declarative route definitions.
- Extendable and reusable contracts for base functionality.
- Rich support for enums and helpers.



## Detailed Description of Components

---

### Config
- **File:** `dust.php`
- **Purpose:** Configuration for the package.
- **Key Options:**
    - **Modules:** Defines the default paths for modular organization.
        - Defaults to the `Modules` directory.
        - Allows for adding multiple module paths.
    - **Guards:** Configures routing and middleware for different contexts.
        - API Guard: Uses attribute-based routing, `api` prefix, and `api` middleware with a rate limit of 60.
        - Playground Guard: Uses file-based routing, `playground` prefix, and `playground` middleware with no rate limit.
    - **Logging:** Sets log channel behavior for various log levels (`info`, `debug`, `warning`, etc.), defaulting to daily logs.
    - **Default Error View:** Specifies a default error page to be used as `'error'`.

### Base
- **Purpose:** Core components for request handling, response management, and repository operations.
- **Key Classes:**
    - `RequestHandlerInterface`: Defines the structure for request handlers.
    - `ResponseInterface`: Manages the response lifecycle with methods like `send`, `onSuccess`, `onFailure`, and `onLog`.
    - `Controller`: Base class for controllers, supports parameter bindings, and ensures a clean request-response flow.
    - `Repository`: Abstract repository for simplifying model operations like `update` and `delete`, while dynamically forwarding calls to the model.
    - `Response`: Handles success/failure chains, custom logging, and error responses. Provides mechanisms for silent responses and event injections.

### Console
#### **Description**:
  The `Console` folder provides a wide range of Artisan commands to scaffold and manage components in a modular Laravel environment. Below is a detailed explanation of each command.

#### **Key Traits**:
1. **AbsolutePathChecker**:
        - Ensures commands resolve absolute paths for modules.
        - Provides support for custom stubs based on the module context.
2. **GuardChecker**:
    - Validates guard options against Laravel's `auth` configuration.
    - Ensures commands only accept valid guard inputs.
3. **ModelQualifier**:
    - Dynamically resolves model namespaces based on module context or default Laravel directories.
    - Simplifies interaction with models across different modules.
4. **OptionsExtender**:
    - Adds common command options like:
        - `--module`: Specify the target module for the command.
        - `--absolute`: Use absolute paths for module resolution.
        - `--guard`: Define a specific guard environment.
    - Ensures consistency in how commands handle options.
5. **SignatureExtender**:
    - Appends additional options or arguments to the command signature.
    - Enables modular flexibility for commands.

#### **Commands and Features**:
- `make:story`
  - **Description:** Creates a "story" structure, including a controller, routes, and tests.
  - **Key Options:**
    - --module: Specify the target module.
    - --guard: Set the guard for this story.
- `make:migration`
    - **Description:** Extends the migration creation command to support module-specific paths.
    - **Key Options:**
     - --module: Generate the migration within a specific module.

- `db:seed`
  - **Description:** Enhances the seeder execution by allowing module-specific seeders.
  - **Key Options:**
    - --module: Run seeders from a specific module.

- `make:cast`
  - **Description:** Generates custom casts for Eloquent attributes within a module's namespace.
  - **Key Options:**
    - --module: Specify the module for the cast.

- `make:command`
  - **Description:** Creates custom Artisan commands within module-specific namespaces.
  - **Key Options:**
    - --module: Generate the command for a specific module.

- `make:controller`
  - **Description:** Extends the controller creation process by generating associated requests, responses, and services.
  - **Key Options:**
    - --module: Specify the module for the controller.
    - --guard: Set a guard to customize the controller namespace.

- `make:event`
  - **Description:** Generates event classes scoped to a specific module.
  - **Key Options:**
    - --module: Define the module for the event.

- `make:exception`
  - **Description:** Creates exceptions within module-specific namespaces.
  - **Key Options:**
    - --module: Target module for the exception.

- `make:factory`
  - **Description:** Extends the factory generation process to support module-specific paths and namespaces.
  - **Key Options:**
    - --module: Specify the module for the factory.

- `make:job`
  - **Description:** Generates job classes for queued tasks, scoped to a module.
  - **Key Options:**
    - --module: Define the module for the job.

- `make:listener`
  - **Description:** Creates event listeners within a module's namespace.
  - **Key Options:**
    - --module: Generate the listener for a specific module.

- `make:mail`
  - **Description:** Generates mail classes within module-specific directories.
  - **Key Options:**
    - --module: Specify the module for the mail.

- `make:middleware`
  - **Description:** Creates middleware classes within a module's namespace.
  - **Key Options:**
    - --module: Define the module for the middleware.

- `make:model`
  - **Description:** Extends model generation by including related factories, migrations, policies, seeders, and controllers.
  - **Key Options:**
    - --module: Target module for the model.

- `make:notification`
  - **Description:** Generates notification classes scoped to a module.
  - **Key Options:**
    - --module: Define the module for the notification.

- `make:observer`
  - **Description:** Creates observer classes to handle model events, scoped to a module.
  - **Key Options:**
    - --module: Specify the module for the observer.

- `make:policy`
  - **Description:** Creates policy classes for model authorization.
  - **Key Options:**
    - --module: Generate the policy for a specific module.
    - --guard: Set the guard for the policy.

- `make:repository`
  - **Description:** Generates repository classes linked to specific models in a module.
  - **Key Options:**
    - --module: Define the module for the repository.

- `make:request`
  - **Description:** Creates form request classes with module and guard support.
  - **Key Options:**
    - --module: Specify the module for the request.

- `make:resource`
  - **Description:** Generates API resource classes within module-specific directories.
  - **Key Options:**
    - --module: Target module for the resource.

- `make:response`
  - **Description:** Creates response classes for handling HTTP responses in a module.
  - **Key Options:**
    - --module: Define the module for the response.

- `make:seeder`
  - **Description:** Extends seeder creation to support module-specific paths.
  - **Key Options:**
    - --module: Generate the seeder for a specific module.

- `make:service`
  - **Description:** Creates service classes for business logic encapsulation.
  - **Key Options:**
    - --module: Specify the module for the service.

- `make:test`
  - **Description:** Enhances test generation with module and guard-specific grouping.
  - **Key Options:**
    - --module: Target module for the test.
    - --unit: Specify if it is a unit test.
### Http

The `Http` folder provides a robust set of tools to enhance Laravel’s routing, response handling, and middleware workflows. It introduces attributes, enums, and custom responses to support a declarative and modular approach to managing HTTP operations.

---

#### **1. Attributes**
Attributes are PHP metadata annotations that define routing, middleware, guards, and other route-specific behaviors. These attributes streamline route configuration by replacing traditional route registration methods.

- **Route**:
    - Declares a route with its HTTP method, URI, and an optional name.
    - Example:
      ```php
      #[Route(Http::POST, '/users', 'users.store')]
      public function store() {
          return response()->json(['message' => 'User created']);
      }
      ```

- **Middleware**:
    - Defines middleware for routes using an array.
    - Example:
      ```php
      #[Middleware(['auth', 'throttle:60,1'])]
      public function update() {
          return response()->json(['message' => 'Middleware applied']);
      }
      ```

- **Guard**:
    - Applies a specific guard to a route.
    - Example:
      ```php
      #[Guard('api')]
      public function index() {
          return response()->json(['message' => 'Secured by API guard']);
      }
      ```

- **Prefix**:
    - Adds a prefix to the route URI.
    - Example:
      ```php
      #[Prefix('admin')]
      #[Route(Http::GET, '/dashboard')]
      public function dashboard() {
          return response()->json(['message' => 'Admin dashboard']);
      }
      ```

---

#### **2. Enums**
Enums provide strongly typed constants for various HTTP-related operations, ensuring clarity and preventing errors in route definitions.

- **Http**:
    - Enum for HTTP methods.
    - Supported Methods:
        - `Http::GET`
        - `Http::POST`
        - `Http::DELETE`
        - `Http::PUT`
        - `Http::PATCH`
        - `Http::OPTIONS`
        - `Http::HEAD`
        - `Http::CONNECT`
        - `Http::TRACE`

- **RoutePath**:
    - Enum for route path types.
    - Supported Types:
        - `RoutePath::None`: No specific path applied.
        - `RoutePath::Module`: Module-specific path.
        - `RoutePath::Root`: Root-level path.

- **Router**:
    - Enum for router strategies.
    - Supported Strategies:
        - `Router::File`: File-based routing.
        - `Router::Attribute`: Attribute-based routing.

---

#### **3. Responses**
- **ErrorResponse**:
    - A custom JSON response class designed to standardize error handling.
    - Automatically formats responses with a `data` payload and a `message`.
    - Example Usage:
      ```php
      return new ErrorResponse('An error occurred.', ['error_code' => 123], 400);
      ```
    - Response Format:
      ```json
      {
        "data": {
          "error_code": 123
        },
        "message": "An error occurred."
      }
      ```

---

#### **4. Exceptions**
- **RouteDefinitionMissing**:
    - A custom exception thrown when a required route definition is missing.
    - Ensures clear error reporting in attribute-based routing workflows.
    - Example:
      ```php
      throw new RouteDefinitionMissing('Custom error message');
      ```

---

#### **5. Key Benefits**
1. **Attribute-Based Routing**:
    - Eliminates the need for manual route registration.
    - Provides a declarative syntax for defining routes, middleware, guards, and prefixes.
    - Ensures routes are self-contained and easy to read.

2. **Standardized Error Responses**:
    - Simplifies error handling with a consistent JSON structure.
    - Reduces boilerplate code for custom error formatting.

3. **Strongly Typed Enums**:
    - Prevents errors by restricting invalid HTTP methods or route configurations.
    - Improves code readability and maintainability.

4. **Modular Design**:
    - Enables guard and prefix-specific routing for modules.
    - Supports both attribute-based and file-based routing strategies.

---

#### **Example Usage**
```php
#[Middleware(['auth'])]
#[Guard('api')]
#[Prefix('admin')]
#[Route(Http::GET, '/users', 'users.index')]
public function index() {
    return response()->json(['message' => 'Admin API Users']);
}
```

### Providers

The **Providers** folder encapsulates the service providers essential to extending Laravel's core functionality and supporting the modular architecture of this package. These providers handle everything from route management to event discovery and Artisan commands, ensuring the package integrates seamlessly with Laravel's ecosystem.

---

#### **1. DustServiceProvider**
The **DustServiceProvider** is the primary service provider of this package, responsible for initializing core services, registering commands, and managing configurations.

- **Responsibilities**:
    - Merges the package configuration (`dust.php`) with Laravel's configuration.
    - Publishes configuration files for user customization.
    - Registers global commands like `StoryMakeCommand` for creating modular components.

- **Highlights**:
    - Automatically binds the package's services and commands for console use.
    - Simplifies project customization by enabling developers to tailor configurations.

---

#### **2. RouteServiceProvider**
The **RouteServiceProvider** revolutionizes route management by offering both file-based and attribute-based routing strategies, allowing developers to define routes declaratively or conventionally.

- **Responsibilities**:
    - Configures and applies rate limits for various guards.
    - Dynamically registers routes based on module configurations.
    - Supports:
        - **File-Based Routing**: Registers routes from specific files (e.g., `routes/api.php`).
        - **Attribute-Based Routing**: Discovers routes directly from controller attributes.

- **Highlights**:
    - Modular route registration: Routes can be tied to individual modules for better organization.
    - Flexible routing strategies that cater to both traditional and modern practices.

---

#### **3. ArtisanServiceProvider**
The **ArtisanServiceProvider** extends Laravel’s Artisan service provider to enhance the CLI experience with modular-focused commands.

- **Responsibilities**:
    - Registers custom commands like `ResponseMake`, `ServiceMake`, and `RepositoryMake`.
    - Overrides Laravel's default commands (`ModelMakeCommand`, `ControllerMakeCommand`) to include module-specific options.

- **Highlights**:
    - Scaffolding commands tailored to modular architecture.
    - Reduces boilerplate code by automating repetitive tasks.

---

#### **4. MigrationServiceProvider**
The **MigrationServiceProvider** extends Laravel’s migration workflows, enabling modular migration management.

- **Responsibilities**:
    - Loads migrations from module directories, ensuring each module's migrations are self-contained.
    - Registers the `MigrateMakeCommand` for module-specific migration creation.

- **Highlights**:
    - Keeps migrations organized and aligned with the module structure.
    - Simplifies migration creation with module-aware commands.

---

#### **5. EventServiceProvider**
The **EventServiceProvider** enhances Laravel's event-handling capabilities by enabling dynamic event and listener discovery.

- **Responsibilities**:
    - Automatically discovers and registers events and listeners within modules.
    - Integrates seamlessly with Laravel's native event-handling system.

- **Highlights**:
    - Reduces the need for manual event-listener bindings.
    - Promotes a clean and modular event management workflow.

---

### Key Benefits of Providers
1. **Centralized Configuration Management**:
    - The **DustServiceProvider** ensures all package configurations are centralized and easily customizable.

2. **Enhanced Routing**:
    - The **RouteServiceProvider** offers flexibility in route definition through attribute-based and file-based routing strategies.

3. **Streamlined CLI Experience**:
    - The **ArtisanServiceProvider** simplifies scaffolding and repetitive tasks, improving developer productivity.

4. **Modular Database Operations**:
    - The **MigrationServiceProvider** aligns migration management with the modular architecture.

5. **Dynamic Event Management**:
    - The **EventServiceProvider** streamlines event and listener registration, adhering to the modular structure.

---


### Support

The **Support** folder provides utility classes, traits, interfaces, and helpers to streamline functionality across the package. It enhances flexibility, modularity, and maintainability by encapsulating common functionalities and reusable patterns.

---

#### **1. Traits**
Traits encapsulate reusable methods that can be shared across multiple classes.

- **HasOptions**:
    - Enables enums to return their options and values in a structured format.
    - Features:
        - `options()`: Returns a list of options as an array of text and value pairs.
        - `values()`: Retrieves only the values from the enum cases.
        - Example Usage:
          ```php
          MyEnum::options();
          MyEnum::values();
          ```

- **IsArrayable**:
    - Converts an enum instance into an associative array.
    - Features:
        - `toArray()`: Converts the instance into an array with `text` and `value` keys.

- **IsStringable**:
    - Ensures enums can be converted into strings.
    - Features:
        - `toString()`: Returns the name of the enum as a string.

---

#### **2. Interfaces**
Interfaces define contracts for reusable functionalities.

- **ArrayableInterface**:
    - Ensures classes implementing it provide a `toArray()` method for converting instances into arrays.
    - Example:
      ```php
      $array = $instance->toArray();
      ```

- **OptionableInterface**:
    - Requires implementing classes to provide static methods for generating options.
    - Example:
      ```php
      $options = MyClass::options();
      ```

- **StringableInterface**:
    - Ensures classes implement a `toString()` method for converting instances to strings.
    - Example:
      ```php
      $string = $instance->toString();
      ```

---

#### **3. Helpers**
Utility functions to facilitate common operations within the package.

- **Functions**:
    - `modules_path()`: Returns the base path for modules.
    - `app_modules()`: Retrieves a list of all available modules.
    - `get_module_path(string $module, array $subdirectories)`: Builds the path to a specific module and its subdirectories.
    - `get_module_namespace(string $rootNamespace, string $module, array $subdirectories, string $modulesRoot = '')`: Resolves the namespace of a module's directory.
    - `modules_view_paths()`: Generates an array of paths to view directories for all modules.
    - Example:
      ```php
      $modulePath = get_module_path('User', ['Domain', 'Entities']);
      ```

---

#### **4. Logger**
Provides a centralized logging mechanism with support for dynamic channels.

- **Features**:
    - Supports all major logging levels: `info`, `warning`, `error`, `debug`, `notice`, and `emergency`.
    - Dynamically resolves the appropriate logging channel based on configuration.
    - Merges exception details into the log context for error and emergency logs.
    - Example:
      ```php
      Logger::info('Operation completed successfully.', ['user_id' => 1]);
      Logger::error('An error occurred.', $exception, ['context' => 'value']);
      ```

- **Error Context**:
    - Includes exception message, file, line, and stack trace for detailed error logging.

---

#### Key Benefits of the Support Folder
1. **Reusability**:
    - Traits and interfaces promote code reuse, reducing duplication across the package.
2. **Utility**:
    - Helper functions simplify and standardize operations related to modules and paths.
3. **Centralized Logging**:
    - The Logger class offers a unified approach to handling logs with dynamic channel support.
4. **Enum Enhancements**:
    - Traits like `HasOptions` and `IsArrayable` add extra functionality to enums, making them more flexible and useful.

---
### Command Cheat Sheet

Below is a comprehensive cheat sheet for all the Artisan commands available in the Dust package. Each command supports modular scaffolding using the `--module` flag.

| **Command**                  | **Description**                                                                                  | **Example**                                                                                      |
|------------------------------|--------------------------------------------------------------------------------------------------|-------------------------------------------------------------------------------------------------|
| `make:story`                 | Creates a "story" structure, including controller, routes, and tests.                           | `php artisan make:story UserStory --module=User --guard=api`                                    |
| `make:migration`             | Generates a migration file in a specific module.                                                | `php artisan make:migration create_users_table --module=User`                                   |
| `db:seed`                    | Runs a module-specific database seeder.                                                        | `php artisan db:seed --module=User`                                                             |
| `make:cast`                  | Creates a custom cast class for a module.                                                      | `php artisan make:cast CustomCast --module=User`                                                |
| `make:command`               | Generates a custom Artisan command for a module.                                               | `php artisan make:command CustomCommand --module=User`                                          |
| `make:controller`            | Scaffolds a controller with associated requests, responses, and services.                      | `php artisan make:controller UserController --module=User --guard=api`                          |
| `make:event`                 | Generates an event class within a module.                                                      | `php artisan make:event UserRegistered --module=User`                                           |
| `make:exception`             | Creates a custom exception class in a module.                                                  | `php artisan make:exception CustomException --module=User`                                      |
| `make:factory`               | Generates a factory class for a module.                                                        | `php artisan make:factory UserFactory --module=User`                                            |
| `make:job`                   | Creates a queued job class within a module.                                                    | `php artisan make:job ProcessUserRegistration --module=User`                                    |
| `make:listener`              | Creates an event listener within a module.                                                     | `php artisan make:listener SendWelcomeEmail --module=User`                                      |
| `make:mail`                  | Generates a mail class for a module.                                                           | `php artisan make:mail WelcomeEmail --module=User`                                              |
| `make:middleware`            | Creates middleware for a module.                                                               | `php artisan make:middleware AdminMiddleware --module=User`                                     |
| `make:model`                 | Scaffolds a model with associated factories, migrations, and policies.                         | `php artisan make:model User --module=User`                                                    |
| `make:notification`          | Generates a notification class scoped to a module.                                             | `php artisan make:notification UserNotification --module=User`                                  |
| `make:observer`              | Creates an observer class for a module.                                                        | `php artisan make:observer UserObserver --module=User`                                          |
| `make:policy`                | Creates a policy class for a model.                                                            | `php artisan make:policy UserPolicy --module=User --guard=api`                                  |
| `make:repository`            | Generates a repository class for a module.                                                    | `php artisan make:repository UserRepository --module=User`                                      |
| `make:request`               | Creates a form request class with guard support.                                               | `php artisan make:request UserRequest --module=User`                                            |
| `make:resource`              | Generates an API resource class within a module.                                               | `php artisan make:resource UserResource --module=User`                                          |
| `make:response`              | Creates a custom HTTP response class for a module.                                             | `php artisan make:response CustomResponse --module=User`                                        |
| `make:seeder`                | Scaffolds a seeder class within a module.                                                      | `php artisan make:seeder UserSeeder --module=User`                                              |
| `make:service`               | Generates a service class for business logic encapsulation.                                    | `php artisan make:service UserService --module=User`                                            |
| `make:test`                  | Creates a unit or feature test in a module.                                                    | `php artisan make:test UserTest --module=User --unit`                                           |

### Key Notes:
- **`--module`**: This flag ensures that generated files are scoped to a specific module, keeping the application modular and organized.
- **`--guard`**: Allows customization of routing and middleware guards for commands where applicable.

---

### Overview

Dust aims to simplify and enhance Laravel development through modularity, extensibility, and developer-centric tools. 
It provides a foundation for scalable, maintainable, and efficient applications.
