<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\TestEmail;

class MailController extends Controller
{
    public function testEmail()
    {
		return view('test.form-mail', [
			'client_id' => config('office365mail.client_id'),
			'tenant' => config('office365mail.tenant'),
			'client_secret' => config('office365mail.client_secret'),
			'to' => auth()->user()?->email,
		]);
    }

	public function postTestEmail(Request $request)
	{
		config()->set('office365mail.client_id', $request->client_id);

		config()->set('office365mail.tenant', $request->tenant);

		config()->set('office365mail.client_secret', $request->client_secret);
		
		try {
			
			Mail::to($request->to)->send(new TestEmail());

			return redirect()->back()->withInput([
				'client_id' => $request->client_id,
				'tenant' => $request->tenant,
				'client_secret' => $request->client_secret,
				'to' => $request->to,
			])->with('success', 'Email enviado correctamente');
			
		} catch (\Throwable $th) {

			return redirect()->back()->withInput([
				'client_id' => $request->client_id,
				'tenant' => $request->tenant,
				'client_secret' => $request->client_secret,
				'to' => $request->to,
			])->with('error', $th->getMessage());

		}
	}
}
