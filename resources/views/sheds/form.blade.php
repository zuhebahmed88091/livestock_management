<div class="row">

    <div class="col-md-6">
        <div class="form-group {{ $errors->has('shed_no') ? 'has-error' : '' }}">
            <label for="shed_no">{{__('commons.shed_no')}}</label>
            <input class="form-control" name="shed_no" type="text" id="shed_no"
                value="{{ old('shed_no', optional($shed)->shed_no) }}" minlength="1" maxlength="50" required>
            {!! $errors->first('shed_no', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('livestock_id') ? 'has-error' : '' }}">
            <label for="livestock_id">{{__('commons.batch_name')}}</label>
            <select class="form-control  select-admin-lte" id="livestock_id" name="livestock_id" required>
                <option value="">-----{{__('commons.select')}}-----</option>
                @foreach ($liveStocks as $liveStock)
                 <option value="{{ $liveStock->id }}" {{optional($shed)->livestock_id == $liveStock->id ? 'selected':' '}}>
                    {{ $liveStock->batch_name }}        
                </option>     
                @endforeach
            </select>
            {!! $errors->first('livestock_id', '<p class="help-block">:message</p>') !!}
        </div>
    </div>

</div>


<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('quantity') ? 'has-error' : '' }}">
            <label for="quantity">{{__('commons.quantity')}}</label>
            <input class="form-control" name="quantity" type="text" id="quantity"
                value="{{ old('quantity', optional($shed)->quantity) }}" minlength="1" maxlength="50" required>
            {!! $errors->first('quantity', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('age') ? 'has-error' : '' }}">
            <label for="age">{{__('commons.age')}}</label>
            <input class="form-control" name="age" type="text" id="age"
                value="{{ old('age', optional($shed)->age) }}" minlength="1" maxlength="50" required>
            {!! $errors->first('age', '<p class="help-block">:message</p>') !!}
        </div>
    </div>

</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('purchase_date') ? 'has-error' : '' }}">
            <label for="purchase_date">{{__('commons.purchase_date')}}</label>

            <div class="input-group date">
                <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                </div>
                <input class="form-control datepicker pull-right" name="purchase_date" id="purchase_date"
                       value="{{ old('purchase_date', optional($shed)->purchase_date) }}"
                       placeholder="format: {{ config('constants.DISPLAY_DATE_FORMAT') }}" required>
            </div>

            {!! $errors->first('purchase_date', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('status') ? 'has-error' : '' }}">
            <label for="status">{{__('commons.status')}}</label>
            <select class="form-control  select-admin-lte" id="status" name="status" required>
                <option value="">-----{{__('commons.select')}}-----</option>
                @foreach (['Active' => 'Active',
'Inactive' => 'Inactive'] as $key => $text)
                <option value="{{ $key }}" {{ old('status', optional($shed)->status) == $key ? 'selected' : '' }}>
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
                      required>{{ old('comments', optional($shed)->comments) }}</textarea>

            {!! $errors->first('comments', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
</div>