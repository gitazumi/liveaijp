<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Google\Service\Calendar;
use Google_Client;
use Illuminate\Support\Facades\Auth;

class GoogleCloudController extends Controller
{
    function index()
    {
        return view('admin.google-cloud.index');
    }

    public function redirectToGoogle()
    {
        $client = new \Google_Client();
        $client->setClientId(env('GOOGLE_CLIENT_ID'));
        $client->setClientSecret(env('GOOGLE_CLIENT_SECRET'));
        $client->setRedirectUri(env('GOOGLE_REDIRECT_URI'));
        $client->addScope(Calendar::CALENDAR);
        $client->setAccessType('offline');
        $client->setPrompt('consent'); 

        return redirect($client->createAuthUrl());
    }

    public function handleGoogleCallback()
    {
        $client = new Google_Client();
        $client->setClientId(env('GOOGLE_CLIENT_ID'));
        $client->setClientSecret(env('GOOGLE_CLIENT_SECRET'));
        $client->setRedirectUri(env('GOOGLE_REDIRECT_URI'));

        $code = request('code');

        if ($code) {
            $accessToken = $client->fetchAccessTokenWithAuthCode($code);
            $client->setAccessToken($accessToken);
            // Save tokens in the database
            User::find(Auth::id())->update([
                'google_access_token' => $accessToken['access_token'],
                'google_refresh_token' => $accessToken['refresh_token'],
                'expires_in' => $accessToken['expires_in']
            ]);

            return redirect('welcome')->with('success', 'Google Calendar linked successfully!');
        }

        return redirect('/')->with('error', 'Failed to authenticate with Google.');
    }

    public function listGoogleCalendarEvents()
    {
        $user = User::find(Auth::id());
        
        $client = new \Google_Client();
        $client->setAccessToken($user->google_access_token);

        if ($client->isAccessTokenExpired()) {
            $client->fetchAccessTokenWithRefreshToken($user->google_refresh_token);
            $user->update(['google_access_token' => $client->getAccessToken()['access_token']]);
        }

        $service = new Calendar($client);
        $calendarId = 'primary';
        $events = $service->events->listEvents($calendarId);

        return view('google.events', ['events' => $events->getItems()]);
    }
}
