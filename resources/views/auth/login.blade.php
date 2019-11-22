@extends('layouts.app')

@section('content')
<div class="mdui-row">
<div class="mdui-col-md-8 center">
<div class="mdui-card">
	<div class="mdui-card-primary">
		<div class="mdui-card-primary-title">
			{{ __('Login') }}
		</div>
	</div>
	<div class="mdui-card-content">
		<form method="POST" action="{{ route('login') }}">
			@csrf
			<div class="mdui-textfield mdui-textfield-floating-label {{$errors->has('name')?'mdui-textfield-invalid':''}}">
				<label class="mdui-textfield-label">{{ __('Name') }}</label>
				<input class="mdui-textfield-input " type="text" name="name"  value="{{old('name')}}" required autofocus/> </input>
				@if ($errors->has('name'))
					<div class="mdui-textfield-error">{{$errors->first('name')}}</div>	
				@endif
			</div>
			<div class="mdui-textfield mdui-textfield-floating-label  {{$errors->has('password')?'mdui-textfield-invalid':''}}">
				<label class="mdui-textfield-label">{{ __('Password') }}</label>
				<input class="mdui-textfield-input " type="password" name="password" required/> </input>
				@if ($errors->has('password'))
					<div class="mdui-textfield-error">{{$errors->first('password')}}</div>	
				@endif
			</div>
			<label class="mdui-switch">
			  记住我
			  <input type="checkbox"  name="remember" id="remember" {{ old('remember') ? 'checked' : '' }} />
			  <i class="mdui-switch-icon"></i>
			</label>
			<div class="mdui-card-actions">
				<button type="submit" class="mdui-btn mdui-btn-raised mdui-color-theme mdui-btn-dense">
					{{ __('Login') }}
				</button>
			</div>
								<!---
								@if (Route::has('password.request'))
									<a class="" href="{{ route('password.request') }}">
										{{ __('Forgot Your Password?') }}
									</a>
								@endif
								--!>
		</form>
	</div>
</div>
</div>
</div>
@endsection
