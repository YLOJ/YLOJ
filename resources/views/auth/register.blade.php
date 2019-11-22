@extends('layouts.app')

@section('content')

<div class="mdui-row">
<div class="mdui-col-md-8 center">
<div class="mdui-card">
	<div class="mdui-card-primary">
		<div class="mdui-card-primary-title">
			{{ __('Register') }}
		</div>
	</div>
	<div class="mdui-card-content">
		<form method="POST" action="{{ route('register') }}">
			@csrf
			<div class="mdui-textfield mdui-textfield-floating-label {{$errors->has('name')?'mdui-textfield-invalid':''}}">
				<label class="mdui-textfield-label">{{ __('Username') }}</label>
				<input class="mdui-textfield-input " type="text" name="name"  value="{{old('name')}}" required autofocus/> </input>
				@if ($errors->has('name'))
					<div class="mdui-textfield-error">{{$errors->first('name')}}</div>	
				@endif
			</div>
			<div class="mdui-textfield mdui-textfield-floating-label {{$errors->has('nickname')?'mdui-textfield-invalid':''}}">
				<label class="mdui-textfield-label">{{ __('Nickname') }}</label>
				<input class="mdui-textfield-input " type="text" name="nickname"  value="{{old('nickname')}}"/> </input>
				@if ($errors->has('nickname'))
					<div class="mdui-textfield-error">{{$errors->first('nickname')}}</div>	
				@endif
			</div>
			<div class="mdui-textfield mdui-textfield-floating-label {{$errors->has('email')?'mdui-textfield-invalid':''}}">
				<label class="mdui-textfield-label">{{ __('E-mail Address') }}</label>
				<input class="mdui-textfield-input " type="email" name="email"  value="{{old('email')}}"/> </input>
				@if ($errors->has('email'))
					<div class="mdui-textfield-error">{{$errors->first('email')}}</div>	
				@endif
			</div>


			<div class="mdui-textfield mdui-textfield-floating-label {{$errors->has('password')?'mdui-textfield-invalid':''}}">
				<label class="mdui-textfield-label">{{ __('Password') }}</label>
				<input class="mdui-textfield-input " type="password" name="password" required/> </input>
				@if ($errors->has('password'))
					<div class="mdui-textfield-error">{{$errors->first('password')}}</div>	
				@endif
			</div>
			<div class="mdui-textfield mdui-textfield-floating-label {{$errors->has('password')?'mdui-textfield-invalid':''}}">
				<label class="mdui-textfield-label">{{  __('Confirm Password')}}</label>
				<input class="mdui-textfield-input " type="password" name="password_confirmation" required/> </input>
			</div>

			<div class="mdui-card-actions">
				<button type="submit" class="mdui-btn mdui-btn-raised mdui-color-theme mdui-btn-dense">
					{{ __('Register') }}
				</button>
			</div>
		</form>
	</div>
</div>
</div>
</div>

@endsection
