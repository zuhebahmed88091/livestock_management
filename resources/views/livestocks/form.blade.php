<div class="row">

    <div class="col-md-6">
        <div class="form-group {{ $errors->has('batch_name') ? 'has-error' : '' }}">
            <label for="batch_name">{{__('commons.batch_name')}}</label>
            <input class="form-control" name="batch_name" type="text" id="batch_name"
                   value="{{ old('batch_name', optional($liveStock)->batch_name) }}" minlength="1" maxlength="50"
                   required>
            {!! $errors->first('batch_name', '<p class="help-block">:message</p>') !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group {{ $errors->has('livestock_type_id') ? 'has-error' : '' }}">
            <label for="livestock_type_id">{{__('commons.livestock_type')}}</label>
            <select class="form-control  select-admin-lte" id="livestock_type_id" name="livestock_type_id" required>
                <option value="">-----{{__('commons.select')}}-----</option>
                @foreach ($liveStockTypes as $liveStockType)
                    <option value="{{ $liveStockType->id }}" {{optional($liveStock)->livestock_type_id == $liveStockType->id ? 'selected':' '}}>
                        {{ $liveStockType->title }}
                    </option>
                @endforeach
            </select>
            {!! $errors->first('livestock_type_id', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('date') ? 'has-error' : '' }}">
            <label for="date">{{__('commons.start_date')}}</label>

            <div class="input-group date">
                <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                </div>
                <input class="form-control datepicker pull-right" name="date" id="date"
                       value="{{ old('date', optional($liveStock)->date) }}"
                       placeholder="format: {{ config('constants.DISPLAY_DATE_FORMAT') }}" required>
            </div>

            {!! $errors->first('date', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('sale_date') ? 'has-error' : '' }}">
            <label for="sale_date">{{__('commons.expected_sale_date')}}</label>

            <div class="input-group date">
                <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                </div>
                <input class="form-control datepicker pull-right" name="sale_date" id="sale_date"
                       value="{{ old('sale_date', optional($liveStock)->sale_date) }}"
                       placeholder="format: {{ config('constants.DISPLAY_DATE_FORMAT') }}" required>
            </div>

            {!! $errors->first('sale_date', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
    
</div>


<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('quantity') ? 'has-error' : '' }}">
            <label for="quantity">{{__('commons.quantity')}}</label>
            <input class="form-control" name="quantity" type="text" id="quantity"
                   value="{{ old('quantity', optional($liveStock)->quantity) }}" minlength="1" maxlength="50" required>
            {!! $errors->first('quantity', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('status') ? 'has-error' : '' }}">
            <label for="status">{{__('commons.status')}}</label>
            <select class="form-control  select-admin-lte" id="status" name="status" required>
                <option value="">-----{{__('commons.select')}}-----</option>
                @foreach (['Active' => 'Active','Sold' => 'Sold',
'Inactive' => 'Inactive'] as $key => $text)
                <option value="{{ $key }}" {{ old('status', optional($liveStock)->status) == $key ? 'selected' : '' }}>
                    {{ $text }}
                </option>
                @endforeach
            </select>
            {!! $errors->first('status', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('comments') ? 'has-error' : '' }}">
            <label for="comments">{{__('commons.comments')}}</label>
            <textarea class="form-control" name="comments" cols="50" rows="3" id="comments"
                      required>{{ old('comments', optional($liveStock)->comments) }}</textarea>

            {!! $errors->first('comments', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
</div>