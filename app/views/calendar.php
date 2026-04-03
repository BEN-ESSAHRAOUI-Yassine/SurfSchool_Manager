<h2>Lesson Calendar</h2>

<div id="calendar"></div>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var cal = new FullCalendar.Calendar(document.getElementById('calendar'), {
        initialView: 'dayGridMonth',
        headerToolbar: {
            left:   'prev,next today',
            center: 'title',
            right:  'dayGridMonth,timeGridWeek,listWeek'
        },
        events: '<?= BASE_URL ?>api/events.php',
        eventColor: '#0EA5E9',
        height: 'auto',
        eventClick: function(info) {
            alert('Lesson: ' + info.event.title);
        }
    });
    cal.render();
});
</script>