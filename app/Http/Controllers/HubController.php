<?php

namespace SmartBots\Http\Controllers;

use Illuminate\Http\Request;
use Moloquent;
use Validator;
use JWTAuth;

use SmartBots\{
    Hub,
    Member,
    HubPermission
};

class HubController
{
    public function index() {

        $hubs = collect([]);

        $members = JWTAuth::toUser()->members()->activated()->get();

        foreach ($members as $member) {
            $hubs = $hubs->merge($member->hub()->with('bots')->get());
        }

        return response()->json($hubs);
    }

    public function create(Request $request) {
        $rules = [
            'name'        => 'required|max:128',
            'description' => 'max:1000',
            'timezone'    => 'required|timezone'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $newHub = new Hub;
        $newHub->name        = $request->name;
        $newHub->description = $request->description;
        $newHub->token       = str_random(50);
        $newHub->timezone    = $request->timezone;
        $newHub->active      = 1;

        // if (!empty($request->image_values)) {
        //     $newHub->image = upload_base64_image(json_decode($request->image_values)->data);
        // }

        $newHub->save();

        $newMember = new Member;
        $newMember->user_id = auth()->user()->id;
        $newMember->hub_id  = $newHub->id;
        $newMember->active = 1;
        $newMember->save();

        $newHubPermission = new HubPermission;
        $newHubPermission->user_id = auth()->user()->id;
        $newHubPermission->hub_id  = $newHub->id;
        $newHubPermission->data    = 0; // Admin
        $newHubPermission->save();

        return response()->json(compact('newHub'));
    }
}
