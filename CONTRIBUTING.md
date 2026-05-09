# Contributing to GlobalCountries

We love your input! We want to make contributing to GlobalCountries as easy and transparent as possible, whether it's:

- Reporting a bug
- Discussing the current state of the code
- Submitting a fix
- Proposing new features
- Becoming a maintainer

## Development Process

1. Fork the repo and create your branch from `main`.
2. If you've added code that should be tested, add tests.
3. If you've changed APIs, update the documentation.
4. Ensure the test suite passes.
5. Make sure your code lints.
6. Issue that pull request!

## Pull Request Guidelines

- The PR title should be descriptive and use [Conventional Commits](https://www.conventionalcommits.org/).
- Provide a clear description of the changes.
- Link any related issues.

## Testing

Run the tests using PHPUnit:

```bash
./vendor/bin/phpunit
```

## Data Updates

If you need to update the country data:
1. Update the `scripts/build.php` if necessary.
2. Run `php scripts/build.php`.
3. Commit the changes to `data/compiled/countries.php`.

---

Thank you for contributing!
