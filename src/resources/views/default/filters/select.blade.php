<div class="form-group">
    <label>{{$label}}</label>
    <div class="row">
        <div class="col-xs-12">
            <select name="f_{{$name}}" class="form-control c-select">
                <option></option>
                @foreach ($options as $key => $option)
                    <option value="{{$key}}" @if ($value == $key) selected @endif>{{$option}}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>