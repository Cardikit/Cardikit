<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?= htmlspecialchars($title ?? 'Categories'); ?></title>
    </head>
    <body>
        <h1><?= htmlspecialchars($title ?? 'Categories'); ?></h1>

        <?php if (!empty($categories ?? [])): ?>
            <section>
                <h2>All Categories</h2>
                <ul>
                    <?php foreach ($categories as $category): ?>
                        <li>
                            <a href="/blog/<?= htmlspecialchars($category['slug']); ?>">
                                <?= htmlspecialchars($category['name']); ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </section>
        <?php endif; ?>

        <?php if (!empty($category ?? null)): ?>
            <section>
                <h2><?= htmlspecialchars($category['name']); ?></h2>
                <?php if (!empty($category['description'])): ?>
                    <p><?= htmlspecialchars($category['description']); ?></p>
                <?php endif; ?>
            </section>
        <?php endif; ?>

        <?php if (!empty($posts ?? [])): ?>
            <section>
                <h2>Posts</h2>
                <?php foreach ($posts as $post): ?>
                    <article>
                        <h3>
                            <a href="/blog/<?= htmlspecialchars($post['category_slug'] ?? ''); ?>/<?= htmlspecialchars($post['slug']); ?>">
                                <?= htmlspecialchars($post['title']); ?>
                            </a>
                        </h3>
                        <p><small><?= htmlspecialchars($post['published_at'] ?? $post['created_at'] ?? ''); ?></small></p>
                        <?php if (!empty($post['excerpt'])): ?>
                            <p><?= htmlspecialchars($post['excerpt']); ?></p>
                        <?php endif; ?>
                    </article>
                    <hr>
                <?php endforeach; ?>
            </section>
        <?php else: ?>
            <p>No posts found.</p>
        <?php endif; ?>
    </body>
</html>
