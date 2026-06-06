# Yii + Mailexam

Minimal [Yii 2](https://www.yiiframework.com/) example that sends test mail through [Mailexam](https://mailexam.io/) SMTP via [yii2-symfonymailer](https://github.com/yiisoft/yii2-symfonymailer).

Based on the [Mailexam Yii guide](https://wiki.mailexam.ru/en/examples/yii/).

## What you need

- A Mailexam account and a project with SMTP credentials.
- PHP 8.2+ and [Composer](https://getcomposer.org/).

From your Mailexam welcome email or dashboard:

| Variable | Description |
|----------|-------------|
| `MAILEXAM_LOGIN` | SMTP login (for example, `xxxxx`) |
| `MAILEXAM_PASSWORD` | SMTP password (paired with the login) |
| Host | `{MAILEXAM_LOGIN}.mailexam.io` (built in `config/web.php`) |

## Quick start (host)

1. Install dependencies:

```bash
composer install
```

2. Copy the example environment file and fill in your credentials:

```bash
cp .env.example .env
```

3. Edit `.env`:

```env
MAILEXAM_LOGIN=YOUR_LOGIN
MAILEXAM_PASSWORD=YOUR_PASSWORD
MAILEXAM_PORT=587
MAIL_FROM=noreply@example.test
```

4. Run the built-in PHP server:

```bash
php -S 127.0.0.1:8080 -t web
```

5. Send a test message:

```bash
curl -X POST 'http://127.0.0.1:8080/index.php?r=mail/test' \
  -H 'Content-Type: application/json' \
  -d '{"to":"user@example.test","subject":"Test","body":"Hello"}'
```

The message appears in the Mailexam dashboard → your project → inbox.

With Apache (Docker below) the same route is available as `POST /mail/test`.

## Environment variables

| Variable | Required | Default | Description |
|----------|----------|---------|-------------|
| `MAILEXAM_LOGIN` | yes | — | SMTP login; also used to build the host name |
| `MAILEXAM_PASSWORD` | yes | — | SMTP password |
| `MAILEXAM_PORT` | no | `587` | SMTP port (`587`, `2525`, or `25`) |
| `MAIL_FROM` | no | `noreply@example.test` | Sender address |

## Project layout

```
.
├── composer.json
├── config/web.php              # mailer component (Mailexam SMTP)
├── controllers/MailController.php
├── web/index.php               # loads .env via phpdotenv
├── .env.example
├── Dockerfile                  # for local debugging only
└── docker-compose.yml
```

## Docker (debugging)

Docker is provided for local debugging. For day-to-day development, run the app on the host (see above).

```bash
cp .env.example .env
# edit .env with your credentials

docker compose up --build
```

Then call the pretty URL on the mapped port:

```bash
curl -X POST http://127.0.0.1:8080/mail/test \
  -H 'Content-Type: application/json' \
  -d '{"to":"user@example.test","subject":"Test","body":"Hello"}'
```

## CI

Set these secrets in your CI environment:

```yaml
variables:
  MAILEXAM_LOGIN: $MAILEXAM_LOGIN
  MAILEXAM_PASSWORD: $MAILEXAM_PASSWORD
  MAILEXAM_PORT: "587"
  MAIL_FROM: "noreply@example.test"
```

After sending a message in a test, verify delivery via the [Mailexam API](https://mailexam.io/api).

## Troubleshooting

**Message not sent, files in `runtime/mail`**

- Check `useFileTransport => false` in the mailer configuration.

**TLS or connection error**

- Host must be `{login}.mailexam.io`, where `{login}` matches `MAILEXAM_LOGIN`.
- Login and password must come from the same Mailexam project.

**Environment variables are empty**

- Ensure `.env` exists and `Dotenv::createImmutable(...)->safeLoad()` runs in `web/index.php`.

**Message not in the dashboard**

- Open the inbox of the same Mailexam project.
- Check `runtime/logs/app.log` for SMTP errors.

## See also

- [Mailexam Yii guide (wiki)](https://wiki.mailexam.ru/en/examples/yii/)
- [Laravel reference implementation](https://github.com/mailexam/Laravel) — another PHP framework
- [Sending mail in Yii 2](https://www.yiiframework.com/doc/guide/2.0/en/tutorial-mailing)
- [Mailexam API documentation](https://mailexam.io/api)
