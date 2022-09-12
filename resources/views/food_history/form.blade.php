<div class="row">

    <div class="col-md-6">
        <div class="form-group {{ $errors->has('inventory_id') ? 'has-error' : '' }}">
            <label for="inventory_id">{{__('commons.food_name')}}</label>
            <select class="form-control  select-admin-lte" id="inventory_id" name="inventory_id" required>
                <option value="">-----{{__('commons.select')}}-----</option>
                @foreach ($inventoryTypes as $inventoryType)
                

                 <option value="{{ $inventoryType->id }}" {{optional($foodHistory)->inventory_id == $inventoryType->id ? 'selected':' '}}>
                    {{ $inventoryType->title }}        
                </option>  
                    
                @endforeach
            </select>
            {!! $errors->first('inventory_id', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('livestock_id') ? 'has-error' : '' }}">
            <label for="livestock_id">{{__('commons.batch_name')}}</label>
            <select class="form-control  select-admin-lte" id="livestock_id" name="livestock_id" required>
                <option value="">-----{{__('commons.select')}}-----</option>
                @foreach ($liveStocks as $liveStock)
                

                 <option value="{{ $liveStock->id }}" {{optional($foodHistory)->livestock_id == $liveStock->id ? 'selected':' '}}>
                    {{ $liveStock->batch_name }}        
                </option>  
                    
                @endforeach
            </select>
            {!! $errors->first('livestock_type_id', '<p class="help-block">:message</p>') !!}
        </div>
    </div>

 
    
</div>
<div class="row">
   
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('shed_id') ? 'has-error' : '' }}">
            <label for="shed_id">{{__('commons.shed')}}</label>
            <select class="form-control  select-admin-lte" id="shed_id" name="shed_id" required>
                <option value="">-----{{__('commons.select')}}-----</option>
                @foreach ($sheds as $shed)
                

                 <option value="{{ $shed->id }}" {{optional($foodHistory)->shed_id == $shed->id ? 'selected':' '}}>
                    {{ $shed->shed_no }}        
                </option>  
                    
                @endforeach
            </select>
            {!! $errors->first('shed_id', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('date') ? 'has-error' : '' }}">
            <label for="date">{{__('commons.date')}}</label>
    
            <div class="input-group date">
                <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                </div>
                <input class="form-control datepicker pull-right" name="date" id="date"
                       value="{{ old('date', optional($foodHistory)->date) }}"
                       placeholder="format: {{ config('constants.DISPLAY_DATE_FORMAT') }}" required>
            </div>
    
            {!! $errors->first('date', '<p class="help-block">:message</p>') !!}
        </div>
    </div>

</div>

<div class="row">

    <div class="col-md-6">
        <div class="form-group {{ $errors->has('consume_quantity') ? 'has-error' : '' }}">
            <label for="consume_quantity">{{__('commons.consume_quantity')}}</label>
            <input class="form-control" name="consume_quantity" type="text" id="consume_quantity"
                value="{{ old('consume_quantity', optional($foodHistory)->consume_quantity) }}" minlength="1" maxlength="50" required>
            {!! $errors->first('consume_quantity', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('duration') ? 'has-error' : '' }}">
            <label for="duration">{{__('commons.duration')}}</label>
            <input class="form-control" name="duration" type="text" id="duration"
                value="{{ old('duration', optional($foodHistory)->duration) }}" minlength="1" maxlength="50" required>
            {!! $errors->first('duration', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
</div>

<div class="row">
  
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('comments') ? 'has-error' : '' }}">
            <label for="comments">{{__('commons.comments')}}</label>
            <textarea class="form-control" name="comments" cols="50" rows="3" id="comments"
                      required>{{ old('comments', optional($foodHistory)->comments) }}</textarea>

            {!! $errors->first('comments', '<p class="help-block">:message</p>') !!}
        </div>
    </div>

</div>

