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
    /*.fc-content {*/
    /*    color: white;*/
    /*}*/
    .fc-close::after {
        display: none;
    }
</style>
<div class=" jumbotron">
    <div class="row">
        <div class="panel panel-default">
            <div class="panel-body">
                <div>
                    <div style="width: 15px; height: 15px; background-color: #1C88A4; display: inline-block;"></div>
                    <div  style="display: inline-block"> = dag kan bewerkt worden</div>
                </div>
                <div>
                    <div style="width: 15px; height: 15px; background-color: #CB6827; display: inline-block;"></div>
                    <div  style="display: inline-block"> = dag kan niet bewerkt worden</div>
                </div>
                {!! $roster->calendar() !!}
            </div>
        </div>
    </div>
</div>

<input value="calendar-{{$roster->script()->id}}" type="hidden" id="id_calender">

{!! $roster->script() !!}

