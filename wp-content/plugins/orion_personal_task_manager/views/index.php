<?php if ($ajax == 0) { ?>
    <div id="optm_widget_wrap">
<?php } ?>
    <table class="table table-striped">
        <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Название</th>
            <th scope="col">Описание</th>
            <th scope="col">Дата и время</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        <?php
        if ($data) {
            foreach ($data as $k => $item) {
                ?>
                <tr>
                    <td><?= $k + 1; ?></td>
                    <td>
                        <? if ($item->state == 1) {?>
                            <i><?= esc_html($item->name); ?></i>
                        <? } else {?>
                            <b><?= esc_html($item->name); ?></b>
                        <? } ?>
                    </td>
                    <td><?= esc_html($item->description); ?></td>
                    <td><?= date('d.m.Y h:i', $item->date) ?></td>
                    <td>
                        <a data-id="<?= $item->id; ?>" href="<?= get_home_url() . '/wp-admin/admin-ajax.php'; ?>"
                           class="optm_delete">Удалить</a>
                        <a data-id="<?= $item->id; ?>" href="<?= get_home_url() . '/wp-admin/admin-ajax.php'; ?>" class="optm_update">Изменить</a>
                        <? if ($item->state == 0) { ?>
                            <a data-state="1" data-id="<?= $item->id; ?>" href="<?= get_home_url() . '/wp-admin/admin-ajax.php'; ?>" class="optm_made">Задача выполнена</a>
                        <? } else { ?>
                            <a data-state="0" data-id="<?= $item->id; ?>" href="<?= get_home_url() . '/wp-admin/admin-ajax.php'; ?>" class="optm_made">Вернуть в работу</a>
                        <? } ?>
                    </td>
                </tr>
                <?php
            }
        } else {
            ?>
            <tr>
                <td colspan="5" class="text-center">Нет записей</td>
            </tr>
            <?php
        }
        ?>
        </tbody>
    </table>
<?php if ($ajax == 0) { ?>
    </div>
    <div class="text-center">
        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#ModalTaskEdit">Добавить
            задачу
        </button>
        <a href="<?= get_home_url();?>/wp-login.php?action=logout&redirect_to=<?=get_home_url();?>">
            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#ModalTaskEdit">Выйти</button>
        </a>
    </div>
    <!-- Modal Create-->
    <div class="modal fade" id="ModalTaskEdit" tabindex="-1" aria-labelledby="ModalTaskEdit" aria-hidden="true">
        <div class="modal-dialog">
            <form id="create_new_optm" data-url="<?= get_home_url() . '/wp-admin/admin-ajax.php'; ?>">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Добавить задачу</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                        <div class="input-group pb-1">
                            <span class="input-group-text" id="add-name">Название</span>
                            <input name="name" type="text" required class="form-control" placeholder="Название задачи" aria-label="Название задачи" aria-describedby="add-name" />
                        </div>
                        <div class="input-group">
                            <span class="input-group-text">Описание</span>
                            <textarea name="description" class="form-control" required aria-label="Описание"></textarea>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                        <button type="submit" class="btn btn-primary">Создать</button>
                    </div>
                </div>
                <input type="hidden" name="action" value="optm_insert_new_task" />
            </form>
        </div>
    </div>
    <!-- Modal Update-->
    <div class="modal fade" id="ModalTaskEditUpdate" tabindex="-1" aria-labelledby="ModalTaskEditUpdate" aria-hidden="true">
        <div class="modal-dialog">
            <form id="update_new_optm" data-url="<?= get_home_url() . '/wp-admin/admin-ajax.php'; ?>">
                <div id="optm_wrapper_update_task"></div>
                <input type="hidden" name="action" value="optm_update_new_task" />
            </form>
        </div>
    </div>
<?php } ?>