# Release instructions

- Repository: `ITMOSOFT/laravel-affiliate-marketing`
- Composer: `itmosoft/laravel-affiliate-marketing`
- Description: `Affiliate program management, conversion attribution, and commission calculation for Laravel applications.`

Validate:
```bash
composer install
composer test
```
Do not tag or publish until PHPUnit and GitHub Actions pass.

Create a public repository named `laravel-affiliate-marketing` under ITMOSOFT, then run:
```bash
git init
git add .
git status
git commit -m "feat: add Laravel affiliate marketing package"
git branch -M main
git remote add origin https://github.com/ITMOSOFT/laravel-affiliate-marketing.git
git push -u origin main
```
After tests and CI pass:
```bash
git tag -a v1.0.0 -m "Release v1.0.0"
git push origin v1.0.0
```
Submit `https://github.com/ITMOSOFT/laravel-affiliate-marketing` to Packagist.
GitHub Release title: `v1.0.0 - Initial Release`.
