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
@if(App\Models\Browser::isMobile())
    <style>
        .fc {
            left: -15px;
            width: 500%;
        }
    </style>
@endif

<div class=" jumbotron">
    <div class="row">
        <div class="panel panel-default">
            <div class="panel-body">
                @if(App\Models\Browser::isMobile())
                    <div class="legenda-mobile">
                        <div>
                            <div style="width: 10px; height: 10px; background-color: #1C88A4; display: inline-block;"></div>
                            <div  style="display: inline-block"> = dag kan bewerkt worden</div>
                        </div>
                        <div>
                            <div style="width: 10px; height: 10px; background-color: #CB6827; display: inline-block;"></div>
                            <div  style="display: inline-block"> = dag kan niet bewerkt worden</div>
                        </div>
                    </div>
                @else
                    <div>
                        <div style="width: 15px; height: 15px; background-color: #1C88A4; display: inline-block;"></div>
                        <div  style="display: inline-block"> = dag kan bewerkt worden</div>
                    </div>
                    <div>
                        <div style="width: 15px; height: 15px; background-color: #CB6827; display: inline-block;"></div>
                        <div  style="display: inline-block"> = dag kan niet bewerkt worden</div>
                    </div>
                @endif
                {!! $roster->calendar() !!}
            </div>
        </div>
    </div>
</div>

<input value="calendar-{{$roster->script()->id}}" type="hidden" id="id_calender">

{!! $roster->script() !!}

