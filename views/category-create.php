<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?= htmlspecialchars($title ?? 'Create Category'); ?></title>
    </head>
    <body>
        <h1><?= htmlspecialchars($title ?? 'Create Category'); ?></h1>

        <form action="/blog/categories" method="POST">
            <div>
                <label for="name">Name</label>
                <input id="name" name="name" type="text" required>
            </div>

            <div>
                <label for="slug">Slug (optional)</label>
                <input id="slug" name="slug" type="text" placeholder="auto-generated from name">
            </div>

            <div>
                <label for="description">Description</label>
                <textarea id="description" name="description" rows="3"></textarea>
            </div>

            <button type="submit">Create Category</button>
        </form>
    </body>
</html>
