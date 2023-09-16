@extends('vironeer::layouts.app')
@section('title', admin_lang('License'))
@section('content')
    <div class="vironeer-steps-body">
        <p class="vironeer-form-info-text">
            {{ admin_lang('As part of protecting our products we are building our systems to validate the license for every customer, the license means your purchase code.') }}
        </p>
        <div class="mb-4">
            <form action="" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label">{{ admin_lang('Purchase Code') }} : <span class="required">*</span></label>
                    <input type="text" name="purchase_code" class="form-control form-control-md"
                        placeholder="{{ admin_lang('Enter RANDOM value') }}" autocomplete="off" autofocus required>
                </div>
                <button class="btn btn-primary btn-md">{{ admin_lang('Continue') }}<i
                        class="fas fa-arrow-right ms-2"></i></button>
            </form>
        </div>
    </div>
@endsection
