<?php

namespace Tests\Unit;

use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class LoginRequestTest extends TestCase
{
    public function test_checked_remember_checkbox_value_is_normalized_to_boolean(): void
    {
        $request = TestableLoginRequest::createFromBase(request()->create('/login', 'POST', [
            'ppr' => 'PPR-1001',
            'password' => 'secret-password',
            'captcha' => 4,
            'remember' => 'on',
        ]));

        $request->normalize();

        $validator = Validator::make($request->all(), $request->rules(), $request->messages());

        $this->assertTrue($validator->passes());
        $this->assertTrue($request->boolean('remember'));
    }
}

class TestableLoginRequest extends LoginRequest
{
    public function normalize(): void
    {
        $this->prepareForValidation();
    }
}
