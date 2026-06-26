# Agent Best Practices

This file is for AI coding agents working in this repository. Follow it before making changes, including when the requester is asking for something small.

## First Steps

1. Inspect the relevant project and nearby code before editing.
2. Check for existing patterns in sibling files and reuse them.
3. Check `git status` before broad work so you know which files were already dirty.
4. If the request is ambiguous, make the smallest reasonable assumption and mention it in the final response.

## Editing Rules

- Keep changes scoped to the request.
- Do not rewrite unrelated code or reformat whole files unless that is the explicit task.
- Do not revert changes you did not make.
- Prefer small, direct implementations over new abstractions unless the existing codebase already has the abstraction.
- Keep comments rare and useful; explain intent only where the code is not obvious.
- Preserve the repository style:
  - PHP files use 4 spaces.
  - XML project files use tabs.
  - JSON files use 4 spaces.
  - Files use CRLF line endings and UTF-8.

## Clean Code Principles

These principles summarize the Clean Code PHP guidance from `https://github.com/piotrplenik/clean-code-php`, adapted for this Laravel project. Apply them pragmatically, and let this repository's existing patterns, `pint.json`, PHPStan/Psalm configuration, Rector rules, and framework conventions win when there is a conflict.

- Respect enforced tooling and code style. If Pint, PHPStan, Psalm, Rector, PHPUnit, Pest, or another configured tool reports an issue, fix the cause instead of suppressing it unless suppression is clearly justified.
- Follow Laravel conventions unless there is a strong reason not to. Prefer idiomatic Laravel code over clever custom abstractions.
- Use meaningful, pronounceable, searchable names for variables, methods, classes, routes, jobs, events, listeners, policies, commands, and tests.
- Use consistent vocabulary for the same concept. Do not alternate between `User`, `Account`, `Member`, and `Profile` unless the distinction is real in the domain.
- Avoid unnecessary context in names. If the class is `UserController`, method and variable names do not need to repeat `User` unless it adds clarity.
- Avoid mental mapping. Use descriptive names instead of abbreviations, vague names, or temporary placeholders such as `$data`, `$arr`, `$obj`, `$tmp`, or `$result` when the meaning is more specific.
- Prefer strict comparisons (`===`, `!==`) and explicit intent over loose comparisons.
- Prefer the null coalescing operator (`??`) or explicit null handling when it makes intent clearer.
- Replace magic strings and numbers with named constants, enums, configuration values, value objects, or domain concepts when the value affects behavior or is reused.
- Prefer early returns and guard clauses over deeply nested conditionals.
- Avoid negative conditionals when a positive condition is clearer.
- Encapsulate complex conditionals behind intention-revealing methods, query scopes, policy methods, specifications, or value objects.
- Keep functions and methods small, focused, and on one level of abstraction.
- Do not mix high-level workflow code with low-level details in the same method. A controller, command, job, or service method should either describe the workflow or implement a detail, not both.
- Keep controllers thin. Controllers should usually validate input, authorize the action, delegate work, and return a response.
- Move business rules out of controllers, form requests, Blade views, route closures, and Eloquent model events when those rules become non-trivial.
- Use Form Request classes for reusable or non-trivial validation and authorization logic.
- Prefer explicit service classes, actions, commands, jobs, or domain objects for workflows that do not naturally belong in an Eloquent model.
- Keep Eloquent models focused on persistence-related behavior, relationships, casts, scopes, accessors, mutators, and small domain helpers.
- Avoid turning Eloquent models into god objects. If a model grows many unrelated responsibilities, extract services, actions, value objects, query objects, or policies.
- Prefer named query scopes or dedicated query objects for reused or complex Eloquent queries.
- Keep query builder and Eloquent chains readable. Break complex queries into named intermediate variables or methods when the intent is not immediately clear.
- Avoid hiding expensive database work behind innocent-looking accessors, casts, resource properties, or view helpers.
- Watch for N+1 queries. Use eager loading, constrained eager loading, aggregates, or explicit query restructuring when needed.
- Avoid long parameter lists. Zero, one, or two parameters are ideal; three should be rare. If more data is required, use a dedicated DTO, command object, request object, value object, or refactor the design.
- Do not use boolean flag arguments when they split a method into multiple behaviors. Prefer separate methods, named constructors, enums, or a small options object.
- Function and method names should say what they do. Prefer `createTemporaryFile()` over `createFile($temporary = true)`.
- Keep side effects explicit. A query-like method should not write files, dispatch jobs, send emails, mutate hidden state, or update the database.
- Centralize side effects. Do not scatter file writes, external API calls, mail sends, queue dispatches, cache invalidation, or audit logging across unrelated classes.
- Avoid global state. Do not introduce custom global functions, global variables, mutable static state, or hidden singleton-like dependencies.
- Do not use the Singleton pattern for application services. Prefer dependency injection through Laravel's service container.
- Prefer constructor or method injection for dependencies when it improves clarity and testability.
- Use Facades pragmatically for Laravel-native features, but avoid using them to hide complex domain dependencies. For business-critical collaborators, prefer explicit dependencies.
- Prefer immutable value objects for domain values that must preserve invariants, such as money, date ranges, identifiers, permissions, or external references.
- Encapsulate mutable state behind methods that preserve invariants.
- Prefer composition over inheritance for shared behavior unless the existing design clearly uses inheritance.
- Prefer final classes for services, actions, DTOs, value objects, jobs, commands, and other concrete application classes unless extension is intentionally supported.
- Avoid fluent interfaces for domain code when they hide order dependencies, side effects, or invalid intermediate states. Use them only when they remain clear and idiomatic.
- Keep classes cohesive. A class should have one main reason to change.
- Follow SOLID principles:
  - Single Responsibility: split unrelated reasons to change.
  - Open/Closed: extend behavior without editing stable code paths when that is simpler and clearer.
  - Liskov Substitution: implementations must honor the contract expected by callers.
  - Interface Segregation: keep interfaces focused on what consumers actually need.
  - Dependency Inversion: depend on abstractions for external systems, file I/O, clocks, queues, mailers, payment providers, and APIs when it improves testability or isolation.
- Avoid premature interfaces. Introduce an interface when there are multiple implementations, an external boundary, or a real testing/isolation benefit.
- Prefer Laravel contracts or existing framework abstractions when they already express the dependency well.
- Do not duplicate business logic across controllers, jobs, commands, listeners, observers, policies, and tests. Extract shared behavior into a clear domain concept.
- Avoid over-abstracting simple Laravel code. Duplication is better than the wrong abstraction, but repeated business rules should be consolidated.
- Prefer explicit return types and parameter types wherever practical.
- Use PHPDoc for generics, array shapes, magic framework behavior, or static-analysis help. Do not add comments that merely repeat the code.
- Comment only the why when business rules, edge cases, historical constraints, or non-obvious tradeoffs would otherwise be lost.
- Do not leave commented-out code, journal comments, decorative separators, or regions that compensate for oversized code.
- Prefer exceptions or result objects for explicit failure paths. Do not return `null`, `false`, or empty arrays when that would make failure ambiguous.
- Do not swallow exceptions silently. Handle, log, translate, report, or let them flow.
- Preserve exception context. When wrapping exceptions, include the relevant domain identifier, file path, external operation, queue job, or API context.
- Use structured logging with message templates and useful context, for example `Log::warning('No import rules found for path {path}', ['path' => $path]);`.
- Do not log secrets, tokens, passwords, session IDs, API keys, personal data, or full request payloads unless explicitly safe and necessary.
- Keep configuration in Laravel config files and environment-specific values in `.env`. Do not read `env()` directly outside configuration files unless the existing project already does so intentionally.
- Prefer named routes over hard-coded URLs.
- Prefer policies, gates, and form request authorization over scattered authorization checks.
- Prefer Laravel resources, DTOs, or view models when response or view data becomes complex.
- Keep Blade templates focused on presentation. Move business decisions and heavy data shaping out of views.
- Keep jobs serializable and focused. Pass identifiers or simple DTOs instead of large mutable objects when queue boundaries are involved.
- Keep events factual and past-tense where possible, such as `OrderPlaced`, not imperative commands such as `SendOrderEmail`.
- Keep listeners focused on one reaction. If a listener performs unrelated work, split it.
- Use database transactions around workflows that must succeed or fail as a unit.
- Do not hide transaction boundaries deep inside low-level helpers when the caller owns the workflow.
- Prefer migrations for schema changes and avoid manual database changes that are not represented in code.
- Keep factories and seeders expressive. Tests should not need large unreadable setup blocks.
- Keep tests focused on one behavior at a time and use Arrange/Act/Assert structure when adding or updating tests.
- Prefer feature tests for Laravel HTTP, console, queue, mail, notification, and database integration behavior.
- Prefer unit tests for pure domain logic, value objects, and isolated services.
- Test behavior, not implementation details. Avoid fragile tests that mirror private method structure.
- Use Laravel testing helpers for fakes, events, queues, notifications, mail, storage, HTTP clients, and time.
- Avoid excessive mocking of Eloquent and Laravel internals. Use the framework's testing tools unless isolation is clearly beneficial.
- Keep one primary top-level class, interface, trait, enum, or record-like DTO per file. Do not place unrelated types in the same file.
- Use namespaces and directory structure that match the responsibility of the code.
- Do not introduce new helper files, macros, global functions, or service container bindings unless they provide clear project-wide value.
- Prefer small, intention-revealing refactors over broad rewrites.
- Preserve existing public APIs, route names, database columns, event names, queue names, and serialized payload shapes unless the change explicitly requires a breaking change.

## File and Media Operations

- Be extra careful around code that moves, renames, deletes, or converts files.
- Avoid destructive filesystem commands unless the user explicitly asked for them.
- Prefer dry-run style reasoning or tests when changing rename, scan, conversion, or cleanup behavior.
- Preserve user media paths and configuration assumptions unless the task is specifically about changing them.

## Dependencies

- Add new dependencies only when they clearly reduce risk or complexity.
- Add package versions only to `Directory.Packages.props`.
- Keep project references narrow and consistent with existing dependency direction.
- Do not fetch network resources unless the task requires it and the user allows it.

## Testing and Verification

- Build or test the smallest relevant scope first.
- If there are tests relevant to the touched area, run them.
- If verification cannot be run, say so in the final response and explain why.
- Do not claim tests passed unless you actually ran them.

## Working With the User

- Keep progress updates short and concrete.
- In final responses, lead with what changed and what was verified.
- Mention file paths that were edited.
- Call out assumptions, skipped tests, and any follow-up risks.
- When doing a review, prioritize bugs, regressions, and missing tests before style comments.

## Safety Checklist Before Finishing

- The change answers the latest user request.
- No unrelated files were modified.
- Existing user changes were preserved.
- Formatting matches `.editorconfig`.
- Build/test status is known and accurately reported.
- Any risky behavior around files, external APIs, or configuration has been called out.
