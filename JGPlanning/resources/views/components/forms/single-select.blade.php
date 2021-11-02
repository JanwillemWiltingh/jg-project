<div class="form-group">
    <label class="black-label-text"
           for="{{ $name }}">
        {{ __('general.'.$name) }}
    </label>
    <select class="form-control"
            name="{{ $name }}"
            id="{{ $name }}"
            @if($disabled) disabled @endif>
        @if($default != null)
        <option value="0">{{ $default }}</option>
        @endif
        @foreach($array as $data)
            <option value="{{$data['id']}}"
                    @if(old($name) == $data['id'])
                        selected
                    @elseif($data['id'] == $value)
                        selected
                @endif>
                @foreach($fields as $field)
                    @if($capitalize)
                        {{ ucfirst($data[$field]) }}
                    @else
                        {{ $data[$field] }}
                    @endif
                @endforeach
            </option>
        @endforeach
    </select>

    @if($errors->has($name))
        <div class="error">
            {{ $errors->first($name) }}
        </div>
    @endif
</div>
