<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?= $title; ?></title>
    </head>
    <body>
        <h1><?= $title; ?></h1>

        <form action="/blog" method="POST">
            <div>
                <label for="title">Title</label>
                <input id="title" name="title" type="text" required>
            </div>

            <div>
                <label for="slug">Slug (optional)</label>
                <input id="slug" name="slug" type="text" placeholder="auto-generated if blank">
            </div>

            <div>
                <label for="category">Category</label>
                <select id="category" name="category_id" required>
                    <option value="">-- Select a category --</option>
                    <?php foreach ($categories ?? [] as $category): ?>
                        <option value="<?= (int) $category['id']; ?>">
                            <?= htmlspecialchars($category['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label for="status">Status</label>
                <select id="status" name="status">
                    <option value="draft">Draft</option>
                    <option value="published">Published</option>
                </select>
            </div>

            <div>
                <label for="cover_image_url">Cover Image URL</label>
                <input id="cover_image_url" name="cover_image_url" type="url" placeholder="https://example.com/image.jpg">
            </div>

            <div>
                <label for="excerpt">Excerpt</label>
                <textarea id="excerpt" name="excerpt" rows="3"></textarea>
            </div>

            <div>
                <label for="content">Content</label>
                <textarea id="content" name="content" rows="10" required></textarea>
            </div>

            <button type="submit">Create Post</button>
        </form>
    </body>
</html>
