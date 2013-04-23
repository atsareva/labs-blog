<h2><?php echo $category->getTitle() ?></h2>
<div class="separator"></div>
<p class="post-date">
    <?php if ($material->getModified()): ?>
        <?php echo date('d/m/Y', $material->getModified()) ?>
    <?php else: ?>
        <?php echo date('d/m/Y', $material->getCreated()) ?>
    <?php endif; ?>
</p>
<div class="description">
    <h4><?php echo $material->getTitle() ?></h4>
    <?php echo $material->getFullText() ?>
</div>
<div class="author-info-small">
    <p>
        <a href="<?php echo Core::getBaseUrl() . 'profile/index/' . $user->getId(); ?>">
            <i class="icon-user"></i>
            <span><?php echo $user->getFullName() ?></span>
        </a>
    </p>
    <p>
        <a href="<?php echo Core::getBaseUrl() . 'convert/index/' . $material->getId() ?>">
            <img title="Скачать материал" width="30" alt="" src="<?php echo Core::getBaseUrl() ?>/assets/img/pdf-icon.png" />
        </a>
    </p>
</div>