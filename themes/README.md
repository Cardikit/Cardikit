# Card themes

Public card pages (`/c/{id}`) are rendered from templates in this directory.

- `default/` holds the initial theme. Add new themes by creating another folder with a `template.php`.
- Set `CARD_THEME=default` (or your folder name) in the environment to switch themes globally.
- Templates receive `$card` (name, color, images, items) and `$qrImageUrl` and should handle escaping output themselves.
