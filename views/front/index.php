<div class="span8"> 
    <div class="row material">
          <center>
                <h2>
                    Главная страница.
                </h2>
            </center>
        <?php if (isset($materials) && !empty($materials)): ?>
            <?php if (isset($materials[0]) && is_array($materials[0])): ?>
                <?php foreach ($materials as $material): ?>
        <hr/>
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
                    <hr/>
                <center>
                    <h2>
                        <a href="/home?page=material&id=<?php echo $materials['id']; ?>"><?php echo $materials['title']; ?></a>
                    </h2>
                </center>
                <br/>
                <?php echo $materials['intro_text']; ?>
                <hr/>
                <br/>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>