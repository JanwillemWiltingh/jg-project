@if(request('admin'))
    <div class="modal fade" id="disableModal" tabindex="-1" role="dialog" aria-labelledby="a" aria-hidden="true" >
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Beschikbaarheid Aangeven</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                    <p style="font-size: 25px; margin-bottom: 0; display: inline">Dagen</p>
                    <select class="form-control" style="width: 14%; height: 50% !important; display: inline" id="weekDropdown">
                        <option value="Uitzetten">Uitzetten</option>
                        <option value="Toevoegen">Toevoegen</option>
                    </select>
                    <div class="border-bottom"></div>
                    <br>

                    <div class="row" id="addDisable">
                            <form method="post" action="{{route('admin.rooster.disable_days', request('user'))}}">
                                @csrf
                                <label style="width: 100%">
                                    <p>Kies een dag:</p>
                                    <select class="form-control" name="weekday">
                                        @for($i = 1; $i <= count($weekDays); $i++)
                                            <option value="{{$i}}">{{$weekDays[$i]}}</option>
                                        @endfor
                                    </select>
                                </label>

                                <label style="width:  49.8%">
                                    <p>Kies een begin week:</p>
                                    <input class="form-control" type="week" name="start_week" id="start_week">
                                </label>

                                <label style="width: 49.8%">
                                    <p>Kies een eind week:</p>
                                    <input class="form-control" type="week" name="end_week" id="end_week">
                                </label>
                                <input type="submit" class="btn btn-success float-right">
                            </form>
                    </div>

                    <div class="row"  style="display: none" id="addWeeks">
                        <form method="post" action="{{route('rooster.availability', request('week'))}}">
                            @csrf

                            <input type="hidden" name="user_id" value="{{request('user')}}">

                            <label style="width: 100%">
                                <p>Kies een dag:</p>
                                <select class="form-control" name="weekday">
                                    @for($i = 1; $i <= count($weekDays); $i++)
                                        <option value="{{$i}}">{{$weekDays[$i]}}</option>
                                    @endfor
                                </select>
                            </label>
                            <label style="width: 49.8%">
                                <p>Begin Tijd:</p>
                                <input type="time" name="start_time" class="form-control" style="outline: none;" id="time_picker_av_start"  min="08:00" max="18:00">
                            </label>

                            <label style="width: 49.8%">
                                <p>Eind Tijd:</p>
                                <input type="time" name="end_time" class="form-control" style="outline: none;" id="time_picker_av_start" min="08:00" max="18:00">
                            </label>

                            <p style="font-size: 12px" class="text-warning">De tijden die u invult worden op halve uren en hele uren afgerond</p>
                            <label style="width: 100%">
                                <textarea rows="5" cols="68" placeholder="Comment (optioneel)" class="form-control" name="comment"></textarea>
                            </label>

                            <label style="width: 49.8%">
                                <p>Begin week:</p>
                                <input type="week" class="form-control" name="begin_week">
                            </label>

                            <label style="width: 49.8%">
                                <p>Eind week:</p>
                                <input type="week" class="form-control" name="week">
                            </label>

                            <input type="checkbox" id="switch" class="toggle-box " name="from_home" />
                            <label for="switch" class="toggle-label float-right" ></label>

                            <p class="float-right" style="margin-right: 15px">Van huis</p>
                            <br>
                            <br>
                            <button type="submit" class="btn btn-success float-right">Submit</button>
                        </form>
                    </div>

                    <select class="form-control" style="width: 14%; height: 50% !important; display: inline" id="manageDropdown">
                        <option value="Uitgezette weken">Uitgezette Dagen</option>
                        <option value="Weken">Dagen</option>
                    </select>
                     Beheren
                    <hr>
                    <div id="DaysDiv" style="display: none">
                        <div class="row" style="overflow: hidden" style=" resize: both !important; position: inherit">
                            @for($i = 1; $i <= count($weekDays); $i++)
                                <div class="col-md-2">
                                    {{$weekDays[$i]}}
                                    <div class="border-bottom"></div>
                                    <br>
                                </div>
                            @endfor
                            @for($i = 1; $i <= count($weekDays); $i++)
                                 @if(isset($availability->where('weekdays', $i)->first()->start_week))
                                    <div class="col-md-2 scrollbar-manage" style="overflow-y: scroll;">
                                        <input type="hidden" id="count_disable{{$i}}" value=" {{count($availability->where('weekdays', $i))}}">
                                        @foreach($availability->where('weekdays', $i)->sortBy('start_week') as $av)
                                            <div class="alert alert-success alert-dismissible fade show jg-color-gradient-3" role="alert">
                                                Week <a href="{{route('admin.rooster.user_rooster', ['user'=> request('user'), 'week' => $av->start_week, 'year' => $av->start_year])}}">{{$av->start_week}}</a> - <a href="{{route('admin.rooster.user_rooster', ['user'=> request('user'), 'week' => $av->end_week, 'year' => $av->end_year])}}">{{$av->end_week}}</a>
                                                <input type="hidden" id="id{{$loop->index + 1}}{{$i}}" value="{{$av->id}}">
                                                <input type="hidden" id="role{{$loop->index + 1}}{{$i}}" value="Admin">
                                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" id="remove_days{{$loop->index + 1}}{{$i}}" style="color: white">
                                                </button>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="col-md-2">
                                    </div>
                                @endif
                            @endfor
                        </div>
                    </div>
                    <div id="disabledDaysDiv">
                        <div class="row" style="overflow: hidden">
                            @for($i = 1; $i <= count($weekDays); $i++)
                                <div class="col-md-2">
                                    {{$weekDays[$i]}}
                                    <div class="border-bottom"></div>
                                    <br>
                                </div>
                            @endfor
                            <br>
                            @for($i = 1; $i <= count($weekDays); $i++)
                                @if(isset($disabled->where('weekday', $i)->first()->start_week))
                                    <div class="col-md-2 scrollbar-manage" style="height: 150px;overflow:auto;">
                                        <input type="hidden" id="count_disable{{$i}}" value=" {{count($disabled->where('weekday', $i))}}">
                                        @foreach($disabled->where('weekday', $i)->sortBy('start_week') as $av)
                                            <div class="alert alert-success alert-dismissible fade show jg-color-gradient-3 " role="alert">
                                                Week {{$av->start_week}} - {{$av->end_week}}
                                                <input type="hidden" id="id_disable{{$loop->index + 1}}{{$i}}" value="{{$av->id}}">
                                                <input type="hidden" id="role{{$loop->index + 1}}{{$i}}" value="Admin">
                                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" id="remove_disable_days{{$loop->index + 1}}{{$i}}">
                                                </button>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="col-md-2">
                                    </div>
                                @endif
                            @endfor
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editDisableModal" tabindex="-1" role="dialog" aria-labelledby="a" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Uitgezette Week bewerken</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                    <form method="post" action="{{route('admin.rooster.edit_disable_days', ['user' => request('user'), 'week' => request('week')])}}">
                        @csrf

                        <input type="hidden" id="weekday" name="weekday">

                        <label style="width: 49%">
                            <p>Kies een begin week:</p>
                            <input class="form-control" type="week" name="start_week" id="start_week">
                        </label>

                        <label style="width: 49%">
                            <p>Kies een eind week:</p>
                            <input class="form-control" type="week" name="end_week" id="end_week">
                        </label>
                        <input type="submit" class="btn btn-success float-right">
                    </form>
                </div>
            </div>
        </div>
    </div>
@else
    <div class="modal fade" id="disableModal" tabindex="-1" role="dialog" aria-labelledby="a" aria-hidden="true" >
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Beschikbaarheid beheren</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p style="font-size: 25px; margin-bottom: 0; display: inline">Dagen</p>
                    <select class="form-control" style="width: 14%; height: 50% !important; display: inline; margin-bottom: 15px" id="weekDropdown">
                        <option value="Uitzetten">Uitzetten</option>
                        <option value="Toevoegen">Toevoegen</option>
                    </select>
                    <div class="border-bottom"></div>
                    <br>
                    <div class="row" id="addDisable">
                        <form method="post" action="{{route('rooster.disable_days')}}">
                            @csrf
                            <label style="width: 100%">
                                <p>Kies een dag:</p>
                                <select class="form-control" name="weekday">
                                    @for($i = 1; $i <= count($weekDays); $i++)
                                        <option value="{{$i}}">{{$weekDays[$i]}}</option>
                                    @endfor
                                </select>
                            </label>
                            @if(str_contains(url()->current(), '/admin/'))
                                <input type="hidden" name="user_id" value="{{request('user')}}">
                            @else
                                <input type="hidden" name="user_id" value="{{\Illuminate\Support\Facades\Auth::user()->id}}">
                            @endif

                            <label style="width:  49.8%">
                                <p>Kies een begin week:</p>
                                <input class="form-control" type="week" name="start_week" id="start_week" min="2000-W01" max="2100-W52">
                            </label>

                            <label style="width: 49.8%">
                                <p>Kies een eind week:</p>
                                <input class="form-control" type="week" name="end_week" id="end_week" min="2000-W01" max="2100-W52">
                            </label>
                            <input type="submit" class="btn btn-primary jg-color-3 border-0 float-right" value="Toepassen">
                        </form>
                    </div>

                    <div class="row"  style="display: none" id="addWeeks">
                        <form method="post" action="{{route('rooster.availability', request('week'))}}">
                            @csrf
                                @if(str_contains(url()->current(), '/admin/'))
                                    <input type="hidden" name="user_id" value="{{request('user')}}">
                                @else
                                    <input type="hidden" name="user_id" value="{{\Illuminate\Support\Facades\Auth::user()->id}}">
                                @endif
                                <label style="font-style: italic;">
                                    <p>Allen van toepassing voor zaterdagen of om fouten te verbeteren.</p>
                                </label>

                                <div style="display: flex">
                                    <div style="width: 50%">
                                        <label style="width: 95%">
                                            <p>Begin Tijd:</p>
                                            <div style="display: flex;">
                                                <input type="week" class="form-control" name="begin_week"  min="2000-W01" max="2100-W52">
                                                <input type="time" name="start_time" class="form-control" style="outline: none;" id="time_picker_av_start"  min="08:00" max="18:00">
                                            </div>
                                        </label>
                                        <br>
                                        <label style="width:95%;">
                                            <p>Eind Tijd:</p>
                                            <div style="display: flex;">
                                                <input style="width:50%; margin-right: 0!important;" type="week" class="form-control" name="week"  min="2000-W01" max="2100-W52">
                                                <input type="time" name="end_time" class="form-control" style="outline: none; width: 50%;" id="time_picker_av_start" min="08:00" max="18:00" >
                                            </div>
                                        </label>
                                        <p style="font-size: 12px" class="text-warning">De tijden die u hier invult worden op halve uren en hele uren afgerond</p>
                                    </div>

                                    <div>
                                        <label style="width: 100%;">
                                            <p>Opmerking:</p>
                                            <textarea rows="5" cols="68" placeholder="Comment (optioneel)" class="form-control" name="comment"></textarea>
                                        </label>
                                    </div>
                                </div>

                            <label style="width: 100%">
                                <p  style="font-style: italic">Mocht het niet voor een zaterdag zijn, kies hier een dag:</p>
                                <select class="form-control" name="weekday">
                                    @for($i = 1; $i <= count($weekDays); $i++)
                                        <option value="{{$i}}" @if($weekDays[$i] == 'Zaterdag') selected @endif>{{$weekDays[$i]}}</option>
                                    @endfor
                                </select>
                            </label>
                            <input type="checkbox" id="switch" class="toggle-box " name="from_home" />
                                <label for="switch" class="toggle-label float-right" ></label>

                                <p class="float-right" style="margin-right: 15px">Thuis werken</p>
                            <br>
                            <br>
                            <button type="submit" class="btn btn-success float-right">Toepassen</button>
                        </form>
                    </div>

{{--                    <select class="form-control" style="width: 14%; height: 50% !important; display: inline" id="manageDropdown">--}}
{{--                        <option value="Uitgezette dagen">Uitgezette Dagen</option>--}}
{{--                        <option value="Dagen">Dagen</option>--}}
{{--                    </select>--}}
{{--                    Beheren--}}
{{--                    <label class="jg-color-1 submit-button-label d-none float-right" id="submit_refresh">--}}
{{--                        <text class="submit-button" style="padding: 7px 15px !important; border: none !important; " id="submit_refresh_text">Toepassen</text>--}}
{{--                        <div class="loader" style="opacity: 0; margin-top: -23px !important;" id="loader"></div>--}}
{{--                    </label>--}}
{{--                    <hr>--}}
{{--                    <div id="DaysDiv" style="display: none">--}}
{{--                        <div class="row" style="overflow: hidden" style=" resize: both !important; position: inherit">--}}
{{--                            @for($i = 0; $i < count($weekDays); $i++)--}}
{{--                                <div class="col-md-2">--}}
{{--                                    {{$weekDays[$i + 1]}}--}}
{{--                                    <div class="border-bottom"></div>--}}
{{--                                    <br>--}}
{{--                                </div>--}}
{{--                            @endfor--}}
{{--                            @for($i = 1; $i < count($weekDays); $i++)--}}
{{--                                @if(str_contains(url()->current(), '/admin/'))--}}
{{--                                    @if(isset($availability->where('weekdays', $i)->first()->start_week))--}}
{{--                                        <div class="col-md-2 scrollbar-manage" style="overflow-y: scroll;">--}}
{{--                                            <input type="hidden" id="count_disable{{$i}}" value=" {{count($availability->where('weekdays', $i))}}">--}}
{{--                                            @foreach($availability->where('weekdays', $i)->sortBy('start_week') as $av)--}}
{{--                                                <div class="alert alert-success alert-dismissible fade show jg-color-gradient-3" role="alert">--}}

{{--                                                    <p style="font-size: 15px; margin-bottom: 0">Week: <a href="{{route('admin.rooster.user_rooster', ['week' => $av->start_week, 'year' =>  $av->start_year, 'user' => request('user')])}}">{{$av->start_week}}</a> - <a href="{{route('admin.rooster.user_rooster', ['week' => $av->end_week, 'year' => $av->end_year, 'user' => request('user')])}}">{{$av->end_week}}</a></p>--}}
{{--                                                    <p style="font-size: 12px; margin-bottom: 0">Jaar: <a href="{{route('admin.rooster.user_rooster', ['week' => $av->start_week, 'year' =>  $av->start_year, 'user' => request('user')])}}">{{$av->start_year}}</a> - <a href="{{route('admin.rooster.user_rooster', ['week' => $av->end_week, 'year' => $av->end_year, 'user' => request('user')])}}">{{$av->end_year}}</a></p>--}}

{{--                                                    <input type="hidden" id="id{{$loop->index + 1}}{{$i}}" value="{{$av->id}}">--}}
{{--                                                    <input type="hidden" id="role{{$loop->index + 1}}{{$i}}" value="User">--}}
{{--                                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" id="remove_days{{$loop->index + 1}}{{$i}}" style="color: white">--}}
{{--                                                    </button>--}}
{{--                                                </div>--}}
{{--                                            @endforeach--}}
{{--                                        </div>--}}
{{--                                    @else--}}
{{--                                        <div class="col-md-2">--}}
{{--                                        </div>--}}
{{--                                    @endif--}}
{{--                                @else--}}
{{--                                    @if(isset($availability->where('weekdays', $i)->first()->start_week))--}}
{{--                                        <div class="col-md-2 scrollbar-manage" style="overflow-y: scroll;">--}}
{{--                                            <input type="hidden" id="count_disable{{$i}}" value=" {{count($availability->where('weekdays', $i))}}">--}}
{{--                                            @foreach($availability->where('weekdays', $i)->sortBy('start_week') as $av)--}}
{{--                                                <div class="alert alert-success alert-dismissible fade show jg-color-gradient-3" role="alert">--}}

{{--                                                    <p style="font-size: 15px; margin-bottom: 0">Week: <a href="{{route('rooster.index', ['week' => $av->start_week, 'year' =>  $av->start_year])}}">{{$av->start_week}}</a> - <a href="{{route('rooster.index', ['week' => $av->end_week, 'year' => $av->end_year])}}">{{$av->end_week}}</a></p>--}}
{{--                                                    <p style="font-size: 12px; margin-bottom: 0">Jaar: <a href="{{route('rooster.index', ['week' => $av->start_week, 'year' =>  $av->start_year])}}">{{$av->start_year}}</a> - <a href="{{route('rooster.index', ['week' => $av->end_week, 'year' => $av->end_year])}}">{{$av->end_year}}</a></p>--}}

{{--                                                    <input type="hidden" id="id{{$loop->index + 1}}{{$i}}" value="{{$av->id}}">--}}
{{--                                                    <input type="hidden" id="role{{$loop->index + 1}}{{$i}}" value="User">--}}
{{--                                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" id="remove_days{{$loop->index + 1}}{{$i}}" >--}}
{{--                                                    </button>--}}
{{--                                                </div>--}}
{{--                                            @endforeach--}}
{{--                                        </div>--}}
{{--                                    @else--}}
{{--                                        <div class="col-md-2">--}}
{{--                                        </div>--}}
{{--                                    @endif--}}
{{--                                @endif--}}
{{--                            @endfor--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <div id="disabledDaysDiv">--}}
{{--                        <div class="row" style="overflow: hidden">--}}
{{--                            @for($i = 0; $i < count($weekDays); $i++)--}}
{{--                                <div class="col-md-2">--}}
{{--                                    {{$weekDays[$i + 1]}}--}}
{{--                                    <div class="border-bottom"></div>--}}
{{--                                    <br>--}}
{{--                                </div>--}}
{{--                            @endfor--}}
{{--                            <br>--}}
{{--                            @for($i = 1; $i < count($weekDays); $i++)--}}
{{--                                @if(str_contains(url()->current(), '/admin/'))--}}
{{--                                    @if(isset($disabled->where('user_id', request('user'))->where('weekday', $i)->first()->start_week))--}}
{{--                                    <div class="col-md-2 scrollbar-manage" style="height: 150px;overflow:auto;">--}}
{{--                                        <input type="hidden" id="count_disable{{$i}}" value=" {{count($disabled->where('weekday', $i))}}">--}}
{{--                                        @foreach($disabled->where('user_id', request('user'))->where('weekday', $i)->sortBy('start_week')->sortBy('start_year') as $av)--}}
{{--                                            <div class="alert alert-success alert-dismissible fade show jg-color-gradient-3 " role="alert">--}}

{{--                                                <p style="font-size: 15px; margin-bottom: 0">Week: <a href="{{route('admin.rooster.user_rooster', ['week' => $av->start_week, 'year' =>  $av->start_year, 'user' => request('user')])}}">{{$av->start_week}}</a> - <a href="{{route('admin.rooster.user_rooster', ['week' => $av->end_week, 'year' => $av->end_year, 'user' => request('user')])}}">{{$av->end_week}}</a></p>--}}
{{--                                                <p style="font-size: 12px; margin-bottom: 0">Jaar: <a href="{{route('admin.rooster.user_rooster', ['week' => $av->start_week, 'year' =>  $av->start_year, 'user' => request('user')])}}">{{$av->start_year}}</a> - <a href="{{route('admin.rooster.user_rooster', ['week' => $av->end_week, 'year' => $av->end_year, 'user' => request('user')])}}">{{$av->end_year}}</a></p>--}}

{{--                                                <input type="hidden" id="id_disable{{$loop->index + 1}}{{$i}}" value="{{$av->id}}">--}}
{{--                                                <input type="hidden" id="role{{$loop->index + 1}}{{$i}}" value="User">--}}
{{--                                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" id="remove_disable_days{{$loop->index + 1}}{{$i}}">--}}
{{--                                                </button>--}}
{{--                                            </div>--}}
{{--                                        @endforeach--}}
{{--                                    </div>--}}
{{--                                    @else--}}
{{--                                        <div class="col-md-2">--}}
{{--                                        </div>--}}
{{--                                    @endif--}}
{{--                                @else--}}
{{--                                    @if(isset($disabled->where('user_id', \Illuminate\Support\Facades\Auth::id())->where('weekday', $i)->first()->start_week))--}}
{{--                                        <div class="col-md-2 scrollbar-manage" style="height: 150px;overflow:auto;">--}}
{{--                                            <input type="hidden" id="count_disable{{$i}}" value=" {{count($disabled->where('weekday', $i))}}">--}}
{{--                                            @foreach($disabled->where('user_id', \Illuminate\Support\Facades\Auth::id())->where('weekday', $i)->sortBy('start_week')->sortBy('start_year') as $av)--}}
{{--                                                <div class="alert alert-success alert-dismissible fade show jg-color-gradient-3 " role="alert">--}}

{{--                                                    <p style="font-size: 15px; margin-bottom: 0">Week: <a href="{{route('rooster.index', ['week' => $av->start_week, 'year' =>  $av->start_year])}}">{{$av->start_week}}</a> - <a href="{{route('rooster.index', ['week' => $av->end_week, 'year' => $av->end_year])}}">{{$av->end_week}}</a></p>--}}
{{--                                                    <p style="font-size: 12px; margin-bottom: 0">Jaar: <a href="{{route('rooster.index', ['week' => $av->start_week, 'year' =>  $av->start_year])}}">{{$av->start_year}}</a> - <a href="{{route('rooster.index', ['week' => $av->end_week, 'year' => $av->end_year])}}">{{$av->end_year}}</a></p>--}}

{{--                                                    <input type="hidden" id="id_disable{{$loop->index + 1}}{{$i}}" value="{{$av->id}}">--}}
{{--                                                    <input type="hidden" id="role{{$loop->index + 1}}{{$i}}" value="User">--}}
{{--                                                    @if(\Illuminate\Support\Facades\Auth::user()->role->name == "admin" || \Illuminate\Support\Facades\Auth::user()->role->name == "maintainer")--}}
{{--                                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" id="remove_disable_days{{$loop->index + 1}}{{$i}}">--}}
{{--                                                        </button>--}}
{{--                                                    @else--}}
{{--                                                        @if($av->by_admin != true)--}}
{{--                                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" id="remove_disable_days{{$loop->index + 1}}{{$i}}">--}}
{{--                                                            </button>--}}
{{--                                                        @endif--}}
{{--                                                    @endif--}}
{{--                                                </div>--}}
{{--                                            @endforeach--}}
{{--                                        </div>--}}
{{--                                    @else--}}
{{--                                        <div class="col-md-2">--}}
{{--                                        </div>--}}
{{--                                    @endif--}}
{{--                                @endif--}}
{{--                            @endfor--}}
{{--                        </div>--}}
{{--                    </div>--}}
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editDisableModal" tabindex="-1" role="dialog" aria-labelledby="a" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Uitgezette Week bewerken</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                    <form method="post" action="{{route('rooster.edit_disable_days', request('week') )}}">
                        @csrf
                        <input type="hidden" id="rooster_id2" name="id">

                        <label style="width: 49%">
                            <p>Kies een begin week:</p>
                            <input class="form-control" type="week" name="start_week" id="start_week_disable_edit"  min="2000-W01" max="2100-W52">
                        </label>

                        <label style="width: 49%">
                            <p>Kies een eind week:</p>
                            <input class="form-control" type="week" name="end_week" id="end_week_disable_edit"  min="2000-W01" max="2100-W52">
                        </label>
                        <input type="submit" class="btn btn-success float-right" value="Toepassen">
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="availabilityModalAdd" tabindex="-1" role="dialog" aria-labelledby="a" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="a">Add Availability</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <form method="post" action="{{route('rooster.availability', request('week'))}}">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="weekday" id="weekday">
                        <input type="hidden" name="is_rooster" id="is_rooster">

                        @if(request('admin'))
                            <input type="hidden" name="user_id" value="{{request('user')}}">
                        @else
                            <input type="hidden" name="user_id" value="{{\Illuminate\Support\Facades\Auth::user()->id}}">
                        @endif

                        <label style="width: 49%">
                            <p>Begin Tijd:</p>
                            <input type="time" name="start_time" class="form-control" style="outline: none;" id="time_picker_av_start"  min="08:00" max="18:00">
                        </label>

                        <label style="width: 49%">
                            <p>Eind Tijd:</p>
                            <input type="time" name="end_time" class="form-control" style="outline: none;" id="time_picker_av_start" min="08:00" max="18:00">
                        </label>

                        <p style="font-size: 12px" class="text-warning">De tijden die u invult worden op halve uren en hele uren afgerond</p>
                        <label style="width: 100%">
                            <textarea rows="5" cols="68" placeholder="Comment (optioneel)" class="form-control" name="comment"></textarea>
                        </label>

                        <label>
                            <p>Eind week:</p>
                            <input type="week" class="form-control" name="week"  min="2000-W01" max="2100-W52">
                        </label>

                        <input type="checkbox" id="switch" class="toggle-box" name="from_home"/>
                        <label for="switch" class="toggle-label"></label>

                        <label for="switch">
                            <p>Van huis</p>
                        </label>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary nav-colo">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="availabilityModalEdit" tabindex="-1" role="dialog" aria-labelledby="a" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="a">Bewerk beschikbaarheid</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <form method="post" action="{{route('rooster.edit_availability', ['week' => request('week'), 'year' => request('year')])}}">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="weekday" id="is_rooster_edit">
                        <input type="hidden" name="rooster_id" id="rooster_id">
                        @if(request('admin'))
                            <input type="hidden" name="user_id" value="{{request('user')}}">
                        @else
                            <input type="hidden" name="user_id" value="{{\Illuminate\Support\Facades\Auth::user()->id}}">
                        @endif
                        <div style="display: flex">
                            <div style="width: 50%">
                                <label style="width: 95%">
                                    <p>Begin Tijd:</p>
                                    <div style="display: flex;">
                                        <input type="number" name="start_time_1" id="time_picker_av_start_1" class="form-control" min="1" max="17" style="width: 49.8% !important;" placeholder="Uur">
                                        <input type="number" name="start_time_2" id="time_picker_av_start_2" class="form-control" min="0" max="60" style="width: 49.8% !important;" placeholder="Minuut">
                                    </div>
                                </label>
                                <br>
                                <label style="width:95%;">
                                    <p>Eind Tijd:</p>
                                    <div style="display: flex;">
                                        <input type="number" name="end_time_1" id="time_picker_av_end_1" class="form-control" min="1" max="17" style="width: 49.8% !important;" placeholder="Uur">
                                        <input type="number" name="end_time_2" id="time_picker_av_end_2" class="form-control" min="0" max="60" style="width: 49.8% !important;" placeholder="Minuut">
                                    </div>
                                </label>
                                <p style="font-size: 12px" class="text-warning">De tijden die u hier invult worden op halve uren en hele uren afgerond</p>
                            </div>

                            <div style="width: 50%">
                                <label style="width: 100%;">
                                    <p>Opmerking:</p>
                                    <textarea rows="5" cols="68" placeholder="Opmerking (optioneel)" class="form-control" id="comment_edit" name="comment"></textarea>
                                </label>
                            </div>
                        </div>

                        <input type="checkbox" id="switch_box" class="toggle-box" name="from_home"/>
                        <label for="switch_box" class="toggle-label float-right"> </label>

                        <p class="float-right" style="margin-right: 15px">Thuis werken</p>
                        <br>
                        <br>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary jg-color-3" style="border: none">Toepassen</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endif
