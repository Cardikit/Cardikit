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

        <?php if (!empty($categories ?? [])): ?>
            <nav>
                <strong>Categories:</strong>
                <ul>
                    <?php foreach ($categories as $category): ?>
                        <li>
                            <a href="/blog?category=<?= htmlspecialchars($category['slug']); ?>">
                                <?= htmlspecialchars($category['name']); ?>
                                <?php if (!empty($activeCategory) && $activeCategory === $category['slug']): ?>
                                    (active)
                                <?php endif; ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </nav>
        <?php endif; ?>

        <?php if (!empty($post)): ?>
            <article>
                <p><em><?= htmlspecialchars($post['category_name'] ?? ''); ?></em></p>
                <h2><?= htmlspecialchars($post['title']); ?></h2>
                <p><?= nl2br(htmlspecialchars($post['content'])); ?></p>
                <p><small>Published: <?= htmlspecialchars($post['published_at'] ?? $post['created_at']); ?></small></p>
            </article>
        <?php else: ?>
            <?php if (!empty($posts ?? [])): ?>
                <section>
                    <?php foreach ($posts as $blog): ?>
                        <article>
                            <h2>
                                <a href="/blog/<?= htmlspecialchars($blog['category_slug'] ?? ''); ?>/<?= htmlspecialchars($blog['slug']); ?>">
                                    <?= htmlspecialchars($blog['title']); ?>
                                </a>
                            </h2>
                            <p><em><?= htmlspecialchars($blog['category_name'] ?? ''); ?></em></p>
                            <?php if (!empty($blog['excerpt'])): ?>
                                <p><?= htmlspecialchars($blog['excerpt']); ?></p>
                            <?php endif; ?>
                            <p><small>Published: <?= htmlspecialchars($blog['published_at'] ?? $blog['created_at']); ?></small></p>
                        </article>
                        <hr>
                    <?php endforeach; ?>
                </section>
            <?php else: ?>
                <p>No posts yet.</p>
            <?php endif; ?>
        <?php endif; ?>
    </body>
</html>
