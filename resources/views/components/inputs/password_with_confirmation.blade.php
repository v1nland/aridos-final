<div class="form-group">
    <label for="{{$key}}">Contraseña</label>
    <input type="password" name="{{$key}}" id="{{$key}}"
           class="form-control{{ $errors->has($key) ? ' is-invalid' : '' }}">
    @if ($errors->has($key))
        <div class="invalid-feedback">
            <strong>{{ $errors->first($key) }}</strong>
        </div>
    @endif
</div>
<div class="form-group">
    <label for="{{$key}}_confirmation">Confirmar Contraseña</label>
    <input type="password" name="{{$key}}_confirmation" id="{{$key}}_confirmation"
           class="form-control{{ $errors->has("{$key}_confirmation") ? ' is-invalid' : '' }}">
    @if ($errors->has("{$key}_confirmation"))
        <div class="invalid-feedback">
            <strong>{{ $errors->first("{$key}_confirmation") }}</strong>
        </div>
    @endif
</div>