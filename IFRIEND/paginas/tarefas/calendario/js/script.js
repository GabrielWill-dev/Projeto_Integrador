    var calendar;
    var Calendar = FullCalendar.Calendar;
    var events = [];
    $(function() 
    {
        if (!!scheds) {
            Object.keys(scheds).map(k => {
                var row = scheds[k]
                events.push({ id: row.idTarefa, title: row.tituloTarefa, start: row.dataInicioTarefa, end: row.dataConclusaoTarefa });
            })
        }
        var date = new Date()
        var d = date.getDate(),
            m = date.getMonth(),
            y = date.getFullYear()

        calendar = new Calendar(document.getElementById('calendar'), 
        {
            themeSystem: 'bootstrap5',
            initialView: 'dayGridMonth',
            locale: 'pt-br', //Idioma Español FullCalendar
            headerToolbar: {
                left: 'prev,next today',
                right: 'dayGridMonth,dayGridWeek,list',
                center: 'title',
            },
            selectable: true,
            themeSystem: 'bootstrap',
            //Eventos predeterminados aleatorios
            events: events,
            eventClick: function(info) 
            {
            var _details = $('#event-details-modal')
            var id = info.event.id
            if (!!scheds[id]) {
                _details.find('#title').text(scheds[id].tituloTarefa)
                _details.find('#descricao').text(scheds[id].descricaoTarefa)
                _details.find('#start').text(scheds[id].sdate)
                _details.find('#end').text(scheds[id].edate)
                _details.find('#edit,#delete').attr('data-id', id)
                _details.modal('show')
            } else {
                alert("Evento não selecionado.");
            }
            },
            eventDidMount: function(info) {
                // Hacer algo después de los eventos montados
            },
            editable: true
        });

        calendar.render();

        // listener de restablecimiento de formulario
        $('#schedule-form').on('reset', function() {
            $(this).find('input:hidden').val('')
            $(this).find('input:visible').first().focus()
        })

        // botão Editar
        $('#edit').click(function() {
            var id = $(this).attr('data-id')
            if (!!scheds[id]) {
                var _form = $('#schedule-form')
                console.log(String(scheds[id].start_datetime), String(scheds[id].start_datetime).replace(" ", "\\t"))
                _form.find('[name="id"]').val(id)
                _form.find('[name="title"]').val(scheds[id].title)
                _form.find('[name="descricao"]').val(scheds[id].descricao)
                _form.find('[name="start_datetime"]').val(String(scheds[id].start_datetime).replace(" ", "T"))
                _form.find('[name="end_datetime"]').val(String(scheds[id].end_datetime).replace(" ", "T"))
                $('#event-details-modal').modal('hide')
                _form.find('[name="title"]').focus()
            } else {
                alert("Event is undefined");
            }
        })

        // Botón Eliminar 
        $('#delete').click(function() {
            var id = $(this).attr('data-id')
            if (!!scheds[id]) {
                var _conf = confirm("Tem certeza de excluir esta Tarefa?");
                if (_conf === true) {
                    location.href = "paginas/tarefas/calendario/delete_tarefa.php?id=" + id;
                }
            } else {
                alert("Event is undefined");
            }
        })
    })