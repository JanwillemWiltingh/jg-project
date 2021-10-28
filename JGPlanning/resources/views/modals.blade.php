@if(!request('admin'))
{{--    <div class="modal fade" id="disableModal" tabindex="-1" role="dialog" aria-labelledby="a" aria-hidden="true">--}}
{{--        <div class="modal-dialog" role="document">--}}
{{--            <div class="modal-content">--}}
{{--                <div class="modal-header">--}}
{{--                    <h5 class="modal-title">Weken uitzetten</h5>--}}
{{--                </div>--}}
{{--                <div class="modal-body">--}}
{{--                    <form method="post" action="{{route('admin.rooster.disable_days', request('user'))}}">--}}
{{--                        @csrf--}}
{{--                        <label style="width: 100%">--}}
{{--                            <p>Kies een dag:</p>--}}
{{--                            <select class="form-control" name="weekday">--}}
{{--                                @for($i = 1; $i < count($weekDays); $i++)--}}
{{--                                    <option value="{{$i}}">{{$weekDays[$i]}}</option>--}}
{{--                                @endfor--}}
{{--                            </select>--}}
{{--                        </label>--}}

{{--                        <label style="width: 49%">--}}
{{--                            <p>Kies een begin week:</p>--}}
{{--                            <input class="form-control" type="week" name="start_week" id="start_week">--}}
{{--                        </label>--}}

{{--                        <label style="width: 49%">--}}
{{--                            <p>Kies een eind week:</p>--}}
{{--                            <input class="form-control" type="week" name="end_week" id="end_week">--}}
{{--                        </label>--}}
{{--                        <input type="submit" class="btn btn-success float-right">--}}
{{--                    </form>--}}
{{--                    <div class="row">--}}
{{--                        @for($i = 0; $i < count($weekDays); $i++)--}}
{{--                            <div class="col-md-2">--}}
{{--                                {{$weekDays[$i + 1]}}--}}
{{--                            </div>--}}
{{--                        @endfor--}}
{{--                        <br>--}}
{{--                        @for($i = 0; $i < count($weekDays); $i++)--}}
{{--                            <div class="col-md-2">--}}
{{--                                <div class="alert alert-success alert-dismissible fade show" role="alert">--}}
{{--                                    {{$i}}--}}
{{--                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">--}}
{{--                                    </button>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        @endfor--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}

{{--    <div class="modal fade" id="editDisableModal" tabindex="-1" role="dialog" aria-labelledby="a" aria-hidden="true">--}}
{{--        <div class="modal-dialog" role="document">--}}
{{--            <div class="modal-content">--}}
{{--                <div class="modal-header">--}}
{{--                    <h5 class="modal-title">Weken bewerken</h5>--}}
{{--                </div>--}}
{{--                <div class="modal-body">--}}
{{--                    <form method="post" action="{{route('admin.rooster.edit_disable_days', ['user' => request('user'), 'week' => request('week')])}}">--}}
{{--                        @csrf--}}

{{--                        <label style="width: 49%">--}}
{{--                            <p>Kies een begin week:</p>--}}
{{--                            <input class="form-control" type="week" name="start_week" id="start_week">--}}
{{--                        </label>--}}

{{--                        <label style="width: 49%">--}}
{{--                            <p>Kies een eind week:</p>--}}
{{--                            <input class="form-control" type="week" name="end_week" id="end_week">--}}
{{--                        </label>--}}
{{--                        <input type="submit" class="btn btn-success float-right">--}}
{{--                    </form>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
@else
    <div class="modal fade" id="availabilityModalAdd" tabindex="-1" role="dialog" aria-labelledby="a" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="a">Add Availability</h5>
                </div>
                <form method="post" action="{{route('availability', request('week'))}}">
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
                <form method="post" action="{{route('edit_availability', request('week'))}}">
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
                        <label class="toggle-box">
                            <input type="checkbox" name="from_home">
                            <span class="toggle-box-slider"></span>
                        </label>
                        <label>
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
