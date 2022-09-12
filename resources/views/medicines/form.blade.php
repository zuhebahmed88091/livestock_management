<div class="row">

    <div class="col-md-6">
        <div class="form-group {{ $errors->has('livestock_id') ? 'has-error' : '' }}">
            <label for="livestock_id">{{__('commons.batch_name')}}</label>
            <select class="form-control  select-admin-lte" id="livestock_id" name="livestock_id" required>
                <option value="">-----{{__('commons.batch_name')}}-----</option>
                @foreach ($liveStocks as $liveStock)
                <option value="{{ $liveStock->id }}" {{optional($medicine)->livestock_id == $liveStock->id ? 'selected':' '}}>
                    {{ $liveStock->batch_name }}        
                </option> 
                @endforeach
            </select>
            {!! $errors->first('livestock_id', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('shed_id') ? 'has-error' : '' }}">
            <label for="shed_id">{{__('commons.shed_no')}}</label>
            <select class="form-control  select-admin-lte" id="shed_id" name="shed_id" required>
                <option value="">-----{{__('commons.shed_no')}}-----</option>
                @foreach ($sheds as $shed)
                <option value="{{ $shed->id }}" {{optional($medicine)->shed_id == $shed->id ? 'selected':' '}}>
                    {{ $shed->shed_no }}        
                </option> 
                @endforeach
            </select>
            {!! $errors->first('shed_id', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
</div>
<div class="row">

    <div class="col-md-6">
        <div class="form-group {{ $errors->has('start_date') ? 'has-error' : '' }}">
            <label for="start_date">{{__('commons.start_date')}}</label>

            <div class="input-group date">
                <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                </div>
                <input class="form-control datepicker pull-right" name="start_date" id="start_date"
                       value="{{ old('start_date', optional($medicine)->start_date) }}"
                       placeholder="format: {{ config('constants.DISPLAY_DATE_FORMAT') }}" required>
            </div>

            {!! $errors->first('start_date', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('end_date') ? 'has-error' : '' }}">
            <label for="end_date">{{__('commons.end_date')}}</label>

            <div class="input-group date">
                <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                </div>
                <input class="form-control datepicker pull-right" name="end_date" id="end_date"
                       value="{{ old('end_date', optional($medicine)->end_date) }}"
                       placeholder="format: {{ config('constants.DISPLAY_DATE_FORMAT') }}" required>
            </div>

            {!! $errors->first('end_date', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
    

</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('identify_date') ? 'has-error' : '' }}">
            <label for="identify_date">{{__('commons.identify_date')}}</label>

            <div class="input-group date">
                <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                </div>
                <input class="form-control datepicker pull-right" name="identify_date" id="identify_date"
                       value="{{ old('identify_date', optional($medicine)->identify_date) }}"
                       placeholder="format: {{ config('constants.DISPLAY_DATE_FORMAT') }}" required>
            </div>

            {!! $errors->first('identify_date', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('next_follow_up_date') ? 'has-error' : '' }}">
            <label for="next_follow_up_date">{{__('commons.next_follow_up_date')}}</label>

            <div class="input-group date">
                <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                </div>
                <input class="form-control datepicker pull-right" name="next_follow_up_date" id="next_follow_up_date"
                       value="{{ old('next_follow_up_date', optional($medicine)->next_follow_up_date) }}"
                       placeholder="format: {{ config('constants.DISPLAY_DATE_FORMAT') }}" required>
            </div>

            {!! $errors->first('next_follow_up_date', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('doctor_id') ? 'has-error' : '' }}">
            <label for="doctor_id">{{__('medicine.doctor')}}</label>
            <select class="form-control  select-admin-lte" id="doctor_id" name="doctor_id" required>
                <option value="">-----{{__('commons.select')}}-----</option>
                @foreach ($doctors as $key => $doctor)
                    <option
                        value="{{ $key }}" {{ old('doctor_id', optional($medicine)->doctor_id) == $key ? 'selected' : '' }}>
                        {{ $doctor }}
                    </option>
                @endforeach
            </select>
            {!! $errors->first('doctor_id', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('regular_dose') ? 'has-error' : '' }}">
            <label for="regular_dose">{{__('medicine.regular_dose')}}</label>
            <textarea class="form-control" name="regular_dose" cols="50" rows="3" id="regular_dose"
                      required>{{ old('regular_dose', optional($medicine)->regular_dose) }}</textarea>

            {!! $errors->first('regular_dose', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
   
    
</div>

<div class="row">
    
    
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('comments') ? 'has-error' : '' }}">
            <label for="comments">{{__('commons.comments')}}</label>
            <textarea class="form-control" name="comments" cols="50" rows="3" id="comments"
                      required>{{ old('comments', optional($medicine)->comments) }}</textarea>

            {!! $errors->first('comments', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('special_dose') ? 'has-error' : '' }}">
            <label for="special_dose">{{__('medicine.special_dose')}}</label>
            <textarea class="form-control" name="special_dose" cols="50" rows="3" id="special_dose"
                      required>{{ old('special_dose', optional($medicine)->special_dose) }}</textarea>

            {!! $errors->first('special_dose', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
</div>

