<?php header('Content-type: text/html; charset=utf-8'); ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
    "http://www.w3.org/TR/html4/loose.dtd">
<html lang="en">
    <?php echo $this->getChild($this->_head); ?>
    <body class="<?php echo $this->getBaseClass() ?>">

        <?php echo $this->getChild($this->_navBar); ?>

        <div class="container">
            <?php echo $this->getChild($this->_navBarMenu) ?>
            <div class="row admin-content">
                <div class="span12">
                    <?php echo $this->getChild($this->_content) ?>
                </div>
            </div>

            <footer class="footer">
                <?php echo $this->getChild($this->_footer); ?>
            </footer>
        </div>
    </body>
</html>
