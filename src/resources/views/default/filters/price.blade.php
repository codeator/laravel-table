<div class="form-group">
    <label>{{$label}}</label>
    <div class="row">
        <div class="col-xs-6">
            <input class="form-control" type="text" value="{{array_get($value, 'from')}}" placeholder="{{ trans('table::filter.price.from') }}" name="f_{{$name}}[from]"/>
        </div>
        <div class="col-xs-6">
            <input class="form-control" type="text" value="{{array_get($value, 'to')}}" placeholder="{{ trans('table::filter.price.to') }}" name="f_{{$name}}[to]"/>
        </div>
    </div>
</div>