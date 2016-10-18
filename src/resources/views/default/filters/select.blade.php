<div class="form-group">
    <label>{{$label}}</label>
    <div class="row">
        <select name="f_{{$name}}">
            <option></option>
            @foreach ($options as $key => $option)
                <option value="{{$key}}" @if ($value == $key) selected @endif>{{$option}}</option>
            @endforeach
        </select>
    </div>
</div>