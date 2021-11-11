@if(\Illuminate\Support\Facades\Auth::user()->role->name == "admin" || \Illuminate\Support\Facades\Auth::user()->role->name == "maintainer")
    <div class="modal fade" id="disableModal" tabindex="-1" role="dialog" aria-labelledby="a" aria-hidden="true" >
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Uitgezette weken beheren</h5>
                </div>
                <div class="modal-body">
                    <p style="font-size: 25px; margin-bottom: 0; display: inline">Weken</p>
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
                                        @for($i = 1; $i < count($weekDays); $i++)
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
                                <p>Start time:</p>
                                <input type="time" name="start_time" class="form-control" style="outline: none;" id="time_picker_av_start"  min="08:00" max="18:00">
                            </label>

                            <label style="width: 49.8%">
                                <p>End Time:</p>
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
                        <option>Uitgezette weken</option>
                        <option>Weken</option>
                    </select>
                     Beheren
                    <hr>
                    <div id="DaysDiv" style="display: none">
                        <div class="row" style="overflow: hidden" style=" resize: both !important; position: inherit">
                            @for($i = 0; $i < count($weekDays); $i++)
                                <div class="col-md-2">
                                    {{$weekDays[$i + 1]}}
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
                                                Week <a href="{{route('admin.rooster.user_rooster', ['user'=> request('user'), 'week' => $av->start_week])}}">{{$av->start_week}}</a> - <a href="{{route('admin.rooster.user_rooster', ['user'=> request('user'), 'week' => $av->end_week])}}">{{$av->end_week}}</a>
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
                            @for($i = 0; $i < count($weekDays); $i++)
                                <div class="col-md-2">
                                    {{$weekDays[$i + 1]}}
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
                    <h5 class="modal-title">Weken bewerken</h5>
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
                    <h5 class="modal-title">Weken beheren</h5>
                </div>
                <div class="modal-body">
                    <p style="font-size: 25px; margin-bottom: 0; display: inline">Weken</p>
                    <select class="form-control" style="width: 14%; height: 50% !important; display: inline" id="weekDropdown">
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
                                    @for($i = 1; $i < count($weekDays); $i++)
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
                                <input type="hidden" name="weekday" id="weekday">
                                <input type="hidden" name="is_rooster" id="is_rooster">

                                @if(request('admin'))
                                    <input type="hidden" name="user_id" value="{{request('user')}}">
                                @else
                                    <input type="hidden" name="user_id" value="{{\Illuminate\Support\Facades\Auth::user()->id}}">
                                @endif

                                <label style="width: 49%">
                                    <p>Start time:</p>
                                    <input type="time" name="start_time" class="form-control" style="outline: none;" id="time_picker_av_start"  min="08:00" max="18:00">
                                </label>

                                <label style="width: 49%">
                                    <p>End Time:</p>
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
                        <option value="Uitgezette dagen">Uitgezette weken</option>
                        <option value="Dagen">Weken</option>
                    </select>
                    Beheren
                    <hr>
                    <div id="DaysDiv" style="display: none">
                        <div class="row" style="overflow: hidden" style=" resize: both !important; position: inherit">
                            @for($i = 0; $i < count($weekDays); $i++)
                                <div class="col-md-2">
                                    {{$weekDays[$i + 1]}}
                                    <div class="border-bottom"></div>
                                    <br>
                                </div>
                            @endfor
                            @for($i = 1; $i < count($weekDays); $i++)
                                @if(isset($availability->where('weekdays', $i)->first()->start_week))
                                    <div class="col-md-2 scrollbar-manage" style="overflow-y: scroll;">
                                        <input type="hidden" id="count_disable{{$i}}" value=" {{count($availability->where('weekdays', $i))}}">
                                        @foreach($availability->where('weekdays', $i)->sortBy('start_week') as $av)
                                            <div class="alert alert-success alert-dismissible fade show jg-color-gradient-3" role="alert">
                                                Week <a href="{{route('rooster.index', $av->start_week)}}">{{$av->start_week}}</a> - <a href="{{route('rooster.index', $av->end_week)}}">{{$av->end_week}}</a>
                                                <input type="hidden" id="id{{$loop->index + 1}}{{$i}}" value="{{$av->id}}">
                                                <input type="hidden" id="role{{$loop->index + 1}}{{$i}}" value="User">
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
                            @for($i = 0; $i < count($weekDays); $i++)
                                <div class="col-md-2">
                                    {{$weekDays[$i + 1]}}
                                    <div class="border-bottom"></div>
                                    <br>
                                </div>
                            @endfor
                            <br>
                            @for($i = 1; $i < count($weekDays); $i++)
                                @if(isset($disabled->where('weekday', $i)->first()->start_week))
                                    <div class="col-md-2 scrollbar-manage" style="height: 150px;overflow:auto;">
                                        <input type="hidden" id="count_disable{{$i}}" value=" {{count($disabled->where('weekday', $i))}}">
                                        @foreach($disabled->where('weekday', $i)->sortBy('start_week') as $av)
                                            <div class="alert alert-success alert-dismissible fade show jg-color-gradient-3 " role="alert">
                                                Week <a href="{{route('rooster.index', $av->start_week)}}">{{$av->start_week}}</a> - <a href="{{route('rooster.index', $av->end_week)}}">{{$av->end_week}}</a>
                                                <input type="hidden" id="id_disable{{$loop->index + 1}}{{$i}}" value="{{$av->id}}">
                                                <input type="hidden" id="role{{$loop->index + 1}}{{$i}}" value="User">
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
                    <h5 class="modal-title">Weken bewerken</h5>
                </div>
                <div class="modal-body">
                    <form method="post" action="{{route('rooster.edit_disable_days', request('week'))}}">
                        @csrf
                        <input type="hidden" id="weekday_edit" name="id">

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

    <div class="modal fade" id="availabilityModalAdd" tabindex="-1" role="dialog" aria-labelledby="a" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="a">Add Availability</h5>
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
                            <p>Start time:</p>
                            <input type="time" name="start_time" class="form-control" style="outline: none;" id="time_picker_av_start"  min="08:00" max="18:00">
                        </label>

                        <label style="width: 49%">
                            <p>End Time:</p>
                            <input type="time" name="end_time" class="form-control" style="outline: none;" id="time_picker_av_start" min="08:00" max="18:00">
                        </label>

                        <p style="font-size: 12px" class="text-warning">De tijden die u invult worden op halve uren en hele uren afgerond</p>
                        <label style="width: 100%">
                            <textarea rows="5" cols="68" placeholder="Comment (optioneel)" class="form-control" name="comment"></textarea>
                        </label>

                        <label>
                            <p>Eind week:</p>
                            <input type="week" class="form-control" name="week">
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
                    <h5 class="modal-title" id="a">Edit Availability</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="post" action="{{route('rooster.edit_availability', request('week'))}}">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="weekday" id="weekday_edit">
                        <input type="hidden" name="is_user" id="is_rooster_edit">
                        @if(request('admin'))
                            <input type="hidden" name="user_id" value="{{request('user')}}">
                        @else
                            <input type="hidden" name="user_id" value="{{\Illuminate\Support\Facades\Auth::user()->id}}">
                        @endif
                        <label style="width: 49%">
                            <p>Start time:</p>
                            <input type="time" name="start_time" class="form-control" style="outline: none;" id="time_picker_av_start"  min="08:00" max="18:00">
                        </label>
                        <label style="width: 49%">
                            <p>End Time:</p>
                            <input type="time" name="end_time" class="form-control" style="outline: none;" id="time_picker_av_start" min="08:00" max="18:00">
                        </label>
                        <p style="font-size: 12px" class="text-warning">De tijden die u invult worden op halve uren en hele uren afgerond</p>
                        <label style="width: 100%">
                            <textarea rows="5" cols="68" placeholder="Comment (optioneel)" class="form-control" name="comment"></textarea>
                        </label>
                        <label style="width: 49%">
                            <p>Kies een begin week:</p>
                            <input class="form-control" type="week" name="start_week">
                        </label>

                        <label style="width: 49%">
                            <p>Kies een eind week:</p>
                            <input class="form-control" type="week" name="end_week">
                        </label>
                            <input class="checkbox" type="checkbox" name="from_home" id="switch-box">
                        <label for="switch-box">
                            <p>Van thuis</p>
                        </label>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary nav-colo">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endif
