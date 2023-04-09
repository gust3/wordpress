document.addEventListener('DOMContentLoaded', function() {
    //активируем бутстраповскую модалку для создания
    var optmModal = new bootstrap.Modal(document.getElementById('ModalTaskEdit'));
    //активируем бутстраповскую модалку для изменения
    var optmModalUpdate = new bootstrap.Modal(document.getElementById('ModalTaskEditUpdate'));
    //ставим слушателя на добавление задачи в модальной форме
    document.querySelector("#create_new_optm").addEventListener("submit", function(e) {
        e.preventDefault();    //stop form from submitting
        url = document.querySelector("#create_new_optm").getAttribute('data-url');
        var form = document.getElementById('create_new_optm');
        var params = new FormData(form);
        //делаем аякс запрос
        dbOptmAjaxAction(url, params, function(result) {
            //подменяем во враппере хтмл от аякса
            document.getElementById("optm_widget_wrap").innerHTML = result;
            //закрываем модалку
            optmModal.hide();
        });
        form.reset();
    });
    //ставим слушателя на изменении задачи в модальной форме
    document.querySelector("#update_new_optm").addEventListener("submit", function(e) {
        e.preventDefault();    //stop form from submitting
        url = document.querySelector("#update_new_optm").getAttribute('data-url');
        var form = document.getElementById('update_new_optm');
        var params = new FormData(form);
        //делаем аякс запрос
        dbOptmAjaxAction(url, params, function(result){
            //подменяем во враппере хтмл от аякса
            document.getElementById("optm_widget_wrap").innerHTML = result;
            //закрываем модалку
            optmModalUpdate.hide();
        });
        form.reset();
    });

    //чтобы не делать копипасты делаем общую функцию добавления в базу данных, так как в целом ее можно
    //унифицировать
    //в параметр actions передаем функцию в которой будем делать какие то не стандартные действия которые
    //не будут характерны для всех событий
    function dbOptmAjaxAction(url, params, actions, needappend = 1) {
        fetch(url, {
            method: 'POST',
            body: params
        })
            .then(response => response.text())
            .then(function(result){
                    if (needappend == 1){
                        //подменяем во враппере хтмл от аякса
                        document.getElementById("optm_widget_wrap").innerHTML = result;
                    }
                    actions(result);
                }
            );
    }

    //вешаем события удаления
    document.querySelector("body").addEventListener("click", function(event) {
        if(!event.target.classList.contains("optm_delete")) return;
        event.preventDefault();
        if (confirm('Вы уверены что хотите удалить задачу?')) {
            var params = new FormData();

            id = event.target.getAttribute('data-id');
            params.append('id', id);
            params.append('action', 'optm_delete_task');

            url = event.target.getAttribute('href');

            dbOptmAjaxAction(url, params, function(){});
        }
    });
    //вешаем событие изменения статуса
    document.querySelector("body").addEventListener("click", function(event) {
        if(!event.target.classList.contains("optm_made")) return;
        event.preventDefault();
        var params = new FormData();

        id = event.target.getAttribute('data-id');
        state = event.target.getAttribute('data-state');


        params.append('id', id);
        params.append('state', state);
        params.append('action', 'optm_state_to_task');

        url = event.target.getAttribute('href');
        dbOptmAjaxAction(url, params, function(){});
    });
    //вешаем событие изменения задачи вызывающее форму
    document.querySelector("body").addEventListener("click", function(event) {
        if(!event.target.classList.contains("optm_update")) return;
        event.preventDefault();
        var params = new FormData();

        id = event.target.getAttribute('data-id');

        params.append('id', id);
        params.append('action', 'optm_update_form');

        url = event.target.getAttribute('href');
        dbOptmAjaxAction(url, params, function(result){
            document.getElementById("optm_wrapper_update_task").innerHTML = result;
            optmModalUpdate.show();
        }, 0);
    });


}, false);

