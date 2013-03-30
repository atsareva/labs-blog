<div class="span8"> 
    <div class="row material">
        <?php if (isset($category) && !empty($category)): ?>
            <?php if (isset($category['title'])): ?>
                <center>
                    <h2>
                        <?php echo $category['title']; ?>
                    </h2>
                </center>
                <br/>
            <?php endif; ?>
            <?php if (isset($category['full_text'])): ?>
                <?php echo $category['full_text']; ?>
                <hr/>
                <table>
                    <tr>
                        <td>
                            <span class="material-info">Автор -</span> 
                            <a href="/profile?id=<?php echo $user['id']; ?>">
                                <?php if (isset($user['photo'])): ?>
                                    <img alt="" src="/assets/resize/timthumb.php?src=<?php echo $user['photo']; ?>&h=20&w=20&zc=1" />
                                <?php endif; ?>
                                <?php echo $user['user_name']; ?> 
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <span class="material-info">Создано - </span><?php echo date('d/m/Y', $category['created']); ?> 
                        </td>
                    </tr>
                </table>
                <hr/>
                <br/>
            <?php endif; ?>
            <?php if (isset($material_list) && !empty($material_list)): ?>
                <?php if (isset($material_list[0]) && is_array($material_list[0])): ?>
                    <?php foreach ($material_list as $material): ?>
                        <center>
                            <h2>
                                <a href="/home?page=material&id=<?php echo $material['id']; ?>"><?php echo $material['title']; ?></a>
                            </h2>
                        </center>
                        <br/>
                        <?php echo $material['intro_text']; ?>
                        <hr/>
                        <br/>
                    <?php endforeach; ?>
                <?php else: ?>
                    <center>
                        <h2>
                            <a href="/home?page=material&id=<?php echo $material_list['id']; ?>"><?php echo $material_list['title']; ?></a>
                        </h2>
                    </center>
                    <br/>
                    <?php echo $material_list['intro_text']; ?>
                    <hr/>
                    <br/>
                <?php endif; ?>
            <?php endif; ?>
        <?php elseif (isset($material) && !empty($material)): ?>
            <center>
                <h2>
                    <?php echo $material['title']; ?>
                </h2>
            </center>
            <?php echo $material['full_text']; ?>
            <hr/>
            <table>
                <tr>
                    <td>
                        <span class="material-info">Автор -</span> 
                        <a href="/profile?id=<?php echo $user['id']; ?>">
                            <?php if (isset($user['photo'])): ?>
                                <img alt="" src="/assets/resize/timthumb.php?src=<?php echo $user['photo']; ?>&h=20&w=20&zc=1" />
                            <?php endif; ?>
                            <?php echo $user['user_name']; ?> 
                        </a>
                    </td>
                </tr>
                <tr>
                    <td>
                        <span class="material-info">Создано - </span><?php echo date('d/m/Y', $material['created']); ?> 
                    </td>
                </tr>
                <?php if (isset($material['modified']) && !empty ($material['modified'])): ?>
                    <tr>
                       <td>
                        <span class="material-info">Изменено -</span> 
                        <?php echo date('d/m/Y', $material['created']); ?>, 
                        <a href="/profile?id=<?php echo $mod_user['id']; ?>">
                            <?php if (isset($mod_user['photo'])): ?>
                                <img alt="" src="/assets/resize/timthumb.php?src=<?php echo $mod_user['photo']; ?>&h=20&w=20&zc=1" />
                            <?php endif; ?>
                            <?php echo $mod_user['user_name']; ?> 
                        </a>
                    </td>
                    </tr>
                <?php endif; ?>
                <tr>
                    <td>
                        <span class="material-info">Скачать - </span>
                        <a href="/convert?id=<?php echo $material['id'] ?>">
                            <img width="30" height="40" alt="" src="/assets/img/pdf.jpg" /> 
                        </a>
                    </td>
                </tr>
                
            </table>
        <?php else: ?>
            <center>
                <h2>
                    По Вашему запросу ничего не найдено.
                </h2>
            </center>
        <?php endif; ?>
    </div>
</div>