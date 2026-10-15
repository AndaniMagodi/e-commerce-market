# Security implementation

## SQL injection

Eloquent queries bind search and form values. No user input is interpolated into raw SQL. Sort fields and status filters are fixed/allow-listed. Product category IDs are checked against existing categories and a database foreign key.

## XSS

Blade `{{ }}` escapes product names, descriptions, category names, administrator names and search terms. Descriptions are plain text, not rich HTML. JavaScript does not inject product text with innerHTML. Tests submit script and event-handler text and verify escaped rendering. Licensed local catalogue photos are separate from uploaded content.

## CSRF and sessions

Routes use the web middleware group. Every state-changing form includes `@csrf`; update/delete also use Laravel method spoofing. Logout is POST. An unauthenticated HTTP POST to login without a token was verified to return 419. Standard feature tests bypass CSRF by Laravel design, so that live check is separate. Login regenerates the session ID; logout invalidates the session and regenerates the CSRF token.

## Passwords and authentication

Passwords are hashed using Laravel Hash; the model also has a hashed cast. Login uses Auth::attempt with an admin-status condition. Errors do not identify whether an email exists. Login POST requests have a five-per-minute IP throttle. Production deployments should evaluate email-plus-IP limits, shared-network behaviour and stronger authentication according to their environment.

## Authorization

All admin resource routes are inside the auth/admin middleware group. The middleware checks is_admin on every request. ProductRequest additionally authorizes administrators. Ordinary authenticated users receive 403. Guests redirect to login. No public registration or role-update route exists, and is_admin is excluded from the user's fillable attributes.

## Input validation

Product validation requires a bounded name and description, an existing category, a non-negative price with at most two decimal places, a bounded non-negative integer quantity and a boolean publication flag. Controllers only use validated fields. Clients cannot supply image_path; the server generates it. Browser validation improves feedback; server validation remains authoritative.

## Image uploads

Uploads require decoded-image validation, JPEG/PNG/WebP MIME types and matching permitted filename extensions. Maximum size is 2 MB and dimensions are limited to 3000 by 3000. SVG, PHP and other active formats are rejected. GD decodes the raster and re-encodes a new WebP, discarding the original byte stream and metadata. Paths use generated UUIDs. Files live on Laravel's public storage disk, outside the application public directory, with a storage symlink. Production web servers must not execute uploaded files and should use correct MIME handling and nosniff headers. On database save failure, a newly stored image is removed; replaced/deleted product images are cleaned after database success. Cleanup only targets generated products/ paths, preserving shared seed photos. Storage cleanup failures remain an operational maintenance concern; file storage and SQL do not form a distributed transaction.

## Credentials and environment

.env is ignored by Git. .env.example contains placeholders, not local credentials. The dedicated local database only listens on loopback and its application user is restricted to the assessment database. Private database files are outside this repository. The admin setup command uses hidden password prompts. Never include credentials, session cookies or full exception dumps in submission documentation.

## Response headers

A small middleware (`SecurityHeaders`) adds `X-Content-Type-Options: nosniff`, `X-Frame-Options: DENY`, a restrictive `Referrer-Policy` and `Permissions-Policy` to every web response. This blocks MIME sniffing of uploaded files, clickjacking of the admin forms and unnecessary browser feature access. A Content-Security-Policy is a sensible next step but was left out to avoid breaking the Google Fonts import without full browser testing.

## Verification and limits

Tests exercise authorization, CRUD persistence, file replacement/deletion, invalid values, disallowed/oversized uploads, escaped output, hidden products, login/logout and throttling. Composer audit was clean during implementation. These checks support the implementation; they are not a penetration-test certification. Debug mode is appropriate locally but must be disabled before public deployment. HTTPS and web-server configuration are deployment responsibilities.
