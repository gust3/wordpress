    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Изменить задачу #<?=$task->id;?></h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">

            <div class="input-group pb-1">
                <span class="input-group-text" id="add-name">Название</span>
                <input name="name" type="text" required class="form-control" placeholder="Название задачи"
                       aria-label="Название задачи" aria-describedby="add-name" value="<?=$task->name;?>">
            </div>
            <div class="input-group">
                <span class="input-group-text">Описание</span>
                <textarea name="description" class="form-control" required aria-label="Описание"><?=$task->description;?></textarea>
            </div>

        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
            <button type="submit" class="btn btn-primary">Сохранить</button>
        </div>
    </div>
    <input type="hidden" name="id" value="<?=$task->id;?>">
    <input type="hidden" name="action" value="optm_update_new_task">

