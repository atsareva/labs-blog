<div class="span8"> 
    <div class="row material">
        <?php if (isset($user)): ?>
            <center>
                <h2 style="margin: 0 0 30px;">
                    <?php echo $user['full_name']; ?>
                </h2>
            </center>
            <br/>
            <div class="row">
                <div class="span3">
                    <img alt="" src="/assets/resize/timthumb.php?src=<?php echo $user['photo']; ?>&h=250&w=250&zc=1" />
                </div>
                <div class="span4" style="width: 370px;">
                    <table class="profile-info">
                        <?php if (isset($faculty) && !empty($faculty)): ?>
                            <tr>
                                <td class="span2">
                                   <h6>Факультет</h6>
                                </td>
                                <td>
                                    <h5><?php echo $faculty['name']; ?></h5>
                                </td>
                            </tr>
                        <?php endif; ?>
                        <?php if (isset($department) && !empty($department)): ?>
                            <tr>
                                <td class="span2">
                                    <h6>Кафедра</h6>
                                </td>
                                <td>
                                    <h5><?php echo $department['name']; ?></h5>
                                </td>
                            </tr>
                        <?php endif; ?>
                        <?php if (isset($user_status) && !empty($user_status)): ?>
                            <tr>
                                <td class="span2">
                                    <h6>Статус</h6>
                                </td>
                                <td>
                                    <h5><?php echo $user_status['name']; ?></h5>
                                </td>
                            </tr>
                        <?php endif; ?>
                            <?php if (isset($user['email']) && !empty($user)): ?>
                            <tr>
                                <td class="span2">
                                    <h6 style="margin-top: 20px;">Email</h6>
                                </td>
                                <td>
                                    <h5 style="margin-top: 20px;"><?php echo $user['email']; ?></h5>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </table>
                </div>
            </div>
        <?php else: ?>
            <center>
                <h2>
                    По Вашему запросу никто не найден
                </h2>
            </center>
        <?php endif; ?>
    </div>
</div>
