@extends('layouts.app')

@section('content')

<div class="mdui-row">
<div class="mdui-col-md-8 center">
<div class="mdui-card">
	<div class="mdui-card-primary">
		<div class="mdui-card-primary-title">
			编辑个人信息
		</div>
	</div>
	<div class="mdui-card-content">
		<form method="POST" action="{{ route('user.update_profile') }}">
			@csrf
			<div class="mdui-textfield mdui-textfield-floating-label {{$errors->has('old-password')?'mdui-textfield-invalid':''}}">
				<label class="mdui-textfield-label">{{ __('Old Password') }}</label>
				<input class="mdui-textfield-input " type="password" name="old-password" required autofocus/> </input>
				@if ($errors->has('old-password'))
					<div class="mdui-textfield-error">{{$errors->first('old-password')}}</div>	
				@endif
			</div>
			<div class="mdui-textfield mdui-textfield-floating-label {{$errors->has('nickname')?'mdui-textfield-invalid':''}}">
				<label class="mdui-textfield-label">{{ __('Nickname') }}</label>
				<input class="mdui-textfield-input " type="text" name="nickname"  value="{{old('nickname')}}"/> </input>
				@if ($errors->has('nickname'))
					<div class="mdui-textfield-error">{{$errors->first('nickname')}}</div>	
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
					{{ __('Update') }}
				</button>
			</div>
		</form>
	</div>
</div>
</div>
</div>


@endsection
