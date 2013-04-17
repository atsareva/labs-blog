<?php header('Content-type: text/html; charset=utf-8'); ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
    "http://www.w3.org/TR/html4/loose.dtd">
<html lang="en">
    <?php echo $this->getChild($this->_head); ?>

    <body class="<?php //echo $this->getBaseClass() ?>">

        <?php echo $this->getChild($this->_navBar); ?>

    <header class="header">
        <?php echo $this->getChild($this->_header); ?>
    </header>

    <div class="clearfix"></div>

    <div class="container">
        <div class="container-shadow">
            <div class="span4 bs-docs-sidebar">
                <?php echo $this->getChild($this->_mainNav) ?>
            </div>
            <div class="span8">
                <?php echo $this->getChild($this->_content) ?>
            </div>
        </div>
    </div>

    <footer class="footer">
        <?php echo $this->getChild($this->_footer) ?>
    </footer>

    <script type="text/javascript">
        $(function() {
            $(".nav.nav-list .parent").append("<i class='icon-chevron-right'></i>");
        })
    </script>
</body>
</html>
