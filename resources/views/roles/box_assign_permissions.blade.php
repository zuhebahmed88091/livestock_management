<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title">{{ $module->name }} {{__('adminPrivilege.permissions')}}</h3>
        <div class="box-tools pull-right" id="{{ $module->slug }}">
            <span class="label" id="msg-{{ $module->slug }}"></span>
            <button type="button" id="{{ $module->id }}" class="btn btn-warning btn-sm btn-save">
                <i class="fa fa-refresh" aria-hidden="true"></i> {{__('commons.update')}}
            </button>
            <button type="button"
                    class="btn btn-info btn-sm btn-checkbox-toggle"
                    data-is-checked="{{ $module->isChecked }}">
                <i class="fa {{ $module->iconCheckAll }}" aria-hidden="true"></i> {{__('commons.check_all')}}
            </button>
        </div>
    </div>

    <div class="box-body no-padding">
        <div class="table-responsive {{ $module->slug }}">
            <table class="table table-hover table-striped">
                <tbody>
                @php($serial = 1)
                @foreach($module->permissions as $permission)
                    <tr>
                        <td>{{ $serial++ }}</td>
                        <td>{{ $permission->display_name }}</td>
                        <td>{{ $permission->name }}</td>
                        <td>
                            <input type="checkbox"
                                   {{ $permission->isChecked ? 'checked' : '' }}
                                   name="{{ $module->slug }}[]"
                                   value="{{ $permission->id }}"
                                   class="{{ $module->slug }}"
                                   title="Permission Checkbox">
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
