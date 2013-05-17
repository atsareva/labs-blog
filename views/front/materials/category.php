<h2><?php echo $category->getTitle() ?></h2>
<div class="separator"></div>
<p class="post-date">
    <?php if ($category->getModified()): ?>
        <?php echo date('d/m/Y', $category->getModified()) ?>
    <?php else: ?>
        <?php echo date('d/m/Y', $category->getCreated()) ?>
    <?php endif; ?>
</p>
<div class="description">
    <?php echo $category->getFullText() ?>
</div>
<div class="author-info-small">
    <a href="<?php echo Core::getBaseUrl() . 'profile/index/' . $user->getId(); ?>">
        <i class="icon-user"></i>
        <span><?php echo $user->getFullName() ?></span>
    </a>
</div>
<div class="separator"></div>
<?php if (count($materialList) > 0): ?>
    <ul class="list-article">
        <?php foreach ($materialList->getData() as $material): ?>
            <li>
                <a href="<?php echo Core::getBaseUrl() ?>home?page=material&id=<?php echo $material->id; ?>"><?php echo $material->title ?></a>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>