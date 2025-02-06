<?php

namespace App\Http\Controllers;

use App\Models\GoogleCalendar;
use Google\Client;
use Google\Service\Calendar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GoogleCalendarController extends Controller
{
    function index()
    {
        $data = GoogleCalendar::where('user_id', Auth::id())->first();
        return view('admin.google-calendar.index', compact('data'));
    }

    // public function update(Request $request)
    // {
    //     $validated = $request->validate([
    //         'client_id' => 'required',
    //         'client_secret' => 'required',
    //         'calendar_id' => 'required'
    //     ]);
    //     $credential = GoogleCalendar::updateOrCreate(
    //         ['id' => $request->id],
    //         array_merge($validated, ['user_id' => Auth::id()])
    //     );
    //     return $this->redirectToGoogle($credential->id);
    // }

    // public function redirectToGoogle($credentialId)
    // {
    //     $credential = GoogleCalendar::findOrFail($credentialId);

    //     $client = new Client();
    //     $client->setClientId($credential->client_id);
    //     $client->setClientSecret($credential->client_secret);
    //     $client->setRedirectUri(route('google-calendar.callback'));
    //     $client->addScope('https://www.googleapis.com/auth/calendar');
    //     $authUrl = $client->createAuthUrl();
    //     $authUrl .= '&state=' . $credentialId;
    //     return redirect($authUrl);
    // }

    // public function handleGoogleCallback(Request $request)
    // {
    //     $credential = GoogleCalendar::findOrFail($request->input('state'));
    //     $client = new Client();
    //     $client->setClientId($credential->client_id);
    //     $client->setClientSecret($credential->client_secret);
    //     $client->setRedirectUri(route('google-calendar.callback'));

    //     try {
    //         // Exchange authorization code for access token
    //         $token = $client->fetchAccessTokenWithAuthCode($request->input('code'));

    //         if (isset($token['error'])) {
    //             throw new \Exception($token['error_description']);
    //         }

    //         // Test access to Google Calendar API
    //         $service = new \Google\Service\Calendar($client);
    //         $events = $service->events->listEvents($credential->calendar_id);

    //         // Update status to active
    //         $credential->update([
    //             'access_token' => $token['access_token'],
    //             'status' => 'active',
    //             'error_message' => null
    //         ]);


    //         return redirect()->route('dashboard')->with('success', 'Google Calendar linked successfully!');
    //     } catch (\Exception $e) {
    //         // Update status to inactive with error message
    //         $credential->update(['status' => 'inactive', 'error_message' => $e->getMessage()]);

    //         return redirect()->route('dashboard')->withErrors(['error' => 'Failed to validate Google Calendar: ' . $e->getMessage()]);
    //     }
    // }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required',
            'client_secret' => 'required',
            'calendar_id' => 'required'
        ]);
        if ($request->id) {
            $credential = GoogleCalendar::findOrFail($request->id)
                ->update(array_merge($validated, [
                    'user_id' => Auth::id(),
                    'status' => 'pending'
                ]));
        } else {
            $credential = GoogleCalendar::create(array_merge($validated, [
                'user_id' => Auth::id(),
                'status' => 'pending'
            ]));
        }
        return $this->redirectToGoogle(GoogleCalendar::where('user_id', Auth::id())->first()->id);
    }

    public function redirectToGoogle($credentialId)
    {
        $credential = GoogleCalendar::findOrFail($credentialId);

        $client = new Client();
        $client->setClientId($credential->client_id);
        $client->setClientSecret($credential->client_secret);
        $client->setRedirectUri(route('google-calendar.callback'));
        $client->addScope('https://www.googleapis.com/auth/calendar');
        $client->setAccessType('offline'); // Request offline access for refresh token
        $client->setPrompt('consent');
        $authUrl = $client->createAuthUrl();
        $authUrl .= '&state=' . $credentialId;
        return redirect($authUrl);
    }

    public function handleGoogleCallback(Request $request)
    {
        $credential = GoogleCalendar::where('user_id', Auth::id())->first();
        $client = new Client();
        $client->setClientId($credential->client_id);
        $client->setClientSecret($credential->client_secret);
        $client->setRedirectUri(route('google-calendar.callback'));
        try {
            $token = $client->fetchAccessTokenWithAuthCode($request->input('code'));

            if (isset($token['error'])) {
                throw new \Exception($token['error_description']);
            }

            // Save tokens and update status
            $credential->update([
                'access_token' => $token['access_token'],
                'refresh_token' => $token['refresh_token'] ?? $credential->refresh_token,
                'status' => 'active',
                'error_message' => null
            ]);

            return redirect()->route('dashboard')->with('success', 'Google Calendar linked successfully!');
        } catch (\Exception $e) {
            $credential->update(['status' => 'inactive', 'error_message' => $e->getMessage()]);

            return redirect()->route('dashboard')->withErrors(['error' => 'Failed to validate Google Calendar: ' . $e->getMessage()]);
        }
    }


    public function refreshAccessToken($credentialId)
    {
        $credential = GoogleCalendar::findOrFail($credentialId);

        $client = new Client();
        $client->setClientId($credential->client_id);
        $client->setClientSecret($credential->client_secret);

        $client->refreshToken($credential->refresh_token);

        $newToken = $client->getAccessToken();
        $credential->update(['access_token' => $newToken['access_token']]);

        return $newToken;
    }
    public function getEvents()
    {
        try {
            $calendar = GoogleCalendar::where('user_id', Auth::id())->first();

            if (!$calendar || !$calendar->access_token) {

                return response()->json([
                    'success' => false,
                    'message' => 'Please authenticate with Google Calendar first.',
                ]);
            }

            $client = new Client();
            $client->setClientId($calendar->client_id);
            $client->setClientSecret($calendar->client_secret);
            $client->setAccessToken($calendar->access_token);

            if ($client->isAccessTokenExpired()) {
                if ($calendar->refresh_token) {
                    $newToken = $client->fetchAccessTokenWithRefreshToken($calendar->refresh_token);
                    $client->setAccessToken($newToken['access_token']);

                    $calendar->update([
                        'access_token' => $newToken['access_token'],
                        'refresh_token' => $newToken['refresh_token'] ?? $calendar->refresh_token,
                    ]);
                } else {

                    return response()->json([
                        'success' => false,
                        'message' => 'Access token expired. Please reauthenticate.',
                    ]);
                }
            }

            $service = new \Google\Service\Calendar($client);
            $calendarId = $calendar->calendar_id ?? 'primary'; // Use the saved calendar ID or 'primary'
            $events = $service->events->listEvents($calendarId);

            return response()->json([
                'success' => true,
                'events' => $events->getItems(),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
