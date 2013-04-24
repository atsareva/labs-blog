<div class="author-info">
    <h2><?php echo $user->getFullName() ?></h2>
    <div class="separator"></div>
    <div class="row-fluid">
        <div class="span4">
            <?php if ($user->getPhoto()): ?>
                <img alt="<?php echo $user->getFullName() ?>" title="<?php echo $user->getFullName() ?>" src="<?php echo Core::getBaseUrl() . 'assets' . $user->getPhoto() ?>" />
            <?php else: ?>
                <img alt="<?php echo $user->getFullName() ?>" title="<?php echo $user->getFullName() ?>" src="<?php echo Core::getBaseUrl() . 'assets/upload/default.png' ?>" />
            <?php endif; ?>
        </div>
        <div class="span8">
            <table>
                <tr>
                    <td class="label">Факультет</td>
                    <td><?php echo $faculty->getName() ?></td>
                </tr>
                <tr>
                    <td class="label">Кафедра</td>
                    <td><?php echo $department->getName() ?></td>
                </tr>
                <tr>
                    <td class="label">Статус</td>
                    <td><?php echo $userStatus->getName() ?></td>
                </tr>
            </table>
            <p class="contacts">Контактная информация</p>
            <div class="separator"></div>
            <table>
                <tr>
                    <td class="label">Email</td>
                    <td><?php echo $user->getEmail() ?></td>
                </tr>
                <?php if ($user->getSkype()): ?>
                    <tr>
                        <td class="label">Skype</td>
                        <td><?php echo $user->getSkype() ?></td>
                    </tr>
                <?php endif; ?>
                <?php if ($user->getPhone()): ?>
                    <tr>
                        <td class="label">Телефон</td>
                        <td><?php echo $user->getPhone() ?></td>
                    </tr>
                <?php endif; ?>
            </table>
        </div>
    </div>
</div>