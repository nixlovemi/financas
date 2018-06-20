<?php
// vars ===
$Eventos = isset($Eventos) ? $Eventos: array();
// ========
?>

<script src="<?php echo base_url(); ?>js/jquery.min.js"></script>
<style>
#calendar {
  max-width: 98%;
  margin: 50px auto;
  background-color: #FFF;
}
#calendar .fc-button-next, #calendar .fc-button-prev{
  background-color: #fff;
  border-top: 1px solid #D5D5D5;
}
#calendar .fc-event{
  cursor: pointer;
}
</style>

<button onClick="openGerEscalas()" style="margin-top:30px;" type="button" class="btn btn-danger">GERENCIAR ESCALAS</button>
<div id='calendar'></div>

<script>

  $(document).ready(function() {

    $('#calendar').fullCalendar({
      ignoreTimezone: false,
      monthNames: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
      monthNamesShort: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
      dayNames: ['Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sabado'],
      dayNamesShort: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab'],
      titleFormat: {
        month: 'MMMM yyyy',
        week: "d[ MMMM][ yyyy]{ - d MMMM yyyy}",
        day: 'dddd, d MMMM yyyy'
      },
      columnFormat: {
        month: 'ddd',
        week: 'ddd d',
        day: ''
      },
      axisFormat: 'H:mm',
      timeFormat: {
        '': 'H:mm',
        agenda: 'H:mm{ - H:mm}'
      },
      buttonText: {
        today: "Hoje",
        month: "Mês",
        week: "Semana",
        day: "Dia"
      },
      fixedWeekCount: false,
      header: {
        left: 'prev,next today',
        center: 'title',
        right: 'month,agendaWeek,agendaDay,list'
      },
      defaultDate: '2018-06-06',
      editable: false,
      eventLimit: true, // allow "more" link when too many events
      eventClick: function(event) {
        if (event.id) {
          var fueId = event.id;
          fncEditarEscala(fueId, true);
        }
      },
      eventSources: <?php echo json_encode($Eventos); ?> /*[
        {
          events: [ // put the array in the `events` property
            {
              title  : 'event1',
              start  : '2018-06-01'
            },
            {
              title  : 'event2',
              start  : '2018-06-01',
              end    : '2018-06-07'
            },
            {
              title  : 'event3',
              start  : '2018-06-10 12:30:00',
            }
          ],
          color: 'black',     // an option!
          textColor: 'yellow' // an option!
        },
        {
          events: [ // put the array in the `events` property
            {
              title  : 'event1',
              start  : '2018-06-10'
            },
            {
              title  : 'event2',
              start  : '2018-06-11',
              end    : '2018-06-12'
            },
            {
              title  : 'event3',
              start  : '2018-06-15 12:30:00',
            },
            {
              title  : 'event4',
              start  : '2018-06-15 12:40:00',
              end    : '2018-06-15 15:40:00',
            }
          ],
          color: 'orange',     // an option!
          textColor: 'yellow' // an option!
        }
      ]*/

      /*events: [
        {
          title: 'All Day Event',
          start: '2018-03-01'
        },
        {
          title: 'Long Event',
          start: '2018-03-07',
          end: '2018-03-10'
        },
        {
          id: 999,
          title: 'Repeating Event',
          start: '2018-03-09T16:00:00'
        },
        {
          id: 999,
          title: 'Repeating Event',
          start: '2018-03-16T16:00:00'
        },
        {
          title: 'Conference',
          start: '2018-03-11',
          end: '2018-03-13'
        },
        {
          title: 'Meeting',
          start: '2018-03-12T10:30:00',
          end: '2018-03-12T12:30:00'
        },
        {
          title: 'Lunch',
          start: '2018-03-12T12:00:00'
        },
        {
          title: 'Meeting',
          start: '2018-03-12T14:30:00'
        },
        {
          title: 'Happy Hour',
          start: '2018-03-12T17:30:00'
        },
        {
          title: 'Dinner',
          start: '2018-03-12T20:00:00'
        },
        {
          title: 'Birthday Party',
          start: '2018-03-13T07:00:00'
        },
        {
          title: 'Click for Google',
          url: 'http://google.com/',
          start: '2018-03-28'
        }
      ]*/
    });

  });

</script>
