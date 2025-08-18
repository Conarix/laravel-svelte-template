# Svelte Inertia / Laravel Template

## Requirements

This template requires watchexec to be installed to run the concurrently file.

If watchexec cannot be installed, you can still run the local vite server with `npm run dev` but you will need to
manually run `php artisan ts:create-enum` for any enums changed.

## Local Development

If you have watchexec installed on the same platform as node and php, then you can run `npm run everything`. This will
run both the vite dev server, and a watch command to watch for updates to PHP enums and copy that over to JavaScript.

If you cannot run watchexec on the same platform as node and php, the you can run `npm run dev` for the local vite
server, and run `php artisan ts:create-enum` manually where necessary.
