# WebNews

News server web interface

## Requirements

- PHP7+
- Composer

## Installation

### Download the project and install dependencies

composer create-project peehaa/web-news

### Set up the configuration

Copy `/config/config.sample.php` to `/config/config.php` and make the changes to it for your environment.

### Set up the web server

Set your document root to the `/public` directory and route all requests through the `/public/index.php` file.

## License

[MIT][mit]

## Security issues

If you found a security issue please contact directly by mail instead of using the issue tracker at codecollab-security@pieterhordijk.com

[mit]: http://spdx.org/licenses/MIT
