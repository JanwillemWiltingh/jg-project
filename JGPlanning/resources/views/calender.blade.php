<head>
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.2.7/fullcalendar.min.css"/>
    <script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment.min.js"></script>
    </script>
</head>
<style>
    .fc-button {
        background: none;
    }
    .jumbotron {
        background: none;
    }
    .fc-right {
        display: none;
    }
    .fc-content {
        background: var(--pagination-color);
        color: white;
    }
    .fc-close::after {
        display: none;
    }
</style>
<div class=" jumbotron">
    <div class="row">
        <div class="panel panel-default">
            <div class="panel-body">
                {!! $roster->calendar() !!}
            </div>
        </div>
    </div>
</div>

<input value="calendar-{{$roster->script()->id}}" type="hidden" id="id_calender">

{!! $roster->script() !!}

