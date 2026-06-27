# Project Instructions

This file contains repository-specific instructions for AI coding agents working in this Laravel/PHP project.

Global instructions still apply. These local instructions add project-specific formatting, Laravel conventions, Clean Code PHP guidance, and repository-specific safety notes.

## First Steps

1. Inspect the relevant project area and nearby code before editing.
2. Check sibling files for existing Laravel, naming, validation, testing, and dependency patterns.
3. Reuse existing project patterns before introducing new ones.
4. For ambiguous requests, make the smallest reasonable assumption when the risk is low and mention it in the final response.

## Repository Formatting

Preserve the repository's existing file formatting:

* PHP files use 4 spaces.
* XML project files use tabs.
* JSON files use 4 spaces.
* Files use CRLF line endings and UTF-8.

Respect `.editorconfig`, `pint.json`, and any configured formatter or analyzer rules.

## Laravel and PHP Tooling

Apply configured tooling pragmatically and fix root causes instead of suppressing issues unless suppression is clearly justified.

Relevant tools may include:

* Laravel Pint
* PHPStan
* Psalm
* Rector
* PHPUnit
* Pest
* Composer scripts
* Project-specific CI checks

When changing PHP or Laravel code, prefer the narrowest relevant build, lint, static-analysis, or test command for the touched area.

## Clean Code PHP Principles

These principles summarize the Clean Code PHP guidance from `https://github.com/piotrplenik/clean-code-php`, adapted for this Laravel project.

Apply them pragmatically. Existing repository conventions, Laravel conventions, `pint.json`, PHPStan/Psalm configuration, Rector rules, and local architecture take precedence when they conflict.

### Naming

* Use meaningful, pronounceable, searchable names for variables, methods, classes, routes, jobs, events, listeners, policies, commands, and tests.
* Use consistent vocabulary for the same concept. Do not alternate between `User`, `Account`, `Member`, and `Profile` unless the distinction is real in the domain.
* Avoid unnecessary context in names. If the class is `UserController`, method and variable names do not need to repeat `User` unless it adds clarity.
* Avoid mental mapping. Use descriptive names instead of abbreviations, vague names, or temporary placeholders such as `$data`, `$arr`, `$obj`, `$tmp`, or `$result` when the meaning is more specific.
* Function and method names should say what they do. Prefer `createTemporaryFile()` over `createFile($temporary = true)`.

### Control Flow and Intent

* Prefer strict comparisons (`===`, `!==`) and explicit intent over loose comparisons.
* Prefer the null coalescing operator (`??`) or explicit null handling when it makes intent clearer.
* Prefer early returns and guard clauses over deeply nested conditionals.
* Avoid negative conditionals when a positive condition is clearer.
* Encapsulate complex conditionals behind intention-revealing methods, query scopes, policy methods, specifications, or value objects.
* Replace magic strings and numbers with named constants, enums, configuration values, value objects, or domain concepts when the value affects behavior or is reused.

### Functions and Methods

* Keep functions and methods small, focused, and on one level of abstraction.
* Do not mix high-level workflow code with low-level details in the same method.
* Avoid long parameter lists. Zero, one, or two parameters are ideal; three should be rare.
* If more data is required, use a dedicated DTO, command object, request object, value object, or refactor the design.
* Do not use boolean flag arguments when they split a method into multiple behaviors.
* Prefer separate methods, named constructors, enums, or a small options object instead of flag arguments.
* Keep side effects explicit. A query-like method should not write files, dispatch jobs, send emails, mutate hidden state, or update the database.

### Laravel Structure

* Follow Laravel conventions unless there is a strong reason not to.
* Prefer idiomatic Laravel code over clever custom abstractions.
* Keep controllers thin. Controllers should usually validate input, authorize the action, delegate work, and return a response.
* Move business rules out of controllers, form requests, Blade views, route closures, and Eloquent model events when those rules become non-trivial.
* Use Form Request classes for reusable or non-trivial validation and authorization logic.
* Prefer explicit service classes, actions, commands, jobs, or domain objects for workflows that do not naturally belong in an Eloquent model.
* Prefer policies, gates, and form request authorization over scattered authorization checks.
* Prefer named routes over hard-coded URLs.
* Prefer Laravel resources, DTOs, or view models when response or view data becomes complex.
* Keep Blade templates focused on presentation. Move business decisions and heavy data shaping out of views.

### Eloquent and Database Access

* Keep Eloquent models focused on persistence-related behavior, relationships, casts, scopes, accessors, mutators, and small domain helpers.
* Avoid turning Eloquent models into god objects.
* If a model grows many unrelated responsibilities, extract services, actions, value objects, query objects, or policies.
* Prefer named query scopes or dedicated query objects for reused or complex Eloquent queries.
* Keep query builder and Eloquent chains readable. Break complex queries into named intermediate variables or methods when the intent is not immediately clear.
* Avoid hiding expensive database work behind innocent-looking accessors, casts, resource properties, or view helpers.
* Watch for N+1 queries. Use eager loading, constrained eager loading, aggregates, or explicit query restructuring when needed.
* Use database transactions around workflows that must succeed or fail as a unit.
* Do not hide transaction boundaries deep inside low-level helpers when the caller owns the workflow.
* Prefer migrations for schema changes and avoid manual database changes that are not represented in code.

### Dependencies and Application Boundaries

* Centralize side effects. Do not scatter file writes, external API calls, mail sends, queue dispatches, cache invalidation, or audit logging across unrelated classes.
* Avoid global state. Do not introduce custom global functions, global variables, mutable static state, or hidden singleton-like dependencies.
* Do not use the Singleton pattern for application services. Prefer dependency injection through Laravel's service container.
* Prefer constructor or method injection for dependencies when it improves clarity and testability.
* Use Facades pragmatically for Laravel-native features, but avoid using them to hide complex domain dependencies.
* For business-critical collaborators, prefer explicit dependencies.
* Prefer Laravel contracts or existing framework abstractions when they already express the dependency well.
* Avoid premature interfaces. Introduce an interface when there are multiple implementations, an external boundary, or a real testing/isolation benefit.
* Do not introduce new helper files, macros, global functions, or service container bindings unless they provide clear project-wide value.

### Object Design

* Prefer immutable value objects for domain values that must preserve invariants, such as money, date ranges, identifiers, permissions, or external references.
* Encapsulate mutable state behind methods that preserve invariants.
* Prefer composition over inheritance for shared behavior unless the existing design clearly uses inheritance.
* Prefer final classes for services, actions, DTOs, value objects, jobs, commands, and other concrete application classes unless extension is intentionally supported.
* Avoid fluent interfaces for domain code when they hide order dependencies, side effects, or invalid intermediate states.
* Keep classes cohesive. A class should have one main reason to change.
* Keep one primary top-level class, interface, trait, enum, or record-like DTO per file.
* Use namespaces and directory structure that match the responsibility of the code.

### SOLID Guidance

Follow SOLID principles pragmatically:

* Single Responsibility: split unrelated reasons to change.
* Open/Closed: extend behavior without editing stable code paths when that is simpler and clearer.
* Liskov Substitution: implementations must honor the contract expected by callers.
* Interface Segregation: keep interfaces focused on what consumers actually need.
* Dependency Inversion: depend on abstractions for external systems, file I/O, clocks, queues, mailers, payment providers, and APIs when it improves testability or isolation.

Avoid over-engineering. Duplication is better than the wrong abstraction, but repeated business rules should be consolidated into a clear domain concept.

### Types, PHPDoc, and Comments

* Prefer explicit return types and parameter types wherever practical.
* Use PHPDoc for generics, array shapes, magic framework behavior, or static-analysis help.
* Do not add comments that merely repeat the code.
* Comment only the why when business rules, edge cases, historical constraints, or non-obvious tradeoffs would otherwise be lost.
* Do not leave commented-out code, journal comments, decorative separators, or regions that compensate for oversized code.

### Errors and Logging

* Prefer exceptions or result objects for explicit failure paths.
* Do not return `null`, `false`, or empty arrays when that would make failure ambiguous.
* Do not swallow exceptions silently. Handle, log, translate, report, or let them flow.
* Preserve exception context. When wrapping exceptions, include the relevant domain identifier, file path, external operation, queue job, or API context.
* Use structured logging with message templates and useful context, for example:
  `Log::warning('No import rules found for path {path}', ['path' => $path]);`
* Do not log secrets, tokens, passwords, session IDs, API keys, personal data, or full request payloads unless explicitly safe and necessary.

### Configuration

* Keep configuration in Laravel config files and environment-specific values in `.env`.
* Do not read `env()` directly outside configuration files unless the existing project already does so intentionally.
* Preserve existing configuration keys and environment variable names unless the task explicitly requires changing them.

### Jobs, Events, and Listeners

* Keep jobs serializable and focused.
* Pass identifiers or simple DTOs instead of large mutable objects when queue boundaries are involved.
* Keep events factual and past-tense where possible, such as `OrderPlaced`, not imperative commands such as `SendOrderEmail`.
* Keep listeners focused on one reaction.
* If a listener performs unrelated work, split it.

### Tests

* Keep tests focused on one behavior at a time.
* Use Arrange/Act/Assert structure when adding or updating tests.
* Prefer feature tests for Laravel HTTP, console, queue, mail, notification, and database integration behavior.
* Prefer unit tests for pure domain logic, value objects, and isolated services.
* Test behavior, not implementation details.
* Avoid fragile tests that mirror private method structure.
* Use Laravel testing helpers for fakes, events, queues, notifications, mail, storage, HTTP clients, and time.
* Avoid excessive mocking of Eloquent and Laravel internals. Use the framework's testing tools unless isolation is clearly beneficial.
* Keep factories and seeders expressive. Tests should not need large unreadable setup blocks.

## File and Media Operations

This repository contains or works with file and media operations. Be extra careful around code that moves, renames, deletes, scans, converts, or cleans up files.

* Prefer dry-run style reasoning or tests when changing rename, scan, conversion, or cleanup behavior.
* Preserve user media paths and configuration assumptions unless the task is specifically about changing them.
* Avoid changing destructive behavior and cleanup rules without explicit task scope.
* When touching file operations, consider edge cases such as special characters, long paths, duplicate names, locked files, missing files, partial conversions, and interrupted runs.

## Project-Specific Dependencies

Dependency and container changes must be conservative, explicit, and reproducible.

* Do not add, remove, upgrade, downgrade, or relax dependency constraints unless the task explicitly requires it.
* Preserve exact dependency versions when they are already pinned.
* Do not replace pinned versions with broad ranges such as `^`, `~`, `*`, `latest`, `dev-main`, `dev-master`, or unconstrained version ranges unless the task explicitly asks for that change.
* Do not upgrade framework or runtime major versions as a side effect of another task.
* For Composer dependencies, preserve the existing constraint style used in `composer.json`.
* If changing a Composer dependency version is required, prefer the narrowest version constraint that satisfies the task and keeps builds reproducible.
* Respect `composer.lock`. Update it only when dependency changes require it.
* For npm/pnpm/yarn dependencies, preserve the existing package manager and version constraint style.
* Respect lockfiles such as `package-lock.json`, `pnpm-lock.yaml`, and `yarn.lock`. Update them only when dependency changes require it.
* For Docker images and container base images, use pinned tags or digests.
* Do not replace a pinned Docker image tag or digest with floating tags such as `latest`, `stable`, `main`, `master`, or broad version tags unless explicitly requested.
* Do not silently change container base image families, distributions, PHP versions, Node versions, database versions, or service versions.
* If a dependency or container version must change, explain why, list the old and new versions, and call out compatibility, security, and reproducibility impact.
* Do not fetch network resources unless the task requires it and the user allows it.

## Working With the User

When giving the final response:

* Lead with what changed.
* Mention edited file paths.
* State what verification was run.
* Call out skipped tests or checks.
* Call out assumptions and follow-up risks.
* For reviews, prioritize bugs, regressions, risky behavior, and missing tests before style comments.

## Safety Checklist Before Finishing

Before finishing, verify that:

* The change answers the latest user request.
* No unrelated files were modified.
* Existing user changes were preserved.
* Formatting matches `.editorconfig` and repository conventions.
* Build/test status is known and accurately reported.
* Any risky behavior around files, external APIs, configuration, or destructive operations has been called out.
