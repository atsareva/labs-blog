<?php if (isset($materialList)): ?>
    <h2>Главная страница</h2>
    <div class="separator"></div>
    <ul class="list-article">
        <?php foreach ($materialList->getData() as $material): ?>
            <li>
                <a href="<?php echo Core::getBaseUrl() ?>home?page=material&id=<?php echo $material->id; ?>"><?php echo $material->title ?></a>
            </li>
            <div class="separator"></div>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <div class="material-notfound">
        <h3>По Вашему запросу ничего не найдено.</h3>
    </div>
<?php endif; ?>