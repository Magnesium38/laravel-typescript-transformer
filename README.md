# Transform PHP to TypeScript

[![Latest Version on Packagist](https://img.shields.io/packagist/v/spatie/laravel-typescript-transformer.svg?style=flat-square)](https://packagist.org/packages/spatie/laravel-typescript-transformer)
[![GitHub Tests Action Status](https://github.com/spatie/laravel-typescript-transformer/actions/workflows/run-tests.yml/badge.svg)](https://github.com/spatie/laravel-typescript-transformer/actions?query=workflow%3Arun-tests+branch%3Amaster)
[![Styling](https://github.com/spatie/laravel-typescript-transformer/workflows/Check%20&%20fix%20styling/badge.svg)](https://github.com/spatie/laravel-typescript-transformer/actions?query=workflow%3A%22Check+%26+fix+styling%22)
[![Total Downloads](https://img.shields.io/packagist/dt/spatie/laravel-typescript-transformer.svg?style=flat-square)](https://packagist.org/packages/spatie/laravel-typescript-transformer)

This package allows you to convert PHP classes and more to TypeScript.

This class...

```php
#[TypeScript]
class User
{
    public int $id;
    public string $name;
    public ?string $address;
}
```

... will be converted to this TypeScript type:

```ts
export type User = {
    id: number;
    name: string;
    address: string | null;
}
```

Here's another example.

```php
enum Languages: string
{
    case TYPESCRIPT = 'typescript';
    case PHP = 'php';
}
```

The `Languages` enum will be converted to:

```tsx
export type Languages = 'typescript' | 'php';
```

And that's just the beginning! TypeScript transformer can handle complex types, generics and even allows you to create
TypeScript functions.

You can find the full documentation [here](https://spatie.be/docs/typescript-transformer/v3/introduction).

## Controller action payload types

Generated controller action results carry the same request and response types as their existing
namespace aliases. An application-owned helper can infer these types without naming each action's
`Request` and `Response` aliases. For example, a fetch helper for endpoints that accept a JSON body:

```ts
import { type ActionResult, PostsController } from './controllers';

async function sendJson<Request, Response>(
    action: ActionResult<Request, Response>,
    data: NoInfer<Request>,
): Promise<Response> {
    const response = await fetch(action.url, {
        method: action.method,
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data),
    });

    if (!response.ok) {
        throw new Error(`Request failed: ${response.status}`);
    }

    return await response.json() as Response;
}

// Given a store action accepting { title: string }:
const post = await sendJson(PostsController.store(), { title: 'Hello' });
// post is inferred as PostsController.store.Response.
```

`NoInfer` requires TypeScript 5.4+ and prevents the body from widening the request type inferred from
the action. It is only used by this example, not the generated support. This helper assumes a JSON
response; it does not validate response data, handle empty responses, or cover GET/HEAD requests.
Authentication, CSRF handling, and other transport concerns remain the application's responsibility.

The metadata is type-only: runtime results remain `{ url, method }`. Resolution is unchanged, including
the `object` fallback when no Data request parameter is found. That fallback is not a no-body guarantee.
Existing aliases and the factory's `P, M` generic positions are preserved. Differently typed action
results may no longer be assignable to one another; use `RouteDefinition` for code that only needs a
URL and method, or `ActionResult<unknown, unknown>` for a payload-agnostic action result.

## Testing

``` bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](https://github.com/spatie/.github/blob/main/CONTRIBUTING.md) for details.

## Security

If you've found a bug regarding security please mail [security@spatie.be](mailto:security@spatie.be) instead of using
the issue tracker.

## Credits

- [Ruben Van Assche](https://github.com/rubenvanassche)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
