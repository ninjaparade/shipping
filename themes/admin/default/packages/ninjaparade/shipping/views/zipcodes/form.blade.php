@extends('layouts/default')

{{-- Page title --}}
@section('title')
	@parent
	: {{{ trans("ninjaparade/shipping::zipcodes/general.{$mode}") }}} {{{ $zipcode->exists ? '- ' . $zipcode->name : null }}}
@stop

{{-- Queue assets --}}
{{ Asset::queue('bootstrap.tabs', 'bootstrap/js/tab.js', 'jquery') }}
{{ Asset::queue('shipping', 'ninjaparade/shipping::js/script.js', 'jquery') }}

{{-- Inline scripts --}}
@section('scripts')
@parent
@stop

{{-- Inline styles --}}
@section('styles')
@parent
@stop

{{-- Page content --}}
@section('content')

{{-- Page header --}}
<div class="page-header">

	<h1>{{{ trans("ninjaparade/shipping::zipcodes/general.{$mode}") }}} <small>{{{ $zipcode->name }}}</small></h1>

</div>

{{-- Content form --}}
<form id="shipping-form" action="{{ Request::fullUrl() }}" method="post" accept-char="UTF-8" autocomplete="off">

	{{-- CSRF Token --}}
	<input type="hidden" name="_token" value="{{ csrf_token() }}">

	{{-- Tabs --}}
	<ul class="nav nav-tabs">
		<li class="active"><a href="#general" data-toggle="tab">{{{ trans('ninjaparade/shipping::general.tabs.general') }}}</a></li>
		<li><a href="#attributes" data-toggle="tab">{{{ trans('ninjaparade/shipping::general.tabs.attributes') }}}</a></li>
	</ul>

	{{-- Tabs content --}}
	<div class="tab-content tab-bordered">

		{{-- General tab --}}
		<div class="tab-pane active" id="general">

			<div class="row">

				<div class="form-group{{ $errors->first('zip', ' has-error') }}">

					<label for="zip" class="control-label">{{{ trans('ninjaparade/shipping::zipcodes/form.zip') }}} <i class="fa fa-info-circle" data-toggle="popover" data-content="{{{ trans('ninjaparade/shipping::zipcodes/form.zip_help') }}}"></i></label>

					<input type="text" class="form-control" name="zip" id="zip" placeholder="{{{ trans('ninjaparade/shipping::zipcodes/form.zip') }}}" value="{{{ Input::old('zip', $zipcode->zip) }}}">

					<span class="help-block">{{{ $errors->first('zip', ':message') }}}</span>

				</div>

				<div class="form-group{{ $errors->first('city', ' has-error') }}">

					<label for="city" class="control-label">{{{ trans('ninjaparade/shipping::zipcodes/form.city') }}} <i class="fa fa-info-circle" data-toggle="popover" data-content="{{{ trans('ninjaparade/shipping::zipcodes/form.city_help') }}}"></i></label>

					<input type="text" class="form-control" name="city" id="city" placeholder="{{{ trans('ninjaparade/shipping::zipcodes/form.city') }}}" value="{{{ Input::old('city', $zipcode->city) }}}">

					<span class="help-block">{{{ $errors->first('city', ':message') }}}</span>

				</div>

				<div class="form-group{{ $errors->first('state', ' has-error') }}">

					<label for="state" class="control-label">{{{ trans('ninjaparade/shipping::zipcodes/form.state') }}} <i class="fa fa-info-circle" data-toggle="popover" data-content="{{{ trans('ninjaparade/shipping::zipcodes/form.state_help') }}}"></i></label>

					<input type="text" class="form-control" name="state" id="state" placeholder="{{{ trans('ninjaparade/shipping::zipcodes/form.state') }}}" value="{{{ Input::old('state', $zipcode->state) }}}">

					<span class="help-block">{{{ $errors->first('state', ':message') }}}</span>

				</div>

				<div class="form-group{{ $errors->first('country', ' has-error') }}">

					<label for="country" class="control-label">{{{ trans('ninjaparade/shipping::zipcodes/form.country') }}} <i class="fa fa-info-circle" data-toggle="popover" data-content="{{{ trans('ninjaparade/shipping::zipcodes/form.country_help') }}}"></i></label>

					<input type="text" class="form-control" name="country" id="country" placeholder="{{{ trans('ninjaparade/shipping::zipcodes/form.country') }}}" value="{{{ Input::old('country', $zipcode->country) }}}">

					<span class="help-block">{{{ $errors->first('country', ':message') }}}</span>

				</div>

				<div class="form-group{{ $errors->first('local', ' has-error') }}">

					<label for="local" class="control-label">{{{ trans('ninjaparade/shipping::zipcodes/form.local') }}} <i class="fa fa-info-circle" data-toggle="popover" data-content="{{{ trans('ninjaparade/shipping::zipcodes/form.local_help') }}}"></i></label>

					<div class="checkbox">
						<label>
							<input type="hidden" name="local" id="local" value="0" checked>
							<input type="checkbox" name="local" id="local" @if($zipcode->local) }}}) checked @endif value="1"> {{ ucfirst('local') }}
						</label>
					</div>

					<span class="help-block">{{{ $errors->first('local', ':message') }}}</span>

				</div>


			</div>

		</div>

		{{-- Attributes tab --}}
		<div class="tab-pane clearfix" id="attributes">

			@widget('platform/attributes::entity.form', [$zipcode])

		</div>

	</div>

	{{-- Form actions --}}
	<div class="row">

		<div class="col-lg-12 text-right">

			{{-- Form actions --}}
			<div class="form-group">

				<button class="btn btn-success" type="submit">{{{ trans('button.save') }}}</button>

				<a class="btn btn-default" href="{{{ URL::toAdmin('shipping/zipcodes') }}}">{{{ trans('button.cancel') }}}</a>

				<a class="btn btn-danger" data-toggle="modal" data-target="modal-confirm" href="{{ URL::toAdmin("shipping/zipcodes/{$zipcode->id}/delete") }}">{{{ trans('button.delete') }}}</a>

			</div>

		</div>

	</div>

</form>

@stop
